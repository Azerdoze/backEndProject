<?php
require("models/god.php");

$model = new God();

// Sanitization Method for CRUD
function sanitize($data) {
    if( !empty($data) ) {
        $data["god_name"] = trim(htmlspecialchars (strip_tags ($data["god_name"]) ) );
        $data["god_alignment"] = trim(htmlspecialchars (strip_tags ($data["god_alignment"]) ) );
        $data["god_domains"] = trim(htmlspecialchars (strip_tags ($data["god_domains"]) ) );
        $data["god_mysteries"] = trim(htmlspecialchars (strip_tags ($data["god_mysteries"]) ) );
        $data["god_fav_weapon"] = trim(htmlspecialchars (strip_tags ($data["god_fav_weapon"]) ) );
        $data["pantheon_id"] = trim(htmlspecialchars (strip_tags ($data["pantheon_id"]) ) );

        return $data;
    }
    return false;
}

// Validation Method
function validator($data) {
    if(
        !empty($data) &&
        isset($data["god_name"]) &&
        mb_strlen($data["god_name"]) >= 2 &&
        mb_strlen($data["god_name"]) <= 30 &&
        isset($data["god_alignment"]) &&
        mb_strlen($data["god_alignment"]) >= 2 &&
        mb_strlen($data["god_alignment"]) <= 15 &&
        isset($data["god_domains"]) &&
        mb_strlen($data["god_domains"]) >= 8 &&
        mb_strlen($data["god_domains"]) <= 50 &&
        isset($data["god_mysteries"]) &&
        mb_strlen($data["god_mysteries"]) >= 2 &&
        mb_strlen($data["god_mysteries"]) <= 50 &&
        isset($data["god_fav_weapon"]) &&
        mb_strlen($data["god_fav_weapon"]) >= 2 &&
        mb_strlen($data["god_fav_weapon"]) <= 50 &&
        isset($data["pantheon_id"]) &&
        is_numeric($data["pantheon_id"])
    ) {
        return true;
    }
    return false;
}

if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getGod( $id );

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
else if ($_SERVER["REQUEST_METHOD"] === "POST" ) {
    
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