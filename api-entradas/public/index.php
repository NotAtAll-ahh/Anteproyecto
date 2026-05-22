<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();

$allowed_origins = [
    'https://tarea-proyecto-seo-victoria-dani.free.nf',
    'http://localhost/anteproyecto',
    'http://127.0.0.1',
    'http://localhost:8080',
    'http://127.0.0.1:8080'
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

require_once __DIR__ . '/../src/routes.php';
?>