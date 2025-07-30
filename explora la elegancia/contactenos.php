<?php
// Parámetros de conexión a la base de datos
$host = "localhost";      // Cambia si es otro host
$usuario = "root";  // Tu usuario de MySQL
$contrasena = ""; // Tu contraseña de MySQL
$base_datos = "hotel_reservas_5";  // El nombre de tu base de datos

// Conectar a la base de datos
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = strval( $_POST['nombre']);
    $correo = strval( $_POST['correo']);
    $mensaje = strval( $_POST['mensaje']);

    // Validar que no estén vacíos
    if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
        // Preparar la consulta
        $sql = $conn->prepare("INSERT INTO contactenos (nombre, correo, mensaje) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $nombre, $correo, $mensaje);

        if ($sql->execute()) {
            echo "Gracias, hemos recibido tu mensaje.";
        } else {
            echo "Error al guardar: " . $sql->error;
        }

        $sql->close();
    } else {
        echo "Error Todos los campos son obligatorios.";
    }
}

$conn->close();
?>