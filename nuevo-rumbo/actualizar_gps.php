<?php
header("Content-Type: application/json");
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conductor_id = $_POST['conductor_id'] ?? 0;
    $latitud      = $_POST['latitud'] ?? 0;
    $longitud     = $_POST['longitud'] ?? 0;

    if ($conductor_id <= 0 || empty($latitud) || empty($longitud)) {
        echo json_encode(["status" => "error", "message" => "Faltan datos de ubicación o ID de conductor."]);
        exit;
    }

    // Verificar si el conductor ya tiene un registro de ubicación, si sí, actualizarlo; si no, insertarlo.
    $sql_check = "SELECT id FROM ubicacion_conductores WHERE conductor_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $conductor_id);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();

    if ($resultado->num_rows > 0) {
        $sql = "UPDATE ubicacion_conductores SET latitud = ?, longitud = ?, ultima_actualizacion = NOW() WHERE conductor_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddi", $latitud, $longitud, $conductor_id);
    } else {
        $sql = "INSERT INTO ubicacion_conductores (conductor_id, latitud, longitud) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idd", $conductor_id, $latitud, $longitud);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Ubicación actualizada en tiempo real."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar la ubicación."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>