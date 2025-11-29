<?php

use App\Controllers\UsuariosController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

return function($app): void {

    $app->get('/', function(Request $request, Response $response){
        $response->getBody()->write("Hello world!");
        return $response;
    });

    // RUTAS DE USUARIOS
   
    $app->group('/usuarios', function(RouteCollectorProxy $group){

        // Obtener todos los usuarios (solo administrador)
        $group->get('/all', [UsuariosController::class, 'getUsuarios'])
              ->add(new RoleMiddleware(['administrador']))
              ->add(new AuthMiddleware());

        // Login
        $group->post('/login', [UsuariosController::class, 'login']);

        // Registrar usuario (solo admin)
        $group->post('/register', [UsuariosController::class, 'register'])
              ->add(new RoleMiddleware(['administrador']))
              ->add(new AuthMiddleware());

        // Logout
        $group->post('/logout', [UsuariosController::class, 'logout'])
              ->add(new AuthMiddleware());

        // Actualizar datos de usuario (solo admin)
        $group->put('/update/{id}', [UsuariosController::class, 'update'])
              ->add(new RoleMiddleware(['administrador']))
              ->add(new AuthMiddleware());

        // Cambiar rol (solo admin)  <<<<<<<<<<<<<< FALTABA ESTA
        $group->put('/role/{id}', [UsuariosController::class, 'updateRole'])
              ->add(new RoleMiddleware(['administrador']))
              ->add(new AuthMiddleware());

    });
};

