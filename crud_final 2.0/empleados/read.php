//read.php
<?php
require 'db.php';

$consulta_hotel = mysqli_query($conn, "SELECT * FROM empleados");

if (mysqli_num_rows($consulta_hotel) > 0) {
    while ($row = mysqli_fetch_assoc($consulta_hotel)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['apellido']}</td>
                <td>{$row['telefono']}</td>
                <td>{$row['correo']}</td>
                <td>{$row['fecha_entrada']}</td>
                <td>{$row['fecha_salida']}</td>
                <td>{$row['salario']}</td>
                <td>
                    <button onclick='eliminarempleados({$row['id']})'>Eliminar</button>
                    <button onclick='mostrarFormularioActualizar({$row['id']}, \"{$row['nombre']}\", \"{$row['apellido']}\", \"{$row['telefono']}\", \"{$row['correo']}\", \"{$row['fecha_entrada']}\", \"{$row['fecha_salida']}\", \"{$row['salario']}\")'>Actualizar</button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No hay empleados registrados.</td></tr>";
}
?>