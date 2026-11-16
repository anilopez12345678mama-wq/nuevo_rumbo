<?php
require_once "conexion.php";

// Obtener la lista de conductores
$sql_cond = "SELECT id, nombre FROM conductores";
$res_cond = $conn->query($sql_cond);

// Obtener los puntos de la ruta del Sindicato Litoral
$sql_ruta = "SELECT latitud, longitud, nombre_tramo FROM trazado_rutas ORDER BY orden_punto ASC";
$res_ruta = $conn->query($sql_ruta);
$puntos_ruta = [];
while ($row = $res_ruta->fetch_assoc()) {
    $puntos_ruta[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Conductor - Sindicato Mixto Litoral</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #eef2f3; display: flex; height: 100vh; }
        .sidebar { width: 350px; background: white; padding: 20px; box-shadow: 4px 0 15px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: space-between; z-index: 1000; }
        #map { flex-grow: 1; height: 100vh; }
        h2 { color: #1b4d3e; margin-top: 0; font-size: 22px; }
        select, button { width: 100%; padding: 12px; margin-top: 15px; border-radius: 6px; border: 1px solid #ccc; font-size: 15px; }
        button { background: #00C853; color: white; border: none; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #00b046; }
        .btn-stop { background: #d32f2f; }
        .btn-stop:hover { background: #b71c1c; }
        .status-box { background: #f8f9fa; padding: 12px; border-radius: 6px; border-left: 5px solid #00C853; margin-top: 20px; }
    </style>
</head>
<body>

    <!-- Panel de control lateral para el chofer -->
    <div class="sidebar">
        <div>
            <h2>🚐 Panel del Chofer</h2>
            <p><b>Sindicato Mixto Litoral</b></p>

            <label for="conductor">Seleccionar Conductor:</label>
            <select id="conductor">
                <?php while ($c = $res_cond->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['nombre']; ?></option>
                <?php endwhile; ?>
            </select>

            <button id="btn-iniciar" onclick="iniciarSimulacion()">▶ Iniciar Recorrido</button>
            <button id="btn-detener" class="btn-stop" onclick="detenerSimulacion()" style="display:none;">⏹ Detener Recorrido</button>

            <div class="status-box">
                <b>Estado GPS:</b><br>
                <span id="estado">Detenido en base</span>
            </div>
        </div>

        <div>
            <p style="font-size: 12px; color: #666; text-align: center;">Proyecto de Titulación - CEA Don Bosco "D"</p>
        </div>
    </div>

    <!-- Mapa visual para el conductor -->
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const puntosRuta = <?php echo json_encode($puntos_ruta); ?>;
        let indiceActual = 0;
        let intervaloSimulacion = null;
        let marcadorChofer = null;

        // Inicializar mapa del chofer
        var map = L.map('map').setView([-16.4920, -68.1450], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Dibujar la ruta verde de referencia en el mapa del chofer
        if (puntosRuta.length > 0) {
            let latLngs = puntosRuta.map(p => [parseFloat(p.latitud), parseFloat(p.longitud)]);
            let polyline = L.polyline(latLngs, { color: '#00C853', weight: 6, opacity: 0.8 }).addTo(map);
            map.fitBounds(polyline.getBounds());
        }

        function iniciarSimulacion() {
            if (puntosRuta.length === 0) {
                alert("No hay ruta registrada.");
                return;
            }

            document.getElementById('btn-iniciar').style.display = 'none';
            document.getElementById('btn-detener').style.display = 'block';
            document.getElementById('estado').innerText = "En movimiento por la ruta 🟢";

            intervaloSimulacion = setInterval(() => {
                let punto = puntosRuta[indiceActual];
                let lat = parseFloat(punto.latitud);
                let lng = parseFloat(punto.longitud);

                // Enviar al servidor
                enviarGPS(lat, lng, punto.nombre_tramo);

                // Actualizar la posición del marcador en el mapa del chofer
                if (marcadorChofer) {
                    marcadorChofer.setLatLng([lat, lng]);
                } else {
                    marcadorChofer = L.marker([lat, lng]).addTo(map)
                        .bindPopup("<b>Mi Minibús (En vivo)</b>").openPopup();
                }

                // Centrar el mapa suavemente en la posición actual del chofer
                map.panTo([lat, lng]);

                indiceActual = (indiceActual + 1) % puntosRuta.length;
            }, 3000);
        }

        function detenerSimulacion() {
            clearInterval(intervaloSimulacion);
            document.getElementById('btn-iniciar').style.display = 'block';
            document.getElementById('btn-detener').style.display = 'none';
            document.getElementById('estado').innerText = "Detenido";
        }

        function enviarGPS(lat, lng, descripcion) {
            let conductorId = document.getElementById('conductor').value;

            let formData = new URLSearchParams();
            formData.append('conductor_id', conductorId);
            formData.append('latitud', lat);
            formData.append('longitud', lng);

            fetch('actualizar_gps.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('estado').innerText = `Ruta: ${descripcion}`;
            })
            .catch(err => console.error("Error GPS:", err));
        }
    </script>
</body>
</html>