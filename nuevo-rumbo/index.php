<?php
require_once "conexion.php";
$sql_sind = "SELECT * FROM sindicatos";
$res_sind = $conn->query($sql_sind);

$sql_lin = "SELECT * FROM lineas";
$res_lin = $conn->query($sql_lin);
$todas_lineas = [];
while ($l = $res_lin->fetch_assoc()) {
    $todas_lineas[] = $l;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Rumbo al Volante — Mapa, Simulador y Surtidores</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #0f172a; color: #f8fafc; overflow: hidden; }
        header { background: #0f4c3a; color: white; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.4); }
        header h2 { margin: 0; font-size: 19px; font-weight: 600; }
        header a { background: rgba(255,255,255,0.15); color: #fff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; transition: 0.2s; }
        header a:hover { background: rgba(255,255,255,0.25); }
        #map { height: calc(100vh - 52px); width: 100%; }
        .control-panel {
            position: absolute; top: 70px; left: 20px; z-index: 1000;
            background: rgba(15, 23, 42, 0.94); backdrop-filter: blur(8px);
            padding: 18px; border-radius: 10px; width: 300px;
            border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .control-panel h4 { margin: 0 0 12px 0; color: #10b981; font-size: 15px; border-bottom: 1px solid #1e293b; padding-bottom: 8px; }
        label.lbl-title { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
        select {
            width: 100%; padding: 9px; margin-top: 5px; margin-bottom: 12px;
            background: #1e293b; color: #f8fafc; border: 1px solid #475569;
            border-radius: 6px; font-size: 13px; cursor: pointer;
        }
        .info-card {
            background: #1e293b; padding: 12px; border-radius: 8px;
            border-left: 4px solid #10b981; font-size: 13px; line-height: 1.5;
            margin-bottom: 12px;
        }
        .badge-api {
            display: inline-block; background: #065f46; color: #6ee7b7;
            font-size: 11px; padding: 2px 8px; border-radius: 10px; margin-top: 6px;
        }
        .capa-box {
            background: #1e293b; padding: 9px 12px; border-radius: 6px;
            margin-bottom: 12px; border: 1px solid #334155; font-size: 13px;
            display: flex; align-items: center; gap: 8px; cursor: pointer;
        }
        .capa-box input { cursor: pointer; accent-color: #f59e0b; width: 16px; height: 16px; }
        .btn-simular {
            width: 100%; padding: 11px; background: #10b981; color: #064e3b;
            border: none; border-radius: 6px; font-weight: 700; font-size: 13px;
            cursor: pointer; transition: 0.2s; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btn-simular:hover { background: #34d399; }
        .btn-simular.detener { background: #ef4444; color: #ffffff; }
        .sim-status {
            margin-top: 10px; font-size: 12px; color: #cbd5e1;
            background: #0f172a; padding: 8px 10px; border-radius: 6px;
            border: 1px solid #334155; display: none;
        }
        .bus-marker {
            background: #0f4c3a; color: #ffffff; border: 2px solid #ffffff;
            border-radius: 50%; width: 32px; height: 32px; display: flex;
            align-items: center; justify-content: center; font-weight: 700;
            font-size: 11px; box-shadow: 0 3px 10px rgba(0,0,0,0.5);
        }
        .fuel-marker {
            background: #d97706; color: #ffffff; border: 2px solid #ffffff;
            border-radius: 6px; padding: 2px 5px; font-weight: 700;
            font-size: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.4);
            white-space: nowrap;
        }
        .leaflet-routing-container { display: none !important; }
    </style>
</head>
<body>

    <header>
        <h2>Nuevo Rumbo al Volante — Visor, Simulador y Abastecimiento</h2>
        <a href="home.php">Volver al Inicio</a>
    </header>

    <div class="control-panel">
        <h4>Control de Rutas Urbanas</h4>
        
        <label class="lbl-title">Sindicato:</label>
        <select id="selectSindicato" onchange="filtrarLineas()">
            <?php while ($s = $res_sind->fetch_assoc()): ?>
                <option value="<?php echo $s['id']; ?>"><?php echo $s['nombre']; ?></option>
            <?php endwhile; ?>
        </select>

        <label class="lbl-title">Línea de Transporte:</label>
        <select id="selectLinea" onchange="cargarRutaAPI()"></select>

        <div class="info-card" id="infoRuta">
            <strong>Recorrido:</strong> Cargando...<br>
            <strong>Distancia est.:</strong> <span id="distanciaTxt">--</span>
            <div class="badge-api">● API REST + OSRM Activo</div>
        </div>

        <label class="capa-box">
            <input type="checkbox" id="chkSurtidores" checked onchange="toggleSurtidores()">
            <span>Mostrar Surtidores (Gasolina / GNV)</span>
        </label>

        <button id="btnSimular" class="btn-simular" onclick="alternarSimulacion()">Iniciar Simulación de Unidad</button>
        <div id="simStatus" class="sim-status">Estado: En espera...</div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        const lineasData = <?php echo json_encode($todas_lineas, JSON_UNESCAPED_UNICODE); ?>;
        let routingControl = null;
        let marcadoresLayer = L.layerGroup();
        let surtidoresLayer = L.layerGroup();
        let coordenadasRutaActual = [];
        let lineaNumeroActual = "";
        let simuladorIntervalo = null;
        let marcadorVehiculo = null;
        let indicePaso = 0;

        // Estaciones de servicio estratégicas cerca de las rutas piloto en La Paz
        const estacionesServicio = [
            { nombre: "E.S. El Tejar / Cementerio", tipo: "Gasolina / GNV", lat: -16.4985, lng: -68.1435 },
            { nombre: "E.S. Uruguay / Montes", tipo: "Gasolina Especial", lat: -16.4922, lng: -68.1392 },
            { nombre: "E.S. Miraflores (Av. Busch)", tipo: "Gasolina / GNV", lat: -16.4968, lng: -68.1215 },
            { nombre: "E.S. San Antonio / Pampahasi", tipo: "Gasolina / GNV", lat: -16.5085, lng: -68.1060 },
            { nombre: "E.S. Villa Salomé / Kupini", tipo: "Gasolina / GNV", lat: -16.5048, lng: -68.0865 }
        ];

        const map = L.map('map', { zoomControl: false }).setView([-16.4980, -68.1200], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap | CEA Don Bosco D',
            maxZoom: 19
        }).addTo(map);

        marcadoresLayer.addTo(map);
        surtidoresLayer.addTo(map);

        function cargarSurtidores() {
            surtidoresLayer.clearLayers();
            estacionesServicio.forEach(est => {
                const iconoFuel = L.divIcon({
                    className: '',
                    html: `<div class="fuel-marker">GNV/GAS</div>`,
                    iconSize: [54, 20],
                    iconAnchor: [27, 10]
                });
                L.marker([est.lat, est.lng], { icon: iconoFuel })
                    .bindPopup(`<b>${est.nombre}</b><br>Combustible: ${est.tipo}`)
                    .addTo(surtidoresLayer);
            });
        }

        function toggleSurtidores() {
            const activo = document.getElementById('chkSurtidores').checked;
            if (activo) {
                map.addLayer(surtidoresLayer);
            } else {
                map.removeLayer(surtidoresLayer);
            }
        }

        function detenerSimulacion() {
            if (simuladorIntervalo) {
                clearInterval(simuladorIntervalo);
                simuladorIntervalo = null;
            }
            if (marcadorVehiculo) {
                map.removeLayer(marcadorVehiculo);
                marcadorVehiculo = null;
            }
            const btn = document.getElementById('btnSimular');
            btn.textContent = "Iniciar Simulación de Unidad";
            btn.classList.remove('detener');
            document.getElementById('simStatus').style.display = 'none';
        }

        function alternarSimulacion() {
            if (simuladorIntervalo) {
                detenerSimulacion();
                return;
            }

            if (coordenadasRutaActual.length === 0) {
                alert("Espera un segundo a que termine de trazarse la ruta en el mapa.");
                return;
            }

            const btn = document.getElementById('btnSimular');
            const statusBox = document.getElementById('simStatus');
            btn.textContent = "Detener Simulación";
            btn.classList.add('detener');
            statusBox.style.display = 'block';

            indicePaso = 0;
            const iconoBus = L.divIcon({
                className: '',
                html: `<div class="bus-marker">${lineaNumeroActual}</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            marcadorVehiculo = L.marker(coordenadasRutaActual[0], { icon: iconoBus, zIndexOffset: 1000 }).addTo(map);

            simuladorIntervalo = setInterval(() => {
                if (indicePaso >= coordenadasRutaActual.length) {
                    statusBox.innerHTML = `<strong>Recorrido completado.</strong> Unidad llegó a destino.`;
                    clearInterval(simuladorIntervalo);
                    simuladorIntervalo = null;
                    btn.textContent = "Reiniciar Simulación";
                    btn.classList.remove('detener');
                    return;
                }

                const punto = coordenadasRutaActual[indicePaso];
                marcadorVehiculo.setLatLng(punto);
                const porcentaje = Math.round(((indicePaso + 1) / coordenadasRutaActual.length) * 100);
                statusBox.innerHTML = `<strong>Unidad Línea ${lineaNumeroActual} en ruta</strong><br>Avance del recorrido: ${porcentaje}%`;

                indicePaso += 3;
            }, 120);
        }

        function filtrarLineas() {
            detenerSimulacion();
            const sindId = document.getElementById('selectSindicato').value;
            const selectLinea = document.getElementById('selectLinea');
            selectLinea.innerHTML = '';

            const filtradas = lineasData.filter(l => l.sindicato_id == sindId);
            filtradas.forEach(l => {
                const opt = document.createElement('option');
                opt.value = l.id;
                opt.textContent = `Línea ${l.numero_linea} — ${l.descripcion}`;
                selectLinea.appendChild(opt);
            });

            cargarRutaAPI();
        }

        async function cargarRutaAPI() {
            detenerSimulacion();
            coordenadasRutaActual = [];
            const lineaId = document.getElementById('selectLinea').value;
            if (!lineaId) return;

            try {
                const respuesta = await fetch(`api_rutas.php?linea_id=${lineaId}`);
                const datos = await respuesta.json();

                if (datos.estado === 'ok' && datos.puntos.length >= 2) {
                    lineaNumeroActual = datos.linea.numero_linea;
                    document.getElementById('infoRuta').innerHTML = `
                        <strong>${datos.linea.sindicato}</strong><br>
                        Línea <b>${datos.linea.numero_linea}</b>: ${datos.linea.descripcion}<br>
                        <strong>Distancia:</strong> <span id="distanciaTxt">Calculando...</span>
                        <div class="badge-api">● API REST + OSRM Activo</div>
                    `;

                    if (routingControl) map.removeControl(routingControl);
                    marcadoresLayer.clearLayers();

                    const waypoints = datos.puntos.map((p, idx) => {
                        const etiqueta = idx === 0 ? 'INICIO' : (idx === datos.puntos.length - 1 ? 'DESTINO' : `Parada ${p.orden}`);
                        L.marker([p.lat, p.lng])
                            .bindPopup(`<b>${etiqueta}</b><br>${p.tramo}`)
                            .addTo(marcadoresLayer);
                        return L.latLng(p.lat, p.lng);
                    });

                    routingControl = L.Routing.control({
                        waypoints: waypoints,
                        router: new L.Routing.osrmv1({
                            serviceUrl: 'https://router.project-osrm.org/route/v1'
                        }),
                        lineOptions: {
                            styles: [
                                { color: '#064e3b', opacity: 0.9, weight: 9 },
                                { color: '#10b981', opacity: 1, weight: 5 }
                            ]
                        },
                        createMarker: () => null,
                        addWaypoints: false,
                        draggableWaypoints: false,
                        fitSelectedRoutes: true,
                        show: false
                    }).on('routesfound', function(e) {
                        const ruta = e.routes[0];
                        coordenadasRutaActual = ruta.coordinates;
                        const km = (ruta.summary.totalDistance / 1000).toFixed(2);
                        const min = Math.round(ruta.summary.totalTime / 60);
                        document.getElementById('distanciaTxt').textContent = `${km} km (~${min} min)`;
                    }).addTo(map);
                }
            } catch (error) {
                console.error("Error al consultar la API:", error);
            }
        }

        cargarSurtidores();
        filtrarLineas();
    </script>
</body>
</html>