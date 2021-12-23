<?php
require("models/user_character.php");

$model = new Character();

// User Authentication & Confirmation that Character Belongs to User

if( in_array($_SERVER["REQUEST_METHOD"], ["POST","PUT","DELETE"]) ) {

    $user = $model->routeRequiresValidation();

    if( empty($user) ) {
        header("HTTP/1.1 401 Unauthorized");
        die ('{"message":"Wrong or missing API Key"}');
    }

    if (
        !(bool)$user["is_admin"] ||
        (!empty($id) && empty( $model->getItemByUyser($id, $userId) ) )
        ) {
        header("HTTP/1.1 403 Forbidden");
        die ('{"message":"You do not have permission to perform this action"}');
    }
}

// Sanitization Method for CRUD
function sanitize($data) {
    if( !empty($data) &&
        (
            !isset($data["user_character_img"]) ||
            isset($data["user_character_img"])
        )
    ) {
        $data["user_character_name"] = trim(htmlspecialchars (strip_tags ($data["user_character_name"]) ) );
        $data["nation_id"] = trim(htmlspecialchars (strip_tags ($data["nation_id"]) ) );
        $data["user_character_classes"] = trim(htmlspecialchars (strip_tags ($data["user_character_classes"]) ) );
        $data["user_character_physical_description"] = trim(htmlspecialchars (strip_tags ($data["user_character_physical_description"]) ) );
        $data["user_character_mental_description"] = trim(htmlspecialchars (strip_tags ($data["user_character_mental_description"]) ) );
        $data["belongs_to_user"] = trim(htmlspecialchars (strip_tags ($data["belongs_to_user"]) ) );

        $sanitize_banner = trim(htmlspecialchars (strip_tags ($data["user_character_img"]) ) );
        $data["user_character_img"] = str_replace("data:image/jpeg;base64,", "", $sanitize_banner);
        return $data;
    }
    return false;
}

// Validator Method for CRUD
function validator($data) {
    if (
        !empty($data) &&
        isset($data["user_character_name"]) &&
        mb_strlen($data["user_character_name"]) >= 0 &&
        mb_strlen($data["user_character_name"]) <= 80 &&
        isset($data["nation_id"]) &&
        mb_strlen($data["nation_id"]) >= 2 &&
        mb_strlen($data["nation_id"]) <= 4 &&
        isset($data["user_character_classes"]) &&
        mb_strlen($data["user_character_classes"]) >= 3 &&
        mb_strlen($data["user_character_classes"]) <= 50 &&
        isset($data["user_character_physical_description"]) &&
        mb_strlen($data["user_character_physical_description"]) >= 0 &&
        mb_strlen($data["user_character_physical_description"]) <= 65535 &&
        isset($data["user_character_mental_description"]) &&
        mb_strlen($data["user_character_mental_description"]) >= 0 &&
        mb_strlen($data["user_character_mental_description"]) <= 65535 &&
        isset($data["belongs_to_user"]) &&
        is_numeric($data["belongs_to_user"]) &&
        ( 
            !isset($data["user_character_img"]) ||
            (isset($data["user_character_img"]) &&
            mb_strlen($data["user_character_img"]) >= 0 &&
            mb_strlen($data["user_character_img"]) <= 100)
        )
    ) {
        return true;
    }
    return false;
}

if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $data = $model->getCharacter( $id );

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