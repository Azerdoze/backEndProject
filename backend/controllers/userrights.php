<?php
require("models/user.php");

$model = new User();

// User Authentication & Admin Confirmation
if( in_array($_SERVER["REQUEST_METHOD"], ["POST"]) ) {

    $user = $model->routeRequiresValidation();

    if( empty($user) ) {
        header("HTTP/1.1 401 Unauthorized");
        die ('{"message":"Wrong or missing Auth Token"}');
    }

    if ( !(bool)$user["is_admin"] ) {
        header("HTTP/1.1 403 Forbidden");
        die ('{"message":"You do not have permission to perform this action"}');
    }
}

// Sanitization Method for CRUD

function sanitize($data) {
    if( !empty($data) ) {
        $data["user_id"] = trim(htmlspecialchars (strip_tags ($data["is_admin"]) ) );
        return $data;
    }
    return false;
}

// Validation METHOD for CRUD

function validator($data) {
    if(
        !empty($data) &&
        isset($data["user_id"])
    ) {
        return true;
    }
    return false;
}

// CRUD

    if($_SERVER["REQUEST_METHOD"] === "POST" ) {

        $data = json_decode( file_get_contents("php://input"), true );
        $result = $model->makeAdmin($data["user_id"]);
        if ( $result ) {
            header("HTTP/1.1 202 Accepted");
            echo '{
                "message":'.($result ? "true" : "false").'
            }';
        }
        // if(
        //     !empty($id) &&
        //     validator($data) &&
        //     sanitize($data)
        // ) {
            
        //     $result = $model->updateRights($id, $data);
        //     if($result) {
        //         header("HTTP/1.1 202 Accepted");
        //         echo json_encode($data);
        //     }
        //     else {
        //         header("HTTP/1.1 400 Bad Request");
        //         echo '{"message":"Bad Request"}';
        //     }
        // }
        // else {
        //     header("HTTP/1.1 400 Bad Request");
        //     echo '{"message":"Bad Request"}';
        // }
    }
    else {
        header("HTTP/1.1 405 Method Not Allowed");
        echo '{"message":"Method Not Allowed"}';
    }