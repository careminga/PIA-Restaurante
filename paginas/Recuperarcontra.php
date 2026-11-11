
<?php
require_once 'conexion.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $correo = $_POST['correo'];

    try{

        $sql = "SELECT correo FROM estudiantes WHERE correo = :correo
                UNION
                SELECT correo FROM administradores WHERE correo = :correo";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'correo' => $correo
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario){

            $mensaje = "Si el correo está registrado, se enviarán las instrucciones para recuperar la contraseña.";

        }else{

            $mensaje = "Si el correo está registrado, se enviarán las instrucciones para recuperar la contraseña.";

        }

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
    <title>Edu Food - Recuperación de Contraseña</title>
    
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

    <!-- Contenido Principal / Formulario -->
    <main class="container my-5 flex-grow-1">
        <h1 class="text-center fw-bold text-grafito mb-4">Recuperación de Contraseña</h1>

        <form action="recuperacion.php" method="POST">

                <p class="text-muted text-center mb-3">
                    Ingresa tu correo electrónico registrado y te enviaremos las instrucciones para restablecer tu
                    contraseña.
                </p>

                <label for="correoRecuperar">Correo Electrónico</label>

                <input type="email" id="correoRecuperar" name="correo" class="custom-input"
                    placeholder="correo@ejemplo.com" required>

                <button type="submit" class="submit-btn mt-4">
                    Enviar Instrucciones
                </button>

                <div class="text-center mt-3">
                    <a href="./iniciodesesión.html" class="text-decoration-none small text-grafito fw-bold">
                        Volver al Inicio de Sesión
                    </a>
                </div>

            </form>
    </main>

    <!-- Footer -->
    <footer class="custom-footer mt-auto">
        <p class="m-0">© 2026 Restaurante Escolar Edu Food. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


