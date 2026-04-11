<?php

class Reserva {

    private static function getFechaColumn($pdo) {
        $stmt = $pdo->query("SHOW COLUMNS FROM reservas");
        $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (in_array("created_at", $columnas, true)) {
            return "created_at";
        }

        if (in_array("fecha_reserva", $columnas, true)) {
            return "fecha_reserva";
        }

        return null;
    }

    // Crear una reserva
    public static function create($pdo, $data) {
        $fechaColumn = self::getFechaColumn($pdo);

        if ($fechaColumn !== null) {
            $sql = "INSERT INTO reservas (usuario_id, evento_id, cantidad, $fechaColumn) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data["usuario_id"],
                $data["evento_id"],
                $data["cantidad"]
            ]);
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, evento_id, cantidad) VALUES (?, ?, ?)");
        $stmt->execute([
            $data["usuario_id"],
            $data["evento_id"],
            $data["cantidad"]
        ]);
    }

    // Buscar reservas por usuario
    public static function findByUser($pdo, $usuario_id) {
        $fechaColumn = self::getFechaColumn($pdo);
        $fechaSelect = $fechaColumn !== null ? "r.$fechaColumn AS fecha_reserva" : "NULL AS fecha_reserva";
        $orderByFecha = $fechaColumn !== null ? "r.$fechaColumn DESC" : "r.id DESC";

        $stmt = $pdo->prepare(" 
            SELECT r.*, $fechaSelect, e.nombre AS evento_nombre, e.fecha AS evento_fecha, e.imagen AS evento_imagen
            FROM reservas r
            JOIN eventos e ON r.evento_id = e.id
            WHERE r.usuario_id = ?
            ORDER BY $orderByFecha
        ");

        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}