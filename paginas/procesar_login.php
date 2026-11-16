<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo   = trim($_POST['correo'] ?? '');
    $password = trim($_POST['contrasena'] ?? '');

    try {
        $stmt = $pdo->prepare("SELECT l.id_usuario, u.nombre, l.rol, l.contrasena, l.estado 
                               FROM login l 
                               JOIN usuarios u ON l.id_usuario = u.id_usuario 
                               WHERE l.correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch();

        if ($user && $user['estado'] == 1 && password_verify($password, $user['contrasena'])) {  

            session_regenerate_id(true);    

            $_SESSION['usuario_id'] = $user['id_usuario'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            switch ($_SESSION['rol']) {
                case 'admin':
                case 'Administrador':
                    header("Location: inadministrador.html");
                    break;
                case 'Estudiante':
                case 'cliente':
                    header("Location: inusuario.html");
                    break;
                default:
                    header("Location: ../index.html");
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "Correo o contraseña incorrectos.";
            header("Location: iniciodesesion.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Error en la autenticación: " . $e->getMessage());
    }
}
?>