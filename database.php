<?php

class Database{
    public static function connect(){
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $base = 'teste-kabum';

        return new PDO("mysql:host={$host};dbname={$base};charset=UTF8;", $user, $pass);
    }
}