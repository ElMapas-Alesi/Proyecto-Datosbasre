<?php
require __DIR__ . '/conexion.php';

// Solo se acepta el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../clientes.php');
    exit;
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellido  = trim($_POST['apellido'] ?? '');
$email     = trim($_POST['email'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// Validaciones básicas
if ($nombre === '' || $apellido === '') {
    header('Location: ../clientes.php?error=Falta+nombre+o+apellido');
    exit;
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../clientes.php?error=El+email+no+es+válido');
    exit;
}

// Lo vacío se guarda como NULL
$email     = ($email === '')     ? null : $email;
$telefono  = ($telefono === '')  ? null : $telefono;
$direccion = ($direccion === '') ? null : $direccion;

$sql = "INSERT INTO clientes (nombre, apellido, email, telefono, direccion)
        VALUES (?, ?, ?, ?, ?)";

$consulta = mysqli_prepare($conexion, $sql);
// "sssss": todo como texto; MySQL lo convierte solo si la columna es int
mysqli_stmt_bind_param($consulta, "sssss", $nombre, $apellido, $email, $telefono, $direccion);

if (mysqli_stmt_execute($consulta)) {
    header('Location: ../clientes.php?ok=1');
} else {
    header('Location: ../clientes.php?error=No+se+pudo+guardar+el+cliente');
}

mysqli_stmt_close($consulta);
mysqli_close($conexion);
exit;
