    //create.php
    <?php
    // Archivo: create.php
    require 'db.php'; // Incluir conexión a la base de datos

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitizar los datos del formulario
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $correo = mysqli_real_escape_string($conn, $_POST['correo']);
        $fecha_entrada = $_POST['fecha_entrada'];
        $fecha_salida = $_POST['fecha_salida'];
        $salario = floatval($_POST['salario']);

        // Consulta SQL para insertar los datos
         $sql = "INSERT INTO empleados (nombre, apellido, telefono, correo, fecha_entrada, fecha_salida, salario) 
                VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$fecha_entrada', '$fecha_salida', '$salario')";

        // Ejecutar la consulta
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'empleados registrado exitosamente']);
            header('Location: index.php?mensaje=empleados registrado exitosamente');
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar: ' . mysqli_error($conn)]);
        }
    }

    // Cerrar la conexión
    mysqli_close($conn);
    ?>
        