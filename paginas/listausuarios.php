<?php
session_start();
require_once 'conexion.php';

// Verificar sesión activa y rol de administrador
$rolActual = $_SESSION['rol'] ?? '';
if (!isset($_SESSION['usuario_id']) || !in_array($rolActual, ['admin', 'Administrador'], true)) {
    header("Location: iniciodesesion.php");
    exit();
}

$mensaje = "";
$error = "";

// Procesar edición de usuario (solo administrador)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion']) && $_POST['accion'] === 'editar_usuario') {
    $id_usuario               = intval($_POST['id_usuario'] ?? 0);
    $nombre                   = trim($_POST['nombre'] ?? '');
    $apellido                 = trim($_POST['apellido'] ?? '');
    $tipo_documento           = trim($_POST['tipo_documento'] ?? '');
    $numero_documento         = trim($_POST['numero_documento'] ?? '');
    $grado                    = intval($_POST['grado'] ?? 0);
    $categoria                = trim($_POST['categoria'] ?? '');
    $estudiante_media_tecnica = isset($_POST['estudiante_media_tecnica']) ? intval($_POST['estudiante_media_tecnica']) : 0;
    $estado                   = isset($_POST['estado']) ? intval($_POST['estado']) : 1;

    if ($id_usuario <= 0 || $nombre === '' || $apellido === '' || $numero_documento === '') {
        $error = "Completa los campos obligatorios del usuario.";
    } else {
        try {
            $pdo->beginTransaction();

            $sqlUsuario = "UPDATE usuarios 
                           SET nombre = :nombre,
                               apellido = :apellido,
                               tipo_documento = :tipo_documento,
                               numero_documento = :numero_documento,
                               grado = :grado,
                               categoria = :categoria,
                               estudiante_media_tecnica = :estudiante_media_tecnica
                           WHERE id_usuario = :id_usuario";
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->execute([
                'nombre'                   => $nombre,
                'apellido'                 => $apellido,
                'tipo_documento'           => $tipo_documento,
                'numero_documento'         => $numero_documento,
                'grado'                    => $grado,
                'categoria'                => $categoria,
                'estudiante_media_tecnica' => $estudiante_media_tecnica,
                'id_usuario'               => $id_usuario
            ]);

            $sqlLogin = "UPDATE login SET estado = :estado WHERE id_usuario = :id_usuario";
            $stmtLogin = $pdo->prepare($sqlLogin);
            $stmtLogin->execute([
                'estado'     => $estado,
                'id_usuario' => $id_usuario
            ]);

            $pdo->commit();
            $mensaje = "Usuario #$id_usuario actualizado correctamente.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error al actualizar el usuario: " . $e->getMessage();
        }
    }
}

// Consultar usuarios junto con correo y estado desde login
$sqlLista = "SELECT u.id_usuario, u.nombre, u.apellido, u.tipo_documento, u.numero_documento,
                    u.grado, u.categoria, u.estudiante_media_tecnica, l.correo, l.rol, l.estado
             FROM usuarios u
             LEFT JOIN login l ON u.id_usuario = l.id_usuario
             ORDER BY u.id_usuario ASC";
