<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Fortaleza");

$GLOBALS['secretJWT'] = '123456';

include_once "autoload.php";
new Autoload();

$rota = new Rotas();
//Cadastro e login de usuários
$rota->add('POST', '/register', 'UserController::cadastrar', false);
$rota->add('POST', '/login', 'UserController::login', false);
//Clientes
$rota->add('POST', '/clientes', 'ClienteController::adicionarCliente', false);
$rota->add('GET', '/clientes', 'ClienteController::listarTodos', false);
$rota->add('GET', '/cliente/[PARAM]', 'ClienteController::getCliente', true);
$rota->add('PUT', '/clientes/[PARAM]', 'ClienteController::atualizarCliente', false);
$rota->add('DELETE', '/cliente/[PARAM]', 'ClienteController::removerCliente', false);
$rota->ir($_GET['path']);