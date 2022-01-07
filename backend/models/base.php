<?php

use ReallySimpleJWT\Token;

class Base {

    protected $db;
    
    public function __construct() {
        $this -> db = new PDO("mysql:host=localhost;dbname=projetobackend;charset=utf8mb4", "root", "");
    }

    public function routeRequiresValidation() {

        // request to the apache server:
        $headers = apache_request_headers();
        
        foreach($headers as $header => $value) {
            if( strtolower($header) === "x-auth-token" ) {
                $token = trim($value);
            }
        }

        $secret = CONFIG["SECRET_KEY"];

        $isValid = Token::validate($token, $secret);

        if($isValid) {
            $user = Token::getPayload($token, $secret);
        }

        if( isset($user) ) {
        return $user;
        }
        
        return 0;
    }
}