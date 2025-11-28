<?php

use Slim\App;
use App\controllers\usuariocontroller;

return function (App $app) {

    $app->get('/usuarios/prueba', function ($request, $response) {
        $response->getBody()->write(json_encode([
            "mensaje" => "ruta usuarios funcionando"
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/usuarios', [usuariocontroller::class, 'listar']);

    $app->get('/usuarios/{id}', [usuariocontroller::class, 'obtener']);

    $app->post('/usuarios', [usuariocontroller::class, 'crear']);

    $app->put('/usuarios/{id}', [usuariocontroller::class, 'actualizar']);

    $app->delete('/usuarios/{id}', [usuariocontroller::class, 'eliminar']);
};
