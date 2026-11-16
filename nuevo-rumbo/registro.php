<?php
header("Content-Type: application/json");
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = $_POST['nombre'] ?? '';
    $correo       = $_POST['correo'] ?? '';
    $password     = $_POST['password'] ?? '';
    $telefono     = $_POST['telefono'] ?? '';
    $sindicato_id = $_POST['sindicato_id'] ?? 1; // Por defecto Sindicato Litoral

    if (empty($nombre) || empty($correo) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios (nombre, correo o contraseña)"]);
        exit;
    }

    // Encriptar contraseña de forma segura
    $pass_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO conductores (nombre, correo, password_hash, telefono, sindicato_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombre, $correo, $pass_hash, $telefono, $sindicato_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Conductor registrado exitosamente en el Sindicato Litoral",
            "id" => $stmt->insert_id
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado o hubo un fallo."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>