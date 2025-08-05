//JAVASCRIPT

// Validar el formulario antes de enviar
function validarFormulario() {
    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const cantidad = document.querySelector('input[name="cantidad"]').value.trim();
    const fechaEntrada = document.querySelector('input[name="fecha_entrada"]').value;
    const fechaSalida = document.querySelector('input[name="fecha_salida"]').value;
    const serie = document.querySelector('input[name="serie"]').value.trim();
    const precio = document.querySelector('input[name="precio"]').value.trim();
    const observaciones = document.querySelector('input[name="observaciones"]').value.trim();
    if (!nombre || !cantidad || !fechaEntrada|| !fechaSalida || !serie || !precio || !observaciones) {
        alert("Por favor, completa todos los campos.");
        return false;
    }
    if (isNaN(precio) || precio <= 0) {
        alert("El precio debe ser un número positivo.");
        return false;
    }
    if (new Date(fechaEntrada) >= new Date(fechaSalida)) {
        alert("La fecha de entrada debe ser anterior a la fecha de salida.");
        return false;
    }
    return true;
}

// Confirmación antes de eliminar una reservación
function eliminarinventario(id) {
    if (confirm("¿Estás seguro de que deseas eliminar esta elemento?")) {
        window.location.href = `delete.php?delete_id=${id}`;
    }
}

// Mostrar el formulario de actualización con los datos cargados
function mostrarFormularioActualizar(id, nombre, serie, cantidad, fecha_entrada, fecha_salida, precio, observaciones) {
    const formActualizar = document.createElement('div');
    formActualizar.innerHTML = `
        <form action="update.php" method="POST">
            <input type="hidden" name="update_id" value="${id}">
            <input type="text" name="update_nombre" value="${nombre}" required>
            <input type="text" name="update_serie" value="${serie}" required>
            <input type="text" name="update_cantidad" value="${cantidad}" required>
            <input type="date" name="update_fecha_entrada" value="${fecha_entrada}" required>
            <input type="date" name="update_fecha_salida" value="${fecha_salida}" required>
            <input type="number" name="update_precio" value="${precio}" required>
            <input type="text" name="update_observaciones" value="${observaciones}" required>
            <button type="submit" name="update">Actualizar</button>
            <button type="button" onclick="cerrarFormularioActualizar()">Cancelar</button>
        </form>
    `;
    document.body.appendChild(formActualizar);
}
// Cerrar el formulario de actualización
function cerrarFormularioActualizar() {
    const formActualizar = document.querySelector('form[action="update.php"]');
    if (formActualizar) {
        formActualizar.remove();
    }
}