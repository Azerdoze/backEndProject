<?php
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
            if( strtolower($header) === "x-api-key" ) {
                $api_key = trim($value);
            }
        }

        if( !empty($api_key) ) {
            $user = $userModel->isValidUser($api_key);
        }

        if( !empty($user) ) {
        return $user;
        }
        
        return 0;
    }
}