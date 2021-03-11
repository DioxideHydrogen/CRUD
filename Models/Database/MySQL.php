<?php

namespace App\Models\Database;

require dirname(__DIR__,2).'/vendor/autoload.php';

use App\Models\User\User;
use PDO;
use PDOException;

class MySQL implements DBInterface {

    private $host;
    private $user;
    private $pass;
    private $database;
    private $charset;
    private PDO $con;

    public function __construct()
    {
        $this->host = "localhost";    
        $this->user = "dev";
        $this->pass = "";
        $this->database = "";
        $this->charset = "utf8";
        $this->con = $this->connect();
    }
    public function connect(): PDO
    {
        try{
            $con = new PDO("mysql:host=".$this->host.";dbname=".$this->database.";charset=".$this->charset,$this->user,$this->pass);
            return $con;
        } catch (PDOException $e){
            die(array("error" => $e->getMessage()));
        }
    }

    /**
     *  CRUD -> Usuário
     */
    public function registerUser(User $u){
        $sql = "INSERT INTO usuarios(`email`,`senha`,`nome_completo`) VALUES (?,?,?)";
        $stm = $this->con->prepare($sql);
        $stm->execute([$u->getEmail(),$u->getHash(),$u->getFullName()]);
        if($stm->rowCount() > 0){
            return array("ok" => "Usuário cadastrado com sucesso.");
        } else {
            die(\json_encode(array("error" => "Não foi possível cadastrar o usuário.")));
        }

    }

    public function loginUser(User $u){
        $sql = "SELECT `email`, `senha` FROM usuarios WHERE id = ?";
        $stm = $this->con->prepare($sql);
        $stm->execute([$u->getId()]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);
        if($stm->rowCount() == 1){
            $result = password_verify($u->getPass(),$user["senha"]);
            if($result){
                return array(
                    "ok" => "Login efetuado com sucesso",
                    "cookie" => \base64_encode($user["email"]),
                    "expiraEm" => strtotime("now +30min")
                );
            } else {
                die(\json_encode(array("error" => "Senha incorreta.")));
            }
        } else {
            die(\json_encode(array("error" => "Não foi possível localizar o usuário.")));
        }
    }

    public function deleteUser(User $u){
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stm = $this->con->prepare($sql);
        $stm->execute([$u->getId()]);
        if($stm->rowCount() > 0){
            return array("ok" => "Usuário deletado com sucesso.");
        } else {
            die(\json_encode(array("error" => "Não foi possível deletar o usuário.")));
        }
    }

    public function updateUser(User $u){
        $sql = "UPDATE usuarios SET 
        `email` = COALESCE(NULLIF(?,''),`email`),
        `senha` = COALESCE(NULLIF(?,''),`senha`),
        `nome_completo` = COALESCE(NULLIF(?,''),`nome_completo`)
        WHERE `id` = ?";
       $stm = $this->con->prepare($sql);
       $stm->execute([
           $u->getEmail(),
           $u->getHash(),
           $u->getFullName(),
           $u->getId()
       ]);
       if($stm->rowCount() > 0){
            return array("ok" => "Informações atualizado com sucesso.");
        } else {
            die(\json_encode(array("error" => "Não foi possível atualizar as informações do usuário.")));
        }
    }
}
