<?php

$host = "sql308.infinityfree.com";
$db = "if0_41747764_venta_entradas";
$user = "if0_41747764";
$pass = "WarWick2018";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(json_encode(["error" => $e->getMessage()]));
}
