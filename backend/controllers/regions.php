<?php
require("models/region.php");

$model = new Region();

// User Authentication & Admin Confirmation

if( in_array($_SERVER["REQUEST_METHOD"], ["POST","PUT","DELETE"]) ) {

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
        $data["region_id"] = trim(htmlspecialchars (strip_tags ($data["region_id"]) ) );
        $data["region_name"] = trim(htmlspecialchars (strip_tags ($data["region_name"]) ) );
        $data["region_description"] = trim(htmlspecialchars (strip_tags ($data["region_description"]) ) );

        return $data;
    }
    return false;
}

// Validation METHOD for CRUD
function validator($data) {
    if(
        !empty($data) &&
        isset($data["region_id"]) &&
        mb_strlen($data["region_id"]) >= 2 &&
        mb_strlen($data["region_id"]) <= 4 &&
        isset($data["region_name"]) &&
        mb_strlen($data["region_name"]) >= 2 &&
        mb_strlen($data["region_name"]) <= 30 &&
        isset($data["region_description"]) &&
        mb_strlen($data["region_description"]) >= 0 &&
        mb_strlen($data["region_description"]) <= 65535
    ) {
        return true;
    }
    return false;
}

// CRUD
if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getRegion( $id );

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
else if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode( file_get_contents("php://input"), true);

    if (
        validator($data) &&
        sanitize($data) &&
        $model -> create( $data ) 
        ) {

        header("HTTP/1.1 202 Accepted");

        echo '{"id":"' . $data["region_id"] . '", "message":"Success"}';
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
        validator($data) &&
        sanitize($data) &&
        $id === $data["region_id"]
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