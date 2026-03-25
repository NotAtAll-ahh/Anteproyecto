<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Evento.php';

class ReservaController {

    // CREAR RESERVA
    public static function store() {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        // Validación mínima
        if (!isset($data["usuario_id"]) || !isset($data["evento_id"]) || !isset($data["cantidad"])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos para la reserva"]);
            return;
        }

        // Comprobar disponibilidad del evento
        $evento = Evento::find($pdo, $data["evento_id"]);

        if (!$evento) {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
            return;
        }

        if ($evento["entradas_disponibles"] < $data["cantidad"]) {
            http_response_code(400);
            echo json_encode(["error" => "No hay suficientes entradas disponibles"]);
            return;
        }

        // Crear reserva
        Reserva::create($pdo, $data);

        // Actualizar disponibilidad
        Evento::update($pdo, $evento["id"], [
            "entradas_disponibles" => $evento["entradas_disponibles"] - $data["cantidad"]
        ]);

        echo json_encode(["status" => "success", "message" => "Reserva realizada"]);
    }


    // LISTAR RESERVAS DE UN USUARIO
    public static function reservasUsuario($usuario_id) {
        global $pdo;

        $reservas = Reserva::findByUser($pdo, $usuario_id);

        echo json_encode(["status" => "success", "data" => $reservas]);
    }
}
