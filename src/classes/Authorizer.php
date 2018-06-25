<?php
use \Firebase\JWT\JWT;

class Authorizer{

    public static $SECRET_KEY = 'dakommtihrniedrauf';

    public static function getToken($user){
        $issuedAt = time();
        $expirationTime = $issuedAt + (60 * 60 * 60 * 24 * 7); // 7 days
        $payload = [
            'userid' => $user->id,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];
        $key = self::$SECRET_KEY;
        $alg = 'HS256';
        $jwt = JWT::encode($payload,$key,$alg);
        return $jwt;
    }

    public static function authorize($jwt){
        if(isset($_POST['jwt'])){
            
            try{
                $decoded = JWT::decode($_POST['jwt'],self::$SECRET_KEY,['HS256']);
                return (array)$decoded;

            } catch(\Exception $e){
                return false;
            }
        }
    }
}