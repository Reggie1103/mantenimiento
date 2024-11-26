<?php
// Conexión a la base de datos usando PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_eSupply", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Inicializar variables
$correo = $_POST['email'] ?? '';
$message = '';
$userExists = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($correo)) {
        try {
            // Verificar si el correo existe en la tabla 'usuarios'
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $userExists = true;
            } else {
                // Si no está en 'usuarios', verificar en la tabla 'colaboradores'
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM colaboradores WHERE correo = :correo");
                $stmt->bindParam(':correo', $correo);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    $userExists = true;
                }
            }

            if (!$userExists) {
                $message = "El correo electrónico no está registrado.";
            }
        } catch (PDOException $e) {
            $message = "Error al conectar con la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi Contraseña</title>
    <link rel="stylesheet" href="forgotPassword.css">
</head>
<body>
<div class="container">
    <div class="logo-container">
        <a href="Index.html">
            <img src="img/Logo2.png" alt="Supply Solutions">
        </a>
    </div>
    <div class="login-box">
        <h1>Olvidé mi Contraseña</h1>

        <?php if (empty($correo)) { ?>
        <form action="forgotPassword.php" method="post">
            <input type="email" name="email" placeholder="Ingrese su correo electrónico" class="input-field" required>
            <input type="submit" value="Verificar Correo" class="sign-in-btn">
        </form>
        <p class="register-link">¿Recordaste tu contraseña? <a href="Login.php">Iniciar Sesión</a></p>
        <?php } elseif ($userExists) { ?>
        <form action="resetPassword.php" method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($correo); ?>">
            <input type="password" name="new_password" placeholder="Ingrese la nueva contraseña" class="input-field" required>
            <input type="password" name="confirm_password" placeholder="Confirme la nueva contraseña" class="input-field" required>
            <input type="submit" value="Restablecer Contraseña" class="sign-in-btn">
        </form>
        <?php } else { ?>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>

        <p class="<?php echo $userExists ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
    </div>
</div>
</body>
</html>
