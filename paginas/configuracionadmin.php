<?php
session_start();
require_once 'conexion.php';
require_once 'autenticacion.php';
requerirRol('Administrador');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre     = $_POST['nombre'];
    $correo     = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $id_usuario = $_SESSION['usuario_id']; // Usamos el ID unificado de sesión

    try {
        if (!empty($contrasena)) {
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            // Actualizamos la tabla login (correo y contraseña) y usuarios (nombre)
            $sqlLogin = "UPDATE login SET correo = :correo, contrasena = :contrasena WHERE id_usuario = :id_usuario";
            $stmt = $pdo->prepare($sqlLogin);
            $stmt->execute(['correo' => $correo, 'contrasena' => $contrasena, 'id_usuario' => $id_usuario]);

        } else {
            // Si no cambia la contraseña, solo actualizamos el correo en login
            $sqlLogin = "UPDATE login SET correo = :correo WHERE id_usuario = :id_usuario";
            $stmt = $pdo->prepare($sqlLogin);
            $stmt->execute(['correo' => $correo, 'id_usuario' => $id_usuario]);
        }

        // Actualizamos el nombre en la tabla usuarios
        $sqlUsuario = "UPDATE usuarios SET nombre = :nombre WHERE id_usuario = :id_usuario";
        $stmtU = $pdo->prepare($sqlUsuario);
        $stmtU->execute(['nombre' => $nombre, 'id_usuario' => $id_usuario]);

        // Actualizamos la variable de sesión del nombre
        $_SESSION['nombre'] = $nombre;

        $mensaje = "Datos de administración actualizados correctamente.";

    } catch (PDOException $e) {
        $mensaje = "Error en la DB: " . $e->getMessage();
    }
}
?>