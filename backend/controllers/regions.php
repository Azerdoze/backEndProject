<?php
require("models/region.php");

$model = new Region();

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

    if ( validator($data) && $model -> create( $data ) ) {

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