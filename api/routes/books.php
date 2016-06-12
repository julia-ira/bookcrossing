<?php
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
	$book["owner"] = $user = $db->user()
						        ->select('id, name, city, date_of_birth, description')
						        ->where('id', $book['id'])
						        ->fetch();
	$book["owners"] = $user->ownership()
			               ->select('user_id, start_date, end_date');
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
//  add book request
$app->post('/books/{id}/requests', function ($request, $response, $args) use ($app, $db) {
	// if auth implemented user_id should not be passed
    $bookrequest = $request->getParsedBody();
    $book = $db->book()->where('id',$args['id']);
    if($bookrequest['user_id'] && $book->fetch() && $book['user_id'] != $bookrequest['user_id']){
		$data = array(
	    	"book_id" => $args['id'],
	    	"user_id" => $bookrequest['user_id']
	    );
	    $result = $db->request()->insert($data);
    }
    $response->write(json_encode($result));
});
// show pending book requests
$app->get('/books/{id}/requests', function ($request, $response, $args) use ($app, $db) {
	// TODO should be shown only for books which are owned by current user
	$requestdb = $db->request()
			   ->select('id, book_id, user_id, date')
			   ->where('book_id', $args['id']);
	foreach ($requestdb as $id => $request) {
		$result['id'] = $request['id'];
		$result["user"] = $db->user()
	                         ->select('id, name, city, date_of_birth, description')
	                         ->where('id', $request['user_id'])
	                         ->fetch();
	    $result["date"] = $request['date'];
	}
	$response->write(json_encode($result));
});
// accept/decline book request (can we decline???)
$app->put('/books/{id}/requests/{request_id}', function ($request, $response, $args) use ($app, $db) {
	// only accept goes here
    $requestbook = $db->request()->where('id', $args['request_id']);
    $data = null;
    if ($requestbook->fetch()) {
    	$status = array(
    		"status" => "completed"
        );
        $data = $requestbook->update($status);
        $book = $db->book()->where('id', $requestbook['book_id']);
        if ($book->fetch()) {
        	$user = array(
        		"user_id" => $requestbook['user_id']
    		);
        	$data = $book->update($user);
        }
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
$app->get('/books/search/{query}', function($request, $response, $args) use ($app, $db) {
	// example : $db->table("MATCH (title) AGAINST (?)", "Adminer")
});