<?php
require("models/nation.php");
require("models/traits_to_nations.php");

$nationmodel = new Nation();
$traittonationmodel = new TraitToNation();

if($_SERVER["REQUEST_METHOD"] === "GET" ) {
    if( isset( $id )) {
        $nation = $nationmodel->getNation( $id );
        
        if (!empty($nation)) {

            $traits = $traittonationmodel->getTraitToNation($id);

            $data = $nation;

            $data["traits"] = $traits;

            if(!empty($data))
            echo json_encode ($data);
        }
        else {
            header("HTTP/1.1 404 Not Found");
            echo '{"message":"Not Found"}';
        }
    }
    else {
        echo json_encode( $nationmodel->get() );
    }
}
else if( $_SERVER["REQUEST_METHOD"] === "POST" ) {
    
    $data = json_decode( file_get_contents("php://input"), true);

    if ( !empty($data) ) {
        $id = $nationmodel -> create ( $data );

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