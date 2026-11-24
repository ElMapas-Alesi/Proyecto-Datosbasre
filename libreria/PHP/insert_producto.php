<?php
require __DIR__ . '/conexion.php';

// Solo se acepta el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../productos.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$precio = $_POST['precio'] ?? '';
$stock  = $_POST['stock'] ?? '';

// Validaciones básicas
if ($nombre === '') {
    header('Location: ../productos.php?error=Falta+el+nombre+del+producto');
    exit;
}
if (!is_numeric($precio) || $precio < 0) {
    header('Location: ../productos.php?error=El+precio+no+es+válido');
    exit;
}
if (filter_var($stock, FILTER_VALIDATE_INT) === false || $stock < 0) {
    header('Location: ../productos.php?error=El+stock+no+es+válido');
    exit;
}

$precio = (float)$precio;
$stock  = (int)$stock;

$sql = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";

$consulta = mysqli_prepare($conexion, $sql);
// "sdi": string, double (decimal), integer
mysqli_stmt_bind_param($consulta, "sdi", $nombre, $precio, $stock);

if (mysqli_stmt_execute($consulta)) {
    header('Location: ../productos.php?ok=1');
} else {
    header('Location: ../productos.php?error=No+se+pudo+guardar+el+producto');
}

mysqli_stmt_close($consulta);
mysqli_close($conexion);
exit;
