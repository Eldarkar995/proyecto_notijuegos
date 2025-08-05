//create.php
<?php
// Archivo: create.php
require 'db.php'; // Incluir conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizar los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $habitacion = mysqli_real_escape_string($conn, $_POST['habitacion']);
    $fecha_entrada = $_POST['fecha_entrada'];
    $fecha_salida = $_POST['fecha_salida'];
    $precio = floatval($_POST['precio']);

    // Consulta SQL para insertar los datos
    $sql = "INSERT INTO reservaciones (nombre, apellido, telefono, habitacion, fecha_entrada, fecha_salida, precio) 
            VALUES ('$nombre', '$apellido', '$telefono', '$habitacion', '$fecha_entrada', '$fecha_salida', '$precio')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Reservación registrada exitosamente']);
        header('Location: index.php?mensaje=Reservación registrada exitosamente');
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar: ' . mysqli_error($conn)]);
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>