<?php
require_once("base.php");

class TraitToNation extends base {
    
    public function getTraitToNation($id) {
        $query = $this -> db -> prepare("
            SELECT  
                traits.trait_name,
                traits.trait_description
            FROM    
                traits_to_nations
            INNER JOIN
                nations USING (nation_id)
            INNER JOIN
                traits USING (trait_id)
            WHERE   traits_to_nations.nation_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    // where to put?
    public function create( $data ) {

        $query = $this->db->prepare("
            INSERT INTO traits_to_nations
            ( trait_id, nation_id)
            VALUES(?, ?)
        ");

        $query->execute([
            $data["trait_id"],
            $data["nation_id"]
        ]);

        return $this->db->lastInsertId();
    }
    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                traits_to_nations
            SET
                trait_id = ?,
                nation_id = ?
            WHERE
                traits_to_nations_id = ?
        ");

        return $query->execute([
            $data["trait_id"],
            $data["nation_id"],
            $id
        ]);
    }
}