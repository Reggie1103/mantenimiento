<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capturar los datos enviados desde el formulario
    $nombre = $_POST["nombre"] ?? '';
    $apellido = $_POST["apellido"] ?? '';
    $correo = $_POST["correo"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';
    $confirmarContrasena = $_POST["confirmarContrasena"] ?? '';

    if ($contrasena !== $confirmarContrasena) {
        header("Location: registerFallido.html");
        exit;
    } else {
        try {
            // Verificar si el correo ya está registrado
            $query = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                header("Location: registerFallido.html");
                exit;
            } else {
                // Llamar al procedimiento almacenado para registrar al usuario
                $query = "CALL registrar_usuario(:nombre, :apellido, :correo, :contrasena)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt->bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt->execute();

                header("Location: registerExitoso.html");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            header("Location: registerFallido.html");
            exit;
        }
    }
}
?>
