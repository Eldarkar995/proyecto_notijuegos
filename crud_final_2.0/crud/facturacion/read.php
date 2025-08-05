<?php
    require 'db.php';

    $consulta_hotel = mysqli_query($conn, "SELECT * FROM facturacion");

    if (mysqli_num_rows($consulta_hotel) > 0) {
        while ($row = mysqli_fetch_assoc($consulta_hotel)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['tipo_doc']}</td>
                    <td>{$row['num_doc']}</td>
                    <td>{$row['correo']}</td>
                    <td>{$row['direccion']}</td>
                    <td>{$row['ciudad']}</td>
                    <td>{$row['fecha_de_ingreso']}</td>
                    <td>{$row['fecha_de_salida']}</td>
                    <td>{$row['concepto']}</td>
                    <td>{$row['valor_noche']}</td>
                    <td>{$row['total']}</td>
                    <td>
                        <button onclick='eliminarReservacion({$row['id']})'>Eliminar</button>
                        <button onclick='mostrarFormularioActualizar({$row['id']}, \"{$row['nombre']}\", \"{$row['tipo_documento']}\", \"{$row['numero_documento']}\", \"{$row['correo']}\", \"{$row['direccion']}\", \"{$row['ciudad']}\", \"{$row['fecha_de_ingreso']}\", \"{$row['fecha_de_salida']}\")'>Actualizar</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='12'>No hay registros de facturación.</td></tr>";
    }
?>
