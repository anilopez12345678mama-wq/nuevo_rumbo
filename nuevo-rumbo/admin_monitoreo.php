<?php
require_once "conexion.php";

$sql = "SELECT v.*, l.numero_linea, s.nombre AS sindicato 
        FROM vehiculos_gps v
        JOIN lineas l ON v.linea_id = l.id
        JOIN sindicatos s ON l.sindicato_id = s.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo - Monitoreo Global</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: #121212; color: #fff; }
        header { background: #1b4d3e; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        header h2 { margin: 0; font-size: 20px; }
        .nav-links a { color: #00C853; text-decoration: none; font-weight: bold; margin-left: 15px; }
        #map { height: 70vh; width: 100%; }
        .table-container { padding: 15px; background: #1e1e1e; max-height: 25vh; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #333; }
        th { background: #2a2a2a; color: #00C853; }
    </style>
</head>
<body>
    <header>
        <h2>⚙️ Panel de Control Global (Creadores)</h2>
        <div class="nav-links">
            <a href="home.php">🏠 Inicio</a>
            <a href="index.php">🗺️ Ver Mapa Principal</a>
        </div>
    </header>

    <div id="map"></div>

    <div class="table-container">
        <h3>Unidades Activas en el Sistema</h3>
        <table>
            <tr>
                <th>Conductor</th>
                <th>Sindicato</th>
                <th>Línea</th>
                <th>Latitud</th>
                <th>Longitud</th>
                <th>Última Actualización</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['conductor']; ?></td>
                        <td><?php echo $row['sindicato']; ?></td>
                        <td>Línea <?php echo $row['numero_linea']; ?></td>
                        <td><?php echo $row['latitud']; ?></td>
                        <td><?php echo $row['longitud']; ?></td>
                        <td><?php echo $row['ultima_actualizacion']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; color:#888;">No hay vehículos simulando GPS actualmente.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-16.4920, -68.1450], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var markersLayer = L.layerGroup().addTo(map);

        function actualizarMonitoreoGlobal() {
            fetch('obtener_ubicaciones.php')
                .then(res => res.json())
                .then(data => {
                    markersLayer.clearLayers();
                    data.forEach(v => {
                        var marker = L.circleMarker([parseFloat(v.latitud), parseFloat(v.longitud)], {
                            radius: 9,
                            fillColor: "#2196F3",
                            color: "#fff",
                            weight: 2,
                            fillOpacity: 0.9
                        }).bindPopup(`<b>🚐 Conductor:</b> ${v.conductor}<br><b>Línea:</b> ${v.numero_linea}`);
                        markersLayer.addLayer(marker);
                    });
                });
        }
        actualizarMonitoreoGlobal();
        setInterval(actualizarMonitoreoGlobal, 3000);
    </script>
</body>
</html>