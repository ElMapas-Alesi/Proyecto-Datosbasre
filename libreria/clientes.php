<?php
require __DIR__ . '/PHP/conexion.php';
require __DIR__ . '/PHP/funciones.php';

$lista = mysqli_query($conexion,
    "SELECT id_cliente, nombre, apellido, email, telefono, direccion
     FROM clientes
     ORDER BY id_cliente DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Gestión de Librería</title>
    <link rel="stylesheet" href="CSS/Pagina.css">
</head>
<body>

    <aside class="menu">
        <h2>Inicio</h2>
        <p>Gestión de librería</p>

        <h4>MENÚ PRINCIPAL</h4>

        <nav>
            <a href="Pagina.php">Dashboard</a>
            <a href="productos.html">Productos</a>
            <a href="clientes.php" class="activo">Clientes</a>
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
                <h4>Clientes</h4>
            </div>

            <div class="perfil">
                <span><?= h(fecha_es('now')) ?></span>
                <strong>ATR</strong>
            </div>
        </header>

        <main>
            <h1>Clientes</h1>
            <p class="subtitulo">Registrá clientes nuevos y consultá los existentes.</p>

            <?php if (isset($_GET['ok'])): ?>
                <div class="aviso ok">Cliente registrado correctamente.</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="aviso error"><?= h($_GET['error']) ?></div>
            <?php endif; ?>

            <section class="tabla-contenedor formulario">
                <h3>Registrar cliente</h3>

                <form action="PHP/insert_cliente.php" method="POST">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">

                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono">

                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion">

                    <button type="submit">Registrar cliente</button>
                </form>
            </section>

            <section class="tabla-contenedor">
                <h3>Lista de clientes</h3>
                <p>Los más recientes primero</p>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>EMAIL</th>
                            <th>TELÉFONO</th>
                            <th>DIRECCIÓN</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($c = mysqli_fetch_assoc($lista)): ?>
                            <tr>
                                <td>#<?= (int)$c['id_cliente'] ?></td>
                                <td><?= h($c['nombre'] . ' ' . $c['apellido']) ?></td>
                                <td><?= h($c['email']) ?></td>
                                <td><?= h($c['telefono']) ?></td>
                                <td><?= h($c['direccion']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

</body>
</html>
