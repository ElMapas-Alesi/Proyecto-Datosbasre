<?php
require __DIR__ . '/PHP/conexion.php';
require __DIR__ . '/PHP/funciones.php';

// Datos para los desplegables del formulario
$clientes = mysqli_query($conexion,
    "SELECT id_cliente, nombre, apellido
     FROM clientes
     ORDER BY nombre, apellido");

$productos = mysqli_query($conexion,
    "SELECT id_producto, nombre, precio, stock
     FROM productos
     ORDER BY nombre");

// Historial de ventas, con el nombre del cliente
$historial = mysqli_query($conexion,
    "SELECT v.id_venta, v.fecha, v.total, c.nombre, c.apellido
     FROM ventas v
     LEFT JOIN clientes c ON c.id_cliente = v.id_cliente
     ORDER BY v.fecha DESC, v.id_venta DESC");

$hayClientes  = mysqli_num_rows($clientes) > 0;
$hayProductos = mysqli_num_rows($productos) > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - Nexo</title>
    <link rel="stylesheet" href="CSS/Pagina.css">
</head>
<body>

    <aside class="menu">
        <h2>Inicio</h2>
        <p>Gestión de librería</p>

        <h4>MENÚ PRINCIPAL</h4>

        <nav>
            <a href="Pagina.php">Dashboard</a>
            <a href="productos.php">Productos</a>
            <a href="clientes.php">Clientes</a>
            <a href="ventas.php" class="activo">Ventas</a>
        </nav>

        <div class="usuario">
            <strong>Marcelito Silva</strong>
            <p>Administrador</p>
        </div>
    </aside>

    <div class="contenido">

        <header>
            <div>
                <small>Gestión de Librería</small>
                <h4>Ventas</h4>
            </div>

            <div class="perfil">
                <strong>ATR</strong>
            </div>
        </header>

        <main>
            <h1>Ventas</h1>
            <p class="subtitulo">
                Registro e historial de ventas de la librería.
            </p>

            <?php if (isset($_GET['ok'])): ?>
                <div class="aviso ok">Venta registrada correctamente.</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="aviso error"><?= h($_GET['error']) ?></div>
            <?php endif; ?>

            <?php if (!$hayClientes || !$hayProductos): ?>
                <div class="aviso error">
                    Para registrar una venta primero tenés que cargar
                    <?= !$hayClientes ? 'al menos un cliente' : '' ?>
                    <?= (!$hayClientes && !$hayProductos) ? 'y' : '' ?>
                    <?= !$hayProductos ? 'al menos un producto' : '' ?>.
                </div>
            <?php endif; ?>

            <section class="tabla-contenedor formulario">
                <h3>Registrar nueva venta</h3>

                <form action="PHP/insert_venta.php" method="POST">
                    <label for="id_cliente">Cliente</label>
                    <select id="id_cliente" name="id_cliente" required>
                        <option value="">Seleccionar cliente</option>
                        <?php while ($c = mysqli_fetch_assoc($clientes)): ?>
                            <option value="<?= (int)$c['id_cliente'] ?>">
                                <?= h($c['nombre'] . ' ' . $c['apellido']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="id_producto">Producto</label>
                    <select id="id_producto" name="id_producto" required>
                        <option value="">Seleccionar producto</option>
                        <?php while ($p = mysqli_fetch_assoc($productos)): ?>
                            <option value="<?= (int)$p['id_producto'] ?>" <?= $p['stock'] <= 0 ? 'disabled' : '' ?>>
                                <?= h($p['nombre']) ?> - <?= h(dinero($p['precio'])) ?>
                                (<?= $p['stock'] > 0 ? 'stock: ' . (int)$p['stock'] : 'sin stock' ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" step="1" value="1" required>

                    <button type="submit">Registrar venta</button>
                </form>
            </section>

            <section class="tabla-contenedor">
                <h3>Historial de ventas</h3>
                <p>Operaciones realizadas recientemente</p>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>FECHA</th>
                            <th>CLIENTE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($v = mysqli_fetch_assoc($historial)): ?>
                            <tr>
                                <td>#<?= (int)$v['id_venta'] ?></td>
                                <td><?= h(fecha_es($v['fecha'])) ?></td>
                                <td><?= h(trim($v['nombre'] . ' ' . $v['apellido']) ?: 'Sin cliente') ?></td>
                                <td><?= h(dinero($v['total'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>
