<?php

require 'vendor/autoload.php';
require_once 'vendor/NotORM.php';

// виносимо паролі і тд в окремий файл
$config = parse_ini_file("config.ini");

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8", $config['username'], $config['password']);
$db = new NotORM($pdo);

$app = new Slim\App();

// виводить дані користувача і його книжки
$app->get('/user/{name}', function ($request, $response, $args) use ($app, $db) {
	$user = $db->user()
	           ->select('id, name','email','city','date_of_birth','description')
			   ->where('name', $args['name'])
			   ->fetch();
    $user['books'] = $user->book()->select('id, title, author, year, photo, state, status');
    $response->write(json_encode($user));
});
// повертає всі книжки
$app->get('/books', function ($request, $response, $args) use ($app, $db) {
	$books = $db->book();
	echo json_encode($books);
});
// повертає конкретну книжку за її id, fetch() використовується щоб виводилось як окремий об*єкт, а не масив
$app->get('/book/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
			   ->select('id, title, author, year, photo, state, status')
			   ->where('id', $args['id'])
			   ->fetch();
	$book["owner"] = $db->user()
						->select('name, city, date_of_birth, description')
						->where('id', $book['id'])
						->fetch();
	$book["owners"] = $db->ownership()->select('user_id, start_date, end_date');
	$book['tags'] = $book->tags()->select('tag');
	$response->write(json_encode($book));
});
// пошук книг за тегом
$app->get('/books/tag/{tag}', function($request, $response, $args) use ($app, $db) {
	$books = $db->tags()
				->select('book_id')
				->where('tag LIKE ?', $args['tag']);
	$response->write(json_encode($books));
});
// повертає усі теги
$app->get('/tags', function($request, $response, $args) use ($app, $db) {
	$tags = $db->tags()
			   ->select('tag')
			   ->group('tag');
	$response->write(json_encode($tags));
});

$app->run();