<?php
// Users
$app->get('/users', function ($request, $response, $args) use ($app, $db) {
	$users = $db->user()
	            ->select('name','email','city','date_of_birth','description');
    $response->write(json_encode($users));
});
$app->get('/users/{id}', function ($request, $response, $args) use ($app, $db) {
	$user = $db->user()
	           ->select('id, name','email','city','date_of_birth','description')
			   ->where('id', $args['id'])
			   ->fetch();
    $user['books'] = $user->book()->select('id, title, author, year, photo, state, status');
    $response->write(json_encode($user));
});
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