// Validar el formulario antes de enviar
function validarFormulario() {
    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const tipo_doc = document.querySelector('input[name="tipo_doc"]').value.trim();
    const num_doc = document.querySelector('input[name="num_doc"]').value.trim();
    const correo = document.querySelector('input[name="correo"]').value.trim();
    const direccion = document.querySelector('input[name="direccion"]').value.trim();
    const telefono = document.querySelector('input[name="telefono"]').value.trim();
    const ciudad = document.querySelector('input[name="ciudad"]').value.trim();
    const fechaEntrada = document.querySelector('input[name="fecha_ingreso"]').value;
    const fechaSalida = document.querySelector('input[name="fecha_salida"]').value;
    const concepto = document.querySelector('input[name="concepto"]').value.trim();
    const valor_noche = document.querySelector('input[name="valor_noche"]').value.trim();
    const total = document.querySelector('input[name="total"]').value.trim();

    if (!nombre || !tipo_doc || !num_doc || !correo || !direccion || !telefono || !ciudad || !concepto || !valor_noche || !total) {
        alert("Por favor, completa todos los campos.");
        return false;
    }

    if (!/^\S+@\S+\.\S+$/.test(correo)) {
        alert("Por favor, ingresa un correo electrónico válido.");
        return false;
    }

    if (isNaN(valor_noche) || parseFloat(valor_noche) <= 0) {
        alert("El valor por noche debe ser un número positivo.");
        return false;
    }

    if (new Date(fechaEntrada) >= new Date(fechaSalida)) {
        alert("La fecha de entrada debe ser anterior a la fecha de salida.");
        return false;
    }

    return true;
}

// Confirmación antes de eliminar una reservación
function eliminarInventario(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este elemento?")) {
        window.location.href = `delete.php?delete_id=${id}`;
    }
}

// Mostrar el formulario de actualización con los datos cargados
function mostrarFormularioActualizar(id, nombre, tipo_doc, num_doc, correo, direccion, telefono, ciudad, fecha_ingreso, fecha_salida, concepto, valor_noche, total) {
    cerrarFormularioActualizar(); // Cierra formularios abiertos

    const formActualizar = document.createElement('div');
    formActualizar.id = 'form-actualizar';

    formActualizar.innerHTML = `
        <form action="update.php" method="POST">
            <input type="hidden" name="update_id" value="${id}">
            <input type="text" name="nombre" value="${nombre}" required>
            <input type="text" name="tipo_doc" value="${tipo_doc}" required>
            <input type="text" name="num_doc" value="${num_doc}" required>
            <input type="email" name="correo" value="${correo}" required>
            <input type="text" name="direccion" value="${direccion}" required>
            <input type="text" name="telefono" value="${telefono}" required>
            <input type="text" name="ciudad" value="${ciudad}" required>
            <input type="date" name="fecha_ingreso" value="${fecha_ingreso}" required>
            <input type="date" name="fecha_salida" value="${fecha_salida}" required>
            <input type="text" name="concepto" value="${concepto}" required>
            <input type="number" step="0.01" name="valor_noche" value="${valor_noche}" required>
            <input type="number" step="0.01" name="total" value="${total}" required>
            <button type="submit">Actualizar</button>
            <button type="button" onclick="cerrarFormularioActualizar()">Cancelar</button>
        </form>
    `;

    document.body.appendChild(formActualizar);
}

// Cerrar el formulario de actualización
function cerrarFormularioActualizar() {
    const existingForm = document.getElementById('form-actualizar');
    if (existingForm) {
        existingForm.remove();
    }
}
