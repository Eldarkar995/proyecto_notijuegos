<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservaciones</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Crear Nueva Reservacion</h2>
    <form action="create.php" method="POST" onsubmit="return validarFormulario()">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="text" name="telefono" placeholder="Telefono" required>
        <input type="text" name="habitacion" placeholder="Habitacion" required>
        <input type="date" name="fecha_entrada" required>
        <input type="date" name="fecha_salida" required>
        <input type="number" name="precio" placeholder="Precio en COP" required>
        <button type="submit">Cargar</button>
    </form>

    <h2>Reservaciones Existentes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Telefono</th>
            <th>Habitacion</th>
            <th>Fecha de Entrada</th>
            <th>Fecha de Salida</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php include 'read.php'; ?>
    </table>
</div>
<script src="js/script.js"></script>
</body>
</html>