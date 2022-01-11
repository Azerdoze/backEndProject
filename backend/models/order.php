<?php
require_once("base.php");

class Order extends base {
    
    public function get() {
        $query = $this -> db -> prepare("
            SELECT  
                order_id,
                order_name,
                order_summary,
                order_goals,
                order_enemies
            FROM    orders
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getOrder($id) {
        $query = $this -> db -> prepare("
            SELECT  
                order_id,
                order_name,
                order_official_name,
                order_summary,
                order_description,
                order_scope,
                order_alignment,
                order_headquarters,
                order_values,
                order_goals,
                order_allies,
                order_enemies,
                order_rivals
            FROM    orders
            WHERE   order_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
    public function create ( $data ) {

        $query = $this -> db -> prepare("
            INSERT INTO orders
            (
                order_name,
                order_official_name,
                order_summary,
                order_description,
                order_scope,
                order_alignment,
                order_headquarters,
                order_values,
                order_goals,
                order_allies,
                order_enemies,
                order_rivals
            )
            VALUES
                ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $query -> execute ([
            $data["order_name"],
            $data["order_official_name"],
            $data["order_summary"],
            $data["order_description"],
            $data["order_scope"],
            $data["order_alignment"],
            $data["order_headquarters"],
            $data["order_values"],
            $data["order_goals"],
            $data["order_allies"],
            $data["order_enemies"],
            $data["order_rivals"]
        ]);

        return $this -> db -> lastInsertId();
    }
    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                orders
            SET     
                order_name = ?,
                order_official_name = ?,
                order_summary = ?,
                order_description = ?,
                order_scope = ?,
                order_alignment = ?,
                order_headquarters = ?,
                order_values = ?,
                order_goals = ?,
                order_allies = ?,
                order_enemies = ?,
                order_rivals = ?
            WHERE
                order_id = ?
        ");

        return $query->execute([
            $data["order_name"],
            $data["order_official_name"],
            $data["order_summary"],
            $data["order_description"],
            $data["order_scope"],
            $data["order_alignment"],
            $data["order_headquarters"],
            $data["order_values"],
            $data["order_goals"],
            $data["order_allies"],
            $data["order_enemies"],
            $data["order_rivals"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM orders
            WHERE order_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}