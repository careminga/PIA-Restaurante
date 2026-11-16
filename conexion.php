<?php
$host = "localhost";
$usuario = "root";
$password = "";
$database = "edufood";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try{
    $pdo = new PDO($dsn, $usuario, $password, $opciones);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>