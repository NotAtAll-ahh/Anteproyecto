<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Evento.php';

class ReservaController {

    // CREAR RESERVA
    public static function store() {
        global $pdo;

        try {
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
        $nuevasDisponibles = $evento["entradas_disponibles"] - $data["cantidad"];
        $stmt = $pdo->prepare("UPDATE eventos SET entradas_disponibles = ? WHERE id = ?");
        $stmt->execute([$nuevasDisponibles, $evento["id"]]);

            echo json_encode(["status" => "success", "message" => "Reserva realizada"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error interno al crear la reserva"]);
        }
    }

    // LISTAR RESERVAS DEL USUARIO LOGUEADO
    public static function misReservas() {
        global $pdo;

        if (!isset($_SESSION["usuario_id"])) {
            http_response_code(401);
            echo json_encode(["error" => "Debes iniciar sesion"]);
            return;
        }

        $reservas = Reserva::findByUser($pdo, $_SESSION["usuario_id"]);
        echo json_encode(["status" => "success", "data" => $reservas]);
    }


    // LISTAR RESERVAS DE UN USUARIO
    public static function reservasUsuario($usuario_id) {
        global $pdo;

        $reservas = Reserva::findByUser($pdo, $usuario_id);

        echo json_encode(["status" => "success", "data" => $reservas]);
    }
}
