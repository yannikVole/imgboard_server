<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/threads', function(Request $req, Response $res){
    $mapper = new pThread($this->db);
    $threads = $mapper->getThreads();

    if($threads){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code,
        'payload' => $threads
    ];

    $res->withStatus($code)->write(json_encode($result));
    return $res;
})->add($authenticate);

$app->get('/threads/{id}', function(Request $req, Response $res, $args){
    $mapper = new pThread($this->db);
    $id = (int)$args['id'];
    $thread = $mapper->getThreadById($id);
    if($thread){
        $msg = 'success';
        $code = 200;
    } else {
        $msg = 'error';
        $code = 409;
    }

    $result = [
        'msg' => $msg,
        'status' => $code,
        'payload' => $thread
    ];

    $res->withStatus($code)->write(json_encode($result));
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
    return $res;
})->add($authenticate);

$app->delete('/threads/{id}', function(Request $req, Response $res, $args){
    $id = (int)$args['id'];
    
    $mapper = new pThread($this->db);

    if($mapper->deleteThreadById($id)){
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
    return $res;
})->add($authenticate);

$app->post('/threads/{id}/comments', function(Request $req, Response $res, $args){
    $data = $req->getParsedBody();
    $threadId = (int)$args['id'];
    $comment = filter_var($data['body'], FILTER_SANITIZE_STRING);

    $mapper = new pThread($this->db);
    if($mapper->addCommentToThread($threadId,$comment)){
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

