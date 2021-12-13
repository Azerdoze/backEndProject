<?php
require_once("base.php");

class Pantheon extends base {
    
    public function get() {
        $query = $this -> db -> prepare("
            SELECT  
                pantheon_id, pantheon_name
            FROM    
                pantheons
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getPantheon($id) {
        $query = $this -> db -> prepare("
            SELECT
                pantheon_id, pantheon_name
            FROM
                pantheons
            WHERE
            pantheon_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
}