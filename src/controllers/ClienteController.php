<?php

namespace App\controllers;
use App\models\Cliente;

class ClienteController{

    public function add($request, $response, $args) {
        
        $cliente = new Cliente;
        
        $email = $request->getParsedBody()['email'];
        $clave = $request->getParsedBody()['clave'];
        $usuario = $request->getParsedBody()['usuario'];
        
        $validar = Cliente::ValidarDatosCliente($usuario,$email,$clave);

        if($validar === true){
            
            $cliente->email = $email;
            $cliente->usuario = $usuario;
            $cliente->clave = Cliente::CodificarClave($clave);
            
            $rta = $cliente->save();
            $rta = array("rta"=>$rta);
            $response->getBody()->write(json_encode($rta));
        } else {
            
            $response->getBody()->write(json_encode($validar));
        }
        
        return $response;
    }
}