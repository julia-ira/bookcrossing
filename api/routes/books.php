<?php
// Books
$app->get('/books', function ($request, $response, $args) use ($app, $db) {
	$books = $db->book()
				->select('id, title, author, year, state, status, user_id');
	$result = [];
	foreach ($books as $key => $value) {
		$result[] = $books[$key];
	}
	echo json_encode($result);
});
$app->post('/books', function ($request, $response, $args) use ($app, $db) {
	// TODO
	// need possibility to add tags, as they are in the separate table
    $book = $request->getParsedBody();
    $book['user_id'] = $this->jwt->id;
    $data = $db->book()->insert($book);
    $data['request']=$book;
    $response->write(json_encode($data));
});
$app->get('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
			   ->select('id, title, author, year, photo, state, status, user_id')
			   ->where('id', $args['id'])
			   ->fetch();
	$book["owner"] = $user = $db->user()
						        ->select('id, name, city, date_of_birth, description')
						        ->where('id', $book['user_id'])
						        ->fetch();
	/*$book["owners"] = $user->ownership()
			               ->select('user_id, start_date, end_date');*/
	$book['tags'] = $book->tags()->select('tag');
	$response->write(json_encode($book));
});
// secured
$app->put('/books/{id}', function ($request, $response, $args) use ($app, $db) {
    $book = $db->book()->where('id', $args['id'])->fetch();
    $data = null;
    if ($book && $book['user_id'] == $this->jwt->id) {
    		$post = $request->getParsedBody();
    		if($post['user_id']){
    			unset($post['user_id']);
    		}
	        // TODO
	        // if post contains user_id => update user and add records to ownership table
	        $data = $book->update($post);
    }
    $response->write(json_encode($data));
});
// secured
$app->delete('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
	           ->where('id', $args['id'])->fetch();
    $data = null;
    if ($book && $book['user_id'] == $this->jwt->id) {
        $data = $book->delete();
    }
    $response->write(json_encode($data));
});
// add book request
// secured
$app->post('/books/{id}/requests', function ($request, $response, $args) use ($app, $db) {
    $book = $db->book()->where('id',$args['id']);
    $result = null;
    if($book && $book['user_id'] != $this->jwt->id){
		$data = array(
	    	"book_id" => $args['id'],
	    	"user_id" => $this->jwt->id
	    	// status
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
	$result = null;
	foreach ($requestdb as $id => $request) {
		$result['id'] = $request['id'];
		$result["user"] = $db->user()
	                         ->select('id, name, city, date_of_birth, description')
	                         ->where('id', $request['user_id'])
	                         ->fetch();
	    $result["date"] = $request['date'];
	    $result['status'] = $request['status'];
	}
	$response->write(json_encode($result));
});
// accept book request
// secured
$app->put('/books/{id}/requests/{request_id}', function ($request, $response, $args) use ($app, $db) {
    $requestbook = $db->request()->where('id', $args['request_id'])->fetch();
    $data = null;
    if ($requestbook) {
    	$book = $db->book()->where('id', $requestbook['book_id'])->fetch();
    	if ($book && $book['user_id'] == $this->jwt->id) {
	    	$status = array(
	    		"status" => "completed"
	        );
	        $data['request'] = $requestbook->update($status);
        	$user = array(
        		"user_id" => $requestbook['user_id']
    		);
        	$data['book'] = $book->update($user);
        }
    }
    $response->write(json_encode($data));
});
$app->get('/books/tags/{tag}', function($request, $response, $args) use ($app, $db) {
	$books = $db->tags()
				->select('book_id')
				->where('tag LIKE ?', $args['tag']);
	$response->write(json_encode($books));
});
$app->get('/books/tags/', function($request, $response, $args) use ($app, $db) {
	$tags = $db->tags()
			   ->select('tag')
			   ->group('tag');
	$response->write(json_encode($tags));
});
$app->get('/books/search/{query}', function($request, $response, $args) use ($app, $db) {
	// example : $db->table("MATCH (title) AGAINST (?)", "Adminer")
});