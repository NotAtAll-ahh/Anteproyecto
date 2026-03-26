<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Evento.php';

class EventoController {

// LISTAR TODOS LOS EVENTOS
    public static function index() {
        global $pdo;
        $eventos = Evento::all($pdo);
        echo json_encode(["status" => "success", "data" => $eventos]);
    }

    // VER DETALLES DE UN EVENTO
    public static function show($id) {
        global $pdo;
        $evento = Evento::find($pdo, $id);

        if (!$evento) {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
            return;
        }

        echo json_encode(["status" => "success", "data" => $evento]);
    }
// VER DISPONIBILIDAD DE ENTRADAS
    public static function disponibilidad($id) {
        global $pdo;
        $evento = Evento::find($pdo, $id);

        if (!$evento) {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
            return;
        }

        echo json_encode([
            "status" => "success",
            "data" => [
                "id" => $evento["id"],
                "entradas_disponibles" => $evento["entradas_disponibles"]
            ]
        ]);
    }
    // CREAR NUEVO EVENTO
    public static function store() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        Evento::create($pdo, $data);
        echo json_encode(["status" => "success", "message" => "Evento creado"]);
    }

    // ACTUALIZAR EVENTO

    public static function update($id) {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        Evento::update($pdo, $id, $data);
        echo json_encode(["status" => "success", "message" => "Evento actualizado"]);
    }
}