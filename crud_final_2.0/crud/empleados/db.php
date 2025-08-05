<?php
// Archivo: db.php
// Descripción: Este archivo se encarga de establecer la conexión con la base de datos MySQL.

// Configuración de la conexión
$host = 'localhost';            // Dirección del servidor de base de datos.
$user = 'root';                 // Usuario de la base de datos.
$password = '';                 // Contraseña del usuario.
$database = 'hotel_reservas';   // Nombre de la base de datos.

// Crear la conexión con la base de datos
$conn = mysqli_connect($host, $user, $password, $database);

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error()); // Corregido mysqli_conn a mysqli_connect_error
}

// Nota: No cerramos la conexión aquí, se cierra después de usarla en cada script.
?>