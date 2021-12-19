<?php
require_once("base.php");

class Nation extends base {
    
    public function get() {
        $query = $this -> db -> prepare("
            SELECT  
                nations.nation_id,
                nations.nation_name,
                nations.nation_summary,
                nations.nation_hub,
                regions.region_name,
                parentnations.nation_name AS parent_nation
            FROM    
                nations
            INNER JOIN
                regions ON (nations.region_id = regions.region_id)
            LEFT JOIN
                nations AS parentnations ON (nations.belongs_to = parentnations.nation_id)
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getNation($id) {
        $query = $this -> db -> prepare("
        SELECT  
            nations.nation_id,
            nations.nation_name,
            nations.nation_summary,
            nations.nation_description,
            nations.nation_hub,
            nations.nation_hub_description,
            nations.nation_banner,
            nations.region_id,
            nations.belongs_to,
            regions.region_name,
            parentnations.nation_name AS parent_nation
        FROM    
            nations
        INNER JOIN
            regions ON (nations.region_id = regions.region_id)
        LEFT JOIN
            nations AS parentnations ON (nations.belongs_to = parentnations.nation_id)
        WHERE
            nations.nation_id = ?
        ");
        
        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }

    public function create ( $data ) {

        $query = $this -> db -> prepare ("
            INSERT INTO nations
                (
                    nation_id,
                    nation_name,
                    nation_summary,
                    nation_description,
                    nation_hub,
                    nation_hub_description,
                    nation_banner,
                    region_id,
                    belongs_to
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $query -> execute ([
            $data["nation_id"],
            $data["nation_name"],
            $data["nation_summary"],
            $data["nation_description"],
            $data["nation_hub"],
            $data["nation_hub_description"],
            $data["nation_banner"],
            $data["region_id"],
            $data["belongs_to"]
        ]);

    }

    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                nations
            SET     
                nation_name = ?,
                nation_summary = ?,
                nation_description = ?,
                nation_hub = ?,
                nation_hub_description = ?,
                nation_banner = ?,
                region_id = ?,
                belongs_to = ?
            WHERE
                nation_id = ?
        ");

        return $query->execute([
            $data["nation_name"],
            $data["nation_summary"],
            $data["nation_description"],
            $data["nation_hub"],
            $data["nation_hub_description"],
            $data["nation_banner"],
            $data["region_id"],
            $data["belongs_to"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM nations
            WHERE nation_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}