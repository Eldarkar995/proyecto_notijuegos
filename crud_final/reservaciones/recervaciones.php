<?php
require 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "ID inválido.";
    exit;
}

$sql = "SELECT * FROM reservaciones WHERE id = $id";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) === 1) {
    $reserva = mysqli_fetch_assoc($resultado);

    $entrada = new DateTime($reserva['fecha_entrada']);
    $salida = new DateTime($reserva['fecha_salida']);
    $dias = $entrada->diff($salida)->days;
    $dias = max($dias, 1); // Siempre al menos una noche

    $total = $reserva['precio'] * $dias;
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resumen de Reserva</title>
        <link rel="stylesheet" href="../styles.css">
        <style>
            .resumen {
                max-width: 600px;
                margin: 50px auto;
                background-color: #fffdf8;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            .resumen h2 {
                text-align: center;
                color: #5c4438;
                margin-bottom: 1.5rem;
            }
            .resumen p {
                margin: 0.5rem 0;
                font-size: 1.1rem;
            }
            .resumen strong {
                color: #3c2f2f;
            }
        </style>
    </head>
    <body>
        <main class="resumen">
            <h2>Resumen de tu Reservación</h2>
            <p><strong>Nombre:</strong> <?= $reserva['nombre'] . ' ' . $reserva['apellido'] ?></p>
            <p><strong>Habitación:</strong> <?= $reserva['habitacion'] ?></p>
            <p><strong>Precio por noche:</strong> $<?= number_format($reserva['precio'], 0, ',', '.') ?></p>
            <p><strong>Fecha de entrada:</strong> <?= $reserva['fecha_entrada'] ?></p>
            <p><strong>Fecha de salida:</strong> <?= $reserva['fecha_salida'] ?></p>
            <p><strong>Noches:</strong> <?= $dias ?></p>
            <p><strong>Total a pagar:</strong> $<?= number_format($total, 0, ',', '.') ?></p>
        </main>
    </body>
    </html>
    <?php
} else {
    echo "Reservacion no encontrada.";
}

mysqli_close($conn);
?>
