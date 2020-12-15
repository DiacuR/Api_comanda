<?php

namespace Clase;

require_once "./vendor/autoload.php";

use \Firebase\JWT\JWT; //namespace
use App\Models\Empleado;
use App\Models\Cliente;

class Token{
    
    private const KEY = "primerparcial";

    public static function GetToken($empleado){
        

        if(is_null($empleado->tipo)){
            
            $payload = ['email' => $empleado->email,'clave' => $empleado->clave,'tipo' => "cliente"];
        } else {

            $payload = ['email' => $empleado->email,'clave' => $empleado->clave,'tipo' => $empleado->tipo];
        }

        try{
        
            return JWT::encode($payload,Token::KEY);
        
        }catch(Exception $e){
            throw new Exception($e->getMessage);
        }
        

    }
    public static function ValidateToken($token){

        if(empty($token) || $token === ""){
            return null;
        }
        try {

            $user = JWT::decode($token, Token::KEY, array('HS256'));
            
            return $user;

        } catch (\Throwable $th) {

            return null;
        }
        
    }

    public static function GetTipoEmpleado($token,$tipoEmpleado){

        $payload = JWT::decode($token,Token::KEY,array('HS256'));

        if($payload->tipo == $tipoEmpleado){
            
            return true;
        } else {

            return false;
        }
    }
}