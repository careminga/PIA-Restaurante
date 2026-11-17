
<?php
session_start();
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo   = trim($_POST['correo'] ?? '');
    $password = trim($_POST['contrasena'] ?? '');

    try {
        $stmt = $pdo->prepare("SELECT l.id_usuario, u.nombre, l.rol, l.contrasena, l.estado 
                               FROM login l 
                               JOIN usuarios u ON l.id_usuario = u.id_usuario 
                               WHERE l.correo = :correo");
        $stmt->execute(['correo' => $correo]);
        $user = $stmt->fetch();

        if ($user && $user['estado'] == 1 && password_verify($password, $user['contrasena'])) {  

            session_regenerate_id(true);    

            $_SESSION['usuario_id'] = $user['id_usuario'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            switch ($_SESSION['rol']) {
                case 'admin':
                case 'Administrador':
                    header("Location: inadministrador.html");
                    break;
                case 'Estudiante':
                case 'cliente':
                    header("Location: inusuario.html");
                    break;
                default:
                    header("Location: ../index.html");
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "Correo o contraseña incorrectos.";
            header("Location: iniciodesesion.html");
            exit();
        }
    } catch (PDOException $e) {
        die("Error en la autenticación: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edu Food - Iniciar Sesión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body class="bg-light-purple d-flex flex-column min-vh-100">

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand titulo d-flex align-items-center gap-2" href="../index.html">
                <img src="../imagenes/plato-removebg-preview.png" alt="Logo Edu Food" style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;">
                Edu Food
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="menu">
                <div class="navbar-nav">
                    <a class="nav-link" href="../index.html">Inicio</a>
                    <a class="nav-link" href="./acercadenosotros.html">Acerca de nosotros</a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Contenido Principal / Formulario de Inicio de Sesión -->
    <main class="container my-5 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5">
                <div class="main-card shadow-lg p-4">
                    <h2 class="text-center fw-bold mb-4 text-grafito">INICIO DE SESIÓN</h2>
                    
                    <form id="loginForm" method="POST" action="iniciodesesion.php">
                        <div class="mb-3">
                            <label for="correo" class="form-label text-grafito fw-bold">Correo</label>
                            <input type="email" id="correo" class="form-control custom-input" placeholder="correo@ejemplo.com" required name="correo">
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label text-grafito fw-bold">Contraseña</label>
                            <input type="password" id="contrasena" class="form-control custom-input" placeholder="••••••••" required name="contrasena">
                        </div>

                        <div class="mb-3">
                            <label for="tipoIngresante" class="form-label text-grafito fw-bold">Tipo de ingresante</label>
                            <select id="tipoIngresante" name="rol" class="form-select custom-input">
                                <option value="estudiante">Estudiante</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>

                        <button type="submit" class="submit-btn mt-3 w-100">Enviar</button>
                        
                        <div class="text-center mt-3">
                            <a href="./Recuperarcontra.php" class="text-decoration-none small">Recuperar contraseña</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="custom-footer mt-auto py-3 text-center">
        <p class="m-0 small">© 2026 Restaurante Escolar Edu Food. Todos los derechos reservados. <br>
        Cualquier información sobre los creadores la podrás encontrar en <a href="./acercadenosotros.html" class="text-decoration-underline">Sobre nosotros</a>.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>