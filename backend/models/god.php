<?php
require_once("base.php");

class God extends base {
    
    public function get() {
        
        $query = $this -> db ->prepare("
            SELECT  
                god_id,
                god_name,
                pantheon_name AS belongs_to_pantheon
            FROM    
                gods
            INNER JOIN
                pantheons USING (pantheon_id)
        ");
        
        $query -> execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getGod($id) {
        $query = $this -> db -> prepare("
            SELECT  god_id, god_name
            FROM    gods
            WHERE   god_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
}