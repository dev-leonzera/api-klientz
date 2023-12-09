<?php

class Cliente
{
    private $db;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function listarTodos()
    {
        $rs = $this->db->prepare("SELECT * FROM clientes");
        $rs->execute();

        $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

        if ($obj) {
            return $obj;
        } else {
            return false;
        }
    }

    public function getClienteById($id)
    {
        $rs = $this->db->prepare("SELECT * FROM clientes WHERE id = :id");
        $rs->bindValue(':id', $id);
        $rs->execute();

        $obj = $rs->fetch(PDO::FETCH_ASSOC);

        return $obj;
    }

    public function cadastrarCliente($cliente)
    {
        $sql = "INSERT INTO clientes (";

        $contador = 1;
        foreach (array_keys($cliente) as $indice) {
            if (count($cliente) > $contador) {
                $sql .= "{$indice},";
            } else {
                $sql .= "{$indice}";
            }
            $contador++;
        }

        $sql .= ") VALUES (";

        $contador = 1;
        foreach (array_values($cliente) as $valor) {
            if (count($cliente) > $contador) {
                $sql .= "'{$valor}',";
            } else {
                $sql .= "'{$valor}'";
            }
            $contador++;
        }

        $sql .= ")";
        
        $rs = $this->db->prepare($sql);
        $exec = $rs->execute();
        $idCliente = $this->db->lastInsertId();

        return $exec;
    }

    public function atualizarCliente($cliente, $id)
    {
        $sql = "UPDATE clientes SET ";

        $contador = 1;
        foreach (array_keys($cliente) as $indice) {
            if (count($cliente) > $contador) {
                $sql .= "{$indice} = '{$cliente[$indice]}', ";
            } else {
                $sql .= "{$indice} = '{$cliente[$indice]}' ";
            }
            $contador++;
        }

        $sql .= "WHERE id={$id}";
        
        $rs = $this->db->prepare($sql);
        $exec = $rs->execute();

        return $exec;
    }

    public function removerCliente($id)
    {
        $rs = $this->db->prepare("DELETE FROM clientes WHERE id={$id}");
        $exec = $rs->execute();

        return $exec;
    }
}
