<?php
require_once("base.php");

class God extends base {
    
    public function get() {
        
        $query = $this -> db ->prepare("
            SELECT  
                god_id,
                god_name,
                god_alignment,
                god_domains,
                god_mysteries,
                god_fav_weapon,
                pantheon_name AS belongs_to_pantheon
            FROM    
                gods
            INNER JOIN
                pantheons USING (pantheon_id)
        ");
        
        $query -> execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getGodsByPantheon( $id ) {
        
        $query = $this -> db ->prepare("
            SELECT  
                god_id,
                god_name,
                pantheon_name AS belongs_to_pantheon
            FROM    
                gods
            INNER JOIN
                pantheons USING (pantheon_id)
            WHERE
                pantheon_id = ?
        ");
        
        $query -> execute([$id] );

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getGod($id) {
        $query = $this -> db -> prepare("
            SELECT  
                god_name,
                god_alignment,
                god_domains,
                god_mysteries,
                god_fav_weapon,
                pantheon_id,
                pantheon_name AS belongs_to_pantheon
            FROM    gods
            INNER JOIN pantheons USING (pantheon_id)
            WHERE   god_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }

    public function create ( $data ) {

        $query = $this -> db -> prepare("
            INSERT INTO gods
            (
                god_name,
                god_alignment,
                god_domains,
                god_mysteries,
                god_fav_weapon,
                pantheon_id
            )
            VALUES
                ( ?, ?, ?, ?, ?, ?)
        ");

        $query -> execute ([
            $data["god_name"],
            $data["god_alignment"],
            $data["god_domains"],
            $data["god_mysteries"],
            $data["god_fav_weapon"],
            $data["pantheon_id"]
        ]);

        return $this -> db -> lastInsertId();
    }
    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                gods
            SET     
                god_name = ?,
                god_alignment = ?,
                god_domains = ?,
                god_mysteries = ?,
                god_fav_weapon = ?,
                pantheon_id = ?
            WHERE
                god_id = ?
        ");

        return $query->execute([
            $data["god_name"],
            $data["god_alignment"],
            $data["god_domains"],
            $data["god_mysteries"],
            $data["god_fav_weapon"],
            $data["pantheon_id"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM gods
            WHERE gods_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}