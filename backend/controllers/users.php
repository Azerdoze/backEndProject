<?php
require("models/user.php");

$model = new User();

// Validation METHOD for CRUD

function validator($data) {
    if(
        !empty($data) &&
        isset($data["user_name"]) &&
        mb_strlen($data["user_name"]) >= 2 &&
        mb_strlen($data["user_name"]) <= 80 &&
        isset($data["user_email"]) &&
        mb_strlen($data["user_email"]) >= 6 &&
        mb_strlen($data["user_email"]) <= 252 &&
        isset($data["user_password"]) &&
        mb_strlen($data["user_password"]) >= 8 &&
        mb_strlen($data["user_password"]) <= 255 &&
        isset($data["user_country"]) &&
        mb_strlen($data["user_country"]) >= 2 &&
        mb_strlen($data["user_country"]) <= 50 &&
        isset($data["user_city"]) &&
        mb_strlen($data["user_city"]) >= 2 &&
        mb_strlen($data["user_city"]) <= 30
    ) {
        return true;
    }
    return false;
}

// CRUD

if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getUser( $id );

        if (!empty($data)) {
            echo json_encode ($data);
        }
        else {
            header("HTTP/1.1 404 Not Found");
            echo '{"message":"Not Found"}';
        }

    }
    else {
        echo json_encode( $model->get() );
    }
}
else if( $_SERVER["REQUEST_METHOD"] === "POST" ) {
    
    $data = json_decode( file_get_contents("php://input"), true);

    if ( !empty($data) ) {
        $id = $model -> create ( $data );

        header("HTTP/1.1 202 Accepted");

        echo '{"id":' . $id . ', "message":"Success"}'; 
    }
    else {
        header("HTTP/1.1 400 Bad Request");

        echo '{"message":"Bad Request"}';
    }
}
else if($_SERVER["REQUEST_METHOD"] === "PUT" ) {

    $data = json_decode( file_get_contents("php://input"), true );

    if(
        !empty($id) &&
        !empty($data)
        ) {
            $result = $model->update($id, $data);
            if($result) {
                header("HTTP/1.1 202 Accepted");
                echo json_encode($data);
            }
            else {
                header("HTTP/1.1 400 Bad Request");
                echo '{"message":"Bad Request"}';
            }
        }
        else {
            header("HTTP/1.1 400 Bad Request");
            echo '{"message":"Bad Request"}';
        }
}
else if($_SERVER["REQUEST_METHOD"] === "DELETE" ) {
    if(!empty($id)) {
        $result = $model->delete($id);

        if($result) {
            header("HTTP/1.1 202 Accepted");
            echo '{"message":"Deleted ID ' .$id. '"}';
        }
        else {
            header("HTTP/1.1 400 Bad Request");
            echo '{"message":"Bad Request"}';
        }
    }
}
else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo '{"message":"Method Not Allowed"}';
}