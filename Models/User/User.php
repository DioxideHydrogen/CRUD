<?php

namespace App\Models\User;

require dirname(__DIR__,2).'/vendor/autoload.php';

class User {
    
    private Int $id;
    private String $email;
    private String $pass;
    private String $hash;
    private String $fullName;

    public function setId(Int $id){
        $this->id = $id;
    }

    public function getId() : Int {
        return $this->id;
    }

    public function setEmail(String $email){
        if(\filter_var($email, \FILTER_VALIDATE_EMAIL)){
            $this->email = addslashes(trim(\strip_tags($email)));
        } else {
            die(\json_encode(array("error" => "Email inválido")));
        }
    }

    public function getEmail() : String{
        return $this->email;
    }

    public function setHash(String $pass){
        if(\strlen($pass) > 0){
            $hash = password_hash($pass,PASSWORD_ARGON2ID);
            $this->hash = $hash;
        } else {
            die(\json_encode(array("error" => "Senha inválida")));
        }
        
    }

    public function getHash() : String{
        return $this->hash; 
     }

    public function setPass(String $pass){
        if(\strlen($pass) > 0){
            $this->pass = $pass;
        } else {
            die(\json_encode(array("error" => "Senha inválida")));
        }
        
    }

    public function getPass() : String{
       return $this->pass; 
    }

    public function setFullName(String $fullName){
        if(\strlen($fullName) > 0){
            $this->fullName = addslashes(trim(strip_tags($fullName)));
        } else {
            die(\json_encode(array("error" => "Nome inválido")));
        }
    }

    public function getFullName() : String {
        return $this->fullName;
    }
}