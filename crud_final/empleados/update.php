//update.php
<?php
require 'db.php';

if (isset($_POST['update'])) {
    $update_id = $_POST['update_id'];
    $update_nombre = $_POST['update_nombre'];
    $update_apellido = $_POST['update_apellido'];
    $update_telefono = $_POST['update_telefono'];
    $update_correo = $_POST['update_correo'];
    $update_fecha_entrada = $_POST['update_fecha_entrada'];
    $update_fecha_salida = $_POST['update_fecha_salida'];
    $update_salario = $_POST['update_salario'];

    $update_query = "UPDATE reservaciones SET 
                    nombre = '$update_nombre', 
                    apellido = '$update_apellido', 
                    telefono = '$update_telefono', 
                    correo = '$update_correo', 
                    fecha_entrada = '$update_fecha_entrada', 
                    fecha_salida = '$update_fecha_salida', 
                    salario = '$update_salario' 
                    WHERE id = $update_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?mensaje=¡empleado actualizada exitosamente!");
    } else {
        echo "Error al actualizar la reservación: " . mysqli_error($conn);
    }
}
?>