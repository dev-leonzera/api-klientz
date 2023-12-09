<?php
require_once 'usuario.php';


class UserController{

    private $user;

    public function __construct()
    {
        $this->user = new Usuario();
    }

    public function cadastrar(){
        $request = $_POST;

        $username = $request['username'];
        $password = $request['password'];

        $options = [
            'cost' => 10,
        ];

        $hashPass = password_hash($password, PASSWORD_BCRYPT, $options);

        $result = $this->user->cadastrar($username, $hashPass);

        if ($result){
            echo json_encode(['message' => 'Usuário criado com sucesso!']);
        }
    }
    public function login()
    {
        $request = $_POST;

        if ($request) {
            if (!$request['username'] OR !$request['password']) {
                echo json_encode(['ERRO' => 'Falta informacoes!']); exit; 
            } else {
                $username = addslashes(htmlspecialchars($request['username'])) ?? '';
                $password = addslashes(htmlspecialchars($request['password'])) ?? '';
                $secretJWT = $GLOBALS['secretJWT'];

                $db = Database::connect();
                $rs = $db->prepare("SELECT * FROM usuarios WHERE username = '{$username}'");
                $exec = $rs->execute();
                $obj = $rs->fetchObject();
                $rows = $rs->rowCount();

                if ($rows > 0) {
                    $idDB          = $obj->id;
                    $passDB        = $obj->password;
                    $validUsername = true; 
                    $validPassword = password_verify($password, $passDB) ? true : false;
                } else {
                    $validUsername = false;
                    $validPassword = false;
                }

                if ($validUsername and $validPassword) {
                    $expire_in = time() + 60000;
                    $token     = JWT::encode([
                        'id'         => $idDB,
                        'username' => $username,
                        'expires_in' => $expire_in,
                    ], $GLOBALS['secretJWT']);

                    $db->query("UPDATE usuarios SET token = '$token' WHERE id = $idDB");
                    echo json_encode(['token' => $token, 'data' => JWT::decode($token, $secretJWT)]);
                } else {
                    if (!$validPassword) {
                        echo json_encode(['ERROR', 'invalid password']);
                    }
                }
            }
        } else {
            echo json_encode(['ERRO' => 'Falta informacoes!']); exit; 
        }

    }

    public function verificar()
    {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            $token = str_replace("Bearer ", "", $headers['Authorization']);
        } else {
            echo json_encode(['ERRO' => 'Você não está logado, ou seu token é inválido.']);
            exit;
        }

        $db   = Database::connect();
        $rs   = $db->prepare("SELECT * FROM usuarios WHERE token = '{$token}'");
        $exec = $rs->execute();
        $obj  = $rs->fetchObject();
        $rows = $rs->rowCount();
        $secretJWT = $GLOBALS['secretJWT'];

        if ($rows > 0) :
            $idDB    = $obj->id;
            $tokenDB = $obj->token;
            
            $decodedJWT = JWT::decode($tokenDB, $secretJWT);
            if ($decodedJWT->expires_in > time()) {
                return true;
            } else {
                $db->query("UPDATE usuarios SET token = '' WHERE id = $idDB");
                return false;
            }
        else :
            return false;
        endif;
    }
}