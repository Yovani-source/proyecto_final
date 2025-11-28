<?php

use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Conectar Base de Datos
|--------------------------------------------------------------------------
*/
require __DIR__ . '/../app/configuracion/database.php';
Database::conectar();


/*
|--------------------------------------------------------------------------
| Crear App Slim
|--------------------------------------------------------------------------
*/
$app = AppFactory::create();
$app->addBodyParsingMiddleware();


/*
|--------------------------------------------------------------------------
| CORS
|--------------------------------------------------------------------------
*/
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->add(function (Request $request, $handler) {
    $origin = $request->getHeaderLine('Origin') ?: '*';
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});


/*
|--------------------------------------------------------------------------
| Ruta raíz
|--------------------------------------------------------------------------
*/
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode([
        "mensaje" => "Microservicio gestion_usuarios OK ✔"
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});


/*
|--------------------------------------------------------------------------
| Cargar rutas de usuarios
|--------------------------------------------------------------------------
*/
(require __DIR__ . '/../app/ruta/usuario.php')($app);


/*
|--------------------------------------------------------------------------
| Ejecutar App
|--------------------------------------------------------------------------
*/
$app->run();
