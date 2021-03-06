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
            SELECT  region_id, region_name, region_description
            FROM    regions
            WHERE   region_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }

    // not working, returning a status code 200 and nothing else
    public function create ( $data ) {

        $query = $this->db->prepare("
            INSERT INTO regions
            (
                region_id,
                region_name,
                region_description
            )
            VALUES (?, ?, ?)
        ");

        return $query->execute([
            $data["region_id"],
            $data["region_name"],
            $data["region_description"]
        ]);

    }

    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                regions
            SET
                region_name = ?,
                region_description = ?
            WHERE
                region_id = ?
        ");

        return $query->execute([
            $data["region_name"],
            $data["region_description"],
            $id
        ]);
    }

    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM regions
            WHERE region_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}