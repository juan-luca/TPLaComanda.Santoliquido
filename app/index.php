<?php
// Error Handling
//juan luca santoliquido
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
require_once './middlewares/Logger.php';
require_once './middlewares/AutentificadorJWT.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// ruta usuarios
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
    $group->put('/{id}', \UsuarioController::class . ':ModificarUno');
  });

  // ruta productos
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
    $group->put('/{id}', \ProductoController::class . ':ModificarUno');
  });

   // ruta Mesas
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');
  $group->put('/{id}', \MesaController::class . ':ModificarUno');
});

   // ruta Pedido
   $app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/{id}', \PedidoController::class . ':TraerUno');
    $group->post('[/]', \PedidoController::class . ':CargarUno');
    $group->delete('/{id}', \PedidoController::class . ':BorrarUno');
    $group->put('/{id}', \PedidoController::class . ':ModificarUno');
  });

//login
  $app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':Login');
    
  });

 

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("TP La comanda Juan Luca Santoliquido<br><br>");
    $response->getBody()->write("Rutas disponibles:<br>");
    $response->getBody()->write("/usuarios<br>");
    $response->getBody()->write("/productos<br>");
    $response->getBody()->write("/mesas<br>");
    $response->getBody()->write("/pedidos<br>");
    $response->getBody()->write("/login");
    return $response;

});

$app->run();
