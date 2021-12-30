<?php

use ReallySimpleJWT\Token;

class Base {

    protected $db;
    
    public function __construct() {
        $this -> db = new PDO("mysql:host=localhost;dbname=projetobackend;charset=utf8mb4", "root", "");
    }

    public function routeRequiresValidation() {
        require("models/user.php");
        $userModel = new User();

        // request para servidor de apache:
        $headers = apache_request_headers();
        
        foreach($headers as $header => $value) {
            if( strtolower($header) === "x-auth-token" ) {
                $token = trim($value);
            }
        }

        $secret = "";
        $isValid = Token::validate($token, $secret);

        if($isValid) {
            $user = Token::getPayload($token, $secret);
        }

        if( !empty($user) ) {
        return $user["userId"];
        }
        
        return 0;
    }
}