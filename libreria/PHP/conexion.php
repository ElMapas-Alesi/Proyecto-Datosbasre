<?php
$conexion = mysqli_connect("localhost", "libreria_user", "libreria123", "libreria");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