$listaUsuarios = $pdo->query($sqlLista)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edu Food - Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="baddy">

    <!-- Navbar unificado de administrador -->
    <header>
        <nav class="navbar navbar-expand-lg w-100">
            <div class="container-fluid">
                <h1 class="text-white ms-3 mb-0 fs-3">Edu Food</h1>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="links_navbar ms-auto d-flex align-items-center gap-3">
                        <a class="nav-link" href="./inadministrador.html">Inicio</a>
                        <a class="nav-link text-white" href="./paginadeplatosadmin.php">Modificar Platos o Refrigerio</a>
                        <a class="nav-link" href="./acercadenosotrosadmin.html">Acerca de nosotros</a>
                        <a href="./configuracionadmin.php" class="profile-icon text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                                class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main class="container my-5 flex-grow-1">
        <div class="card shadow-lg p-4 border-0" style="border-radius: 1rem;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h2 class="fw-bold text-grafito fs-2 mb-0">
                    Lista de Estudiantes y Usuarios
                </h2>
            </div>

            <?php if ($mensaje !== ''): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="mb-4">
                <input type="text"
                    id="buscadorUsuarios"
                    class="form-control"
                    placeholder="Buscar por nombre, apellido, correo o documento...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Documento</th>
                            <th>Grado</th>
                            <th>Categoría</th>
                            <th>Media Técnica</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaUsuarios as $u): ?>
                            <?php $estadoActual = isset($u['estado']) ? (int)$u['estado'] : 1; ?>
                            <tr class="fila-usuario">
                                <form method="POST" action="listausuarios.php">
                                    <input type="hidden" name="accion" value="editar_usuario">
                                    <input type="hidden" name="id_usuario" value="<?= (int)$u['id_usuario'] ?>">
                                    <input type="hidden" name="tipo_documento" value="<?= htmlspecialchars($u['tipo_documento'] ?? 'Tarjeta de Identidad') ?>">

                                    <td><?= (int)$u['id_usuario'] ?></td>
                                    <td>
                                        <input type="text" name="nombre" class="form-control form-control-sm" value="<?= htmlspecialchars($u['nombre']) ?>" required>
                                    </td>
                                    <td>
                                        <input type="text" name="apellido" class="form-control form-control-sm" value="<?= htmlspecialchars($u['apellido']) ?>" required>
                                    </td>
                                    <td class="small text-muted">
                                        <?= htmlspecialchars($u['correo'] ?? 'Sin correo') ?>
                                    </td>
                                    <td style="min-width: 130px;">
                                        <input type="text" name="numero_documento" class="form-control form-control-sm" value="<?= htmlspecialchars($u['numero_documento']) ?>" required>
                                    </td>
                                    <td style="width: 85px;">
                                        <input type="number" name="grado" class="form-control form-control-sm" value="<?= (int)$u['grado'] ?>" required>
                                    </td>
                                    <td style="min-width: 125px;">
                                        <select name="categoria" class="form-select form-select-sm">
                                            <?php $cat = $u['categoria'] ?? ''; ?>
                                            <option value="Almuerzo" <?= $cat === 'Almuerzo' ? 'selected' : '' ?>>Almuerzo</option>
                                            <option value="Refrigerio" <?= $cat === 'Refrigerio' ? 'selected' : '' ?>>Refrigerio</option>
                                            <option value="Ambos" <?= $cat === 'Ambos' ? 'selected' : '' ?>>Ambos</option>
                                            <option value="Ninguna" <?= $cat === 'Ninguna' ? 'selected' : '' ?>>Ninguna</option>
                                        </select>
                                    </td>
                                    <td style="width: 95px;">
                                        <select name="estudiante_media_tecnica" class="form-select form-select-sm">
                                            <option value="1" <?= (int)$u['estudiante_media_tecnica'] === 1 ? 'selected' : '' ?>>Sí</option>
                                            <option value="0" <?= (int)$u['estudiante_media_tecnica'] === 0 ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </td>
                                    <td style="width: 105px;">
                                        <select name="estado" class="form-select form-select-sm">
                                            <option value="1" <?= $estadoActual === 1 ? 'selected' : '' ?>>Activo</option>
                                            <option value="0" <?= $estadoActual === 0 ? 'selected' : '' ?>>Inactivo</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer unificado -->
    <footer class="footer-user">
        <div>
            <p>&copy; Derechos reservados a los creadores de la página. <br>
                Más información en <a href="./acercadenosotrosadmin.html">Sobre nosotros</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('buscadorUsuarios').addEventListener('input', function () {
            const filtro = this.value.toLowerCase().trim();
            document.querySelectorAll('#tablaUsuarios tbody tr.fila-usuario').forEach(function (fila) {
                const textoFila = fila.innerText.toLowerCase();
                const inputs = Array.from(fila.querySelectorAll('input, select')).map(el => el.value.toLowerCase()).join(' ');
                fila.style.display = (textoFila.includes(filtro) || inputs.includes(filtro)) ? '' : 'none';
            });
        });
    </script>
</body>

</html>