<?php
$servidor = "127.0.0.1";
$usuario = "root";
$clave = "";
$base_datos = "nuevo_rumbo";

$conexion = mysqli_connect($servidor, $usuario, $clave, $base_datos);

if (!$conexion) {
    die("Fallo la conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
$conn = $conexion;
?>