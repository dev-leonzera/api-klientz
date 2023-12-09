<?php

class Usuario {
    private $db;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function cadastrar($username, $password){
        
        $rs = $this->db->prepare("INSERT INTO usuarios(username, password) VALUES (:username, :hashPass)");
        $rs->bindValue(':username', $username);
        $rs->bindValue(':hashPass', $password);
        $exec = $rs->execute();

        return $exec;
    }
}
