<?php

require 'vendor/autoload.php';
require_once 'vendor/NotORM.php';
// виносимо паролі і тд в окремий файл
$config = parse_ini_file("config.ini");

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8", $config['username'], $config['password']);
$db = new NotORM($pdo);

$app = new Slim\App();
// поки не використовувати для api call-ів, щось типу hello world 
$app->get('/user/{name}', function ($request, $response, $args) use ($app, $db) {
	$user = $db->User()->where('name', $args['name']);
    $response->write("Hello, " . $args['name'] . ". Your info in DB:<br> " . json_encode($user));
    return $response;
});
// повертає всі книжки
$app->get('/books', function ($request, $response, $args) use ($app, $db) {
	$books = $db->Book();
	echo json_encode($books);
});
// повертає конкретну книжку за її id, fetch() використовується щоб виводилось як окремий об*єкт, а не масив
$app->get('/book/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->Book()->where('id', $args['id'])->fetch();
	echo json_encode($book);
});

$app->run();
