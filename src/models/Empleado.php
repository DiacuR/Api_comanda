<?php

namespace App\models;
use Illuminate\DataBase\Eloquent\Model;

class Empleado extends Model{
    public $timestamps = false;

    public static function CodificarClave($clave)
    {
        $claveCifrada=password_hash($clave, PASSWORD_DEFAULT);
        return $claveCifrada;
    }
    public static function validarDatosEmpleado($usuario, $email, $clave, $tipo){

        $valido = Empleado::get()->where('email',$email)->first()?? null;
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
        if(!(strcasecmp($tipo,"bartender") == 0 || strcasecmp($tipo,"cocinero") == 0 || strcasecmp($tipo,"cervecero") == 0 || strcasecmp($tipo,"mozo") == 0 || strcasecmp($tipo,"socio") == 0 )){
            
            $rta = array("rta"=>"Tipo de usuario invalido");
        }
        
        return $rta;
    }

    public static function getEmpleadoParaProducto($arrayProductoSector,$producto){
        
        $tipoEmpleado = $arrayProductoSector['tipoEmpleado'];
        
        if($arrayProductoSector['item'] == $producto->item){
            
            $empleados = Empleado::get()->where('tipo',$tipoEmpleado);
            
            if(count($empleados)>1){
                $indice = rand(0,count($empleados));
                $empleado = $empleados[$indice];
            } else {

                $empleado = Empleado::get()->where('tipo',$tipoEmpleado)->first();
            }
            
        } else {
            $empleado = '';
        }
        return $empleado;
    }
}