<?php
require_once "conexion.php";
header('Content-Type: application/json');

$sql = "SELECT v.latitud, v.longitud, v.conductor, l.numero_linea 
        FROM vehiculos_gps v 
        JOIN lineas l ON v.linea_id = l.id";
$result = $conn->query($sql);

$vehiculos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehiculos[] = $row;
    }
}
echo json_encode($vehiculos);
?>