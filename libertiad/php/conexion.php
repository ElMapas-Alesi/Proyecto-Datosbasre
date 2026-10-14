<?php
$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "libreria"
);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>