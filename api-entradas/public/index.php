<?php
ini_set('display_errors', 0);
error_reporting(0);

// CORS: allow Netlify frontend and local dev while keeping credentials enabled.
$frontendUrl = rtrim((getenv('FRONTEND_URL') ?: 'https://tarea-proyecto-seo-victoria-dani.netlify.app'), '/');
$allowedOrigins = array_filter([
	$frontendUrl,
	'https://tarea-proyecto-seo-victoria-dani.netlify.app',
	'http://localhost',
	'http://127.0.0.1',
	'http://localhost:5500',
	'http://127.0.0.1:5500',
]);

$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($requestOrigin !== '' && in_array($requestOrigin, $allowedOrigins, true)) {
	header("Access-Control-Allow-Origin: $requestOrigin");
}

header('Vary: Origin');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
	http_response_code(204);
	exit;
}

// Required so browser can send PHP session cookie from Netlify -> Railway.
$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
	'lifetime' => 0,
	'path' => '/',
	'secure' => $isHttps,
	'httponly' => true,
	'samesite' => 'None',
]);

session_start();

header("Content-Type: application/json");

require_once __DIR__ . '/../src/routes.php';
?>