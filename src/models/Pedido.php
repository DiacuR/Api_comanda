<?php

namespace App\models;
use Illuminate\DataBase\Eloquent\Model;
use App\models\Producto;

class Pedido extends Model{
    

    public static function getCodigo(){

        $codigo = null;

        while(is_null($codigo)){

        $codigo = rand(10,99);
        $random_string = chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));

        $codigo = $random_string . $codigo;
        $valido = Pedido::get()->where('codigo',$codigo)->first();

            if(is_null($valido)){
                return $codigo;
            } else {
                $codigo = null;
            }
        }
    }

    public static function ActualizarTiempo($timestamps,$tiempoDePreparacion){

        $tiempoInicial = Pedido::getHoraDeTimestamps($timestamps);
        $tiempoPreparacion = explode(":", $tiempoDePreparacion);
        
        $tiempofinal = 0;
        $strTime = '';
        $tiempofinal = [];
        
        $inicial = Pedido::ConvertirTiempoASegundos($tiempoInicial);
        $preparacion = Pedido::ConvertirTiempoASegundos($tiempoPreparacion);

        $entrega = $inicial + $preparacion;

        $hora = Producto::getTimeString($entrega);
        
        return $hora;
    }

    public static function CompararStringsDeHorario($horaActual,$horaTerminado){
        
        return strcasecmp($horaActual,$horaTerminado);
    }

    public static function getHoraActual(){
        
        $arrayHoraActual = getdate();
        $hora = array($arrayHoraActual["hours"],$arrayHoraActual["minutes"],$arrayHoraActual["seconds"]);
        $strTime = '';
        for ($i=0; $i < count($hora); $i++){
            
            if($hora[$i]<10){

                $strTime = $strTime ."0".$hora[$i];
            } else {

                $strTime = $strTime . $hora[$i];
            }

            if($i != 2){
                $strTime = $strTime.":";
            }
        }
        
        return $strTime;
    }

    public static function getHoraDeTimestamps($timestamps){
        $separarDia = explode("T", $timestamps);   
        $separarHora = explode(" ", $separarDia[0]);
        $tiempoInicial = explode(":", $separarHora[1]);

        return $tiempoInicial;
    }

    public static function ConvertirTiempoASegundos($arrayTiempo){

        $horasEnSeg = floor($arrayTiempo[0] * 3600);
        $minEnSeg = floor($arrayTiempo[1] * 60);
        $seg = $arrayTiempo[2];
        $segDeTiempo = $horasEnSeg + $minEnSeg + $seg;

        return $segDeTiempo;
    }
}