<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Reservaciones</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="container">
    <h2>Crear Nueva Reservación</h2>
    <form action="create.php" method="POST" onsubmit="return validarFormulario()">

        <label for="nombre">Nombre</label>
         <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
        <label for="tipo_documento">Tipo de Doc</label>
         <input type="text" id="tip_doc" name="tipo_doc" placeholder="CC, Pasaporte..." required>
        <label for="documento">Núm de Doc</label>
         <input type="number" id="num_doc" name="num_doc" placeholder="Número de documento" required>
        <label for="correo">Correo electrónico</label>
         <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
        <label for="direccion">Dirección</label>
         <input type="text" id="direccion" name="direccion" placeholder="Dirección de residencia" required>
        <label for="telefono">Teléfono</label>
         <input type="tel" id="telefono" name="telefono" placeholder="Número de contacto" required>
        <label for="ciudad">Ciudad</label>
         <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad de residencia" required>
        <label for="fecha_ingreso">Fecha de ingreso</label>
         <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
        <label for="fecha_salida">Fecha de salida</label>
         <input type="date" id="fecha_salida" name="fecha_salida" required>
        <label for="valor_noche">Valor por noche</label>
         <input type="number" id="valor_noche" name="valor_noche" placeholder="Precio por noche" required>
        <label for="concepto">Concepto / Experiencia</label>
         <input type="text" id="concepto" name="concepto" placeholder="Descripción de la experiencia" required>
        <label for="total">Valor total</label>
         <input type="number" id="total" name="total" placeholder="Valor total" required>
        <button type="submit">Guardar Reservación</button>
    </form>

    <h2>Reservaciones Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Correo</th>
                <th>Ciudad</th>
                <th>Ingreso</th>
                <th>Salida</th>
                <th>Valor total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php include 'read.php'; ?>
        </tbody>
    </table>
</div>
<script src="js/script.js"></script>
</body>
</html>
