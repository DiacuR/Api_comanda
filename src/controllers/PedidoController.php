<?php

namespace App\controllers;
use App\Models\Cliente;
use App\Models\Empleado;
use App\models\Mesa;
use App\models\Pedido;
use Clase\Token;
use App\models\Pendiente;
use App\models\Producto;
use App\models\Comanda;

class PedidoController{ 

    public function add($request, $response, $args){

        $items = $request->getParsedBody()['items'] ?? null;
        $idCliente = $args['idCliente'];
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $codigo = Pedido::getCodigo();
        $payload = Token::ValidateToken($token);
        $strItems = '';
        $strTiket = '';
        $total = 0;
        
        $cliente = Cliente::get()->where('id',$idCliente)->first();
        $mozo = Empleado::get()->where('email',$payload->email)->first();
        
        if(!is_null($cliente)){

            $mesa = Mesa::BuscarMesa();
            
            if(is_array($mesa)){
                
                $response->getBody()->write(json_encode($mesa));
            } else {
                
                $mesa->idEstado = 1;
                
                foreach ($items as $item) {
                    $cantProducto = intval($item[1]);
                    $nombreProducto = $item[0];
                    for ($i=0; $i < $cantProducto ; $i++) { 
                        $nuevoPendiente = new Pendiente;                
                        $nuevoPendiente->codigoPedido = $codigo;
                        
                        $producto = Producto::get()->where('item',$nombreProducto)->first();
                    
                        if(!is_null($producto)){
                            $nuevoPendiente->idProducto = $producto->id;

                            $itemsForEmployees = Producto::select('productos.item','sectores.tipoEmpleado')
                                ->join('sectores','productos.idSector','=','sectores.id')
                                ->get();

                            foreach ($itemsForEmployees as $itemByEmployee) {

                                $empleado = Empleado::getEmpleadoParaProducto($itemByEmployee,$producto);
                                
                                if(!empty($empleado)) {
                                    $nuevoPendiente->idEmpleado = $empleado->id;
                                    $nuevoPendiente->estado = "Pendiente";
                                    
                                    $res = $nuevoPendiente->save();
                                    break;
                                } 
                            }

                        } else {
                            $rta = array("rta"=>"No contamos con el producto: ". $nombreProducto );
                            $response->getBody()->write(json_encode($rta));

                            return $response;
                        }
                    }

                    $strItems = $strItems . $nombreProducto.";";
                    $strTiket = $strTiket . $nombreProducto. " --- " . $cantProducto." ";
                }
                
                if($res){
                    
                    $pedido = new Pedido;
                    $pedido->codigo_pedido = $codigo;
                    $pedido->id_cliente = $idCliente;
                    $pedido->id_mesero = $mozo->id;
                    $pedido->codigo_mesa = $mesa->codigo;
                    $pedido->items = $strItems;
                    $pedido->estado = "Pendiente";
                    $pedido->save();
                    $mesa->save();
                    $strPedido = "Codigo Pedido: ". $codigo . "  Codigo Mesa: ". $mesa->codigo."   " . $strTiket;
                    $rta = array("rta"=> $strPedido);
                    $response->getBody()->write(json_encode($rta));
                }
            }

        } else {
                $rta = array("rta"=>"El id del Cliente no es correcto");
                $response->getBody()->write(json_encode($rta));
        }

        return $response;
    }

    public function getPendientesPorEmpleado($request, $response, $args){

        $token = $_SERVER["HTTP_TOKEN"];
        $foto = $request->getParsedBody()["foto"] ?? null;                     //todo
        $payload = Token::ValidateToken($token);
        $strPendientes = '';
        $empleado = Empleado::get()->where('email',$payload->email)->first();

        if(!is_null($empleado)){

            $pendientes = Producto::where('idEmpleado',$empleado->id)
                                ->select('productos.item','pendientes.estado')
                                ->join('pendientes', 'productos.id','=','pendientes.idProducto')
                                ->get();

            if(!empty($pendientes)){

                foreach ($pendientes as $pendiente) {
                    $strPendientes = $strPendientes. $pendiente["item"] ." --- " . $pendiente["estado"]. "   ";
                }
                $rta = array("rta"=>$strPendientes);
                $response->getBody()->write(json_encode($rta));
            } else {

                $rta = array("rta"=>"No tiene pendientes");
                $response->getBody()->write(json_encode($rta));
            }
        } else {
            
            $rta = array("rta"=>"No existe el empleado");
            $response->getBody()->write(json_encode($rta));
        }

        return $response;
    }
 
