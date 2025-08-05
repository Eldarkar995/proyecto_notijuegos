//create.php
<?php
    // Archivo: create.php
    require 'db.php'; // Incluir conexión a la base de datos
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitizar los datos del formulario
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $tipo_doc = mysqli_real_escape_string($conn, $_POST['tipo_doc']);
        $num_doc = $_POST['num_doc'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $ciudad = $_POST['ciudad'];
        $fecha_de_ingreso = $_POST['fecha_de_ingreso'];
        $fecha_salida = $_POST['fecha_salida'];
        $concepto = $_POST['concepto'];
        $valor_noche = floatval($_POST['valor_noche']);
        $total = mysqli_real_escape_string($conn, $_POST['total']);
        // Consulta SQL para insertar los datos
        $sql = "INSERT INTO facturacion (nombre, tipo_doc, num_doc, correo, direccion, telefono, ciudad, fecha_de_ingreso, fecha_salida, concepto, valor_noche, total,) 
                VALUES ('$nombre' , '$tipo_doc' , '$num_doc', '$correo' , '$direccion' , '$telefono' , '$ciudad' , '$$fecha_de_ingreso , '$fecha_salida' , '$concepto' , '$valor_noche' , '$total')";
        // Ejecutar la consulta
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'factura registrada exitosamente']);
            header('Location: index.php?mensaje=Reservación registrada exitosamente');
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar: ' . mysqli_error($conn)]);
        }
    }
    // Cerrar la conexión
    mysqli_close($conn);
?>