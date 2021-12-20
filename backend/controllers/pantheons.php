<?php
require("models/pantheon.php");

$model = new Pantheon();

// Sanitization Method for CRUD
function sanitize($data) {
    if(
        !empty($data) &&
        (
            !isset($data["pantheon_banner"]) ||
            isset($data["pantheon_banner"])
        )
    ) {
        $data["pantheon_name"] = trim(htmlspecialchars (strip_tags ($data["pantheon_name"]) ) );
        $data["pantheon_summary"] = trim(htmlspecialchars (strip_tags ($data["pantheon_summary"]) ) );
        $data["pantheon_description"] = trim(htmlspecialchars (strip_tags ($data["pantheon_description"]) ) );
        $data["pantheon_scope"] = trim(htmlspecialchars (strip_tags ($data["pantheon_scope"]) ) );

        $sanitize_banner = trim(htmlspecialchars (strip_tags ($data["pantheon_banner"]) ) );
        $data["pantheon_banner"] = str_replace("data:image/jpeg;base64,", "", $sanitize_banner);

        return $data;
    }
    return false;
}

// Validation METHOD for CRUD
function validator($data) {
    if(
        !empty($data) &&
        isset($data["pantheon_name"]) &&
        mb_strlen($data["pantheon_name"]) >= 2 &&
        mb_strlen($data["pantheon_name"]) <= 30 &&
        isset($data["pantheon_summary"]) &&
        mb_strlen($data["pantheon_summary"]) >= 2 &&
        mb_strlen($data["pantheon_summary"]) <= 200 &&
        isset($data["pantheon_description"]) &&
        mb_strlen($data["pantheon_description"]) >= 0 &&
        mb_strlen($data["pantheon_description"]) <= 65535 &&
        isset($data["pantheon_scope"]) &&
        mb_strlen($data["pantheon_scope"]) >= 2 &&
        mb_strlen($data["pantheon_scope"]) <= 50 &&
        ( 
            !isset($data["pantheon_banner"]) ||
            (
                isset($data["pantheon_banner"]) &&
                mb_strlen($data["pantheon_banner"]) >= 0 &&
                mb_strlen($data["pantheon_banner"]) <= 100
            )
        )
    ) {
        return true;
    }
    return false;
}


// CRUD
if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getPantheon( $id );

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
else if($_SERVER["REQUEST_METHOD"] === "POST" ) {

    $data = json_decode( file_get_contents("php://input"), true );

    if( validator($data) && sanitize($data) ) {

        $id = $model->create( $data );

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
        validator($data) &&
        sanitize($data)
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