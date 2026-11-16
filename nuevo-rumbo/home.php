<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Rumbo al Volante | Transporte Urbano La Paz</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            color: #1e293b;
            line-height: 1.6;
        }
        /* Barra superior institucional */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-mark {
            width: 36px;
            height: 36px;
            background-color: #0f4c3a;
            color: #ffffff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
        }
        .brand-text h1 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .brand-text span {
            font-size: 12px;
            color: #64748b;
            display: block;
        }
        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
            margin-left: 24px;
            transition: color 0.2s;
        }
        .nav-links a:hover {
            color: #0f4c3a;
        }
        .nav-links a.btn-nav {
            background-color: #0f4c3a;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 6px;
            font-weight: 600;
        }
        .nav-links a.btn-nav:hover {
            background-color: #16654e;
        }

        /* Sección principal (Hero) */
        .hero {
            max-width: 1080px;
            margin: 50px auto 40px;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 40px;
            align-items: center;
        }
        .tag-institucional {
            display: inline-block;
            background-color: #e6f4ef;
            color: #0f4c3a;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 4px;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .hero h2 {
            font-size: 34px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 16px;
        }
        .hero p {
            font-size: 16px;
            color: #475569;
            margin-bottom: 28px;
        }
        .actions {
            display: flex;
            gap: 14px;
        }
        .btn-primary {
            background-color: #0f4c3a;
            color: #ffffff;
            text-decoration: none;
            padding: 13px 26px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background-color: #16654e;
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            text-decoration: none;
            padding: 13px 22px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: background 0.2s;
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
        }

        /* Tarjeta lateral de resumen operativo */
        .status-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 26px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }
        .status-box h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .route-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 14px;
        }
        .route-item:last-child {
            border-bottom: none;
        }
        .route-badge {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
        }
        .route-desc {
            color: #475569;
            flex: 1;
            margin-left: 12px;
        }
        .route-sind {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Pie de página discreto */
        footer {
            max-width: 1080px;
            margin: 50px auto 20px;
            padding: 20px 24px 0;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .hero { grid-template-columns: 1fr; }
            .navbar { padding: 14px 20px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <div class="brand-mark">NR</div>
            <div class="brand-text">
                <h1>Nuevo Rumbo al Volante</h1>
                <span>Sistema de Consulta de Rutas Urbanas — La Paz</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="admin.php">Administración</a>
            <a href="manual.php">Guía de Uso</a>
            <a href="index.php" class="btn-nav">Abrir Mapa</a>
        </div>
    </nav>

    <main class="hero">
        <div>
            <span class="tag-institucional">Proyecto de Grado • CEA Don Bosco "D"</span>
            <h2>Consulta de recorridos y paradas del transporte público urbano</h2>
            <p>
                Herramienta web diseñada para orientar a pasajeros y conductores mediante la visualización geográfica exacta de sindicatos, líneas, puntos de parada y distancias estimadas en la ciudad de La Paz.
            </p>
            <div class="actions">
                <a href="index.php" class="btn-primary">Explorar Rutas en el Mapa</a>
                <a href="admin.php" class="btn-secondary">Gestión de Líneas</a>
            </div>
        </div>

        <aside class="status-box">
            <h3>Líneas Piloto Registradas</h3>
            
            <div class="route-item">
                <span class="route-badge">Línea 212</span>
                <div class="route-desc">
                    <strong>Villa Litoral - El Tejar</strong>
                    <div class="route-sind">Sindicato Mixto Litoral</div>
                </div>
            </div>

            <div class="route-item">
                <span class="route-badge">Línea 304</span>
                <div class="route-desc">
                    <strong>El Tejar - Villa Salomé</strong>
                    <div class="route-sind">Sindicato Mixto Simón Bolívar</div>
                </div>
            </div>

            <div class="route-item">
                <span class="route-badge">Línea 267</span>
                <div class="route-desc">
                    <strong>El Tejar - Alto San Antonio</strong>
                    <div class="route-sind">Sindicato Mixto Simón Bolívar</div>
                </div>
            </div>
        </aside>
    </main>

    <footer>
        <span>Proyecto: Nuevo Rumbo al Volante — Gestión 2026</span>
        <span>Centro de Educación Alternativa "Don Bosco D" • La Paz, Bolivia</span>
    </footer>

</body>
</html>