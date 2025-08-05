<?php
    require 'db.php';
    $consulta_hotel = mysqli_query($conn, "SELECT * FROM inventario");
    if (mysqli_num_rows($consulta_hotel) > 0) {
        while ($row = mysqli_fetch_assoc($consulta_hotel)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>{$row['fecha_entrada']}</td>
                    <td>{$row['fecha_salida']}</td>
                    <td>{$row['seri']}</td>
                    <td>{$row['precio']}</td>
                    <td>{$row['observaciones']}</td>
                    <td>
                        <button onclick='eliminarinventario({$row['id']})'>Eliminar</button>
                        <button onclick='mostrarFormularioActualizar({$row['id']}, \"{$row['nombre']}\", \"{$row['cantidad']}\", \"{$row['fecha_entrada']}\", \"{$row['fecha_salida']}\", \"{$row['precio']}\", \"{$row['observaciones']}\")'>Actualizar</button>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No hay inventario registrados.</td></tr>";
    }
?>