<?php
session_start();
require_once 'conexion.php';
require_once 'autenticacion.php';
requerirRol('Administrador');

$id_usuario = $_SESSION['usuario_id'] ?? 0;
$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre     = trim($_POST['nombre'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($nombre === '' || $correo === '') {
        $error = "El nombre y el correo electrónico son obligatorios.";
    } else {
        try {
            $pdo->beginTransaction();

            if (!empty($contrasena)) {
                $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
                $sqlLogin = "UPDATE login SET correo = :correo, contrasena = :contrasena WHERE id_usuario = :id_usuario";
                $stmt = $pdo->prepare($sqlLogin);
                $stmt->execute([
                    'correo'     => $correo,
                    'contrasena' => $hashContrasena,
                    'id_usuario' => $id_usuario
                ]);
            } else {
                $sqlLogin = "UPDATE login SET correo = :correo WHERE id_usuario = :id_usuario";
                $stmt = $pdo->prepare($sqlLogin);
                $stmt->execute([
                    'correo'     => $correo,
                    'id_usuario' => $id_usuario
                ]);
            }

            $sqlUsuario = "UPDATE usuarios SET nombre = :nombre WHERE id_usuario = :id_usuario";
            $stmtU = $pdo->prepare($sqlUsuario);
            $stmtU->execute([
                'nombre'     => $nombre,
                'id_usuario' => $id_usuario
            ]);

            $pdo->commit();
            $_SESSION['nombre'] = $nombre;
            $mensaje = "Datos de administración actualizados correctamente.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error en la DB: " . $e->getMessage();
        }
    }
}

// Consultar datos actuales del administrador que inició sesión
$sqlDatos = "SELECT u.nombre, u.apellido, l.correo, l.rol
             FROM usuarios u
             LEFT JOIN login l ON u.id_usuario = l.id_usuario
             WHERE u.id_usuario = :id_usuario";
$stmtDatos = $pdo->prepare($sqlDatos);
$stmtDatos->execute(['id_usuario' => $id_usuario]);
$adminDatos = $stmtDatos->fetch(PDO::FETCH_ASSOC);

$nombreActual = $adminDatos['nombre'] ?? ($_SESSION['nombre'] ?? 'Administrador');
$apellidoActual = $adminDatos['apellido'] ?? '';
$nombreCompleto = trim($nombreActual . ' ' . $apellidoActual);
$correoActual = $adminDatos['correo'] ?? '';
$rolTexto = $adminDatos['rol'] ?? ($_SESSION['rol'] ?? 'Administrador');
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
                <a href="./configuracionadministrador.php" class="nav-link active">
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
                <?php if ($mensaje !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
                <?php endif; ?>
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

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
                            <h4 class="fw-bold text-grafito"><?= htmlspecialchars($nombreCompleto) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($rolTexto) ?></p>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="main-card p-4">
                            <h4 class="fw-bold mb-4 text-grafito">Actualizar Datos de Cuenta</h4>
                            <form method="POST" action="configuracionadministrador.php" style="margin: 0; max-width: 100%; background: transparent; padding: 0; box-shadow: none; border-top: none;">
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nombre Completo</label>
                                    <input type="text" name="nombre" class="form-control custom-input" value="<?= htmlspecialchars($nombreActual) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Correo Electrónico</label>
                                    <input type="email" name="correo" class="form-control custom-input" value="<?= htmlspecialchars($correoActual) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nueva Contraseña</label>
                                    <input type="password" name="contrasena" class="form-control custom-input" placeholder="Déjala en blanco si no deseas cambiarla">
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