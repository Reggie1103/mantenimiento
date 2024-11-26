<?php
require_once 'db.php'; // Conexión a la base de datos

// Consulta para obtener las solicitudes
try {
    $query = "SELECT ID, DATE_FORMAT(fecha, '%Y-%m-%d') AS fecha, DIRECCION_ENTREGA FROM PAQUETE";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="verSolicitudes.css">
    <title>Ver Solicitudes - Administrador</title>
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

<main>
    <section>
        <div id="solicitud_envio">
            <h2>Solicitudes de envío</h2>
        </div>

        <div id="solicitud_envio" class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Número de solicitud</th>
                    <th>Fecha de envío</th>
                    <th>Destino</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($solicitudes)): ?>
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <tr>
                            <td><?= htmlspecialchars($solicitud['ID']) ?></td>
                            <td><?= htmlspecialchars($solicitud['fecha']) ?></td>
                            <td><?= htmlspecialchars($solicitud['DIRECCION_ENTREGA']) ?></td>
                            <td><a href="detalles.php?id=<?= htmlspecialchars($solicitud['ID']) ?>">Ver detalles</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay solicitudes registradas.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="footer-container">
    <div class="footer-section">
        <h2 class="footer-heading">SUCURSALES</h2>
        <ul class="branch-schedule">
            <li class="schedule-item">El dorado: lunes a viernes: 8:00 am - 8:00 pm</li>
            <li class="schedule-item">sábado: 8:00 am - 6:00 pm</li>
            <li class="schedule-item">Nuevo tocumen: lunes a viernes: 9:30 am - 5:00 pm</li>
            <li class="schedule-item">sábado: 10:00 am - 4:00 pm</li>
            <li class="schedule-item">Arraiján: lunes a viernes: 9:30 am - 6:00 pm</li>
            <li class="schedule-item">sábado: 9:30 am - 4:00 pm</li>
        </ul>
    </div>
    <div class="footer-section">
        <h2 class="footer-heading">CONTACTO</h2>
        <ul class="contact-info">
            <li class="contact-item">El dorado: XXXX-XXXX</li>
            <li class="contact-item">Arraijan: XXXX-XXXX</li>
            <li class="contact-item">Nuevo tocumen: XXXX-XXXX</li>
        </ul>
        <div class="social-icons">
            <a href="#" class="social-icon"><img src="img/tik_tok@512px.png" alt="TikTok"></a>
            <a href="#" class="social-icon"><img src="img/linkedin@512px.png" alt="Linkedin"></a>
            <a href="#" class="social-icon"><img src="img/instagram@512px.png" alt="Instagram"></a>
            <a href="#" class="social-icon"><img src="img/facebook@512px.png" alt="Facebook"></a>
        </div>
    </div>
</footer>
</body>
</html>
