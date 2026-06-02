<?php

class Evento {

    private static function getExistingColumns($pdo)
    {
        $stmt = $pdo->query("SHOW COLUMNS FROM eventos");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

// OBTENER TODOS LOS EVENTOS
    public static function all($pdo)
    {
        $stmt = $pdo->query("SELECT * FROM eventos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // OBTENER EVENTOS POR CATEGORÍA
    public static function getByCategoria($pdo, $categoria)
    {
        $availableColumns = array_flip(self::getExistingColumns($pdo));

        if (!isset($availableColumns['categoria'])) {
            return [];
        }

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
    $availableColumns = array_flip(self::getExistingColumns($pdo));

    $insertData = [
        'nombre' => $data['nombre'],
        'descripcion' => $data['descripcion'],
        'ubicacion' => $data['ubicacion'],
        'fecha' => $data['fecha'],
        'entradas_totales' => $data['entradas_totales'],
        'entradas_disponibles' => $data['entradas_disponibles'],
        'categoria' => $data['categoria'] ?? 'concierto'
    ];

    $columns = [];
    $values = [];
    $placeholders = [];

    foreach ($insertData as $column => $value) {
        if (!isset($availableColumns[$column])) {
            continue;
        }

        $columns[] = $column;
        $values[] = $value;
        $placeholders[] = '?';
    }

    if (empty($columns)) {
        throw new RuntimeException('La tabla eventos no tiene columnas compatibles para crear registros');
    }

    $sql = sprintf(
        'INSERT INTO eventos (%s) VALUES (%s)',
        implode(', ', $columns),
        implode(', ', $placeholders)
    );

    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}
    // ACTUALIZAR EVENTO
    public static function update($pdo, $id, $data)
    {
        // Verificar que la tabla eventos tiene las columnas necesarias para actualizar el registro
        $availableColumns = array_flip(self::getExistingColumns($pdo));

        $updateData = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'ubicacion' => $data['ubicacion'],
            'fecha' => $data['fecha'],
            'entradas_totales' => $data['entradas_totales'],
            'entradas_disponibles' => $data['entradas_disponibles'],
            'categoria' => $data['categoria'] ?? 'concierto'
        ];

        $assignments = [];
        $values = [];

        foreach ($updateData as $column => $value) {
            if (!isset($availableColumns[$column])) {
                continue;
            }

            $assignments[] = $column . ' = ?';
            $values[] = $value;
        }

        if (empty($assignments)) {
            throw new RuntimeException('La tabla eventos no tiene columnas compatibles para actualizar registros');
        }

        $values[] = $id;

        $sql = sprintf(
            'UPDATE eventos SET %s WHERE id = ?',
            implode(', ', $assignments)
        );

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
    }
//ELIMINAR EVENTO
    public static function delete($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
}

}