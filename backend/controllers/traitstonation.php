<?php
require("models/traits_by_nation.php");

$model = new TraitToNation();

// User Authentication & Admin Confirmation

if( in_array($_SERVER["REQUEST_METHOD"], ["POST","DELETE"]) ) {

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

        return $data;
    }
    return false;
}

// Validation METHOD for CRUD

function validator($data) {
    if(
        !empty($data) 
    ) {
        return true;
    }
    return false;
}

// POST & DELETE
if( $_SERVER["REQUEST_METHOD"] === "POST" ) {
    
    $data = json_decode( file_get_contents("php://input"), true);

    if ( validator($data) && sanitize($data) ) {
        $id = $model -> create ( $data );

        header("HTTP/1.1 202 Accepted");

        echo '{"message":"Success"}'; 
    }
    else {
        header("HTTP/1.1 400 Bad Request");

        echo '{"message":"Bad Request"}';
    }
}
else if($_SERVER["REQUEST_METHOD"] === "DELETE" ) {
    if(!empty($traitid)) {
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