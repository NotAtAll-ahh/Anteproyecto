<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Evento.php';

class EventoController {

    public static function index() {
        global $pdo;
        $eventos = Evento::all($pdo);
        echo json_encode(["status" => "success", "data" => $eventos]);
    }

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

    public static function store() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        Evento::create($pdo, $data);
        echo json_encode(["status" => "success", "message" => "Evento creado"]);
    }

    public static function update($id) {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        Evento::update($pdo, $id, $data);
        echo json_encode(["status" => "success", "message" => "Evento actualizado"]);
    }
}