<?php
require("models/trait.php");

$model = new NationTrait();

// Sanitization Method for CRUD
function sanitize($data) {
    if( !empty($data) ) {
        $data["trait_name"] = trim(htmlspecialchars (strip_tags ($data["trait_name"]) ) );
        $data["trait_description"] = trim(htmlspecialchars (strip_tags ($data["trait_description"]) ) );

        return $data;
    }
    return false;
}

// Validation METHOD for CRUD

function validator($data) {
    if(
        !empty($data) &&
        isset($data["trait_name"]) &&
        mb_strlen($data["trait_name"]) >= 2 &&
        mb_strlen($data["trait_name"]) <= 50 &&
        isset($data["trait_description"]) &&
        mb_strlen($data["trait_description"]) >= 0 &&
        mb_strlen($data["trait_description"]) <= 65535
    ) {
        return true;
    }
    return false;
}

// CRUD

if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getNationTrait( $id );

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

    if ( validator($data) && sanitize($data) ) {
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