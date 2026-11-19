<?php
session_start();
require_once 'conexion.php';

$mensaje = "";
$error = "";
$pestanaActiva = "platos";

// 1. AGREGAR NUEVO PLATO O REFRIGERIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre      = trim($_POST['nombre_plato'] ?? '');
    $categoria   = $_POST['categoria'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $calorias    = floatval($_POST['calorias'] ?? 0);
    $proteina    = floatval($_POST['proteina'] ?? 0);
    $grasas      = intval($_POST['grasas'] ?? 0);

    if ($nombre === '' || $descripcion === '') {
        $error = "Completa el nombre y la descripción.";
    } elseif ($categoria === '2') {
        $stmt = $pdo->prepare("INSERT INTO registros_refrigerios (nombre_refrigerio, descripcion, calorias, proteina, grasas) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $calorias, $proteina, $grasas]);
        $mensaje = "Refrigerio agregado correctamente a la base de datos.";
        $pestanaActiva = "refrigerios";
    } else {
        $stmt = $pdo->prepare("INSERT INTO platos (nombre_plato, descripcion, estado, calorias, proteina, grasas) VALUES (?, ?, 1, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $calorias, $proteina, $grasas]);
        $mensaje = "Plato principal agregado correctamente a la base de datos.";
        $pestanaActiva = "platos";
    }
}

// 2. MODIFICAR PLATO EXISTENTE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion']) && $_POST['accion'] === 'editar_plato') {
    $id_plato    = intval($_POST['id_plato'] ?? 0);
    $nombre      = trim($_POST['nombre_plato'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $calorias    = floatval($_POST['calorias'] ?? 0);
    $proteina    = floatval($_POST['proteina'] ?? 0);
    $grasas      = intval($_POST['grasas'] ?? 0);
    $estado      = isset($_POST['estado']) ? intval($_POST['estado']) : 1;
    $pestanaActiva = "platos";

    if ($id_plato > 0 && $nombre !== '' && $descripcion !== '') {
        $stmt = $pdo->prepare("UPDATE platos 
                               SET nombre_plato = ?, descripcion = ?, calorias = ?, proteina = ?, grasas = ?, estado = ? 
                               WHERE id_plato = ?");
        $stmt->execute([$nombre, $descripcion, $calorias, $proteina, $grasas, $estado, $id_plato]);
        $mensaje = "Plato #$id_plato actualizado en la base de datos.";
    } else {
        $error = "Datos inválidos al intentar modificar el plato.";
    }
}

// 3. MODIFICAR REFRIGERIO EXISTENTE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accion']) && $_POST['accion'] === 'editar_refrigerio') {
    $id_refrigerio     = intval($_POST['id_refrigerio'] ?? 0);
    $nombre_refrigerio = trim($_POST['nombre_refrigerio'] ?? '');
    $descripcion       = trim($_POST['descripcion'] ?? '');
    $calorias          = floatval($_POST['calorias'] ?? 0);
    $proteina          = floatval($_POST['proteina'] ?? 0);
    $grasas            = intval($_POST['grasas'] ?? 0);
    $pestanaActiva     = "refrigerios";

    if ($id_refrigerio > 0 && $nombre_refrigerio !== '' && $descripcion !== '') {
        $stmt = $pdo->prepare("UPDATE registros_refrigerios 
                               SET nombre_refrigerio = ?, descripcion = ?, calorias = ?, proteina = ?, grasas = ? 
                               WHERE id_refrigerio = ?");
        $stmt->execute([$nombre_refrigerio, $descripcion, $calorias, $proteina, $grasas, $id_refrigerio]);
        $mensaje = "Refrigerio #$id_refrigerio actualizado en la base de datos.";
    } else {
        $error = "Datos inválidos al intentar modificar el refrigerio.";
    }
}

// Consultar platos y refrigerios actuales
$listaPlatos = $pdo->query("SELECT * FROM platos ORDER BY id_plato DESC")->fetchAll(PDO::FETCH_ASSOC);
$listaRefrigerios = $pdo->query("SELECT * FROM registros_refrigerios ORDER BY id_refrigerio DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edu Food - Administrar Platos y Refrigerios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .plate-container {
            max-width: 1100px;
            margin: 0 auto;
        }
        .preview-img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-light-purple d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <h1 class="text-white ms-3 mb-0 fs-3">Edu Food</h1>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav ms-auto links-navinad align-items-center gap-3">
                        <a class="nav-link text-white" href="./inicioadmin.php">Inicio</a>
                        <a class="nav-link text-white active" href="./paginadeplatosadmin.php">Platos</a>
                        <a class="nav-link text-white" href="./listausuarios.html">lista de usuarios</a>
                        <a class="nav-link text-white" href="./acercadenosotrosadmin.html">Acerca de nosotros</a>
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

    <main class="container my-5 flex-grow-1">
        <div class="main-card p-4 p-md-5 plate-container mb-5">

            <?php if ($mensaje): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="mb-4">
                <h2 class="fw-bold text-grafito m-0">Añadir Nuevo Plato o Refrigerio</h2>
                <p class="text-muted small mt-1">Registra un nuevo plato o refrigerio saludable para el menú escolar.</p>
            </div>

            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <form method="POST" action="paginadeplatosadmin.php">
                        <input type="hidden" name="accion" value="agregar">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label text-grafito fw-bold small">Nombre del plato o refrigerio</label>
                                <input type="text" name="nombre_plato" class="form-control custom-input" placeholder="Ej: Hamburguesa saludable" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-grafito fw-bold small">Categoría</label>
                                <select name="categoria" class="form-select custom-input">
                                    <option value="1" selected>Plato Principal</option>
                                    <option value="2">Refrigerio</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-grafito fw-bold small">Descripción</label>
                            <textarea name="descripcion" class="form-control custom-input" rows="3" placeholder="Describe el plato o refrigerio..." required></textarea>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <label class="form-label text-grafito fw-bold small">Calorías</label>
                                <input type="number" step="0.1" name="calorias" class="form-control custom-input" placeholder="320" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label text-grafito fw-bold small">Proteínas (g)</label>
                                <input type="number" step="0.1" name="proteina" class="form-control custom-input" placeholder="25" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label text-grafito fw-bold small">Grasas (g)</label>
                                <input type="number" name="grasas" class="form-control custom-input" placeholder="10" required>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn">Guardar Registro</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="food-card shadow-sm h-100 p-2">
                                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=400&q=80" alt="Plato 1" class="preview-img">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="food-card shadow-sm h-100 p-2">
                                <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=400&q=80" alt="Plato 2" class="preview-img">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="food-card shadow-sm h-100 p-2">
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80" alt="Plato 3" class="preview-img">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="food-card shadow-sm h-100 p-2">
                                <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=400&q=80" alt="Plato 4" class="preview-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESTAÑAS TIPO NAVEGADOR PARA MODIFICAR PLATOS Y REFRIGERIOS -->
        <div class="main-card p-4 p-md-5 plate-container">
            <h3 class="fw-bold text-grafito mb-3">Modificar Registros</h3>
            <p class="text-muted small mb-4">Cambia de pestaña para editar <strong>Platos</strong> o <strong>Refrigerios</strong> y pulsa <strong>Guardar cambios</strong>.</p>

            <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $pestanaActiva === 'platos' ? 'active fw-bold' : '' ?>"
                            id="tab-platos"
                            data-bs-toggle="tab"
                            data-bs-target="#panel-platos"
                            type="button"
                            role="tab"
                            aria-controls="panel-platos"
                            aria-selected="<?= $pestanaActiva === 'platos' ? 'true' : 'false' ?>">
                        Platos (<?= count($listaPlatos) ?>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $pestanaActiva === 'refrigerios' ? 'active fw-bold' : '' ?>"
                            id="tab-refrigerios"
                            data-bs-toggle="tab"
                            data-bs-target="#panel-refrigerios"
                            type="button"
                            role="tab"
                            aria-controls="panel-refrigerios"
                            aria-selected="<?= $pestanaActiva === 'refrigerios' ? 'true' : 'false' ?>">
                        Refrigerios (<?= count($listaRefrigerios) ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="adminTabsContent">
                <!-- PESTAÑA 1: PLATOS -->
                <div class="tab-pane fade <?= $pestanaActiva === 'platos' ? 'show active' : '' ?>"
                     id="panel-platos"
                     role="tabpanel"
                     aria-labelledby="tab-platos">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del plato</th>
                                    <th>Descripción</th>
                                    <th>Kcal</th>
                                    <th>Prot (g)</th>
                                    <th>Grasas (g)</th>
                                    <th>Visible</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaPlatos as $plato): ?>
                                    <tr>
                                        <form method="POST" action="paginadeplatosadmin.php">
                                            <input type="hidden" name="accion" value="editar_plato">
                                            <input type="hidden" name="id_plato" value="<?= (int)$plato['id_plato'] ?>">
                                            <td><?= (int)$plato['id_plato'] ?></td>
                                            <td>
                                                <input type="text" name="nombre_plato" class="form-control form-control-sm" value="<?= htmlspecialchars($plato['nombre_plato']) ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" name="descripcion" class="form-control form-control-sm" value="<?= htmlspecialchars($plato['descripcion']) ?>" required>
                                            </td>
                                            <td style="width: 90px;">
                                                <input type="number" step="0.1" name="calorias" class="form-control form-control-sm" value="<?= htmlspecialchars($plato['calorias']) ?>" required>
                                            </td>
                                            <td style="width: 85px;">
                                                <input type="number" step="0.1" name="proteina" class="form-control form-control-sm" value="<?= htmlspecialchars($plato['proteina']) ?>" required>
                                            </td>
                                            <td style="width: 85px;">
                                                <input type="number" name="grasas" class="form-control form-control-sm" value="<?= htmlspecialchars($plato['grasas']) ?>" required>
                                            </td>
                                            <td style="width: 95px;">
                                                <select name="estado" class="form-select form-select-sm">
                                                    <option value="1" <?= (int)$plato['estado'] === 1 ? 'selected' : '' ?>>Sí</option>
                                                    <option value="0" <?= (int)$plato['estado'] === 0 ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">Guardar cambios</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PESTAÑA 2: REFRIGERIOS -->
                <div class="tab-pane fade <?= $pestanaActiva === 'refrigerios' ? 'show active' : '' ?>"
                     id="panel-refrigerios"
                     role="tabpanel"
                     aria-labelledby="tab-refrigerios">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del refrigerio</th>
                                    <th>Descripción</th>
                                    <th>Kcal</th>
                                    <th>Prot (g)</th>
                                    <th>Grasas (g)</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($listaRefrigerios as $ref): ?>
                                    <tr>
                                        <form method="POST" action="paginadeplatosadmin.php">
                                            <input type="hidden" name="accion" value="editar_refrigerio">
                                            <input type="hidden" name="id_refrigerio" value="<?= (int)$ref['id_refrigerio'] ?>">
                                            <td><?= (int)$ref['id_refrigerio'] ?></td>
                                            <td>
                                                <input type="text" name="nombre_refrigerio" class="form-control form-control-sm" value="<?= htmlspecialchars($ref['nombre_refrigerio']) ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" name="descripcion" class="form-control form-control-sm" value="<?= htmlspecialchars($ref['descripcion']) ?>" required>
                                            </td>
                                            <td style="width: 90px;">
                                                <input type="number" step="0.1" name="calorias" class="form-control form-control-sm" value="<?= htmlspecialchars($ref['calorias']) ?>" required>
                                            </td>
                                            <td style="width: 85px;">
                                                <input type="number" step="0.1" name="proteina" class="form-control form-control-sm" value="<?= htmlspecialchars($ref['proteina']) ?>" required>
                                            </td>
                                            <td style="width: 85px;">
                                                <input type="number" name="grasas" class="form-control form-control-sm" value="<?= htmlspecialchars($ref['grasas']) ?>" required>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">Guardar cambios</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="custom-footer">
        <p class="m-0">© 2026 Restaurante Escolar Edu Food. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>