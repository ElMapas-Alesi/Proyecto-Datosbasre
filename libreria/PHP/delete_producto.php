<?php
require __DIR__ . '/conexion.php';

// Solo se acepta el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../productos.php');
    exit;
}

$id = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: ../productos.php?error=' . urlencode('Producto inválido'));
    exit;
}

try {
    $q = mysqli_prepare($conexion, "DELETE FROM productos WHERE id_producto = ?");
    mysqli_stmt_bind_param($q, "i", $id);
    mysqli_stmt_execute($q);
    mysqli_stmt_close($q);
    mysqli_close($conexion);
    header('Location: ../productos.php?ok=eliminado');
} catch (mysqli_sql_exception $e) {
    // 1451 = el producto está usado en otra tabla (por ejemplo, ya tiene ventas)
    if ($e->getCode() === 1451) {
        header('Location: ../productos.php?error=' . urlencode('No se puede eliminar: el producto ya tiene ventas registradas'));
    } else {
        header('Location: ../productos.php?error=' . urlencode('No se pudo eliminar el producto'));
    }
}
exit;
