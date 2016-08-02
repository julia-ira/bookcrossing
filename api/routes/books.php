<?php
// Books
$app->get('/books', function ($request, $response, $args) use ($app, $db) {
	$books = $db->book()
				->select('id, title, author, year, state, status, user_id');
	if($books){
		$result = [];
		foreach ($books as $key => $value) {
			$result[] = $books[$key];
		}
		return $response->write(json_encode($result));
	}
	return $response->withStatus(500);
});
$app->post('/books', function ($request, $response, $args) use ($app, $db) {
    $book = $request->getParsedBody();
    if($book['title'] && $book['author'] && $this->jwt->id){
	    $book['user_id'] = $this->jwt->id;
	    $data = $db->book()->insert($book);
	    if($data){
		    return $response->write(json_encode($data));
	    }
	    return $response->withStatus(500);
    }
    return $response->withStatus(400);
});
$app->get('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
			   ->select('id, title, author, year, photo, state, status, user_id')
			   ->where('id', $args['id'])
			   ->fetch();
	if($book){
		$book["owner"] = $user = $db->user()
							        ->select('id, name, city, date_of_birth, description')
							        ->where('id', $book['user_id'])
							        ->fetch();
		/*$book["owners"] = $user->ownership()
				               ->select('user_id, start_date, end_date');*/
		$book['tags'] = $book->tags()->select('tag');
		return $response->write(json_encode($book));
	}
	return $response->withStatus(404);
});
// secured
$app->put('/books/{id}', function ($request, $response, $args) use ($app, $db) {
    $book = $db->book()->where('id', $args['id'])->fetch();
    $data = null;
    if ($book) {
    	if($book['user_id'] == $this->jwt->id){
    		$post = $request->getParsedBody();
			if($post['user_id']){
				unset($post['user_id']);
			}
	        // TODO
	        // if post contains user_id => update user and add records to ownership table
	        $updated = $book->update($post);
	        $data = $db->book()->where('id', $args['id'])->fetch();
	        if($updated && $data){
	        	return $response->write(json_encode($data));
	        }
	        return $response->withStatus(500);
    	}
		return $response->withStatus(403);
    }
    return $response->withStatus(404);
});
// secured
$app->delete('/books/{id}', function ($request, $response, $args) use ($app, $db) {
	$book = $db->book()
	           ->where('id', $args['id'])->fetch();
    $data = null;
    if ($book) {
    	if($book['user_id'] == $this->jwt->id){
    		$data = $book->delete();
    		if($data){
    			return $response->withStatus(200);
    		}
    		return $response->withStatus(500);
    	}
    	return $response->withStatus(403);
    }
    return $response->withStatus(404);
});
// add book request
// secured
$app->post('/books/{id}/requests', function ($request, $response, $args) use ($app, $db) {
    $book = $db->book()->where('id',$args['id'])->fetch();
    $result = null;
    if($book){
    	if($book['user_id'] != $this->jwt->id){
    		$data = array(
		    	"book_id" => $args['id'],
		    	"user_id" => $this->jwt->id
		    );
		    $result = $db->request()->insert($data);
		    if($result){
		    	return $response->write(json_encode($result));
		    }
		    return $response->withStatus(500);
    	}
		return $response->withStatus(403);
    }
    return $response->withStatus(404);
});
// show pending book requests
$app->get('/books/{id}/requests', function ($request, $response, $args) use ($app, $db) {
	// TODO should be shown only for books which are owned by current user
	$requestdb = $db->request()
			   ->select('id, book_id, user_id, date, status')
			   ->where('book_id', $args['id']);
	$result = [];
	foreach ($requestdb as $id => $request) {
		$r['id'] = $request['id'];
		$r["user"] = $db->user()
	                         ->select('id, name, city, date_of_birth, description')
	                         ->where('id', $request['user_id'])
	                         ->fetch();
	    $r["date"] = $request['date'];
	    $r['status'] = $request['status'];
	    $result[] = $r;
	}
	return $response->write(json_encode($result));
});
// accept book request
// secured
$app->put('/books/{id}/requests/{request_id}', function ($request, $response, $args) use ($app, $db) {
    $requestbook = $db->request()->where('id', $args['request_id'])->fetch();
    $data = null;
    if ($requestbook && $args['id'] == $requestbook['book_id']) {
    	$book = $db->book()->where('id', $requestbook['book_id'])->fetch();
    	if ($book) {
    		if($book['user_id'] == $this->jwt->id){
    			$status = array(
		    		"status" => "completed"
		        );
		        $data['request'] = $requestbook->update($status);
	        	$user = array(
	        		"user_id" => $requestbook['user_id']
	    		);
	        	$data['book'] = $book->update($user);
	        	if($data){
	        		return $response->write(json_encode($data));
	        	}
	        	return $response->withStatus(500);
    		}
	    	return $response->withStatus(403);
        }
    }
    return $response->withStatus(404);
});
$app->get('/books/tags/{tag}', function($request, $response, $args) use ($app, $db) {
	$books = $db->tags()
				->select('book_id')
				->where('tag LIKE ?', $args['tag']);
	return $response->write(json_encode($books));
});
$app->get('/books/tags/', function($request, $response, $args) use ($app, $db) {
	$tags = $db->tags()
			   ->select('tag')
			   ->group('tag');
	return $response->write(json_encode($tags));
});
$app->get('/books/search/{query}', function($request, $response, $args) use ($app, $db) {
	// example : $db->table("MATCH (title) AGAINST (?)", "Adminer")
});