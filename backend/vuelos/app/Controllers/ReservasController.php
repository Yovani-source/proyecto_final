<?php
namespace App\Controllers;

use App\Models\Reserva;
use App\Models\Vuelo;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ReservasController
{
    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');

        if (!isset($data['vuelo_id'])) {
            return jsonResponse($response, ["error" => "Se requiere vuelo_id"], 400);
        }

        $vuelo = Vuelo::find($data['vuelo_id']);
        if (!$vuelo) {
            return jsonResponse($response, ["error" => "Vuelo no encontrado"], 404);
        }

        $reserva = Reserva::create([
            "vuelo_id" => $data['vuelo_id'],
            "usuario_id" => $user->id,
            "fecha_reserva" => date("Y-m-d H:i:s")
        ]);

        return jsonResponse($response, [
            "message" => "Reserva creada correctamente",
            "reserva" => $reserva
        ], 201);
    }

    public function my(Request $request, Response $response)
    {
        $user = $request->getAttribute('user');

        $rows = Reserva::with('vuelo')
                       ->where('usuario_id', $user->id)
                       ->get();

        return jsonResponse($response, $rows);
    }

    public function delete(Request $request, Response $response, array $args)
    {
        $user = $request->getAttribute('user');
        $id = (int)$args['id'];

        $reserva = Reserva::where('id', $id)
                          ->where('usuario_id', $user->id)
                          ->first();

        if (!$reserva)
            return jsonResponse($response, ["error" => "Reserva no encontrada"], 404);

        $reserva->delete();

        return jsonResponse($response, ["message" => "Reserva cancelada correctamente"]);
    }
}
