<?php

namespace App\models;
use Illuminate\DataBase\Eloquent\Model;

class Cliente extends Model{
    public $timestamps = false;

    public static function CodificarClave($clave)
    {
        $claveCifrada=password_hash($clave, PASSWORD_DEFAULT);
        return $claveCifrada;
    }

    public static function ValidarDatosCliente($usuario, $email, $clave){

        $valido = Cliente::get()->where('email',$email)->first()?? null;
        $rta = true;

        if(strpos($usuario, " ")){
            $rta = array("rta"=>"Nombre de usuario invalido");
        }
        if(!is_null($valido)){
            
            $rta = array("rta"=>"Email invalido");
        }
        if(strlen($clave)<4){
           
            $rta = array("rta"=>"Clave invalido");
        }
        return $rta;
    }
}