<?php
require_once("base.php");

class TraitToNation extends Base {
    
    public function getTraitsByNation($id) {
        $query = $this -> db -> prepare("
            SELECT  
                traits.trait_id,
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

    public function delete( $traitid, $nationid ) {

        $query = $this->db->prepare("
            DELETE FROM traits_by_nation
            WHERE trait_id = ? AND nation_id = ?
        ");
            
        $id = $query->execute([
            $traitid,
            $nationid
        ]);

        return $id;
    }
}