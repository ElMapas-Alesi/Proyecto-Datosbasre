<?php
require __DIR__ . '/PHP/conexion.php';
require __DIR__ . '/PHP/funciones.php';

// Tarjetas del resumen
$productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS cantidad FROM productos"))['cantidad'];
$clientes  = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS cantidad FROM clientes"))['cantidad'];
$ventas    = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS cantidad FROM ventas"))['cantidad'];
$recaudado = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COALESCE(SUM(total), 0) AS suma FROM ventas"))['suma'];

// Últimas 5 ventas, con el nombre del cliente
$ultimas = mysqli_query($conexion,
    "SELECT v.id_venta, v.fecha, v.total, c.nombre, c.apellido
     FROM ventas v
     LEFT JOIN clientes c ON c.id_cliente = v.id_cliente
     ORDER BY v.fecha DESC
     LIMIT 5");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Librería</title>
    <link rel="stylesheet" href="CSS/Pagina.css">
</head>
<body>

    <aside class="menu">
        <h2>Inicio</h2>
        <p>Gestión de librería</p>

        <h4>MENÚ PRINCIPAL</h4>

        <nav>
            <a href="Pagina.php" class="activo">Dashboard</a>
            <a href="productos.html">Productos</a>
            <a href="clientes.php">Clientes</a>
            <a href="ventas.html">Ventas</a>
        </nav>

        <div class="usuario">
            <strong>Marcelito Silva</strong>
            <p>Administrador Profesional</p>
        </div>
    </aside>

    <div class="contenido">

        <header>
            <div>
                <small>Gestión de Librería</small>
                <h4>Dashboard</h4>
            </div>

            <div class="perfil">
                <span><?= h(fecha_es('now')) ?></span>
                <strong>ATR</strong>
            </div>
        </header>

        <main>
            <h1>Dashboard</h1>
            <p class="subtitulo">Resumen de la actividad de la librería.</p>

            <section class="tarjetas">
                <div class="tarjeta">
                    <p>Productos</p>
                    <h2><?= (int)$productos ?></h2>
                </div>

                <div class="tarjeta">
                    <p>Clientes</p>
                    <h2><?= (int)$clientes ?></h2>
                </div>

                <div class="tarjeta">
                    <p>Ventas</p>
                    <h2><?= (int)$ventas ?></h2>
                </div>

                <div class="tarjeta">
                    <p>Total recaudado</p>
                    <h2><?= h(dinero($recaudado)) ?></h2>
                </div>
            </section>

            <section class="tabla-contenedor">
                <h3>Últimas ventas</h3>
                <p>Operaciones registradas recientemente</p>

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
                        <?php while ($fila = mysqli_fetch_assoc($ultimas)): ?>
                            <tr>
                                <td>#<?= (int)$fila['id_venta'] ?></td>
                                <td><?= h(fecha_es($fila['fecha'])) ?></td>
                                <td><?= h(trim($fila['nombre'] . ' ' . $fila['apellido']) ?: 'Sin cliente') ?></td>
                                <td><?= h(dinero($fila['total'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>
