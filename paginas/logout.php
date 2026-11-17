<?php
session_start();
$_SESSION = array(); // Vaciar variables de sesión

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirigir al formulario de inicio de sesión o al index principal
header("Location: iniciodesesion.html"); 
exit();
?>