<?php
require __DIR__ . '/PHP/conexion.php';
require __DIR__ . '/PHP/funciones.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: productos.php');
    exit;
}

$q = mysqli_prepare($conexion, "SELECT id_producto, nombre, precio, stock FROM productos WHERE id_producto = ?");
mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);
$producto = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
mysqli_stmt_close($q);

if (!$producto) {
    header('Location: productos.php?error=' . urlencode('El producto no existe'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto - Nexo</title>
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
                <h4>Editar producto</h4>
            </div>

            <div class="perfil">
                <strong>ATR</strong>
            </div>
        </header>

        <main>
            <h1>Editar producto</h1>
            <p class="subtitulo">Modificá los datos y guardá los cambios.</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="aviso error"><?= h($_GET['error']) ?></div>
            <?php endif; ?>

            <section class="tabla-contenedor formulario">
                <h3>Producto #<?= (int)$producto['id_producto'] ?></h3>

                <form action="PHP/update_producto.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?= (int)$producto['id_producto'] ?>">

                    <label for="nombre">Nombre del producto</label>
                    <input type="text" id="nombre" name="nombre" value="<?= h($producto['nombre']) ?>" required>

                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" value="<?= h($producto['precio']) ?>" min="0" step="0.01" required>

                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" value="<?= (int)$producto['stock'] ?>" min="0" step="1" required>

                    <button type="submit">Guardar cambios</button>
                    <a href="productos.php" style="margin-left:12px">Cancelar</a>
                </form>
            </section>
        </main>
    </div>

</body>
</html>
