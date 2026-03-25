<?php

class Reserva {

    // Crear una reserva
    public static function create($pdo, $data) {
        $stmt = $pdo->prepare("
            INSERT INTO reservas (usuario_id, evento_id, cantidad, fecha_reserva)
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->execute([
            $data["usuario_id"],
            $data["evento_id"],
            $data["cantidad"]
        ]);
    }

    // Buscar reservas por usuario
    public static function findByUser($pdo, $usuario_id) {
        $stmt = $pdo->prepare("
            SELECT r.*, e.nombre AS evento_nombre, e.fecha AS evento_fecha
            FROM reservas r
            JOIN eventos e ON r.evento_id = e.id
            WHERE r.usuario_id = ?
            ORDER BY r.fecha_reserva DESC
        ");

        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}