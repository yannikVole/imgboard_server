<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/users', function (Request $request, Response $response) {
    $mapper = new User($this->db);
    $users = $mapper->getUsers();

    if($users){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code,
        'payload' => $users
    ];
    $res->withStatus($code)->write(json_encode($result));
})->add($authenticate);

$app->get('/users/{id}', function(Request $req,Response $res, $args){
    $user_id = (int)$args['id'];
    $mapper = new User($this->db);
    $user = $mapper->getUserById($user_id);
    
    if($user){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code,
        'payload' => $user
    ];
    $res->withStatus($code)->write(json_encode($result));
})->add($authenticate);

$app->delete('/users/{id}', function(Request $req,Response $res, $args){
    $user_id = (int)$args['id'];
    $mapper = new User($this->db);
    $user = $mapper->deleteUserById($user_id);

    $msg = '';
    if($mapper->deleteUserById($user)){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code
    ];
    $res->withStatus($code)->write(json_encode($result));
})->add($authenticate);

$app->post('/users', function(Request $req, Response $res){
    $mapper = new User($this->db);
    $data = $req->getParsedBody();
    $user = [];
    $user['username'] = filter_var($data['username'], FILTER_SANITIZE_STRING);

    if($mapper->getUserByUsername($user['username'])){
        return $res->withStatus(409)->write('username allready taken');
    }

    $user['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $user['password'] = password_hash($password,PASSWORD_BCRYPT);

    $msg = '';
    if($mapper->addNewUser($user)){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code
    ];
    $res->withStatus($code)->write(json_encode($result));
});

$app->put('/users/{id}', function(Request $req, Response $res, $args){
    $data = $req->getParsedBody();
    $user_id = (int)$args['id'];
    $user = [];
    $user['username'] = filter_var($data['username'], FILTER_SANITIZE_STRING);
    $user['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    $user['is_active'] = filter_var($data['is_active'], FILTER_SANITIZE_NUMBER_INT);

    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $user['password'] = password_hash($password,PASSWORD_BCRYPT);

    $mapper = new User($this->db);
    if($mapper->updateUserById($user,$user_id)){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code
    ];
    $res->withStatus($code)->write(json_encode($result));
})->add($authenticate);

$app->post('/users/login', function(Request $req, Response $res){
    $data = $req->getParsedBody();
    $mapper = new User($this->db);

    $user = $mapper->getUserByUsername($data['username']);
    
    if(password_verify($data['password'],$user->password)){
        $jwt = Authorizer::getToken($user);
        $res->getBody()->write(json_encode(array(
            'code' => 200,
            'status' => 'success',
            'message' => 'valid login credentials',
            'user_id' => $user->id,
            'jwt' => $jwt
        )));
    }else{
        $res->getBody()->write(json_encode(array(
            'code' => 666,
            'status' => 'error',
            'message' => 'INVALID LOGIN CREDENTIALS'
        )));
    }
});

//move to new file?
$app->get('/users/{id}/threads', function(Request $req,Response $res, $args){
    $user_id = (int)$args['id'];
    $mapper = new pThread($this->db);
    $threads = $mapper->getThreadsByUserId($user_id);

    if($threads){
        return $res->write(json_encode($threads));
    } else {
        $res->write('{msg:error}');
    }
})->add($authenticate);

