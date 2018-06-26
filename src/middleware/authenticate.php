<?php
use \Firebase\JWT\JWT;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$authenticate = function(Request $request, Response $response,$next){
    $headers = $request->getHeaders();
    $result = array();

    if($request->hasHeader('HTTP_AUTHORIZATION')){
        try{
            $secretKey = base64_decode(getenv('JWT_SECRET'));
            $decoded = JWT::decode(substr($headers['HTTP_AUTHORIZATION'][0],7),$secretKey,array(getenv('ALGORITHM')));

            $result['error'] = false;
            $result['data'] = json_encode($decoded);

            $response = $next($request,$response);

            return $response;
        } catch(\Exception $e){
            return $response->withStatus(401)->write('Unauthorized. Invalid Token!'.$e);
        }
    } else {
        return $response->withStatus(401)->write('Unauthorized. Invalid Token');
    }
};