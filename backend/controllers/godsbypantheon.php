<?php
require ("models/god.php");

$model = new God();

if($_SERVER["REQUEST_METHOD"] === "GET" ) {

    if( isset( $id )) {
    
        $data = $model->getGodsByPantheon( $id );

        if (!empty($data)) {
            echo json_encode ($data);
        }
        else {
            header("HTTP/1.1 404 Not Found");
            echo '{"message":"Not Found"}';
        }
    }
    else {
        header("HTTP/1.1 405 Method Not Allowed");
        echo '{"message":"Method Not Allowed"}';
    }
}
else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo '{"message":"Method Not Allowed"}';
}