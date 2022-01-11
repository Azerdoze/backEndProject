<?php

use ReallySimpleJWT\Token;

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if(
        !empty($data) &&
        !empty($data["user_email"]) &&
        !empty($data["user_password"]) &&
        mb_strlen($data["user_email"]) <= 30 &&
        mb_strlen($data["user_password"]) <= 1000         
        ) {
            require("models/user.php");
            $model = new User;

            $user = $model -> login ($data);

            if (empty($user)) {
                header("HTTP/1.1 400 Bad Request");
                die('{"message":"Incorrect login information"}');
            }

            $payload = [
                "user_id" => $user["user_id"],
                "user_mail" => $user["user_email"],
                "user_password" => $user["user_password"],
                "user_name" => $user["user_name"],
                "user_country" => $user["user_country"],
                "user_city" => $user["user_city"],
                "is_admin" => $user["is_admin"],
                "iat" => time()
            ];

            $secret = CONFIG["SECRET_KEY"];

            $token = Token::customPayload($payload, $secret);
            
            header("X-AUTH-TOKEN: " . $token);

            echo '{
                "X-Auth-Token" : "' .$token. '"
            }';

        }
        else {
            header("HTTP/1.1 400 Bad Request");
            echo '{"message":"Incorrect login information"}';
        }
}
else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo '{"message":"Method Not Allowed"}';
}