    public function PrepararPedido($request, $response, $args){
        
        $codigoPedido = $args['CodigoPedido'] ?? null;
        $tiempoTotal = 0;
        $pedido = Pedido::get()->where('codigo_pedido', $codigoPedido)->first();

        if(!is_null($pedido)){

            if($pedido->estado == 'Pendiente'){

                $items = explode(";",$pedido->items);

                for ($i=0; $i < count($items); $i++) {
                    
                    if(!empty($items[$i])){
                        $producto = Producto::get()->where('item', $items[$i])->first();
                        $pendientes = Pendiente::get()->where('idProducto',$producto->id)->where('codigoPedido',$codigoPedido);
                        
                        foreach ($pendientes as $pendiente) {
                        
                            $pendiente->estado = "En Preparacion";
                            $time = Producto::AsignarTiempoPreparacion($producto);
                            $tiempoItem = Producto::getTimeString($time);
                            
                            $pendiente->tiempo = $tiempoItem;
                            $pendiente->save();
                            $tiempoTotal += $time;
                        }
                    }
                }
                
                $tiempoTotal = Producto::getTimeString($tiempoTotal);
                $pedido->tiempo = $tiempoTotal;
                $pedido->estado = "En Preparacion";
                $rta = $pedido->save();

                if($rta){

                    $rta = array("rta"=>"Pedido en Preparacion");
                } else {

                    $rta = array("rta"=>"No se puso en preparacion el pedido");
                }
                
            } else {
                $rta = array("rta"=>"El estado del pedido no esta en 'Pendiente'");
            }
        } else {
            
            $rta = array("rta"=>"El pedido no existe");
        }
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function LlevarPedido($request, $response, $args){

        $codigoPedido = $args['CodigoPedido'];
        $pedido = Pedido::get()->where('codigo_pedido',$codigoPedido)->first();

        if(!is_null($pedido)){

            if($pedido->estado == "En Preparacion"){
                
                $horarioEntrega = Pedido::ActualizarTiempo($pedido->updated_at,$pedido->tiempo);
                
                $horaActual = Pedido::getHoraActual();
                
                if(Pedido::CompararStringsDeHorario($horaActual,$horarioEntrega) >= 0){
                    
                    $pedido->estado = "Entregado";
                    $mesa = Mesa::get()->where('codigo',$pedido->codigo_mesa)->first();
                    $mesa->idEstado += 1;
                    $mesa->save();
                    $pendientes = Pendiente::get()->where('codigoPedido',$codigoPedido);
                    $pedido->save();
                    $rta = array("rta"=>"Pedido Entregado");
                    foreach ($pendientes as $pendiente) {
                        $pendiente->estado = "Entregado";
                        $pendiente->save();
                    }
                } else {
                    
                    $rta = array("rta"=>"Todabia no se encuentra listo su pedido");
                }
                
            }else {
                $rta = array("rta"=>"El estado del pedido no esta en 'En preparacion'");
            } 
        } else {
            $rta = array("rta"=>"No existe pedido con ese codigo");
        }
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function EntregarCuenta($request, $response, $args){
        $codigo = $args['CodigoPedido'] ?? null;
        $pedido = Pedido::get()->where('codigo_pedido', $codigo)->first();
        $precioFinal = 0;
        $strTicket = '';

        if(!is_null($pedido)){

            if($pedido->estado == "Entregado"){
                
                $pendientes = Pendiente::get()->where('codigoPedido', $codigo);
                $strTicket = "Pedido---". $pedido->codigo_pedido. " ";
                
                foreach ($pendientes as $pendiente) {
                    
                    $producto = Producto::get()->where('id', $pendiente->idProducto)->first();

                    $strTicket = $strTicket . $producto->item . "---" . $producto->precio. "$ ";
                    $precioFinal += $producto->precio;
                    $producto->cantVendida++;
                    $producto->save();
                    $pendiente->delete();
                }

                $pedido->estado = "Pagando";
                $pedido->precioTotal = $precioFinal;
                $mesa = Mesa::get()->where('codigo', $pedido->codigo_mesa)->first();
                $mesa->idEstado += 1;
                $mesa->pidieronCuenta = "--";
                $mesa->save();
                $pedido->save();

                $strTicket = $strTicket . "Total---". $precioFinal."$";
                $rta = array("rta"=>$strTicket);
            } else {
                $rta = array("rta"=>"El estado del pedido no esta en 'Entregado'");
            }
        } else {

            $rta = array("rta"=>"No existe pedido con ese codigo");
        }

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function CobrarCuenta($request, $response, $args){
        
        $codigoPedido = $args['CodigoPedido'];
        $pedido = Pedido::get()->where('codigo_pedido', $codigoPedido)->first();
        

        if(!is_null($pedido)){
            
            $mesa = Mesa::get()->where('codigo', $pedido->codigo_mesa)->first();

            if($pedido->estado == 'Pagando'){

                $comanda = new Comanda;
                $comanda->idPedido = $pedido->id;
                $comanda->total = $pedido->precioTotal;
                $comanda->save();
                $mesa->idEstado++;
                $mesa->save();
                $pedido->estado = "Cobrado";
                $pedido->save();

                $rta = array("rta"=>"Cobrado con exito.");
            } else {
                
                $rta = array("rta"=>"El estado del pedido no esta en 'Pagando'");    
            }

        } else {

            $rta = array("rta"=>"El codigo del pedido no existe");
        }

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function GetAll($request, $response, $args){

        $pedidos = Pedido::select(['codigo_pedido','estado'])
                            ->get();
        $rta = '';
        
        if (strlen($pedidos)> 2) {
    
            foreach ($pedidos as $pedido) {
                
                $rta = $rta . json_encode($pedido);
            }

        } else {

            $rta = json_encode(array("rta"=>"No hay pedidos que mostrar"));
        }
        
        $response->getBody()->write($rta);

        return $response;
    }

    public function GetTiempoDePedido($request, $response, $args){

        $codigoPedido = $request->getParsedBody()['codigoPedido'];
        $codigoMesa = $request->getParsedBody()['codigoMesa'];

        $pedido = Pedido::get()->where('codigo_pedido', $codigoPedido)->first();
        $mesa = Mesa::get()->where('codigo', $codigoMesa)->first();

        if(!is_null($pedido)){

            if(!is_null($mesa)){

                $horarioEntrega = Pedido::ActualizarTiempo($pedido->updated_at,$pedido->tiempo);
                $horaActual = Pedido::getHoraActual();

                $horarioEntrega = explode(":", $horarioEntrega);
                $horaActual = explode(":", $horaActual);

                $entregaEnSeg = Pedido::ConvertirTiempoASegundos($horarioEntrega);
                $actualEnSeg = Pedido::ConvertirTiempoASegundos($horaActual);

                $tiempoFaltante = $entregaEnSeg - $actualEnSeg;

                if($tiempoFaltante<0){
                    $rta = array("rta" =>"El Pedido ya esta Listo");
                } else {
                    $tiempoFaltante = Producto::getTimeString($tiempoFaltante);
                    $rta = array("rta" =>"El tiempo flatante es: ".$tiempoFaltante);
                }

            } else {

                $rta = array("rta"=>"No existe esa mesa");
            }

        } else {
            
            $rta = array("rta"=>"No existe ese pedido");
        }

        $response->getBody()->write(json_encode($rta));

        return $response;
    }
}