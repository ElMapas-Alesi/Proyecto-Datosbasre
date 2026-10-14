<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

$sql = "INSERT INTO clientes
        (nombre, apellido, email, telefono, direccion)
        VALUES (?, ?, ?, ?, ?)";

$consulta = $conexion->prepare($sql);

$consulta->bind_param(
    "sssis",
    $nombre,
    $apellido,
    $email,
    $telefono,
    $direccion
);

if ($consulta->execute()) {
    echo "Cliente registrado correctamente.";
} else {
    echo "Error al registrar el cliente.";
}

$consulta->close();
$conexion->close();
?>

<CodeBlock language="php" editable>
<?php
$conexion = new mysqli("localhost", "root", "", "libreria");

if ($conexion->connect_error) {
    die("Error de conexión");
}
?>