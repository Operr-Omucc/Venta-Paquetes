<?php
// Conexión a la base de datos
$servernamePHP = "localhost";
$usernamePHP = "root";
$passwordPHP = "";
$dbnamePHP = "paqueviaje";
$connPHP = new mysqli($servernamePHP, $usernamePHP, $passwordPHP, $dbnamePHP);

// Verificar la conexión
if ($connPHP->connect_error) {
    die("Error de conección: " . $connPHP->connect_error);
}
?>