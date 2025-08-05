<?php
session_start();
require_once 'crud/db.php';

// Verificar si ya hay una sesión activa
if (isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    
    // Validar campos
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $error = "Por favor, complete todos los campos.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, ingrese un correo electrónico válido.";
    } else {
        // Verificar si el usuario ya existe
        $check_sql = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "El nombre de usuario o correo electrónico ya está en uso.";
        } else {
            // Encriptar la contraseña
            $hashed_password = md5($password);
            
            // Insertar nuevo usuario
            $insert_sql = "INSERT INTO usuarios (username, password, email, created_at) VALUES (?, ?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $username, $hashed_password, $email);
            
            if ($insert_stmt->execute()) {
                // Registro exitoso, redirigir al login
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
                header("Refresh: 2; URL=login.php");
            } else {
                $error = "Error al registrar: " . $conn->error;
            }
            
            $insert_stmt->close();
        }
        
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Explora la Elegancia</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .register-container {
            background-color: #ffffff;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }
        
        .register-header {
            text-align: center;
            color: #5c4438;
            margin-bottom: 20px;
            font-family: 'Georgia', serif;
        }
        
        .register-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .register-btn {
            width: 100%;
            padding: 10px;
            background-color: #8c6e5a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .register-btn:hover {
            background-color: #a2836c;
        }
        
        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        
        .login-link a {
            color: #5c4438;
            text-decoration: underline;
        }
        
        .error-message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .success-message {
            color: #5cb85c;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <span class="close-btn" onclick="window.location.href='index.html'">&times;</span>
        <h2 class="register-header">Registro</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form class="register-form" method="post" action="">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            <button type="submit" class="register-btn">Registrarse</button>
        </form>
        
        <div class="login-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
        </div>
    </div>
</body>
</html>