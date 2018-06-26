<?php
use \Firebase\JWT\JWT;

class Authorizer{


    public static function getToken($user){
        $issuedAt = time();
        $expirationTime = $issuedAt + (60 * 60 * 60 * 24 * 7); // 7 days
        $payload = [
            'userid' => $user->id,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];
        $key = base64_decode(getenv('JWT_SECRET'));
        $alg = getenv('ALGORITHM');
        $jwt = JWT::encode($payload,$key,$alg);
        return $jwt;
    }

    public static function authorize($jwt){
        if(isset($_POST['jwt'])){
            
            try{
                $decoded = JWT::decode($_POST['jwt'],base64_decode(getenv('JWT_SECRET')),getenv('ALGORITHM'));
                return (array)$decoded;

            } catch(\Exception $e){
                return false;
            }
        }
    }
}