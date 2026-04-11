<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Evento.php';

class EventoController
{

    // LISTAR TODOS LOS EVENTOS
    public static function index()
    {
        global $pdo;
        $eventos = Evento::all($pdo);
        echo json_encode(["status" => "success", "data" => $eventos]);
    }

    // VER DETALLES DE UN EVENTO
    public static function show($id)
    {
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
    public static function disponibilidad($id)
    {
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
    public static function store()
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        // Validación mínima
        $required = ["nombre", "descripcion", "ubicacion", "fecha", "entradas_totales"];

        foreach ($required as $campo) {
            if (!isset($data[$campo]) || empty($data[$campo])) {
                http_response_code(400);
                echo json_encode(["error" => "El campo '$campo' es obligatorio"]);
                return;
            }
        }

        $entradasTotales = filter_var($data["entradas_totales"], FILTER_VALIDATE_INT);
        if ($entradasTotales === false || $entradasTotales < 1 || $entradasTotales > 155) {
            http_response_code(400);
            echo json_encode(["error" => "Las entradas totales deben estar entre 1 y 155"]);
            return;
        }

        $data["entradas_totales"] = $entradasTotales;

        // Entradas disponibles = totales al inicio
        $data["entradas_disponibles"] = $data["entradas_totales"];

        Evento::create($pdo, $data);

        // Obtener ID del último evento creado
        $id = $pdo->lastInsertId();

        echo json_encode([
            "status" => "success",
            "message" => "Evento creado",
            "id" => $id
        ]);
    }



    // ACTUALIZAR EVENTO

    public static function update($id)
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        // Validación básica
        if (isset($data["entradas_totales"]) && isset($data["entradas_disponibles"])) {
            if ($data["entradas_disponibles"] > $data["entradas_totales"]) {
                http_response_code(400);
                echo json_encode(["error" => "Las entradas disponibles no pueden superar las totales"]);
                return;
            }
        }

        Evento::update($pdo, $id, $data);

        echo json_encode(["status" => "success", "message" => "Evento actualizado"]);
    }


    //SUBIR IMAGEN DE EVENTO
    public static function uploadImagen($id)
    {
        global $pdo;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $directorioDestino = __DIR__ . '/../../public/uploads/eventos/';
            if (!is_dir($directorioDestino) && !mkdir($directorioDestino, 0777, true) && !is_dir($directorioDestino)) {
                http_response_code(500);
                echo json_encode(["error" => "No se pudo crear el directorio de imagenes"]);
                return;
            }

            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            if ($extension === '') {
                $extension = 'jpg';
            }

            $nombreArchivo = 'evento_' . $id . '_' . time() . '.' . $extension;
            $rutaDestino = $directorioDestino . $nombreArchivo;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                http_response_code(500);
                echo json_encode(["error" => "No se pudo guardar la imagen del evento"]);
                return;
            }

            // Guardar ruta en BD
            $rutaBD = '/uploads/eventos/' . $nombreArchivo;

            $stmt = $pdo->prepare("UPDATE eventos SET imagen = ? WHERE id = ?");
            $stmt->execute([$rutaBD, $id]);

            echo json_encode(["status" => "success", "imagen" => $rutaBD]);
            exit;
        }

        http_response_code(400);
        echo json_encode(["error" => "No se envió ninguna imagen"]);
    }

    //ELIMINAR EVENTO
    public static function destroy($id)
    {
        global $pdo;

        // Comprobar si el evento existe
        $evento = Evento::find($pdo, $id);

        if (!$evento) {
            http_response_code(404);
            echo json_encode(["error" => "Evento no encontrado"]);
            return;
        }

        // Si tiene imagen, eliminarla del servidor
        if (!empty($evento["imagen"])) {
            $rutaImagen = __DIR__ . '/../../public' . $evento["imagen"];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        // Eliminar el evento
        Evento::delete($pdo, $id);

        echo json_encode([
            "status" => "success",
            "message" => "Evento eliminado correctamente"
        ]);
    }
    //BUSCAR EVENTOS POR NOMBRE
    public static function searchByName($nombre)
    {
        global $pdo;
        $eventos = Evento::searchByName($pdo, $nombre);
        echo json_encode(["status" => "success", "data" => $eventos]);
    }


}


