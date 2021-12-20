<?php
require("models/nation.php");
require("models/traits_to_nations.php");

$nationmodel = new Nation();
$traittonationmodel = new TraitToNation();

// Sanitization Method for CRUD
function sanitize($data) {
    if( !empty($data) &&
        (
            !isset($data["nation_banner"]) ||
            isset($data["nation_banner"])
        )
    ) {
        $data["nation_id"] = trim(htmlspecialchars (strip_tags ($data["nation_id"]) ) );
        $data["nation_name"] = trim(htmlspecialchars (strip_tags ($data["nation_name"]) ) );
        $data["nation_summary"] = trim(htmlspecialchars (strip_tags ($data["nation_summary"]) ) );
        $data["nation_description"] = trim(htmlspecialchars (strip_tags ($data["nation_description"]) ) );
        $data["nation_hub"] = trim(htmlspecialchars (strip_tags ($data["nation_hub"]) ) );
        $data["nation_hub_description"] = trim(htmlspecialchars (strip_tags ($data["nation_hub_description"]) ) );
        $data["region_id"] = trim(htmlspecialchars (strip_tags ($data["region_id"]) ) );

        $sanitize_banner = trim(htmlspecialchars (strip_tags ($data["nation_banner"]) ) );
        $data["nation_banner"] = str_replace("data:image/jpeg;base64,", "", $sanitize_banner);

        return $data;
    }
    return false;
}

// Validator Method for CRUD
function validator($data) {
    if (
        !empty($data) &&
        isset($data["nation_id"]) &&
        mb_strlen($data["nation_id"]) >= 2 &&
        mb_strlen($data["nation_id"]) <= 4 &&
        isset($data["nation_name"]) &&
        mb_strlen($data["nation_name"]) >= 6 &&
        mb_strlen($data["nation_name"]) <= 50 &&
        isset($data["nation_summary"]) &&
        mb_strlen($data["nation_summary"]) <= 255 &&
        isset($data["nation_description"]) &&
        isset($data["nation_hub"]) &&
        mb_strlen($data["nation_hub"]) >= 3 &&
        mb_strlen($data["nation_hub"]) <= 30 &&
        isset($data["nation_hub_description"]) &&
        mb_strlen($data["nation_hub_description"]) >= 0 &&
        mb_strlen($data["nation_hub_description"]) <= 65535 &&
        isset($data["region_id"]) &&
        mb_strlen($data["region_id"]) >= 2 &&
        mb_strlen($data["region_id"]) <= 4 &&
        ( 
            !isset($data["nation_banner"]) ||
            (isset($data["nation_banner"]) &&
            mb_strlen($data["nation_banner"]) >= 0 &&
            mb_strlen($data["nation_banner"]) <= 100)

            // testar se funciona, dps rever base 64!
        )
        &&
        (
            !isset($data["belongs_to"]) ||
            (isset($data["belongs_to"]) &&
            mb_strlen($data["belongs_to"]) >= 0 &&
            mb_strlen($data["belongs_to"]) <= 4)
        )
    ) {
        return true;
    }
    return false;
}

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

    if (
        validator($data) &&
        sanitize($data) &&
        $nationmodel -> create ( $data ) ) {

        header("HTTP/1.1 202 Accepted");

        echo '{"id":' . $data["nation_id"] . ', "message":"Success"}'; 

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
        $id === $data["nation_id"]
        ) {
            $result = $nationmodel->update($id, $data);
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