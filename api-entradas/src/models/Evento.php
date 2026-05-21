<?php

class Evento {

// OBTENER TODOS LOS EVENTOS
    public static function all($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM eventos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // OBTENER EVENTOS POR CATEGORÍA
    public static function getByCategoria($pdo, $categoria)
    {
        $stmt = $pdo->prepare("SELECT * FROM eventos WHERE categoria = ?");
        $stmt->execute([$categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// OBTENER UN EVENTO POR ID
    public static function find($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
//BUSCAR EVENTOS POR NOMBRE
    public static function searchByName($pdo, $nombre) {
        $stmt = $pdo->prepare("SELECT * FROM eventos WHERE nombre LIKE ?");
        $stmt->execute(['%' . $nombre . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


// CREAR NUEVO EVENTO
public static function create($pdo, $data)
{
    $stmt = $pdo->prepare("INSERT INTO eventos (nombre, descripcion, ubicacion, fecha, entradas_totales, entradas_disponibles, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data["nombre"],
        $data["descripcion"],
        $data["ubicacion"],
        $data["fecha"],
        $data["entradas_totales"],
        $data["entradas_disponibles"],
        $data["categoria"] ?? 'concierto' // valor por defecto si no se envía
    ]);
}
    // ACTUALIZAR EVENTO
    public static function update($pdo, $id, $data)
    {
        $stmt = $pdo->prepare("UPDATE eventos SET nombre=?, descripcion=?, ubicacion=?, fecha=?, entradas_totales=?, entradas_disponibles=? WHERE id=?");
        $stmt->execute([
            $data["nombre"],
            $data["descripcion"],
            $data["ubicacion"],
            $data["fecha"],
            $data["entradas_totales"],
            $data["entradas_disponibles"],
            $id,
            $data["categoria"] ?? 'concierto' // valor por defecto si no se envía
        ]);
    }
//ELIMINAR EVENTO
    public static function delete($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
}

}