<?php
// Users
$app->get('/users', function ($request, $response, $args) use ($app, $db) {
	$users = $db->user()
	            ->select('name','email','city','date_of_birth','description');
    if($users){
        return $response->write(json_encode($users));
    }
    return $response->withStatus(500);
});
$app->get('/users/{id}', function ($request, $response, $args) use ($app, $db) {
	$user = $db->user()
	           ->select('id, name','email','city','date_of_birth','description')
			   ->where('id', $args['id'])
			   ->fetch();
    if($user){
        $user['books'] = $user->book()->select('id, title, author, year, photo, state, status');
        return $response->write(json_encode($user));
    }
    return $response->withStatus(404);
});
// secured
$app->put('/users', function ($request, $response, $args) use ($app, $db) {
    $user = $db->user()
    		   ->where('id', $this->jwt->id);
    $data = null;
    if ($user->fetch()) {
        $post = $request->getParsedBody();
        // encoding password before updating
        if(array_key_exists("password", $post)){
            $post["password"] = password_hash($post['password'] , PASSWORD_DEFAULT);
        }
        // user can't update it's userid
        if(array_key_exists("id", $post)){
            unset($post["id"]);
        }
        $data = $user->update($post);
        if($data){
            return $response->write(json_encode($data));
        }
    }
    return $response->withStatus(500);
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