<?php

namespace App\models;
use Illuminate\DataBase\Eloquent\Model;

class Producto extends Model{
    public $timestamps = false;

    public static function AsignarTiempoPreparacion($producto)
    {
        switch ($producto->idSector) {
            case 1:
                $time = 10;
                break;
            case 2:
                $time = 5;
                break;
            case 3:
                $time = 20;
                break;
            case 4:
                $time = 15;
                break;
        }

        return $time;
    }

    public static function getTimeString($segundos){

        $horas = floor($segundos/ 3600);
	    $minutos = floor(($segundos - ($horas * 3600)) / 60);
	    $segundos = $segundos - ($horas * 3600) - ($minutos * 60);
        $strTime = '';
        $arrayTiempo = array($horas,$minutos,$segundos);

        for ($i=0; $i < count($arrayTiempo); $i++) { 
            
            if($arrayTiempo[$i]<10){
                
                $strTime = $strTime ."0".$arrayTiempo[$i];
            } else {

                $strTime = $strTime . $arrayTiempo[$i];
            }

            if($i != 2){
                $strTime = $strTime.":";
            }
        }
        return $strTime;
    }
}