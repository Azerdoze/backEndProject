<?php
require_once("base.php");

class User extends base {

    public function get() {
        $query = $this -> db -> prepare("
            SELECT 
                user_id, 
                user_name,
                user_email,
                user_password,
                user_country,
                user_city,
                is_admin,
                newsletter
            FROM    users
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }
    public function getUser($id) {
        $query = $this -> db -> prepare("
            SELECT 
                user_id, 
                user_name,
                user_email,
                user_password,
                user_country,
                user_city,
                is_admin,
                newsletter
            FROM    users
            WHERE   user_id = ?
        ");

        $query->execute([ $id ]);

        return $query->fetch( PDO::FETCH_ASSOC );
    }
    public function create( $data ) {
        $query = $this -> db -> prepare("
            INSERT INTO users
                (
                    user_name,
                    user_email,
                    user_password,
                    user_country,
                    user_city,
                    newsletter
                    )
            VALUES
            (?, ?, ?, ?, ?, ?)
        ");
        $query -> execute ([
            $data["user_name"],
            $data["user_email"],
            $data["user_password"],
            $data["user_country"],
            $data["user_city"],
            $data["newsletter"]
        ]);

        return $this -> db -> lastInsertId();
    }
}