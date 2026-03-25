<?php

class Evento {

    public static function all($pdo) {
        $stmt = $pdo->query("SELECT * FROM eventos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare("INSERT INTO eventos (nombre, descripcion, ubicacion, fecha, entradas_totales, entradas_disponibles) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data["nombre"],
            $data["descripcion"],
            $data["ubicacion"],
            $data["fecha"],
            $data["entradas_totales"],
            $data["entradas_disponibles"]
        ]);
    }

    public static function update($pdo, $id, $data) {
        $stmt = $pdo->prepare("UPDATE eventos SET nombre=?, descripcion=?, ubicacion=?, fecha=?, entradas_totales=?, entradas_disponibles=? WHERE id=?");
        $stmt->execute([
            $data["nombre"],
            $data["descripcion"],
            $data["ubicacion"],
            $data["fecha"],
            $data["entradas_totales"],
            $data["entradas_disponibles"],
            $id
        ]);
    }
}