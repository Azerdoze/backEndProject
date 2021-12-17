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
                pantheon_id,
                pantheon_name,
                pantheon_summary,
                pantheon_description,
                pantheon_scope
            FROM
                pantheons
            WHERE
            pantheon_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
    public function create( $data ) {

        $query = $this->db->prepare("
            INSERT INTO pantheons
            ( 
                pantheon_name,
                pantheon_summary,
                pantheon_description,
                pantheon_scope
            )
            VALUES(?, ?, ?, ?)
        ");

        $query->execute([
            $data["pantheon_name"],
            $data["pantheon_summary"],
            $data["pantheon_description"],
            $data["pantheon_scope"]
        ]);

        return $this->db->lastInsertId();
    }
    public function update( $id, $data ) {
        $query = $this -> db -> prepare ("
            UPDATE
                pantheons
            SET
                pantheon_name = ?,
                pantheon_summary = ?,
                pantheon_description = ?,
                pantheon_scope = ?
            WHERE
                pantheon_id = ?
        ");

        return $query->execute ([
            $data["pantheon_name"],
            $data["pantheon_summary"],
            $data["pantheon_description"],
            $data["pantheon_scope"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM pantheons
            WHERE pantheon_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}