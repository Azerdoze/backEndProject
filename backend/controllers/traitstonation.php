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

// Sanitization Method for POST
function sanitize($data) {
    if( !empty($data) ) {

        $data["trait_id"] = trim(htmlspecialchars (strip_tags ($data["trait_id"]) ) );
        $data["nation_id"] = trim(htmlspecialchars (strip_tags ($data["nation_id"]) ) );

        return $data;
    }
    return false;
}

// Sanitization Method for DELETE
function sanitizeForDelete ($traitid, $nationid) {
    if( !empty($traitid) & !empty($nationid) ) {
        $traitid = trim(htmlspecialchars (strip_tags ($traitid) ) );
        $nationid = trim(htmlspecialchars (strip_tags ($nationid) ) );

        return $traitid & $nationid;
    }
    return false;
}

// Validation METHOD for POST

function validator($data) {
    if(
        !empty($data) &&
        isset($data["nation_id"]) &&
        mb_strlen($data["nation_id"]) >= 2 &&
        mb_strlen($data["nation_id"]) <= 4 &&
        isset($data["trait_id"]) &&
        is_numeric($data["trait_id"])
    ) {
        return true;
    }
    return false;
}

// Validation METHOD for DELETE

function validatorForDelete($traitid, $nationid) {
    if(
        !empty($traitid) &&
        isset($traitid) &&
        is_numeric($traitid) &&
        !empty($nationid) &&
        isset($nationid) &&
        mb_strlen($nationid) >= 2 &&
        mb_strlen($nationid) <= 4
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

    $idsplit = explode("&", $id);

    $traitid = $idsplit[0];
    $nationid = $idsplit[1];
    
    if(
        sanitizeForDelete($traitid, $nationid) &&
        validatorForDelete($traitid, $nationid)
        ) {

        $result = $model->delete($traitid,$nationid);

        if($result) {
            header("HTTP/1.1 202 Accepted");
            echo '{"message":"Item Deleted Sucessfully"}';
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