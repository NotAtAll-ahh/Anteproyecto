<?php
//importar conexión a la base de datos y modelo de usuario
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    // REGISTRO DE USUARIO
    public static function store() {
        //usa la conexión global a la base de datos
        global $pdo;

        // Leer JSON enviado por el cliente
        $data = json_decode(file_get_contents("php://input"), true);

        // Validación mínima
        if (!isset($data["email"]) || !isset($data["password"])) {
            http_response_code(400);
            echo json_encode(["error" => "Email y contraseña son obligatorios"]);
            return;
        }

        // Encriptar contraseña
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);

        // Crear usuario
        Usuario::create($pdo, $data);

        echo json_encode(["status" => "success", "message" => "Usuario registrado"]);
    }


    // LOGIN
    public static function login() {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        $usuario = Usuario::findByEmail($pdo, $data["email"]);

        if (!$usuario || !password_verify($data["password"], $usuario["password"])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales incorrectas"]);
            return;
        }

        // Aquí podrías generar un token JWT manual si quieres
        echo json_encode([
            "status" => "success",
            "message" => "Login correcto",
            "usuario" => [
                "id" => $usuario["id"],
                "email" => $usuario["email"]
            ]
        ]);
    }


    // VER PERFIL
    public static function show($id) {
        global $pdo;

        $usuario = Usuario::find($pdo, $id);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
            return;
        }

        unset($usuario["password"]); // Nunca devolver la contraseña

        echo json_encode(["status" => "success", "data" => $usuario]);
    }


    // ACTUALIZAR PERFIL
    public static function update($id) {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        }

        Usuario::update($pdo, $id, $data);

        echo json_encode(["status" => "success", "message" => "Usuario actualizado"]);
    }

    //Subir foto de perfil 
    public static function uploadFotoDePerfil($id){
        global $pdo;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {

        $nombreArchivo = 'usuario_' . $id . '_' . time() . '.jpg';
        $rutaDestino = __DIR__ . '/../../public/uploads/usuarios/' . $nombreArchivo;

        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);

        // Guardar la ruta en la base de datos
        $rutaBD = '/uploads/usuarios/' . $nombreArchivo;

        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->execute([$rutaBD, $id]);

        echo json_encode(["status" => "success", "foto" => $rutaBD]);
        exit;
    }



    }
}