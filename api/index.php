<?php

require 'vendor/autoload.php';
require_once 'vendor/NotORM.php';

// виносимо паролі і тд в окремий файл
$config = parse_ini_file("config.ini");

$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8", $config['username'], $config['password']);
$db = new NotORM($pdo);

$app = new Slim\App();
// Users
// виводимо всіх користувачів
$app->get('/users', function ($request, $response, $args) use ($app, $db) {
	$users = $db->user()
	            ->select('name','email','city','date_of_birth','description');
    $response->write(json_encode($users));
});
// додаємо користувача, повератається об’єкт user або помилка
$app->post('/users', function ($request, $response, $args) use ($app, $db) {
    $user = $request->getParsedBody();
    $data = $db->user()
               ->insert($user);
    $response->write(json_encode($data));
});
// виводить дані користувача і його книжки
$app->get('/users/{id}', function ($request, $response, $args) use ($app, $db) {
	$user = $db->user()
	           ->select('id, name','email','city','date_of_birth','description')
			   ->where('id', $args['id'])
			   ->fetch();
    $user['books'] = $user->book()->select('id, title, author, year, photo, state, status');
    $response->write(json_encode($user));
});
// оновлюємо дані користувача
$app->put('/users/{id}', function ($request, $response, $args) use ($app, $db) {
    $user = $db->user()
    		   ->where('id', $args['id']);
    $data = null;
    if ($user->fetch()) {
        $post = $request->getParsedBody();
        $data = $user->update($post);
    }
    $response->write(json_encode($data));
});
// видаляємо користувача
$app->delete('/users/{id}', function ($request, $response, $args) use ($app, $db) {
	// TODO
	// Delete user books???
	// ownership table????
	// Do we need this at all?
	$user = $db->user()
	           ->where('id', $args['id']);
    $data = null;
    if ($user->fetch()) {
        $data = $user->delete();
    }
    $response->write(json_encode($data));
});

// Books
// повертає всі книжки
$app->get('/books', function ($request, $response, $args) use ($app, $db) {
	$books = $db->book();
	echo json_encode($books);
});
// додаємо нову книжку
$app->post('/books', function ($request, $response, $args) use ($app, $db) {
	// TODO
	// need possibility to add tags, as they are in the separate table
    $book = $request->getParsedBody();
    $data = $db->book()->insert($book);
    $response->write(json_encode($data));
});
// повертає конкретну книжку за її id, fetch() використовується щоб виводилось як окремий об*єкт, а не масив
$app->get('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
			   ->select('id, title, author, year, photo, state, status')
			   ->where('id', $args['id'])
			   ->fetch();
	$book["owner"] = $db->user()
						->select('name, city, date_of_birth, description')
						->where('id', $book['user_id'])
						->fetch();
	$book["owners"] = $db->ownership()->select('user_id, start_date, end_date');
	$book['tags'] = $book->tags()->select('tag');
	$response->write(json_encode($book));
});
// оновлюємо книжку
$app->put('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	// TODO
	// the same as for post - tags etc
    $book = $db->book()->where('id', $args['id']);
    $data = null;
    if ($book->fetch()) {
        $post = $request->getParsedBody();
        // TODO
        // if post contains user_id => update user and add records to ownership table
        $data = $book->update($post);
    }
    $response->write(json_encode($data));
});
// видаляємо книжку
$app->delete('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
	           ->where('id', $args['id']);
    $data = null;
    if ($book->fetch()) {
        $data = $book->delete();
    }
    $response->write(json_encode($data));
});
// пошук книг за тегом
$app->get('/books/tags/{tag}', function($request, $response, $args) use ($app, $db) {
	$books = $db->tags()
				->select('book_id')
				->where('tag LIKE ?', $args['tag']);
	$response->write(json_encode($books));
});
// повертає усі теги
$app->get('/books/tags/', function($request, $response, $args) use ($app, $db) {
	$tags = $db->tags()
			   ->select('tag')
			   ->group('tag');
	$response->write(json_encode($tags));
});

$app->run();