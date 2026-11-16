<?php
require_once "conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ayuda - Nuevo Rumbo al Volante</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #121212; color: #ddd; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: rgba(30,30,30,0.95); padding: 30px; border-radius: 12px; border: 1px solid #444; box-shadow: 0 4px 20px rgba(0,0,0,0.6); }
        h1 { color: #00C853; border-bottom: 2px solid #333; padding-bottom: 10px; }
        h3 { color: #2196F3; margin-top: 20px; }
        p, li { line-height: 1.6; color: #bbb; }
        ul { padding-left: 20px; }
        .back-btn { display: inline-block; margin-top: 20px; background: #444; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .back-btn:hover { background: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📖 Manual de Ayuda y Documentación</h1>
        <p>Bienvenido al sistema <b>Nuevo Rumbo al Volante</b>, desarrollado para el <b>CEA Don Bosco "D"</b>. Esta plataforma optimiza el control y visualización de trayectos de minibuses urbanos en La Paz.</p>
        
        <h3>1. Mapa y Enrutamiento Realista</h3>
        <p>El sistema utiliza Leaflet y el motor OSRM para que las trayectorias sigan de forma precisa el asfalto de las calles y avenidas, evitando líneas directas sobre construcciones.</p>

        <h3>2. Sindicatos y Líneas Soportadas</h3>
        <ul>
            <li><b>Sindicato Mixto Litoral:</b> Línea 212.</li>
            <li><b>Sindicato Mixto Simón Bolívar:</b> Líneas actualizadas 304, 267, 847, 893, 387, 984 y 296.</li>
        </ul>

        <h3>3. Panel Administrativo</h3>
        <p>Permite a los administradores observar en tiempo real la ubicación geográfica de todas las unidades operativas conectadas.</p>

        <a href="home.php" class="back-btn">← Volver al Inicio</a>
    </div>
</body>
</html>