<?php
require __DIR__ . '/conexion.php';

// Solo se acepta el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../productos.php');
    exit;
}

$id     = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre'] ?? '');
$precio = $_POST['precio'] ?? '';
$stock  = $_POST['stock'] ?? '';

if (!$id) {
    header('Location: ../productos.php?error=' . urlencode('Producto inválido'));
    exit;
}

// Si algo está mal, volvemos al formulario de edición con el mensaje
function volver_editar($id, $mensaje) {
    header('Location: ../editar_producto.php?id=' . $id . '&error=' . urlencode($mensaje));
    exit;
}

if ($nombre === '') {
    volver_editar($id, 'Falta el nombre del producto');
}
if (!is_numeric($precio) || $precio < 0) {
    volver_editar($id, 'El precio no es válido');
}
if (filter_var($stock, FILTER_VALIDATE_INT) === false || $stock < 0) {
    volver_editar($id, 'El stock no es válido');
}

$precio = (float)$precio;
$stock  = (int)$stock;

try {
    $q = mysqli_prepare($conexion,
        "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id_producto = ?");
    // "sdii": string, double, integer, integer
    mysqli_stmt_bind_param($q, "sdii", $nombre, $precio, $stock, $id);
    mysqli_stmt_execute($q);
    mysqli_stmt_close($q);
    mysqli_close($conexion);
    header('Location: ../productos.php?ok=editado');
} catch (Throwable $e) {
    volver_editar($id, 'No se pudo guardar los cambios');
}
exit;
