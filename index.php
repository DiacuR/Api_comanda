<?php

require_once "./vendor/autoload.php";

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Exception\NotFoundException;
use Slim\Routing\RouteCollectorProxy;       //Group
use Slim\Middleware\ErrorMiddleware;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

use Config\DataBase;
use App\Controllers\LoginController;
use App\Controllers\ClienteController;
use App\Controllers\EmpleadoController;
use App\controllers\PedidoController;
use App\controllers\MesaController;
use App\controllers\CriticaController;

use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;


$app = AppFactory::create();
new DataBase;
//$app->setBasePath("/ProgramacionIII/LaComanda_tp");

$app->group('/registro', function (RouteCollectorProxy $group) {
    $group->post('/staff[/]', EmpleadoController::class . ":add");
    $group->post('/cliente[/]', ClienteController::class . ":add");

});
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post("/staff[/]", LoginController::class . ":loginEmpleado");
    $group->post("/cliente[/]", LoginController::class . ":loginCliente");

});

$app->group('/pedido', function (RouteCollectorProxy $group) {
    
    $group->post("/{idCliente}", PedidoController::class . ":add")->add(new AuthMiddleware(array('mozo')));    

    $group->get("/preparar/{CodigoPedido}", PedidoController::class . ":PrepararPedido")->add(new AuthMiddleware(array('mozo')));

    $group->get("/entrega/{CodigoPedido}", PedidoController::class . ":LlevarPedido")->add(new AuthMiddleware(array('mozo')));

    $group->get("/entregar_la_cuenta/{CodigoPedido}", PedidoController::class . ":EntregarCuenta")->add(new AuthMiddleware(array('mozo')));

    $group->get("/cobrar_la_cuenta/{CodigoPedido}", PedidoController::class . ":CobrarCuenta")->add(new AuthMiddleware(array('mozo')));

    $group->get("[/]", PedidoController::class . ":GetAll")->add(new AuthMiddleware(array('socio')));

    $group->post("[/]", PedidoController::class . ":GetTiempoDePedido")->add(new AuthMiddleware(array('cliente')));

});

$app->group('/mesas', function (RouteCollectorProxy $group) {

    $group->get('/pidieron_cuenta[/]', MesaController::class . ":getMesasQuePidieronCuenta")->add(new AuthMiddleware(array('mozo')));

    $group->get('/pedir_cuenta/{CodigoMesa}', MesaController::class . ":PedirCuenta")->add(new AuthMiddleware(array('cliente')));

    $group->get('/cerrar_mesa/{CodigoMesa}', MesaController::class . ":CerrarMesa")->add(new AuthMiddleware(array('socio')));
});

$app->post('/critica/{CodigoPedido}', CriticaController::class . ":add")->add(new AuthMiddleware(array('cliente')));

$app->post('/pendiente[/]', PedidoController::class . ":getPendientesPorEmpleado")->add(new AuthMiddleware(array('cervecero','cocinero','bartender')));

$app->add(new JsonMiddleware);

$app->addBodyParsingMiddleware();

$app->run();