<?php
// autenticacion.php - Incluir en la primera línea de cualquier página protegida
function verificarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['error'] = "Debes iniciar sesión para acceder.";
        header("Location: iniciodesesión.html"); // Apunta a tu formulario de login real
        exit();
    }
}

function requerirRol($rolEsperado) {
    verificarSesion();
    
    // Si el rol en sesión no coincide con el rol esperado para esta página
    if ($_SESSION['rol'] !== $rolEsperado) {
        $_SESSION['error'] = "No tienes permisos para acceder a esta sección.";
        
        switch ($_SESSION['rol']) {
            case 'admin':
            case 'Administrador':
                header("Location: configuracionadmin.php");
                break;
            case 'Estudiante':
            case 'cliente':
                header("Location: configuracionusuario.php");
                break;
            default:
                header("Location: iniciodesesion.html");
                break;
        }
        exit();
    }
}
?>