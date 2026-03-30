<?php
session_start();

header("Content-Type: application/json");

require_once __DIR__ . '/../src/routes.php';
?>