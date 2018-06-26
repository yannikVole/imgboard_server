<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \imgb\Thread as pThread;

$app->get('/threads', function(Request $req, Response $res){
    $mapper = new pThread($this->db);
    $threads = $mapper->getThreads();

    $res->getBody()->write(json_encode($threads));
    return $res;
})->add($authenticate);

$app->get('/threads/{id}', function(Request $req, Response $res, $args){
    $mapper = new pThread($this->db);
    $id = $args['id'];
    $thread = $mapper->getThreadById($id);

    $res->getBody()->write(json_encode($thread));
    return $res;
})->add($authenticate);

$app->post('/threads', function(Request $req, Response $res){
    $data = $req->getParsedBody();
    $thread = [];
    $thread['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $thread['body'] = filter_var($data['body'], FILTER_SANITIZE_STRING);
    $thread['user_id'] = filter_var($data['user_id'], FILTER_SANITIZE_NUMBER_INT);

    $mapper = new pThread($this->db);
    $msg = '';
    if($mapper->addNewThread($thread)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write('{msg:'.$msg.'}');
    return $res;
})->add($authenticate);

$app->put('/threads/{id}', function(Request $req, Response $res, $args){
    $data = $req->getParsedBody();
    $thread = [];
    $threadId = (int)$args['id'];
    $thread['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $thread['body'] = filter_var($data['body'], FILTER_SANITIZE_STRING);

    $mapper = new pThread($this->db);
    $msg = '';
    if($mapper->updateThreadById($user,$threadId)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write('{msg:'.$msg.'}');
    return $res;
})->add($authenticate);

$app->delete('/threads/{id}', function(Request $req, Response $res, $args){
    $id = $args['id'];
    
    $mapper = new pThread($this->db);
    $msg = '';
    if($mapper->deleteThreadById($id)){
        $msg = 'success';
    } else {
        $msg = 'error';
    }

    $res->getBody()->write(json_encode($thread));
    return $res;
})->add($authenticate);