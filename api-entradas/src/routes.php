<?php
//Obtener la URL y el método HTTP

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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
        EventoController::store();
        break;

    //Actualiza un evento existente
    case preg_match('#^/api/eventos/(\d+)$#', $uri, $matches) && $method === 'PUT':
        EventoController::update($matches[1]);
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

    // RESERVAS
    //Crear una reserva
    case $uri === '/api/reservas' && $method === 'POST':
        ReservaController::store();
        break;
    //Caso por defecto (ruta no encontrada)
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
}
