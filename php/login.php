<?php
include 'db.php'; 

$userType = $_POST['user-type'] ?? '';
$correo = $_POST['email'] ?? '';
$contrasena = $_POST['password'] ?? '';
$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($correo) || empty($contrasena)) {
        $message = "Por favor, complete todos los campos.";
    } else {
        try {
            $tableName = $userType === "cliente" ? "usuarios" : "colaboradores";

            $stmt = $pdo->prepare("SELECT contrasena FROM $tableName WHERE correo = :correo");
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $dbContrasena = $row['contrasena'];

                if ($dbContrasena === $contrasena) {
                    $message = "Inicio de sesión exitoso!";
                    $success = true;

                    // Redirigir según el tipo de usuario
                    if ($userType === "cliente") {
                        header("Location: pantallaCliente.html");
                        exit;
                    } else {
                        header("Location: pantallaAdministrador.html");
                        exit;
                    }
                } else {
                    $message = "Contraseña incorrecta.";
                }
            } else {
                $message = "Correo no registrado.";
            }

            $stmt->closeCursor(); 
        } catch (PDOException $e) {
            $message = "Error al conectar con la base de datos: " . $e->getMessage();
        }
    }

    // Si el inicio de sesión falla, redirigir con el mensaje de error
    if (!$success) {
        header("Location: inicioSesionFallido.html?message=" . urlencode($message));
        exit;
    }
}
?>