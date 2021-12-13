<?php
require("models/pantheon.php");

$model = new Pantheon();

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