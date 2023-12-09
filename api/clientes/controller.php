<?php

include_once 'cliente.php';

class ClienteController {

    private $cliente;
    private $request;

    public function __construct() {
        $this->cliente = new Cliente();
        $this->request = $_POST;
    }

    public function listarTodos()
    {
        $obj = $this->cliente->listarTodos();

        if ($obj) {
            echo json_encode(['dados' => $obj]);
        } else {
            echo json_encode(['dados' => 'Dados não encontrados']);
        }
    }

    public function getCliente($id){
        $obj = $this->cliente->getClienteById($id);

        if ($obj) {
            echo json_encode(['dados' => $obj]);
        }
    }

    public function adicionarCliente(){       

        $result = $this->cliente->cadastrarCliente($this->request);

        if(!$result){
            echo json_encode(["dados" => 'Houve algum erro ao inserir os dados.']);
        }
        echo json_encode(["dados" => 'Dados foram inseridos com sucesso.']);
    }

    public function atualizarCliente($id){
        $result = $this->cliente->atualizarCliente($this->request, $id);

        if (!$result) {
            echo json_encode(["dados" => 'Houve erro ao atualizar dados.']);
        } 
        echo json_encode(["dados" => 'Dados atualizados com sucesso.']);
    }

    public function removerCliente($id){
        $result = $this->cliente->removerCliente($id);

        if($result){
            echo json_encode(["dados" => 'Dados foram removidos com sucesso.']);
        }
        echo json_encode(["dados" => 'Houve algum erro ao remover os dados.']);
    }
}