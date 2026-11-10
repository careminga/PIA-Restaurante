
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <link rel="stylesheet" href="../css/recuperarcontra.css">
</head>

<body>

    <header class="custom-navbar">

        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container">

                <a class="navbar-brand titulo" href="#">
                    Edu Food
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="menu">

                    <div class="navbar-nav">
                        <a class="nav-link" href="../index.html">Inicio</a>
                        <a class="nav-link" href="./iniciodesesión.html">
                            Iniciar sesión
                        </a>
                    </div>

                </div>
            </div>
        </nav>

    </header>

    <main class="contenedor-recuperar">

        <div class="card-recuperar">

            <h2 class="titulo-recuperar">
                Recuperar contraseña
            </h2>

            <p class="texto-recuperar">
                Ingresa tu correo electrónico para enviarte un enlace de recuperación.
            </p>

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
        </div>

    </main>

    <footer class="custom-footer py-4">

        <div class="container text-center">

            <p class="mb-0 small opacity-75">

                &copy; 2026 Restaurante Escolar Edu Food.
                Todos los derechos reservados.

            </p>

        </div>

    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

</body>

</html>