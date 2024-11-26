<?php
// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_eSupply", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Variables de entrada
$correo = isset($_POST['email']) ? $_POST['email'] : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$message = '';
$success = false;

// Verificar si las contraseñas coinciden
if ($newPassword !== $confirmPassword) {
    $message = "Las contraseñas no coinciden.";
} else {
    // Si las contraseñas coinciden, actualizar en la base de datos
    try {
        // Actualizar la contraseña del usuario
        $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = :newPassword WHERE correo = :correo");
        $stmt->execute(['newPassword' => $newPassword, 'correo' => $correo]);

        if ($stmt->rowCount() == 0) {
            // Si no se actualizó, intentar en la tabla colaboradores
            $stmt = $pdo->prepare("UPDATE colaboradores SET contrasena = :newPassword WHERE correo = :correo");
            $stmt->execute(['newPassword' => $newPassword, 'correo' => $correo]);
        }

        if ($stmt->rowCount() > 0) {
            $message = "Contraseña restablecida exitosamente.";
            $success = true;
        } else {
            $message = "Error al restablecer la contraseña.";
        }

    } catch (PDOException $e) {
        $message = "Error al conectar con la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <link rel="stylesheet" href="forgotPassword.css">
</head>
<body>
<div class="container">
    <div class="logo-container">
        <a href="index.html">
            <img src="img/Logo2.png" alt="Supply Solutions">
        </a>
    </div>
    <div class="login-box">
        <h1>Restablecimiento de Contraseña</h1>
        <p class="<?php echo ($success) ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
        <script>
            // Redirigir después de 2 segundos si la contraseña fue restablecida exitosamente
            <?php if ($success): ?>
                setTimeout(function () {
                    window.location.href = 'Login.html';
                }, 2000);
            <?php endif; ?>
        </script>
    </div>
</div>
</body>
</html>
