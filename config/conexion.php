<?php
$host = 'localhost';
$db   = 'tutorias_db';
$user = 'tutorias_user';
$pass = '12345';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opciones);
    $conexion = $pdo;
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
