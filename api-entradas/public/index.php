<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();

header("Content-Type: application/json");

require_once __DIR__ . '/../src/routes.php';
?>