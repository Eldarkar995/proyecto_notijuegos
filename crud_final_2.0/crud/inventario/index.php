<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD del inventario</title>
    <link rel="stylesheet" href="crud/inventario/styles.css">
</head>
<body>
    <a href="../administrador.html" class="btn-inicio">Inicio</a>
    <div class="contenedor-tabla">
        <h2>Crear Nuevo inventario</h2>
        <form action="create.php" method="POST" onsubmit="return validarFormulario()">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" name="cantidad" placeholder="cantidad" required>
            <input type="date" name="fecha_entrada" required>
            <input type="date" name="fecha_salida" required>
            <input type="text" name = "seri" placeholder="Serial" required>
            <input type="number" name="precio" placeholder="Precio en COP" required>
            <input type="text" name="observaciones" placeholder= "observacion" required>
            <button type="submit">Cargar</button>
        </form>
        <h2>inventarios Existentes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>cantidad</th>
                <th>Fechade Entrada</th>
                <th>Fechade Salida</th>
                <th>serial</th>
                <th>Precio</th>
                <th>observaciones</th>
                <th>Acciones</th>
            </tr>
            <?php include 'read.php'; ?>
        </table>
    </div>
    <script src="js/script.js"></script>
</body>
</html>