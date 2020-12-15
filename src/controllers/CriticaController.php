<?php

namespace App\controllers;
use App\models\Critica;
use App\models\Pedido;
class CriticaController{

    public function add($request, $response, $args) {

       $codigoPedido = $args['CodigoPedido'] ?? null;
        $pedido = Pedido::get()->where('codigo_pedido', $codigoPedido)->first();
        $critica = Critica::get()->where('idPedido',$pedido->id)->first();
        $arrayDeParametros = $request->getParsedBody();
        $experiencia = $request->getParsedBody()['experiencia'] ?? null;
        $arrayDeParametros  = array_slice($arrayDeParametros,0,4);
        
        if(is_null($critica)){
            if(!is_null($pedido)){                                          
                
                foreach ($arrayDeParametros as $parametro) {
                    
                    if($parametro > 0 && $parametro < 10){
                        $flag = true;
                    } else {
                        $flag = false;
                        break;
                    }
                }
                if($flag){
                    $critica = new Critica;

                    $critica->idPedido = $pedido->id;
                    $critica->restaurante = $arrayDeParametros['restaurante'];
                    $critica->mozo = $arrayDeParametros['mozo'];
                    $critica->mesa = $arrayDeParametros['mesa'];
                    $critica->cocinero = $arrayDeParametros['cocinero'];
                    
                    if(strlen($experiencia) <= 66){
                        $critica->experiencia = $experiencia;
                        $critica->save();
                        $rta = array("rta"=> "La critica se guardo correctamente");
                    } else {

                        $rta = array("rta"=> "El campo de 'experiencia tiene que contener como maximo 66 caracteres");    
                    }
                } else {

                    $rta = array("rta"=> "Los rangos de puntuacion son incorrectos");
                }
            } else {

                $rta = array("rta"=> "El codigo no pertenece a ningun pedido");
            }
        } else {
            $rta = array("rta"=> "Ya se agrego una reseña a este pedido");
        }
        $response->getBody()->write(json_encode($rta));

        return $response;
    }
}