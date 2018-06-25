<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/users', function (Request $request, Response $response) {
    $mapper = new User($this->db);
    $users = $mapper->getUsers();
    $response->getBody()->write(json_encode($users));
    return $response;
});

$app->get('/users/{id}', function(Request $req,Response $res, $args){
    $user_id = (int)$args['id'];
    $mapper = new User($this->db);
    $user = $mapper->getUserById($user_id);
    $res->getBody()->write(json_encode($user));
});

$app->delete('/users/{id}', function(Request $req,Response $res, $args){
    $user_id = (int)$args['id'];
    $mapper = new User($this->db);
    $user = $mapper->deleteUserById($user_id);

    $msg = '';
    if($mapper->deleteUserById($user)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write('{msg:'.$msg.'}');
});

$app->post('/users', function(Request $req, Response $res){
    $data = $req->getParsedBody();
    $user = [];
    $user['username'] = filter_var($data['username'], FILTER_SANITIZE_STRING);
    $user['email'] = filter_var($data['email'], FILTER_SANITIZE_STRING);
    
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
    $user['password'] = password_hash($password,PASSWORD_BCRYPT);

    $mapper = new User($this->db);
    $msg = '';
    if($mapper->addNewUser($user)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write('{msg:'.$msg.'}');
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
    $msg = '';
    if($mapper->updateUserById($user,$user_id)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write('{"msg":"'.$msg.'"}');
});

$app->post('/users/login', function(Request $req, Response $res){
    //TODO: login stuff
});