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
// secured
// added "update" to this route to let other "/users" routes be accesible anonimously
$app->put('/users', function ($request, $response, $args) use ($app, $db) {
    $user = $db->user()
    		   ->where('id', $this->jwt->id);
    $data = null;
    if ($user->fetch()) {
        $post = $request->getParsedBody();
        print_r($post);
        // encoding password before updating
        if(array_key_exists("password", $post)){
            $post["password"] = password_hash($post['password'] , PASSWORD_DEFAULT);
        }
        // user can't update it's userid
        if(array_key_exists("id", $post)){
            unset($post["id"]);
        }
        print_r($post);
        $data["rows_updated"] = $user->update($post);
        $data["data"] = $db->user()
               ->where('id', $this->jwt->id); 
    }
    $response->write(json_encode($data));
});
// need to be removed soon
// secured till that time
// $app->delete('/users/{id}', function ($request, $response, $args) use ($app, $db) {
// 	// TODO
// 	// Delete user books???
// 	// ownership table????
// 	// Do we need this at all?
// 	$user = $db->user()
// 	           ->where('id', $args['id']);
//     $data = null;
//     if ($user->fetch()) {
//         $data = $user->delete();
//     }
//     $response->write(json_encode($data));
// });