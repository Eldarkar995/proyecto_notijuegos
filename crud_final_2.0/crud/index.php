<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Redirigir a la página principal si no es administrador
    header("Location: ../main.php");
    exit();
}

// Resto del código del panel de administración
?>