<?php
//importar conexión a la base de datos y modelo de usuario
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{

    // REGISTRO DE USUARIO
    public static function store()
    {
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
        // Comprobar si el email ya existe
        $existe = Usuario::findByEmail($pdo, $data["email"]);

        if ($existe) {
            http_response_code(409); // 409 = conflicto
            echo json_encode(["error" => "El email ya está registrado"]);
            return;
        }


        // Encriptar contraseña
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);

        // Crear usuario
        $nuevoId = Usuario::create($pdo, $data);

        echo json_encode(["status" => "success", "message" => "Usuario registrado", "id" => $nuevoId]);
    }


    // LOGIN
    public static function login()
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        $usuario = Usuario::findByEmail($pdo, $data["email"]);

        if (!$usuario || !password_verify($data["password"], $usuario["password"])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales incorrectas"]);
            return;
        }
        //crear sesión para el usuario
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_email"] = $usuario["email"];
        $_SESSION["usuario_rol"] = $usuario["rol"];

        echo json_encode([
            "status" => "success",
            "message" => "Login correcto",
            "usuario" => [
                "id" => $usuario["id"],
                "email" => $usuario["email"],
                "rol" => $usuario["rol"]

            ]
        ]);
    }
    // VER SI HAY SESIÓN ACTIVA
    public static function sesion()
    {
        if (!isset($_SESSION["usuario_id"])) {
            echo json_encode(["logueado" => false]);
            return;
        }

        echo json_encode([
            "logueado" => true,
            "usuario_id" => $_SESSION["usuario_id"],
            "email" => $_SESSION["usuario_email"],
            "rol" => $_SESSION["usuario_rol"]
        ]);
    }

    // LOGOUT
    public static function logout()
    {
        session_destroy();
        echo json_encode(["status" => "success", "message" => "Logout correcto"]);
    }


    // VER PERFIL
    public static function show($id)
    {
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
    public static function update($id)
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        // Si intenta cambiar el email, comprobar duplicado
        if (isset($data["email"])) {
            $existe = Usuario::findByEmail($pdo, $data["email"]);

            if ($existe && $existe["id"] != $id) {
                http_response_code(409);
                echo json_encode(["error" => "Ese email ya está en uso por otro usuario"]);
                return;
            }
        }

        if (isset($data["password"])) {
            $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        }

        Usuario::update($pdo, $id, $data);

        echo json_encode(["status" => "success", "message" => "Usuario actualizado"]);
    }

    //Subir foto de perfil 
    public static function uploadFotoDePerfil($id)
    {
        global $pdo;

        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0) {
            http_response_code(400);
            echo json_encode(["error" => "No se recibió ninguna imagen o hubo un error en la subida"]);
            return;
        }

        $dirDestino = __DIR__ . '/../../public/uploads/usuarios/';

        // Crear el directorio si no existe
        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0755, true);
        }

        $nombreArchivo = 'usuario_' . $id . '_' . time() . '.jpg';
        $rutaDestino = $dirDestino . $nombreArchivo;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo guardar la imagen en el servidor"]);
            return;
        }

        // Guardar la ruta en la base de datos
        $rutaBD = '/uploads/usuarios/' . $nombreArchivo;

        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->execute([$rutaBD, $id]);

        echo json_encode(["status" => "success", "foto" => $rutaBD]);
    }

    //Comprobar si el usuario es admin
    public static function requireAdmin()
    {
        if (!isset($_SESSION["usuario_rol"]) || $_SESSION["usuario_rol"] !== 'admin') {
            http_response_code(403);
            echo json_encode(["error" => "Acceso denegado. Solo administradores."]);
            exit;
        }
    }
}
