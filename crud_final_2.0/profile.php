<?php
session_start();
require_once 'crud/db.php';

// Comprobar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: main.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT username, email, created_at, password FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$success = $error = '';
// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new      = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($new !== $confirm) {
        $error = 'La nueva contraseña y su confirmación no coinciden.';
    } elseif (md5($current) !== $user['password']) {
        $error = 'La contraseña actual es incorrecta.';
    } else {
        $hashed = md5($new);
        $upd = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $upd->bind_param('si', $hashed, $user_id);
        if ($upd->execute()) {
            $success = '¡Contraseña actualizada correctamente!';
        } else {
            $error = 'Error al actualizar la contraseña.';
        }
        $upd->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .profile-container{max-width:400px;margin:40px auto;padding:20px;border:1px solid #ccc;border-radius:8px;}
        .profile-container h2{text-align:center;margin-bottom:20px;}
        .profile-container label{display:block;margin-top:10px;}
        .profile-container input{width:100%;padding:8px;margin-top:4px;}
        .msg{margin-top:15px;text-align:center;}
        .success{color:green;}
        .error{color:red;}
    </style>
</head>
<body>
<div class="profile-container">
    <h2>Mi Perfil</h2>
    <p><strong>Usuario:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Fecha de registro:</strong> <?= htmlspecialchars($user['created_at']) ?></p>

    <hr>
    <h3>Cambiar contraseña</h3>
    <?php if ($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <label>Contraseña actual</label>
        <input type="password" name="current_password" required>

        <label>Nueva contraseña</label>
        <input type="password" name="new_password" required>

        <label>Confirmar nueva contraseña</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" style="margin-top:15px;width:100%;">Actualizar contraseña</button>
    </form>
</div>
</body>
</html>
<hr>
<a href="main.php" style="display:inline-block;margin-top:15px;background:#333;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;">Volver a la página principal</a>