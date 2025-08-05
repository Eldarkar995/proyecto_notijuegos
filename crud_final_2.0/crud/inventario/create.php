//create.php
<?php
    // Archivo: create.php
    require 'db.php'; // Incluir conexión a la base de datos
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitizar los datos del formulario
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $cantidad = mysqli_real_escape_string($conn, $_POST['cantidad']);
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $seri = mysqli_real_escape_string($conn, $_POST['seri']);
        $precio = floatval($_POST['precio']);
        $observaciones = mysqli_real_escape_string($conn, $_POST['observaciones']);
        // Consulta SQL para insertar los datos
        $sql = "INSERT INTO inventario (nombre, cantidad, fecha_entrada, fecha_salida, seri, precio, observaciones) 
                VALUES ('$nombre', '$cantidad' , '$fecha_entrada', '$fecha_salida','$seri','$precio','$observaciones')";
        // Ejecutar la consulta
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'inventario registrada exitosamente']);
            header('Location: index.php?mensaje=Reservación registrada exitosamente');
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar: ' . mysqli_error($conn)]);
        }
    }
    // Cerrar la conexión
    mysqli_close($conn);
?>