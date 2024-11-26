<?php
// Incluir archivo de conexión a la base de datos
require_once 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

// Variables para almacenar los datos
$nombreRemitente = $cedulaRemitente = $correoRemitente = $telefonoRemitente = "";
$nombreDestinatario = $cedulaDestinatario = $correoDestinatario = $telefonoDestinatario = $horaRetiro = "";
$descripcionPaquete = $especificacionPaquete = $direccionRetiro = $direccionEntrega = "";

if ($id) {
    try {
        // Consultar datos del remitente
        $stmt = $pdo->prepare("SELECT NOMBRE, CEDULA, CORREO_ELECTRONICO, TELEFONO FROM REMITENTE WHERE ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombreRemitente = $row['NOMBRE'];
            $cedulaRemitente = $row['CEDULA'];
            $correoRemitente = $row['CORREO_ELECTRONICO'];
            $telefonoRemitente = $row['TELEFONO'];
        }

        // Consultar datos del destinatario
        $stmt = $pdo->prepare("SELECT NOMBRE, CEDULA, CORREO_ELECTRONICO, TELEFONO, HORA_RETIRO FROM DESTINATARIO WHERE ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombreDestinatario = $row['NOMBRE'];
            $cedulaDestinatario = $row['CEDULA'];
            $correoDestinatario = $row['CORREO_ELECTRONICO'];
            $telefonoDestinatario = $row['TELEFONO'];
            $horaRetiro = $row['HORA_RETIRO'];
        }

        // Consultar datos del paquete
        $stmt = $pdo->prepare("SELECT DESCRIPCION, ESPECIFICACIONES, DIRECCION_RETIRO, DIRECCION_ENTREGA FROM PAQUETE WHERE ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $descripcionPaquete = $row['DESCRIPCION'];
            $especificacionPaquete = $row['ESPECIFICACIONES'];
            $direccionRetiro = $row['DIRECCION_RETIRO'];
            $direccionEntrega = $row['DIRECCION_ENTREGA'];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="detalles.css">
    <title>Detalles del Paquete</title>
    <style>
        /* Aquí puedes agregar el estilo CSS */
    </style>
</head>
<body>
<header id="encabezado">
    <div id="logo">
        <a href="pantallaAdministrador.html">
            <img src="img/Logo.png" alt="Logo">
        </a>
    </div>
    <div class="iconos">
        <button class="volver-boton" onclick="window.location.href='pantallaAdministrador.html'">Volver</button>
    </div>
</header>
</section>

<div class="content">
    <!-- Información del remitente -->
    <div class="table-container">
        <h2>Información del Remitente</h2>
        <table>
            <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Teléfono</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= htmlspecialchars($nombreRemitente) ?></td>
                <td><?= htmlspecialchars($cedulaRemitente) ?></td>
                <td><?= htmlspecialchars($correoRemitente) ?></td>
                <td><?= htmlspecialchars($telefonoRemitente) ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Información del destinatario -->
    <div class="table-container">
        <h2>Información del Destinatario</h2>
        <table>
            <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Cédula</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Hora Retiro</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= htmlspecialchars($nombreDestinatario) ?></td>
                <td><?= htmlspecialchars($cedulaDestinatario) ?></td>
                <td><?= htmlspecialchars($correoDestinatario) ?></td>
                <td><?= htmlspecialchars($telefonoDestinatario) ?></td>
                <td><?= htmlspecialchars($horaRetiro) ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Información del paquete -->
    <div class="table-container">
        <h2>Información del Paquete</h2>
        <table>
            <thead>
            <tr>
                <th>Descripción</th>
                <th>Especificaciones</th>
                <th>Dirección Retiro</th>
                <th>Dirección Entrega</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= htmlspecialchars($descripcionPaquete) ?></td>
                <td><?= htmlspecialchars($especificacionPaquete) ?></td>
                <td><?= htmlspecialchars($direccionRetiro) ?></td>
                <td><?= htmlspecialchars($direccionEntrega) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<section id="botones-container">
    <button class="boton boton-rechazar">Rechazar</button>
    <button class="boton boton-aceptar">Aceptar</button>
</section>
</body>
<footer>
    <!-- Footer -->
</footer>
</html>
