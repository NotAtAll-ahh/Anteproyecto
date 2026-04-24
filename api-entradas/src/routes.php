<?php
//Obtener la URL y el método HTTP
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalizar ruta (para pode accedes desde el navegador desde /public)
$basePath = '/api-entradas/public';
$uri = str_replace($basePath, '', $uri);

$method = $_SERVER['REQUEST_METHOD'];

//Incluir los controladores
require_once __DIR__ . '/controllers/EventoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/ReservaController.php';

switch (true) {

    // EVENTOS
    //Lista todos los eventos
    case $uri === '/api/eventos' && $method === 'GET':
        EventoController::index();
        break;
    // Buscar eventos por nombre
    case preg_match('#^/api/eventos/buscar/nombre/(.+)$#', $uri, $matches) && $method === 'GET':
        EventoController::searchByName($matches[1]);
        break;

    //Muestra un evento específico
    case preg_match('#^/api/eventos/(\d+)$#', $uri, $matches) && $method === 'GET':
        EventoController::show($matches[1]);
        break;
    //Muestra la disponibilidad de entradas de un evento
    case preg_match('#^/api/eventos/(\d+)/disponibilidad$#', $uri, $matches) && $method === 'GET':
        EventoController::disponibilidad($matches[1]);
        break;
    //Crea un nuevo evento
    case $uri === '/api/eventos' && $method === 'POST':
        UsuarioController::requireAdmin(); //solo admins pueden crear eventos
        EventoController::store();

        break;

    //Actualiza un evento existente
    case preg_match('#^/api/eventos/(\d+)$#', $uri, $matches) && $method === 'PUT':
        UsuarioController::requireAdmin(); //solo admins pueden actualizar eventos
        EventoController::update($matches[1]);
        break;

    //añadir imagen a un evento
    case preg_match('#^/api/eventos/(\d+)/imagen$#', $uri, $matches) && $method === 'POST':
        UsuarioController::requireAdmin(); // solo admin
        EventoController::uploadImagen($matches[1]);
        break;

    // Eliminar un evento
    case preg_match('#^/api/eventos/(\d+)$#', $uri, $matches) && $method === 'DELETE':
        UsuarioController::requireAdmin(); // solo admin
        EventoController::destroy($matches[1]);
        break;


    // USUARIOS
    //Registro de usuario
    case $uri === '/api/usuarios' && $method === 'POST':
        UsuarioController::store();
        break;
    //Login de usuario
    case $uri === '/api/login' && $method === 'POST':
        UsuarioController::login();
        break;

    //ruta para ver si hay sesión activa
    case $uri === '/api/sesion' && $method === 'GET':
        UsuarioController::sesion();
        break;

    //Logout de usuario
    case $uri === '/api/logout' && $method === 'POST':
        UsuarioController::logout();
        break;

    //Mostrar perfil de usuario
    case preg_match('#^/api/usuarios/(\d+)$#', $uri, $matches) && $method === 'GET':
        UsuarioController::show($matches[1]);
        break;
    //subir foto de perfil
    case preg_match('#^/api/usuarios/(\d+)/foto$#', $uri, $matches) && $method === 'POST':
        UsuarioController::uploadFotoDePerfil($matches[1]);
        break;
    //Actualizar perfil de usuario
    case preg_match('#^/api/usuarios/(\d+)$#', $uri, $matches) && $method === 'PUT':
        UsuarioController::update($matches[1]);
        break;


    // RESERVAS
    //Crear una reserva
    case $uri === '/api/reservas' && $method === 'POST':
        ReservaController::store();
        break;
    //Listar reservas del usuario logueado
    case $uri === '/api/mis-reservas' && $method === 'GET':
        ReservaController::misReservas();
        break;
    //Caso por defecto (ruta no encontrada)
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
}
