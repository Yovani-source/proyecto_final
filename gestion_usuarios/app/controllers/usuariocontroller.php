<?php

namespace App\controllers;

use App\models\usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class usuariocontroller
{
    public function crear(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        // encriptar contraseña
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        // crear usuario
        $usuario = usuario::create($data);

        $response->getBody()->write(json_encode([
            'mensaje' => 'usuario creado correctamente',
            'data' => $usuario
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listar(Request $request, Response $response)
    {
        $usuarios = usuario::all();
        $response->getBody()->write(json_encode($usuarios));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function obtener(Request $request, Response $response, $args)
    {
        $usuario = usuario::find($args['id']);
        $response->getBody()->write(json_encode($usuario));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function actualizar(Request $request, Response $response, $args)
    {
        $usuario = usuario::find($args['id']);
        $data = $request->getParsedBody();

        // si se actualiza contraseña → volver a encriptar
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $usuario->update($data);

        $response->getBody()->write(json_encode([
            'mensaje' => 'usuario actualizado',
            'data' => $usuario
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function eliminar(Request $request, Response $response, $args)
    {
        $usuario = usuario::find($args['id']);
        $usuario->delete();

        $response->getBody()->write(json_encode([
            'mensaje' => 'usuario eliminado'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
