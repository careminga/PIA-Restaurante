
<?php
session_start();
require_once 'conexion.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    $id_estudiante = $_SESSION['id_estudiante'];

    try{

        $sql = "UPDATE estudiantes SET nombre = :nombre, apellido = :apellido WHERE id_estudiante = :id_estudiante";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'id_estudiante' => $id_estudiante
        ]);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Perfil - Edu Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="dashboard-grid bg-light-purple">
    <aside class="sidebar text-white">
        <header class="sidebar-header">
            <span class="fs-4 fw-bold text-uppercase">Edu Food</span>
        </header>
        <nav class="sidebar-nav">
            <a href="inusuario.html" class="nav-link"><i class="bi bi-house-door"></i> Inicio</a>
            <a href="configuracionusuario.html" class="nav-link active"><i class="bi bi-person-gear"></i> Mi Perfil</a>
            <a href="../index.html" class="nav-link logout mt-auto"><i class="bi bi-box-arrow-left"></i> Salir</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="content-header bg-white px-4 py-3 shadow-sm">
            <h4 class="mb-0 fw-bold text-grafito">Configuración de Cuenta</h4>
        </header>
        <section class="container-fluid p-4">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="row g-4">
                        <aside class="col-12 col-lg-4">
                            <article class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                                <div class="position-relative d-inline-block mx-auto mb-3">
                                    <img src="usu.jpg" alt="Foto de perfil"
                                        class="rounded-circle img-thumbnail shadow-sm"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                    <label for="file-input" class="btn-edit-img shadow"><i
                                            class="bi bi-pencil-fill"></i></label>
                                    <input id="file-input" type="file" hidden />
                                </div>
                                <h5 class="fw-bold text-grafito mb-1">Juanito Felipe Pérez</h5>
                                <p class="text-muted small">ID: EST-2026-04</p>
                            </article>
                        </aside>
                        <article class="col-12 col-lg-8">
                            <section class="card border-0 shadow-sm rounded-4 p-4">
                                <h5 class="fw-bold text-grafito mb-4 border-bottom pb-2">Información Personal</h5>
                                <form action="configuracionusuario.php" method="POST">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nombre</label>
                                        <input type="text" name="nombre" class="form-control" value="Juanito">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Apellido</label>
                                        <input type="text" name="apellido" class="form-control" value="Pérez">
                                    </div>

                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-grafito px-4 py-2 fw-bold"
                                            style="background:#343a40; color:white;">
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </form>
                            </section>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>