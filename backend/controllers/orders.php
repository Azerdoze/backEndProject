<?php
require("models/order.php");

$model = new Order();


// Validation Method for CRUD
function validator($data) {
    if(
        !empty($data) &&
        isset($data["order_name"]) &&
        mb_strlen($data["order_name"]) >= 3 &&
        mb_strlen($data["order_name"]) <= 30 &&
        isset($data["order_official_name"]) &&
        mb_strlen($data["order_official_name"]) >= 3 &&
        mb_strlen($data["order_official_name"]) <= 30 &&
        isset($data["order_summary"]) &&
        mb_strlen($data["order_summary"]) >= 0 &&
        mb_strlen($data["order_summary"]) <= 150 &&
        isset($data["order_description"]) &&
        mb_strlen($data["order_description"]) >= 0 &&
        mb_strlen($data["order_description"]) <= 65535 &&
        isset($data["order_banner"]) &&
        mb_strlen($data["order_banner"]) >= 0 &&
        mb_strlen($data["order_banner"]) <= 100 &&
        isset($data["order_scope"]) &&
        mb_strlen($data["order_scope"]) >= 3 &&
        mb_strlen($data["order_scope"]) <= 30 &&
        isset($data["order_alignment"]) &&
        mb_strlen($data["order_alignment"]) >= 2 &&
        mb_strlen($data["order_alignment"]) <= 20 &&
        isset($data["order_headquarters"]) &&
        mb_strlen($data["order_headquarters"]) >= 3 &&
        mb_strlen($data["order_headquarters"]) <= 30 &&
        isset($data["order_values"]) &&
        mb_strlen($data["order_values"]) >= 3 &&
        mb_strlen($data["order_values"]) <= 60 &&
        isset($data["order_goals"]) &&
        mb_strlen($data["order_goals"]) >= 3 &&
        mb_strlen($data["order_goals"]) <= 150 &&
        isset($data["order_allies"]) &&
        mb_strlen($data["order_allies"]) >= 3 &&
        mb_strlen($data["order_allies"]) <= 80 &&
        isset($data["order_enemies"]) &&
        mb_strlen($data["order_enemies"]) >= 3 &&
        mb_strlen($data["order_enemies"]) <= 80 &&
        isset($data["order_rivals"]) &&
        mb_strlen($data["order_rivals"]) >= 3 &&
        mb_strlen($data["order_rivals"]) <= 80 &&
        (
            !isset($data["order_banner"]) ||
            (
                isset($data["order_banner"]) &&
                mb_strlen($data["order_banner"]) >= 0 &&
                mb_strlen($data["order_banner"]) <= 100
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
        $data = $model->getOrder( $id );

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

    if ( validator($data) ) {
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
        validator($id) &&
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