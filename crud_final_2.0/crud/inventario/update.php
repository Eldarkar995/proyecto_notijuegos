//update.php
<?php
    require 'db.php';
    if (isset($_POST['update'])) {
        $update_id = $_POST['update_id'];
        $update_nombre = $_POST['update_nombre'];
        $update_cantidad = $_POST['update_cantidad'];
        $update_fecha_entrada = $_POST['update_fecha_entrada'];
        $update_fecha_salida = $_POST['update_fecha_salida'];
        $update_seri = $_POST['update_seri'];
        $update_precio = $_POST['update_precio'];
        $update_observaciones = $_POST['update_observaciones'];
        $update_query = "UPDATE inventario SET 
                        nombre = '$update_nombre', 
                        cantidad = '$update_cantidad', 
                        fecha_entrada = '$update_fecha_entrada', 
                        fecha_salida = '$update_fecha_salida', 
                        seri = '$update_seri',
                        precio = '$update_precio',
                        observaciones = '$update_observaciones'
                        WHERE id = $update_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: index.php?mensaje=¡inventario actualizada exitosamente!");
        } else {
            echo "Error al actualizar el inventario: " . mysqli_error($conn);
        }
    }
?>