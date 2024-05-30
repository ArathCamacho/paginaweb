<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "Halo_2017";
$database = "dbPagWeb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $direccion_envio = $_POST['direccion_envio'];
    $telefono = $_POST['telefono'];

    // Asegurar que los datos requeridos están presentes
    if (empty($nombre) || empty($email) || empty($contraseña)) {
        die('Faltan campos requeridos');
    }

    // Hash de la contraseña utilizando password_hash de PHP
    $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, direccion_envio, telefono) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $hashedPassword, $direccion_envio, $telefono);

    if ($stmt->execute()) {
        // Redireccionar al usuario después de registrar con éxito
        header("Location: frontend/registro_exitoso.html");
        exit(); // Asegurarse de que el script se detenga después de la redirección
    } else {
        echo "Error al registrar usuario: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>
    <form action="conexion.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br>

        <label for="direccion_envio">Dirección de Envío:</label>
        <input type="text" id="direccion_envio" name="direccion_envio"><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono"><br>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>
