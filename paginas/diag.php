<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

$mensaje = "";

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passwordIngresada = $_POST['password'] ?? '';

    if (!empty($passwordIngresada)) {
        try {
            // Consultar el hash del usuario con ID 1 de forma segura
            $stmt = $pdo->prepare("SELECT contrasena FROM login WHERE id_usuario = 1");
            $stmt->execute();
            $usuario = $stmt->fetch();

            if ($usuario) {
                $hashGuardado = $usuario['contrasena'];

                //password_verify compara el texto plano con el hash guardado
                if (password_verify($passwordIngresada, $hashGuardado)) {
                    $mensaje = "<p style='color: green; font-weight: bold;'>¡Éxito! La contraseña coincide perfectamente con el hash.</p>";
                } else {
                    $mensaje = "<p style='color: red; font-weight: bold;'>Error: La contraseña NO coincide con el hash almacenado.</p>";
                }
            } else {
                $mensaje = "<p style='color: orange;'>No se encontró ningún usuario con el ID 1 en la base de datos.</p>";
            }
        } catch (PDOException $e) {
            $mensaje = "<p style='color: red;'>Error en la base de datos: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensaje = "<p style='color: orange;'>Por favor, escribe una contraseña.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Contraseña - ID 1</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); max-width: 400px; }
        input[type="password"] { padding: 8px; width: 90%; margin-bottom: 10px; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="card">
    <h2>Validador de Contraseña (ID 1)</h2>
    <form method="POST" action="">
        <label for="password">Ingresa tu contraseña:</label><br>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">Validar</button>
    </form>

    <?php echo $mensaje; ?>
</div>

</body>
</html>