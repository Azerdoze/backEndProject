<?php

    // devolver todo o texto em JSON
    header("Content-Type: application/json");
    
    // load the .env as a CONFIG file
    define ("CONFIG", parse_ini_file(".env"));

    // carregar o JWT 
    require("../vendor/autoload.php");

    /* criação de CONSTANTE + costumização para remover um "/" do final */ 
        define("ROOT",
            rtrim(
                str_replace(
                    "\\",
                    "/",
                    dirname($_SERVER["SCRIPT_NAME"])
                ),
            "/")
        );

        $url_parts = explode("/", $_SERVER["REQUEST_URI"]);

        $controllers = [
            "regions",
            "nations",
            "nationsbyregion",
            "traits",
            "pantheons",
            "gods",
            "godsbypantheon",
            "orders",
            "users",
            "user_characters",
            "login"
        ];

        $controller = $url_parts[2];
        
        if(!empty($url_parts[3])) {
            $id = $url_parts[3];
        } 

        if( !in_array($controller, $controllers) ) {
            header("HTTP/1.1 400 Bad Request");
            die('{"message":"Invalid Route"}');
        }

    require ("controllers/" . $controller . ".php");