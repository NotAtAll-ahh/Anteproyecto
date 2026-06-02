<?php

$host = "localhost";
$db = "venta_entradas";
$user = "root";
$pass = "";

try {
    // Establecer conexión con la bbDD
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Configurar PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(json_encode(["error" => $e->getMessage()]));
}
