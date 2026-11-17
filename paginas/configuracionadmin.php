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
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Configuración de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="bg-light-purple">

    <div class="dashboard-grid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>EDU FOOD ADMIN</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="./inadministrador.html" class="nav-link">
                    <i class="fa-solid fa-utensils"></i> Gestión Menú
                </a>
                <a href="./configuracionadmin.html" class="nav-link active">
                    <i class="fa-solid fa-user-gear"></i> Mi Perfil
                </a>
                <a href="./logout.php">Cerrar sesión</a>
            </nav>
        </aside>

        <main class="main-content-dashboard">
            <header class="top-bar bg-white p-4 shadow-sm mb-4">
                <h3 class="m-0 text-grafito fw-bold">Perfil de Administrador</h3>
            </header>

            <div class="container-fluid px-4">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="main-card text-center p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="../imagenes/imagen_3_carrucel.jpg" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover;" alt="Avatar Admin">
                                <label for="file-input" class="btn-edit-img" title="Cambiar foto">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input id="file-input" type="file" class="d-none">
                            </div>
                            <h4 class="fw-bold text-grafito">Dra. Elena Ramos</h4>
                            <p class="text-muted">Administrador General</p>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="main-card p-4">
                            <h4 class="fw-bold mb-4 text-grafito">Actualizar Datos de Cuenta</h4>
                            <form style="margin: 0; max-width: 100%; background: transparent; padding: 0; box-shadow: none; border-top: none;">
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nombre Completo</label>
                                    <input type="text" class="form-control custom-input" value="Elena Ramos">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Correo Electrónico</label>
                                    <input type="email" class="form-control custom-input" value="elena.ramos@edufood.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nueva Contraseña</label>
                                    <input type="password" class="form-control custom-input" placeholder="••••••••">
                                </div>
                                <button type="submit" class="submit-btn mt-3">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> 