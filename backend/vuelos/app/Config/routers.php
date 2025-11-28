<?php

use App\Controllers\NavesController;
use App\Controllers\VuelosController;
use App\Controllers\ReservasController;
use App\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function($app){

    // ==========================
    // RUTAS NAVES
    // ==========================
    $app->group('/naves', function(RouteCollectorProxy $group){

        $group->get('/all', [NavesController::class, 'all']);
        $group->post('/create', [NavesController::class, 'create']);
        $group->put('/update/{id}', [NavesController::class, 'update']);
        $group->delete('/delete/{id}', [NavesController::class, 'delete']);

    });

    // ==========================
    // RUTAS VUELOS
    // ==========================
    $app->group('/vuelos', function(RouteCollectorProxy $group){

        $group->get('/all', [VuelosController::class, 'all']);
        $group->post('/create', [VuelosController::class, 'create']);
        $group->put('/update/{id}', [VuelosController::class, 'update']);
        $group->delete('/delete/{id}', [VuelosController::class, 'delete']);

        // Buscar vuelos
        $group->get('/search', [VuelosController::class, 'search']);
    });

    // ==========================
    // RUTAS RESERVAS
    // ==========================
    $app->group('/reservas', function(RouteCollectorProxy $group){

        // Crear reserva (solo gestor autenticado)
        $group->post('/create', [ReservasController::class, 'create'])
              ->add(new AuthMiddleware());

        // Listar reservas del usuario autenticado
        $group->get('/my', [ReservasController::class, 'my'])
              ->add(new AuthMiddleware());

        // Cancelar reserva
        $group->delete('/delete/{id}', [ReservasController::class, 'delete'])
              ->add(new AuthMiddleware());
    });
};
