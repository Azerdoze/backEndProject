<?php
require_once("base.php");

class Region extends base {
    
    public function get() {
        $query = $this -> db -> prepare("
            SELECT  region_id, region_name
            FROM    regions
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getRegion($id) {
        $query = $this -> db -> prepare("
            SELECT  region_id, region_name
            FROM    regions
            WHERE   region_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }

    public function create ( $data ) {
        $query = $this->db->prepare("
            INSERT INTO regions
            (region_id, region_name, region_description)
            VALUES(?, ?)
        ");
        $query->execute([
            $data["region_id"],
            $data["region_name"],
            $data["region_description"]
        ]);

        return $this->db->lastInsertId();
    }
}