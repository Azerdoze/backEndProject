<?php
require_once("base.php");

class Character extends base {
    
    public function get() {
        $query = $this -> db -> prepare("
        SELECT  
            user_characters.user_character_id,
            user_characters.user_character_name,           
            user_characters.user_character_classes,
            nations.nation_name,
            users.user_name AS belongs_to_user
        FROM    
            user_characters
        INNER JOIN
            nations ON (user_characters.nation_id = nations.nation_id)
        INNER JOIN
            users ON (user_characters.belongs_to_user = users.user_id)
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }

    public function getCharacter($id) {
        $query = $this -> db -> prepare("
        
        SELECT  
            user_characters.user_character_id,
            user_characters.user_character_name,
            user_characters.user_character_classes,
            nations.nation_name,
            users.user_name AS belongs_to_user
        FROM    
            user_characters
        INNER JOIN
            nations ON (user_characters.nation_id = nations.nation_id)
        INNER JOIN
            users ON (user_characters.belongs_to_user = users.user_id)
        Where
            user_characters.user_character_id = ?
        ");
        
        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
    public function create( $data ) {
        $query = $this -> db -> prepare("
            INSERT INTO user_characters
                (
                    user_character_name,
                    nation_id,
                    user_character_classes,
                    user_character_img,
                    user_character_physical_description,
                    user_character_mental_description,
                    belongs_to_user
                    )
            VALUES
            (?, ?, ?, ?, ?, ?, ?)
        ");
        $query -> execute ([
            $data["user_character_name"],
            $data["nation_id"],
            $data["user_character_classes"],
            $data["user_character_img"],
            $data["user_character_physical_description"],
            $data["user_character_mental_description"],
            $data["belongs_to_user"]
        ]);

        return $this -> db -> lastInsertId();
    }
    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                user_characters
            SET     
            user_character_name = ?,
            nation_id = ?,
            user_character_classes = ?,
            user_character_img = ?,
            user_character_physical_description = ?,
            user_character_mental_description = ?,
            belongs_to_user = ?
            WHERE
                user_character_id = ?
        ");

        return $query->execute([
            $data["user_character_name"],
            $data["nation_id"],
            $data["user_character_classes"],
            $data["user_character_img"],
            $data["user_character_physical_description"],
            $data["user_character_mental_description"],
            $data["belongs_to_user"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM user_characters
            WHERE user_character_id = ?
        ");
        
        return $query->execute([ $id ]);
    }
}