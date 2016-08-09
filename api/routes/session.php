<?php

use Firebase\JWT\JWT;
use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;

// виносимо паролі і тд в окремий файл
$config = parse_ini_file("config.ini");

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8", $config['username'], $config['password']);
$db = new NotORM($pdo);

$container = $app->getContainer();

$container["jwt"] = function ($container) {
    return new StdClass;
};
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/login",
    "secure" => false,
    "authenticator" => new PdoAuthenticator([
        "pdo" => $pdo,
        "table" => "user",
        "user" => "email",
        "hash" => "password"
    ])
]));
// for test
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => $config['jwt'],
    "secure" => false,
    "path" => "/securetest",
    "passthrough" => ["/login","/signup"],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    }
]));
// all post, put and delete requests
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => $config['jwt'],
    "secure" => false,
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => [
                "/users", 
                "/books"
            ],
            "passthrough" => ["/login","/signup"]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            "passthrough" => ["OPTIONS", "GET"]
        ])
    ],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    }
]));
// get requests that need to be secured
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => $config['jwt'],
    "secure" => false,
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => [
                "/books/(.*)/requests"
            ],
            "passthrough" => ["/login","/signup"]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            "path" => ["GET"]
        ])
    ],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    }
]));

function generateJWT($sub, $id, $secret) {
    $now = new DateTime();
    $future = new DateTime("now +2 hours");
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "sub" => $sub,
        "id"  => $id
    ];
    $token = JWT::encode($payload, $secret, "HS256");
    return $token;
}
$app->post("/login", function ($request, $response, $arguments) use ($db,$config){
    $server = $request->getServerParams();
    $user= $db->user()->where('email',$server["PHP_AUTH_USER"])->fetch();
    $token = generateJWT($server["PHP_AUTH_USER"], $user['id'], $config['jwt']);
    $data["status"] = "ok";
    $data["id_token"] = $token;

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
$app->post("/signup", function ($request, $response, $arguments) use ($db,$config){
    $post = $request->getParsedBody();
	if($post['name'] && $post['email'] && $post['password']){
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            return $response->withStatus(422);
        }
        $check = $db->user()->where('email',$post['email'])->fetch();
        if($check){
            return $response->withStatus(409);
        }
        $keys = ['name','email','password','city','date_of_birth','description','address'];
        $userdata = [];
        foreach ($post as $key => $value) {
            if(in_array($key, $keys)){
                $userdata[$key] = $value;
            }
        }
        $userdata['password'] = password_hash($userdata['password'] , PASSWORD_DEFAULT);
        $user = $db->user()
                   ->insert($userdata);
        if($user){
            $token = generateJWT($user["email"], $user['id'], $config['jwt']);
            $data["status"] = "ok";
            $data["id_token"] = $token;

            return $response->withStatus(201)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
	    return $response->withStatus(500);
	}
    return $response->withStatus(400);
});
$app->get('/securetest', function ($request, $response, $args) use ($app, $db) {
	$books = $db->book()
                ->select('id, title, author, year, state, status, user_id');
    $result = [];
    foreach ($books as $key => $value) {
        $result[] = $books[$key];
    }
    $response->write(json_encode($result));
});