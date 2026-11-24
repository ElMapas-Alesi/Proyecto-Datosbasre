<?php
require __DIR__ . '/PHP/conexion.php';
require __DIR__ . '/PHP/funciones.php';

$lista = mysqli_query($conexion,
    "SELECT id_producto, nombre, precio, stock
     FROM productos
     ORDER BY id_producto DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Nexo</title>
    <link rel="stylesheet" href="CSS/Pagina.css">
</head>
<body>

    <aside class="menu">
        <h2>Inicio</h2>
        <p>Gestión de librería</p>

        <h4>MENÚ PRINCIPAL</h4>

        <nav>
            <a href="Pagina.php">Dashboard</a>
            <a href="productos.php" class="activo">Productos</a>
            <a href="clientes.php">Clientes</a>
            <a href="ventas.php">Ventas</a>
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
                <h4>Productos</h4>
            </div>

            <div class="perfil">
                <strong>ATR</strong>
            </div>
        </header>

        <main>
            <h1>Productos</h1>
            <p class="subtitulo">
                Administración de productos de la librería.
            </p>

            <?php if (isset($_GET['ok'])): ?>
                <div class="aviso ok">Producto agregado correctamente.</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="aviso error"><?= h($_GET['error']) ?></div>
            <?php endif; ?>

            <section class="tabla-contenedor formulario">
                <h3>Agregar producto</h3>

                <form action="PHP/insert_producto.php" method="POST">
                    <label for="nombre">Nombre del producto</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Cuaderno" required>

                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" placeholder="Ej: 5000" min="0" step="0.01" required>

                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" placeholder="Ej: 20" min="0" step="1" required>

                    <button type="submit">Agregar producto</button>
                </form>
            </section>

            <section class="tabla-contenedor">
                <h3>Listado de productos</h3>
                <p>Productos registrados en la librería</p>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>STOCK</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($p = mysqli_fetch_assoc($lista)): ?>
                            <tr>
                                <td>#<?= (int)$p['id_producto'] ?></td>
                                <td><?= h($p['nombre']) ?></td>
                                <td><?= h(dinero($p['precio'])) ?></td>
                                <td><?= (int)$p['stock'] ?></td>
                                <td>
                                    <button type="button">Editar</button>
                                    <button type="button" class="eliminar">Eliminar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>
