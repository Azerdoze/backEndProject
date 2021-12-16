<?php
require_once("base.php");

class User extends base {

    public function getUserLogin( $id ) {
        if(
            filter_var($id["email"], FILTER_VALIDATE_EMAIL) &&
            mb_strlen($id["password"]) >= 8 &&
            mb_strlen($id["password"]) <= 1000 
        ) {
            $query = $this->db->prepare("
                SELECT  user_id, user_name, user_password
                FROM    users
                WHERE   email = ?
            ");

            $query->execute([
                $id["email"]
            ]);

            return $query->fetch();
            
        }

        return [];
    }

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
    
    public function getUser( $id ) {
        $query = $this -> db -> prepare("
            SELECT 
                user_id, 
                user_name,
                user_email,
                user_password,
                user_country,
                user_city
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
                        user_city
                        )
                VALUES
                (?, ?, ?, ?, ?, ?)
            ");

            $query -> execute ([
                $data["user_name"],
                $data["user_email"],
                password_hash($data["user_password"], PASSWORD_DEFAULT),
                $data["user_country"],
                $data["user_city"]
            ]);
            // erro no password_hash? porquê?
            return $this -> db -> lastInsertId();

    }
    public function update( $id, $data ) {
        $query = $this->db->prepare("
            UPDATE
                users
            SET
                user_name = ?,
                user_email = ?,
                user_password = ?,
                user_country = ?,
                user_city = ?
            WHERE
                user_id = ?
        ");

        return $query->execute([
            $data["user_name"],
            $data["user_email"],
            $data["user_password"],
            $data["user_country"],
            $data["user_city"],
            $id
        ]);
    }
}