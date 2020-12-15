<?php

namespace App\controllers;
use App\models\Mesa;


class MesaController{ 

    public function PedirCuenta($request, $response, $args) {

        $codigoMesa = $args['CodigoMesa']?? null;
        $mesa = Mesa::get()->where('codigo', $codigoMesa)->first();
        
        if (!is_null($mesa)) {
            
            if($mesa->idEstado == 2){

                if(!is_null($mesa)){
                    $mesa->pidieronCuenta = "Si";
                    $mesa->idEstado++;
                    $mesa->save();
                    $rta = array("rta"=>"Se pidio la cuenta");
                }
            } else {

                $rta = array("rta"=>"El estado del pedido no esta en 'Entregado'");
            }
        } else {

            $rta = array("rta"=>"El codigo de esta mesa no existe");
        }

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function CerrarMesa($request, $response, $args) {
        $codigoMesa = $args['CodigoMesa'] ?? null;
        $mesa = Mesa::get()->where('codigo',$codigoMesa)->first();

        if(!is_null($mesa)){
            
            if($mesa->idEstado == 5){

                $mesa->idEstado++;
                $mesa->pidieronCuenta = "--";
                $mesa->save();

                $rta = array("rta"=>"La mesa ha sido cerrada.");
            } else if($mesa->idEstado == 6) {

                $rta = array("rta"=>"La mesa ya esta cerrada");
            } else {

                $rta = array("rta"=>"Los clientes todabia estan en la mesa");
            }
        } else {

            $rta = array ("rta"=>"El codigo de esta mesa no existe");
        }

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function getMesasQuePidieronCuenta($request, $response, $args) {

        $str = [];
        $mesas = Mesa::select('mesas.codigo as codigo_mesa','pedidos.codigo_pedido','mesas.pidieronCuenta')
                        ->join('pedidos','mesas.codigo','=', 'pedidos.codigo_mesa')
                        ->where('mesas.pidieronCuenta','=','Si')
                        ->get();

        if(strlen($mesas) > 2){
            foreach ($mesas as $mesa){
    
                array_push($str, $mesa);
            }
    
            $response->getBody()->write(json_encode($str));
        } else {
            $rta = array ("rta"=>"Ninguna mesa pidio la cuenta");
            $response->getBody()->write(json_encode($rta));
        }
        return $response;
    }
    
}