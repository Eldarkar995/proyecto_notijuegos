//JAVASCRIPT

// Validar el formulario antes de enviar
function validarFormulario() {
    const nombre = document.querySelector('input[name="nombre"]').value.trim();
    const apellido = document.querySelector('input[name="apellido"]').value.trim();
    const telefono = document.querySelector('input[name="telefono"]').value.trim();
    const correo = document.querySelector('input[name="correo"]').value.trim();
    const fechaEntrada = document.querySelector('input[name="fecha_entrada"]').value;
    const fechaSalida = document.querySelector('input[name="fecha_salida"]').value;
    const salario = document.querySelector('input[name="salario"]').value.trim();

    if (!nombre || !apellido || !telefono || !correo || !fechaEntrada || !fechaSalida || !salario) {
        alert("Por favor, completa todos los campos.");
        return false;
    }

    if (isNaN(salario) || salario <= 0) {
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
function eliminarReservacion(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
        window.location.href = `delete.php?delete_id=${id}`;
    }
}

// Mostrar el formulario de actualización con los datos cargados
function mostrarFormularioActualizar(id, nombre, apellido, telefono, correo, fecha_entrada, fecha_salida, salario) {
    const formActualizar = document.createElement('div');
    formActualizar.innerHTML = `
        <form action="update.php" method="POST">
            <input type="hidden" name="update_id" value="${id}">
            <input type="text" name="update_nombre" value="${nombre}" required>
            <input type="text" name="update_apellido" value="${apellido}" required>
            <input type="text" name="update_telefono" value="${telefono}" required>
            <input type="text" name="update_correo" value="${correo}" required>
            <input type="date" name="update_fecha_entrada" value="${fecha_entrada}" required>
            <input type="date" name="update_fecha_salida" value="${fecha_salida}" required>
            <input type="number" name="update_salario" value="${salario}" required>
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