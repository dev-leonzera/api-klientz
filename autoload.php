<?php

class Autoload
{
    public function __construct(){
        $files = [
            'database.php',
            'rotas.php',
            'api/utils/jwt.php',
            'api/clientes/controller.php',
            'api/usuarios/controller.php'
        ];

        foreach($files as $file)
        {
            if (!in_array($file, ['.', '..']))
            {
                include_once $file;
            }
        }
    }
}