<?php
require_once("base.php");

class User extends base {

    public function login($data) {
        $query = $this->db->prepare("
            SELECT 
                user_id,
                user_name,
                user_email,
                user_password,
                user_country,
                user_city,
                is_admin
            FROM users
            WHERE user_email = ?
        ");
    
        $query->execute([ $data["user_email"] ]);

        $user = $query->fetch( PDO::FETCH_ASSOC );

        if(
            !empty($user) &&
            password_verify($data["user_password"], $user["user_password"])
        ) {
            return $user;
        }
        return [];
    }

    // CRUD BELOW

    public function get() {
        $query = $this -> db -> prepare("
            SELECT 
                user_id, 
                user_name,
                user_email,
                user_password,
                user_country,
                user_city,
                is_admin
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
                user_city,
                is_admin
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
                (?, ?, ?, ?, ?)
            ");

            $query -> execute ([
                $data["user_name"],
                $data["user_email"],
                password_hash($data["user_password"], PASSWORD_DEFAULT),
                $data["user_country"],
                $data["user_city"]
            ]);

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
            password_hash($data["user_password"], PASSWORD_DEFAULT),
            $data["user_country"],
            $data["user_city"],
            $id
        ]);
    }
    public function delete( $id ) {
        $query = $this->db->prepare("
            DELETE FROM users
            WHERE user_id = ?
        ");

        return $query->execute([
            $id
        ]);
    }
}