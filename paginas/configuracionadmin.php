

<?php
session_start();
require_once 'conexion.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $id_admin = $_SESSION['id_admin'];

    try{

        if(!empty($contrasena)){

            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            $sql = "UPDATE administradores SET nombre = :nombre, correo = :correo, contrasena = :contrasena WHERE id_admin = :id_admin";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'nombre' => $nombre,
                'correo' => $correo,
                'contrasena' => $contrasena,
                'id_admin' => $id_admin
            ]);

        }else{

            $sql = "UPDATE administradores SET nombre = :nombre, correo = :correo WHERE id_admin = :id_admin";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'nombre' => $nombre,
                'correo' => $correo,
                'id_admin' => $id_admin
            ]);

        }

        $mensaje = "Datos actualizados correctamente";

    }catch (PDOException $e){

        $mensaje = "Error en la DB: " . $e->getMessage();

    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración Admin</title>
</head>
<body>

    <?php
    if(isset($mensaje)){
        echo "<p>$mensaje</p>";
    }
    ?>

</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Configuración y Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="bg-light-purple">

    <div class="dashboard-grid">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>EDU FOOD ADMIN</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="./inadministrador.html" class="nav-link">
                    <i class="fa-solid fa-utensils"></i> Gestión Menú
                </a>
                <a href="./configuracionadmin.html" class="nav-link active">
                    <i class="fa-solid fa-user-gear"></i> Gestión de Usuarios
                </a>
                <a href="../index.html" class="nav-link logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Salir
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content-dashboard">
            <header class="top-bar bg-white p-4 shadow-sm mb-4">
                <h3 class="m-0 text-grafito fw-bold">Panel de Administración - Perfiles y Usuarios</h3>
            </header>

            <div class="container-fluid px-4">
                <div class="row g-4">
                    <!-- Tarjeta de Información, Avatar y Buscador de Usuarios -->
                    <div class="col-lg-4">
                        <div class="main-card text-center p-4">
                            <div class="position-relative d-inline-block mb-3">
                                <img id="userAvatar" src="../imagenes/imagen_3_carrucel.jpg"
                                    class="rounded-circle shadow"
                                    style="width: 130px; height: 130px; object-fit: cover;" alt="Avatar Usuario">
                                <label for="file-input" class="btn-edit-img" title="Cambiar foto">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                                <input id="file-input" type="file" class="d-none">
                            </div>
                            <h4 id="userNameDisplay" class="fw-bold text-grafito">Dra. Elena Ramos</h4>
                            <p id="userRoleDisplay" class="text-muted mb-4">Administrador General</p>

                            <hr class="my-3">

                            <!-- Buscador de Usuarios (Vinculado a Backend PHP) -->
                            <div class="text-start">
                                <label for="buscarUsuarioInput" class="form-label text-grafito fw-bold small">
                                    <i class="fa-solid fa-search me-1"></i> Buscar Usuario a Editar
                                </label>
                                <div class="input-group mb-2">
                                    <input type="text" id="buscarUsuarioInput" class="form-control form-control-sm"
                                        placeholder="Nombre o correo...">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                        id="btnBuscarUsuario">Buscar</button>
                                </div>
                                <div id="sugerenciasUsuarios" class="list-group position-absolute shadow-sm w-100"
                                    style="z-index: 1000; max-height: 150px; overflow-y: auto;">
                                    <!-- Aquí se cargarán dinámicamente las sugerencias desde PHP vía AJAX/Fetch -->
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Escribe para autocompletar o
                                    selecciona un usuario de la base de datos.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de Configuración y Edición -->
                    <div class="col-lg-8">
                        <div class="main-card p-4">
                            <h4 class="fw-bold mb-4 text-grafito">Modificar Datos del Usuario</h4>
                            <!-- Este formulario enviará los datos actualizados a tu script de PHP (ej. actualizar_usuario.php) -->
                            <form action="configuracionadmin.php" method="POST"
                                style="margin: 0; max-width: 100%; background: transparent; padding: 0; box-shadow: none; border-top: none;">

                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nombre Completo</label>
                                    <input type="text" name="nombre" class="form-control custom-input"
                                        value="Elena Ramos" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-grafito">Correo Electrónico</label>
                                    <input type="email" name="correo" class="form-control custom-input"
                                        value="elena.ramos@edufood.com" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-grafito">Nueva Contraseña</label>
                                    <input type="password" name="contrasena" class="form-control custom-input"
                                        placeholder="Nueva contraseña">
                                </div>

                                <button type="submit" class="submit-btn mt-3">Guardar Cambios</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Script de ejemplo para gestionar la búsqueda y la conexión con el backend PHP -->
    <script>
        document.getElementById('buscarUsuarioInput').addEventListener('input', function () {
            let query = this.value;
            if (query.length > 1) {
                // Aquí harás la llamada a tu archivo PHP (ej. buscar_usuario.php?q=...) usando fetch:
                /*
                fetch(`../php/buscar_usuario.php?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        // Renderizar resultados en sugerenciasUsuarios
                    });
                */
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>