<?php
header('Content-Type: application/json; charset=utf-8');
require_once "conexion.php";

$linea_id = isset($_GET['linea_id']) ? intval($_GET['linea_id']) : 1;

// Consultar información de la línea y su sindicato
$sql_info = "SELECT l.numero_linea, l.descripcion, s.nombre AS sindicato 
             FROM lineas l 
             INNER JOIN sindicatos s ON l.sindicato_id = s.id 
             WHERE l.id = $linea_id";
$res_info = $conn->query($sql_info);
$info = $res_info->fetch_assoc();

// Consultar puntos del trazado
$sql_puntos = "SELECT latitud, longitud, orden_punto, nombre_tramo 
               FROM trazado_rutas 
               WHERE linea_id = $linea_id 
               ORDER BY orden_punto ASC";
$res_puntos = $conn->query($sql_puntos);

$puntos = [];
while ($row = $res_puntos->fetch_assoc()) {
    $puntos[] = [
        'lat' => floatval($row['latitud']),
        'lng' => floatval($row['longitud']),
        'orden' => intval($row['orden_punto']),
        'tramo' => $row['nombre_tramo']
    ];
}

echo json_encode([
    'estado' => 'ok',
    'linea' => $info,
    'puntos' => $puntos
], JSON_UNESCAPED_UNICODE);
?>