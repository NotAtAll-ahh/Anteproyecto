<?php

class Usuario
{

    // Crear usuario nuevo
    public static function create($pdo, $data)
    {
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (email, password, nombre)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $data["email"],
            $data["password"],
            //si no se envía el nombre, se pone null 
            $data["nombre"] ?? null
        ]);
    }

    // Buscar usuario por ID
    public static function find($pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por email (para login)
    public static function findByEmail($pdo, $email)
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario
    public static function update($pdo, $id, $data)
    {
        // Construir consulta dinámica según los campos enviados
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;

        $sql = "UPDATE usuarios SET " . implode(", ", $fields) . " WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
    }

    //Subir foto de perfil
    public static function uploadFotoDePerfil($pdo, $id, $ruta)
    {
        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->execute([$ruta, $id]);
    }
}
