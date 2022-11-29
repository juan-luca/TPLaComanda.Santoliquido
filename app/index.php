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
require_once './controllers/ComandaController.php';
require_once './controllers/MesaController.php';
require_once './middlewares/Logger.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/AdminVerificador.php';
require_once './middlewares/UsuarioVerificador.php';

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
    $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(new AdminVerificador);
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno')->add(new AdminVerificador);
    $group->put('/{id}', \UsuarioController::class . ':ModificarUno')->add(new AdminVerificador);
    $group->get('/exportar/', \UsuarioController::class . ':exportarUsuariosCSV')->add(new AdminVerificador);
  })->add(new UsuarioVerificador);



  // ruta productos
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
    $group->put('/{id}', \ProductoController::class . ':ModificarUno');
  })->add(new UsuarioVerificador);

   // ruta Mesas
$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new AdminVerificador);
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->get('/servirMesa/{id}', \MesaController::class . ':servirMesa');
  $group->get('/cerrarMesa/{id}', \MesaController::class . ':cerrarMesa')->add(new AdminVerificador);
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');
  $group->put('/{id}', \MesaController::class . ':ModificarUno');
})->add(new UsuarioVerificador);

   // ruta Pedido
   $app->group('/pedidos', function (RouteCollectorProxy $group) {
     $group->get('/{id}', \PedidoController::class . ':TraerUno');
     $group->get('/cobrar/{codigo}', \PedidoController::class . ':cobrarPedido');
     $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new AdminVerificador);
    $group->get('/empleado/{id}', \PedidoController::class . ':TraerTodosPorEmpleado');
    $group->post('[/]', \PedidoController::class . ':CargarUno');
    $group->delete('/{id}', \PedidoController::class . ':BorrarUno');
    $group->put('/{id}', \PedidoController::class . ':ModificarUno');
    $group->put('/actualizar/', \PedidoController::class . ':actualizarEstado');
    $group->get('/listos/', \PedidoController::class . ':TraerListos');
  })->add(new UsuarioVerificador);

   // ruta Comanda
   $app->group('/comandas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ComandaController::class . ':TraerTodos');
    $group->get('/{id}', \ComandaController::class . ':TraerUno');
    $group->post('[/]', \ComandaController::class . ':CargarUno');
    $group->delete('/{id}', \ComandaController::class . ':BorrarUno');
    $group->put('/{id}', \ComandaController::class . ':ModificarUno');
  })->add(new UsuarioVerificador);
    // ruta Pedido
    $app->group('/clientes', function (RouteCollectorProxy $group) {
      $group->get('/tiempoEstimado/{codigo}', \PedidoController::class . ':tiempoEstimado');
      
    });

//login
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':Login')->add(new LoggerMiddleware());
  
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
