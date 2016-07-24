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
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => $config['jwt'],
    "secure" => false,
    "path" => "/securetest",
    "passthrough" => ["/login","/signup"],
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
    // TODO, check if email is unique
    $post = $request->getParsedBody();
	if($post['name'] && $post['email'] && $post['password']){
        $post['password'] = password_hash($post['password'] , PASSWORD_DEFAULT);
		$user = $db->user()
	               ->insert($post);
	    $token = generateJWT($user["email"], $user['id'], $config['jwt']);
	    $data["status"] = "ok";
	    $data["id_token"] = $token;

	    return $response->withStatus(201)
	        ->withHeader("Content-Type", "application/json")
	        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
	}	
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