//update.php
<?php
    require 'db.php';

    if (isset($_POST['update'])) {
        $update_id = $_POST['update_id'];
        $update_nombre = $_POST['update_nombre'];
        $update_tipo_de_documento = $_POST['update_tipo_de_documento'];
        $update_num_de_doc = $_POST['update_num_de_doc'];
        $update_correo = $_POST['update_correo'];
        $update_direccion = $_POST['update_direccion'];
        $update_telefono = $_POST['update_telefono'];
        $update_ciudad = $_POST['update_ciudad'];
        $update_fecha_de_ingreso = $_POST['update_fecha_de_ingreso'];
        $update_fecha_de_salida = $_POST['update_fecha_de_salida'];
        $update_concepto = $_POST['update_concepto'];
        $update_valor_noche = $_POST['update_valor_noche'];
        $update_total = $_POST['update_total'];

        $update_query = "UPDATE inventario SET 
                        nombre = '$update_nombre', 
                        tipo_de_documento = '$update_tipo_de_documento',
                        num_de_doc = '$update_num_de_doc',
                        correo = '$update_correo',
                        direccion = '$update_direccion',
                        telefono = '$update_telefono',
                        ciudad = '$update_ciudad',
                        fecha_de_ingreso = '$update_fecha_de_ingreso',
                        fecha_de_salida = '$update_fecha_de_salida',
                        concepto = '$update_concepto',
                        valor_noche = '$update_valor_noche',
                        total = '$update_total'
                        WHERE id = $update_id";

        if (mysqli_query($conn, $update_query)) {
            header("Location: index.php?mensaje=¡facturacion actualizada exitosamente!");
        } else {
            echo "Error al actualizar el inventario: " . mysqli_error($conn);
        }
    }
?>
