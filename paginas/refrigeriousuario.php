<?php
session_start();
require_once 'conexion.php';

$stmt = $pdo->query("SELECT id_refrigerio, nombre_refrigerio, descripcion, calorias, proteina, grasas, imagen 
                      FROM registros_refrigerios 
                      ORDER BY id_refrigerio ASC");
$refrigerios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$imagenesRefrigerio = [
    "https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1200&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=1200&auto=format&fit=crop",
    "https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1200&auto=format&fit=crop"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edu Food - Refrigerios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <header class="navbar">
        <nav class="navbar navbar-expand-lg w-100">
            <div class="container-fluid">
                <h1 class="text-white ms-3 fs-3">Edu Food</h1>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="links_navbar ms-auto d-flex align-items-center gap-3">
                        <a class="nav-link text-white" href="./iniciousuario.php">Inicio</a>
                        <a class="nav-link text-white" href="./paginadeplatosusuario.php">Platos</a>
                        <a class="nav-link text-white" href="./acercadenosotrosusuario.html">Acerca de nosotros</a>
                        <a href="./configuracionusuario.php" class="profile-icon text-white">
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
        <div class="text-center mb-5">
            <h2 class="fw-bold text-grafito fs-1">Menú de Refrigerios</h2>
            <p class="text-muted">Conoce los refrigerios disponibles para el servicio escolar.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (count($refrigerios) > 0): ?>
                <?php foreach ($refrigerios as $index => $ref): ?>
                    <?php $imgSrc = !empty($ref['imagen']) ? $ref['imagen'] : $imagenesRefrigerio[$index % count($imagenesRefrigerio)]; ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="food-card shadow-sm h-100 p-4">
                            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($ref['nombre_refrigerio']) ?>" class="mb-3 rounded w-100" style="height: 200px; object-fit: cover;">
                            <h3 class="fw-bold text-grafito fs-4"><?= htmlspecialchars($ref['nombre_refrigerio']) ?></h3>
                            <p class="text-muted small mt-2"><?= htmlspecialchars($ref['descripcion']) ?></p>
                            <div class="mt-3 pt-2 border-top small text-grafito">
                                <strong>Calorías:</strong> <?= htmlspecialchars($ref['calorias']) ?> kcal ·
                                <strong>Proteínas:</strong> <?= htmlspecialchars($ref['proteina']) ?>g ·
                                <strong>Grasas:</strong> <?= htmlspecialchars($ref['grasas']) ?>g
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No hay refrigerios registrados en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer-user">
        <div>
            <p>&copy; Derechos reservados a los creadores de la página. <br>
                Más información en <a href="./acercadenosotrosusuario.html">Sobre nosotros</a></p>
        </div>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>