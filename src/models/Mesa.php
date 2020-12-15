<?php

namespace App\models;
use Illuminate\DataBase\Eloquent\Model;

class Mesa extends Model{
    public $timestamps = false;

    public static function BuscarMesa(){
    
        $mesa = Mesa::get()->where('idEstado',6)->first();

        if (is_null($mesa)) {

            $mesa = array("rta"=>"No hay mesas disponibles");
        } else {
            $mesa->pidieronCuenta = "No";
        }

        return $mesa;
    }
}