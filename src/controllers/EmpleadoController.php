<?php

namespace App\controllers;
use App\models\Empleado;

class EmpleadoController{

    public function add($request, $response, $args) {
        
        $empleado = new Empleado;
        
        $email = $request->getParsedBody()['email'];
        $clave = $request->getParsedBody()['clave'];
        $usuario = $request->getParsedBody()['usuario'];
        $tipo = $request->getParsedBody()['tipo'];
        
        $validar = Empleado::validarDatosEmpleado($usuario,$email,$clave,$tipo);

        if($validar === true){
            
            $empleado->email = $email;
            $empleado->tipo = $tipo;
            $empleado->clave = Empleado::CodificarClave($clave);
            $empleado->usuario = $usuario;
            
            
            $rta = $empleado->save();
            $rta = array("rta"=>$rta);
            $response->getBody()->write(json_encode($rta));
        } else {
            
            $response->getBody()->write(json_encode($validar));
        }
        
        return $response;
    }
}