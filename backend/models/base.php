<?php

use ReallySimpleJWT\Token;

class Base {

    protected $db;
    
    public function __construct() {
        // $this -> db = new PDO("mysql:host=localhost;dbname=projetobackend;charset=utf8mb4", "root", "");
        $host = 'arthan-uthyl-reborn.c69jaifibnpl.eu-west-3.rds.amazonaws.com';
        $db = 'projetobackend';
        $port = '3306';
        $charset = 'utf8mb4';
        $user = 'eurico';
        $pass = '12341234';
        
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port;";
        
        $this -> db = new PDO($dsn, $user, $pass);
        // $this -> db = new PDO("mysql:host=$host;dbname=$db;charset=$charset;port=$port", "$user", "$pass");
        // $this -> db = new PDO("mysql:host=arthan-uthyl-reborn.c69jaifibnpl.eu-west-3.rds.amazonaws.com;dbname=projetobackend;charset=utf8mb4;port=3306", "euricor", "12341234");
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

    public function adminValidation() {

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
        return $user["is_admin"];
        }
        
        return 0;
    }
}