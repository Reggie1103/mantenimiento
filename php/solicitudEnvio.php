<?php
require_once 'db.php'; // Archivo para la conexión a la base de datos

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener parámetros del formulario
    $remitenteNombre = $_POST['remitente-nombre'] ?? '';
    $remitenteCedula = $_POST['remitente-cedula'] ?? '';
    $remitenteEmail = $_POST['remitente-email'] ?? '';
    $remitenteTelefono = $_POST['remitente-telefono'] ?? '';

    $destinatarioNombre = $_POST['destinatario-nombre'] ?? '';
    $destinatarioCedula = $_POST['destinatario-cedula'] ?? '';
    $destinatarioEmail = $_POST['destinatario-email'] ?? '';
    $destinatarioTelefono = $_POST['destinatario-telefono'] ?? '';
    $destinatarioHoraRetiro = $_POST['destinatario-hora-retiro'] ?? '';

    $paqueteDescripcion = $_POST['paquete-descripcion'] ?? '';
    $paqueteEspecificaciones = $_POST['paquete-especificaciones'] ?? '';
    $paqueteDireccionRetiro = $_POST['paquete-direccion-retiro'] ?? '';
    $paqueteDireccionEntrega = $_POST['paquete-direccion-entrega'] ?? '';

    try {
        // Verificar conexión a la base de datos
        if (!$pdo) {
            throw new Exception("Conexión a la base de datos fallida.");
        }

        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar remitente
        $sqlRemitente = "CALL insertar_remitente(:nombre, :cedula, :email, :telefono, @remitenteId)";
        $stmtRemitente = $pdo->prepare($sqlRemitente);
        $stmtRemitente->bindParam(':nombre', $remitenteNombre, PDO::PARAM_STR);
        $stmtRemitente->bindParam(':cedula', $remitenteCedula, PDO::PARAM_STR);
        $stmtRemitente->bindParam(':email', $remitenteEmail, PDO::PARAM_STR);
        $stmtRemitente->bindParam(':telefono', $remitenteTelefono, PDO::PARAM_STR);
        $stmtRemitente->execute();

        // Obtener el ID del remitente
        $stmtRemitenteId = $pdo->query("SELECT @remitenteId AS remitenteId");
        $remitenteId = $stmtRemitenteId->fetch(PDO::FETCH_ASSOC)['remitenteId'];

        // Insertar destinatario
        $sqlDestinatario = "CALL insertar_destinatario(:nombre, :cedula, :email, :telefono, :hora_retiro, @destinatarioId)";
        $stmtDestinatario = $pdo->prepare($sqlDestinatario);
        $stmtDestinatario->bindParam(':nombre', $destinatarioNombre, PDO::PARAM_STR);
        $stmtDestinatario->bindParam(':cedula', $destinatarioCedula, PDO::PARAM_STR);
        $stmtDestinatario->bindParam(':email', $destinatarioEmail, PDO::PARAM_STR);
        $stmtDestinatario->bindParam(':telefono', $destinatarioTelefono, PDO::PARAM_STR);
        $stmtDestinatario->bindParam(':hora_retiro', $destinatarioHoraRetiro, PDO::PARAM_STR);
        $stmtDestinatario->execute();

        // Obtener el ID del destinatario
        $stmtDestinatarioId = $pdo->query("SELECT @destinatarioId AS destinatarioId");
        $destinatarioId = $stmtDestinatarioId->fetch(PDO::FETCH_ASSOC)['destinatarioId'];

        // Insertar paquete
        $sqlPaquete = "CALL insertar_paquete(:descripcion, :especificaciones, :direccion_retiro, :direccion_entrega, :remitente_id, :destinatario_id, @paqueteId)";
        $stmtPaquete = $pdo->prepare($sqlPaquete);
        $stmtPaquete->bindParam(':descripcion', $paqueteDescripcion, PDO::PARAM_STR);
        $stmtPaquete->bindParam(':especificaciones', $paqueteEspecificaciones, PDO::PARAM_STR);
        $stmtPaquete->bindParam(':direccion_retiro', $paqueteDireccionRetiro, PDO::PARAM_STR);
        $stmtPaquete->bindParam(':direccion_entrega', $paqueteDireccionEntrega, PDO::PARAM_STR);
        $stmtPaquete->bindParam(':remitente_id', $remitenteId, PDO::PARAM_INT);
        $stmtPaquete->bindParam(':destinatario_id', $destinatarioId, PDO::PARAM_INT);
        $stmtPaquete->execute();

        // Confirmar transacción
        $pdo->commit();
        $message = "¡Información guardada exitosamente!";
        $success = true;
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        $message = "Error al guardar la información: " . $e->getMessage();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guardar Información</title>
    <link rel="stylesheet" href="shared_styles.css">
</head>
<body>
<div class="container">
    <div class="logo-container">
        <img src="img/Logo2.png" alt="eSupply Solutions">
    </div>
    <h1>Resultado del Registro</h1>
    <p class="<?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>

    <script>
        // Redirige después de 2 segundos
        setTimeout(function () {
            window.location.href = 'pantallaCliente.html';
        }, 2000);
    </script>
</div>
</body>
</html>
