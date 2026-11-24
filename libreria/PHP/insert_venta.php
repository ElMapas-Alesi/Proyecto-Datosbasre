<?php
require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones.php';

// Error "de negocio" (stock insuficiente, producto inexistente...): se muestra al usuario.
class VentaException extends Exception {}

function volver($parametros) {
    header('Location: ../ventas.php?' . $parametros);
    exit;
}

// Solo se acepta el envío del formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    volver('');
}

$id_cliente  = filter_var($_POST['id_cliente']  ?? '', FILTER_VALIDATE_INT);
$id_producto = filter_var($_POST['id_producto'] ?? '', FILTER_VALIDATE_INT);
$cantidad    = filter_var($_POST['cantidad']    ?? '', FILTER_VALIDATE_INT);

if (!$id_cliente || !$id_producto || !$cantidad || $cantidad < 1) {
    volver('error=' . urlencode('Elegí cliente, producto y una cantidad válida'));
}

try {
    // Todo o nada: si algo falla, no se guarda ni la venta ni el cambio de stock
    mysqli_begin_transaction($conexion);

    // 1) Precio y stock actuales del producto (FOR UPDATE lo bloquea mientras dura la venta)
    $q = mysqli_prepare($conexion, "SELECT precio, stock FROM productos WHERE id_producto = ? FOR UPDATE");
    mysqli_stmt_bind_param($q, "i", $id_producto);
    mysqli_stmt_execute($q);
    $producto = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
    mysqli_stmt_close($q);

    if (!$producto) {
        throw new VentaException('El producto no existe');
    }
    if ((int)$producto['stock'] < $cantidad) {
        throw new VentaException('Stock insuficiente: quedan ' . (int)$producto['stock'] . ' unidades');
    }

    $precio = (float)$producto['precio'];
    $total  = round($precio * $cantidad, 2);
    $fecha  = date('Y-m-d H:i:s');

    // 2) La venta
    $q = mysqli_prepare($conexion, "INSERT INTO ventas (fecha, total, id_cliente) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($q, "sdi", $fecha, $total, $id_cliente);
    mysqli_stmt_execute($q);
    $id_venta = mysqli_insert_id($conexion);
    mysqli_stmt_close($q);

    // 3) El detalle (qué producto, cuántos y a qué precio)
    $q = mysqli_prepare($conexion,
        "INSERT INTO detalle_ventas (cantidad, precio_unitario, subtotal, id_venta, id_producto)
         VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($q, "iddii", $cantidad, $precio, $total, $id_venta, $id_producto);
    mysqli_stmt_execute($q);
    mysqli_stmt_close($q);

    // 4) Descontar el stock
    $q = mysqli_prepare($conexion, "UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
    mysqli_stmt_bind_param($q, "ii", $cantidad, $id_producto);
    mysqli_stmt_execute($q);
    mysqli_stmt_close($q);

    mysqli_commit($conexion);
    mysqli_close($conexion);
    volver('ok=1');

} catch (VentaException $e) {
    mysqli_rollback($conexion);
    volver('error=' . urlencode($e->getMessage()));
} catch (Throwable $e) {
    mysqli_rollback($conexion);
    volver('error=' . urlencode('No se pudo registrar la venta'));
}
