<?php

namespace App\controllers;
use App\models\Cliente;
use App\models\Empleado;
use Clase\Token;

class LoginController{ 

    public function loginEmpleado($request, $response, $args) {
        
        $clave = $request->getParsedBody()['clave'];
        $email = $request->getParsedBody()['email'];
        
        $user = Empleado::where('email',$email)->get()->first();
        
        if(!is_null($user) && password_verify($clave,$user->clave)){
            $rta = Token::GetToken($user)?? '';    
        } else {
            $rta = 'Error. El email/clave no son correctos';
        }
        
        $rta= array("rta"=> $rta);
        $response->getBody()->write(json_encode($rta,JSON_UNESCAPED_SLASHES));

        return $response;
    }

    public function loginCliente($request, $response, $args) {
        
        $clave = $request->getParsedBody()['clave'];
        $email = $request->getParsedBody()['email'];
        
        $user = Cliente::where('email',$email)->first();
        
        if(!is_null($user) && password_verify($clave,$user->clave)){
            $rta = Token::getToken($user)?? '';    
        } else {
            $rta = 'Error. El email/clave no son correctos';
        }
        
        $rta= array("rta"=> $rta);
        $response->getBody()->write(json_encode($rta,JSON_UNESCAPED_SLASHES));

        return $response;
    }
}