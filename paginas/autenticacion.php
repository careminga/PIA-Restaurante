
<?php

function verificarSesion() {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['error'] = "Debes iniciar sesión para acceder.";
        header("Location: iniciodesesion.html");
        exit();
    }
}

function requerirRol($rolEsperado) {

    verificarSesion();

    $rolUsuario = strtolower(trim($_SESSION['rol']));
    $rolEsperado = strtolower(trim($rolEsperado));

    $rolesAdmin = ['admin', 'administrador'];
    $rolesEstudiante = ['estudiante', 'cliente'];

    if ($rolEsperado == 'administrador' || $rolEsperado == 'admin') {
        $permitidos = $rolesAdmin;
    } else {
        $permitidos = $rolesEstudiante;
    }

    if (!in_array($rolUsuario, $permitidos)) {

        $_SESSION['error'] = "No tienes permisos para acceder.";

        if (in_array($rolUsuario, $rolesAdmin)) {
            header("Location: inadministrador.html");
        } elseif (in_array($rolUsuario, $rolesEstudiante)) {
            header("Location: inusuario.html");
        } else {
            header("Location: iniciodesesion.php");
        }

        exit();
    }
}

?>

