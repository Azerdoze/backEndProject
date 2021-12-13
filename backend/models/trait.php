<?php
require_once("base.php");

class NationTrait extends Base {
    public function get() {
        
        $query = $this -> db ->prepare("
            SELECT  trait_id, trait_name, trait_description
            FROM    traits
        ");
        
        $query -> execute();

        return $query->fetchAll( PDO::FETCH_ASSOC);
    }
    public function getNationTrait( $id ) {
        
        $query = $this -> db ->prepare("
            SELECT 
                trait_id,
                trait_name,
                trait_description
            FROM    traits
            WHERE
                trait_id = ?
        ");
        
        $query -> execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC);
    }
    public function create ( $data ) {

        $query = $this -> db -> prepare("
            INSERT INTO traits
            (
                trait_name,
                trait_description
            )
            VALUES
                ( ?, ?)
        ");

        $query -> execute ([
            $data["trait_name"],
            $data["trait_description"]
        ]);

        return $this -> db -> lastInsertId();
    }
}