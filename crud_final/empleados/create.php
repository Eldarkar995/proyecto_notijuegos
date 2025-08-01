<?php

require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $tipo_doc = mysqli_real_escape_string($conn, $_POST['tipo_doc']);
    $num_doc = mysqli_real_escape_string($conn, $_POST['num_doc']);
    $ciudad = mysqli_real_escape_string($conn, $_POST['ciudad']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo  = mysqli_real_escape_string($conn, $_POST['correo']);
    $fecha_entrada = mysqli_real_escape_string($conn, $_POST['fecha_entrada']);
    $fecha_salida = mysqli_real_escape_string($conn, $_POST['fecha_salida']);
    $concepto = floatval($_POST['concepto']);
    $valor_noche = floatval($_POST['valor_noche']);
    $total = floatval($_POST['total']);

    
    $sql = "INSERT INTO facturacion 
            (nombre, tipo_doc, num_doc, ciudad, direccion, telefono, correo, fecha_entrada, fecha_salida, concepto, valor_noche, total) 
            VALUES 
            ('$nombre', '$tipo_doc', '$num_doc', '$ciudad', '$direccion', '$telefono', '$correo', '$fecha_entrada', '$fecha_salida', '$concepto', '$valor_noche', '$total')";

    
    if (mysqli_query($conn, $sql)) {
       
        header('Location: index.php?mensaje=factura registrada exitosamente');
        exit;
    } else {
       
        echo "Error al registrar: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
?>
