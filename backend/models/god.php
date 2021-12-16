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
            SELECT  
                god_name AS name,
                god_alignment AS alignment,
                god_domains AS domains,
                god_mysteries AS mysteries,
                god_fav_weapon AS favoured_weapon,
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
}