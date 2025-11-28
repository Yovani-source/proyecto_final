<?php

namespace App\Middleware;

use Slim\Psr7\Response as SlimResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RoleMiddleware implements MiddlewareInterface
{
    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        // Usuario que AuthMiddleware ya insertó en el request
        $user = $request->getAttribute('user');

        // Si no hay usuario o el rol no está autorizado:
        if (!$user || !in_array($user->role, $this->roles)) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(["error" => "No autorizado"]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Continuar a la ruta
        return $handler->handle($request);
    }
}


