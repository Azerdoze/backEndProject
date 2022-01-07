<?php
require_once("base.php");

class TraitToNation extends Base {
    
    public function getTraitsByNation($id) {
        $query = $this -> db -> prepare("
            SELECT  
                traits.trait_name,
                traits.trait_description
            FROM    
                traits_by_nation
            INNER JOIN
                nations USING (nation_id)
            INNER JOIN
                traits USING (trait_id)
            WHERE   traits_by_nation.nation_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    // where to put?
    public function create( $data ) {

        $query = $this->db->prepare("
            INSERT INTO traits_by_nation
            ( trait_id, nation_id)
            VALUES(?, ?)
        ");

        $query->execute([
            $data["trait_id"],
            $data["nation_id"]
        ]);

        return $this->db->lastInsertId();
    }

    // change table to have a key equal do both trait_id+nation_id
}