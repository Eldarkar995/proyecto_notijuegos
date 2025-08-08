<?php
session_start();
require_once 'crud/db.php';

// Verificar si el usuario acaba de iniciar sesión
$just_logged_in = false;
if (isset($_SESSION['just_logged_in']) && $_SESSION['just_logged_in'] === true) {
    $just_logged_in = true;
    $_SESSION['just_logged_in'] = false; // Resetear la bandera
}

// Procesar el formulario de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login-submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validar campos
    if (empty($username) || empty($password)) {
        $login_error = "Por favor, complete todos los campos.";
    } else {
        // Consultar la base de datos
        $sql = "SELECT id, username, password, is_admin FROM usuarios WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verificar la contraseña
            if (md5($password) === $user['password']) {
                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['just_logged_in'] = true; // Marcar que acaba de iniciar sesión
                
                // Recargar la página para mostrar el menú de usuario
                header("Location: main.php");
                exit();
            } else {
                $login_error = "Contraseña incorrecta.";
            }
        } else {
            $login_error = "Usuario no encontrado.";
        }
        
        $stmt->close();
    }
}

// Procesar el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register-submit'])) {
    $username = trim($_POST['reg-username']);
    $password = $_POST['reg-password'];
    $confirm_password = $_POST['reg-confirm-password'];
    $email = trim($_POST['reg-email']);
    
    // Validar campos
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $register_error = "Por favor, complete todos los campos.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Las contraseñas no coinciden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Por favor, ingrese un correo electrónico válido.";
    } else {
        // Verificar si el usuario ya existe
        $check_sql = "SELECT id FROM usuarios WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $register_error = "El nombre de usuario o correo electrónico ya está en uso.";
        } else {
            // Encriptar la contraseña
            $hashed_password = md5($password);
            
            // Insertar nuevo usuario
            $insert_sql = "INSERT INTO usuarios (username, password, email, created_at) VALUES (?, ?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sss", $username, $hashed_password, $email);
            
            if ($insert_stmt->execute()) {
                // Registro exitoso
                $register_success = "Registro exitoso. Ahora puedes iniciar sesión.";
                // Mostrar el formulario de login después del registro exitoso
                echo "<script>setTimeout(function() { showLoginTab(); }, 2000);</script>";
            } else {
                $register_error = "Error al registrar: " . $conn->error;
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Landing page elegante con fondo fijo y desplazamiento interactivo." />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <title>Hotel La Elegancia | Luxury Experience</title>
  <style>
    :root {
      --primary: #9c7c52;
      --primary-dark: #7a623f;
      --secondary: #2a2118;
      --light: #f5f0e8;
      --dark: #16120d;
      --text: #333333;
      --white: #ffffff;
      --gray: #d1d1d1;
      --transition: all 0.3s ease;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --radius: 8px;
      --menu-bg: rgba(42, 33, 24, 0.98);
      --menu-text: #f5f0e8;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      color: var(--text);
      line-height: 1.6;
      background-color: var(--light);
      overflow-x: hidden;
    }

    h1, h2, h3, h4 {
      font-family: 'Playfair Display', serif;
      font-weight: 600;
      color: var(--secondary);
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: var(--transition);
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
      background-color: #f5f0e8;
      position: relative;
    }

    img::after {
      content: "Imagen no disponible";
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: var(--primary);
      font-size: 14px;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Header */
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background: var(--menu-bg);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
      padding: 12px 0;
      backdrop-filter: blur(5px);
    }

    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--primary);
      letter-spacing: 0.5px;
    }

    .logo span {
      color: var(--menu-text);
    }

    nav ul {
      display: flex;
      list-style: none;
      align-items: center;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      font-weight: 400;
      font-size: 0.85rem;
      color: var(--menu-text);
      letter-spacing: 0.5px;
      position: relative;
      padding: 5px 0;
      text-transform: uppercase;
    }

    nav ul li a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 1px;
      background: var(--primary);
      transition: var(--transition);
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    .user-menu {
      position: relative;
    }

    .user-menu-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      color: var(--menu-text);
      font-size: 0.85rem;
      text-transform: uppercase;
    }

    .user-menu-content {
      position: absolute;
      top: 100%;
      right: 0;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      min-width: 160px;
      padding: 8px 0;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition);
      transform: translateY(10px);
    }

    .user-menu:hover .user-menu-content {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .user-menu-content a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 15px;
      font-size: 0.8rem;
      color: var(--secondary);
    }

    .user-menu-content a:hover {
      background: rgba(0, 0, 0, 0.05);
      color: var(--primary);
    }

    .login-link {
      padding: 6px 15px;
      border: 1px solid var(--primary);
      border-radius: 50px;
      font-weight: 500;
      color: var(--menu-text);
      font-size: 0.85rem;
      text-transform: uppercase;
    }

    .login-link:hover {
      background: var(--primary);
      color: var(--white);
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      min-height: 800px;
      position: relative;
      display: flex;
      align-items: center;
      color: var(--white);
      margin-top: -80px;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7));
      z-index: 1;
    }

    .hero-video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hero-background {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      padding: 0 20px;
    }

    .hero h1 {
      font-size: 3.5rem;
      margin-bottom: 20px;
      line-height: 1.2;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    }

    .hero p {
      font-size: 1.1rem;
      margin-bottom: 30px;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
      max-width: 600px;
    }

    .btn {
      display: inline-block;
      padding: 12px 25px;
      background: var(--primary);
      color: var(--white);
      border-radius: 50px;
      font-weight: 500;
      transition: var(--transition);
      margin-right: 10px;
    }

    .btn:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    .btn-outline {
      background: transparent;
      border: 2px solid var(--white);
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Galería - NUEVO ESTILO MEJORADO */
    .gallery {
      padding: 80px 0;
    }

    .section-title {
      text-align: center;
      margin-bottom: 40px;
    }

    .section-title h2 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .section-title p {
      color: var(--primary);
      font-size: 1.1rem;
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
    }

    .gallery-item {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      height: 350px;
      box-shadow: var(--shadow);
      transition: transform 0.5s ease;
    }

    .gallery-item:hover {
      transform: translateY(-5px);
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      transition: transform 0.5s ease;
    }

    .gallery-item:hover img {
      transform: scale(1.05);
    }

    .gallery-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: var(--transition);
    }

    .gallery-item:hover .gallery-overlay {
      opacity: 1;
    }

    .gallery-overlay i {
      color: white;
      font-size: 2.5rem;
      transition: var(--transition);
    }

    .gallery-overlay:hover i {
      transform: scale(1.2);
    }

    /* Habitaciones */
    .rooms {
      padding: 80px 0;
      background: rgba(245, 240, 232, 0.5);
    }

    .rooms-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
    }

    .room-card {
      background: var(--white);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .room-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .room-img {
      height: 280px;
      overflow: hidden;
    }

    .room-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .room-card:hover .room-img img {
      transform: scale(1.1);
    }

    .room-content {
      padding: 25px;
    }

    .room-content h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .room-price {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .room-price span {
      font-size: 1rem;
      color: var(--text);
      font-weight: 400;
    }

    .room-features {
      display: flex;
      gap: 15px;
      margin-bottom: 15px;
      flex-wrap: wrap;
    }

    .room-feature {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.9rem;
      color: var(--primary);
    }

    .room-feature i {
      font-size: 1rem;
    }

    .room-content p {
      margin-bottom: 20px;
      font-size: 0.95rem;
    }

    /* Media Queries */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero p {
        font-size: 1rem;
      }
      
      .rooms-grid {
        grid-template-columns: 1fr;
      }
      
      .gallery-grid {
        grid-template-columns: 1fr 1fr;
      }
      
      .gallery-item {
        height: 250px;
      }
    }

    @media (max-width: 480px) {
      .hero h1 {
        font-size: 2rem;
      }
      
      .gallery-grid {
        grid-template-columns: 1fr;
      }
      
      .gallery-item {
        height: 300px;
      }
      
      .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="container header-container">
      <a href="index.php" class="logo">HOTEL <span>LA ELEGANCIA</span></a>
      <nav>
        <ul>
          <li><a href="#inicio">INICIO</a></li>
          <li><a href="#habitaciones">HABITACIONES</a></li>
          <li><a href="#servicios">SERVICIOS</a></li>
          <li><a href="#galeria">GALERÍA</a></li>
          <li><a href="#contacto">CONTACTO</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li>
              <div class="user-menu">
                <div class="user-menu-btn">
                  <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="user-menu-content">
                  <a href="perfil.php"><i class="fas fa-user"></i> PERFIL</a>
                  <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="admin.php"><i class="fas fa-cog"></i> ADMIN</a>
                  <?php endif; ?>
                  <a href="logout.php"><i class="fas fa-sign-out-alt"></i> SALIR</a>
                </div>
              </div>
            </li>
          <?php else: ?>
            <li><a href="#" class="login-link" id="open-login-modal">INGRESAR</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero" id="inicio">
    <?php if (file_exists('videos/hotel-video.mp4')): ?>
      <video autoplay muted loop class="hero-video">
        <source src="videos/hotel-video.mp4" type="video/mp4">
        <img src="<?php echo file_exists('images/hero-background.webp') ? 'images/hero-background.webp' : 'https://source.unsplash.com/random/1920x1080/?hotel,luxury'; ?>" alt="Hotel La Elegancia">
      </video>
    <?php else: ?>
      <div class="hero-background" style="background-image: url('<?php echo file_exists('images/hero-background.webp') ? 'images/hero-background.webp' : 'https://source.unsplash.com/random/1920x1080/?hotel,luxury'; ?>')"></div>
    <?php endif; ?>
    <div class="container">
      <div class="hero-content">
        <h1>EXPERIENCIA DE LUJO Y CONFORT</h1>
        <p>Descubre el encanto único de nuestro hotel boutique, donde cada detalle está diseñado para tu comodidad y placer.</p>
        <div class="hero-btns">
          <a href="#habitaciones" class="btn">VER HABITACIONES</a>
          <a href="#contacto" class="btn btn-outline">CONTACTAR</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Galería MEJORADA -->
  <section class="gallery" id="galeria">
    <div class="container">
      <div class="section-title">
        <h2>Galería</h2>
        <p>Un vistazo a nuestras exclusivas instalaciones</p>
      </div>
      <div class="gallery-grid">
        <!-- Lobby de lujo -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Lobby de lujo del hotel" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
        
        <!-- Piscina infinity -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1530521954074-e64f6810b32d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Piscina infinity con vista panorámica" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
        
        <!-- Restaurante gourmet -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Restaurante gourmet" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
        
        <!-- Bar elegante -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Bar elegante con iluminación cálida" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
        
        <!-- Spa de lujo -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Spa de lujo con tratamientos exclusivos" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
        
        <!-- Terraza con vista -->
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" 
               alt="Terraza con vista al atardecer" loading="lazy">
          <div class="gallery-overlay">
            <i class="fas fa-search-plus"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Habitaciones -->
  <section class="rooms" id="habitaciones">
    <div class="container">
      <div class="section-title">
        <h2>Nuestras Habitaciones</h2>
        <p>Espacios diseñados para tu máximo confort</p>
      </div>
      <div class="rooms-grid">
        <!-- Habitación Estándar -->
        <div class="room-card">
          <div class="room-img">
            <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                 alt="Habitación Estándar" loading="lazy">
          </div>
          <div class="room-content">
            <h3>Habitación Estándar</h3>
            <div class="room-price">$120 <span>/ noche</span></div>
            <div class="room-features">
              <div class="room-feature">
                <i class="fas fa-user"></i> 2 Personas
              </div>
              <div class="room-feature">
                <i class="fas fa-expand"></i> 28 m²
              </div>
              <div class="room-feature">
                <i class="fas fa-wifi"></i> WiFi gratis
              </div>
            </div>
            <p>Tu refugio perfecto con cama queen, diseño moderno y detalles que marcan la diferencia. Ideal para viajeros que buscan comodidad sin complicaciones.</p>
            <a href="crud/reservaciones/index.php" class="btn" style="background-color: #9c7c52; color: white; border: none; padding: 12px 25px; font-weight: 600; display: inline-block; margin-top: 10px;">RESERVAR AHORA</a>
          </div>
        </div>
        
        <!-- Habitación Deluxe -->
        <div class="room-card">
          <div class="room-img">
            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                 alt="Habitación Deluxe" loading="lazy">
          </div>
          <div class="room-content">
            <h3>Habitación Deluxe</h3>
            <div class="room-price">$180 <span>/ noche</span></div>
            <div class="room-features">
              <div class="room-feature">
                <i class="fas fa-user"></i> 2 Personas
              </div>
              <div class="room-feature">
                <i class="fas fa-expand"></i> 35 m²
              </div>
              <div class="room-feature">
                <i class="fas fa-wifi"></i> WiFi gratis
              </div>
            </div>
            <p>Amplia habitación con vista a la ciudad, cama king size y todas las comodidades para una estancia placentera. Incluye minibar y acceso premium a nuestro spa.</p>
            <a href="crud/reservaciones/index.php" class="btn" style="background-color: #9c7c52; color: white; border: none; padding: 12px 25px; font-weight: 600; display: inline-block; margin-top: 10px;">RESERVAR AHORA</a>
          </div>
        </div>
        
        <!-- Suite Presidencial -->
        <div class="room-card">
          <div class="room-img">
            <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" 
                 alt="Suite Presidencial" loading="lazy">
          </div>
          <div class="room-content">
            <h3>Suite Presidencial</h3>
            <div class="room-price">$450 <span>/ noche</span></div>
            <div class="room-features">
              <div class="room-feature">
                <i class="fas fa-user"></i> 4 Personas
              </div>
              <div class="room-feature">
                <i class="fas fa-expand"></i> 80 m²
              </div>
              <div class="room-feature">
                <i class="fas fa-wifi"></i> WiFi gratis
              </div>
            </div>
            <p>La máxima expresión de lujo con terraza privada, jacuzzi y servicio de mayordomo las 24 horas. Experiencia VIP con detalles exclusivos que te harán sentir como royalty.</p>
            <a href="crud/reservaciones/index.php" class="btn" style="background-color: #9c7c52; color: white; border: none; padding: 12px 25px; font-weight: 600; display: inline-block; margin-top: 10px;">RESERVAR AHORA</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Servicios -->
  <section class="services" id="servicios">
    <div class="container">
      <div class="section-title">
        <h2>Nuestros Servicios</h2>
        <p>Experiencias exclusivas para nuestros huéspedes</p>
      </div>
      <div class="services-grid">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-utensils"></i>
          </div>
          <h3>Restaurante Gourmet</h3>
          <p>Cocina de autor con ingredientes locales seleccionados por nuestro chef estrella Michelin.</p>
        </div>
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-spa"></i>
          </div>
          <h3>Spa de Lujo</h3>
          <p>Tratamientos personalizados con productos orgánicos en un ambiente de relajación total.</p>
        </div>
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-swimming-pool"></i>
          </div>
          <h3>Piscina Infinity</h3>
          <p>Disfruta de nuestra piscina con vista panorámica a la ciudad y servicio de cócteles.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contacto -->
  <section class="contact" id="contacto">
    <div class="container">
      <div class="section-title">
        <h2>Contacto</h2>
        <p>Estamos aquí para atenderte</p>
      </div>
      <div class="contact-grid">
        <div class="contact-info">
          <h3>Información de Contacto</h3>
          <p><i class="fas fa-map-marker-alt"></i> Av. Elegante 123, Ciudad Luxe</p>
          <p><i class="fas fa-phone"></i> +1 234 567 890</p>
          <p><i class="fas fa-envelope"></i> reservas@laelegancia.com</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
          </div>
        </div>
        <div class="contact-form">
          <form action="#" method="POST">
            <div class="form-group">
              <input type="text" name="name" placeholder="Nombre completo" required>
            </div>
            <div class="form-group">
              <input type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="form-group">
              <textarea name="message" placeholder="Tu mensaje" required></textarea>
            </div>
            <button type="submit" class="btn">Enviar Mensaje</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-logo">
          <a href="#" class="logo">HOTEL <span>LA ELEGANCIA</span></a>
          <p>El arte de la hospitalidad en su máxima expresión.</p>
        </div>
        <div class="footer-links">
          <h3>Enlaces Rápidos</h3>
          <ul>
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#habitaciones">Habitaciones</a></li>
            <li><a href="#servicios">Servicios</a></li>
            <li><a href="#galeria">Galería</a></li>
            <li><a href="#contacto">Contacto</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h3>Newsletter</h3>
          <p>Suscríbete para recibir ofertas exclusivas.</p>
          <form>
            <input type="email" placeholder="Tu correo electrónico">
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Hotel La Elegancia. Todos los derechos reservados.</p>
      </div>
    </div>
 
  <!-- Estilos adicionales para las nuevas secciones -->
  <style>
    .services {
      padding: 80px 0;
      background: var(--white);
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .service-card {
      text-align: center;
      padding: 30px;
      border-radius: var(--radius);
      background: var(--light);
      transition: var(--transition);
    }

    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow);
    }

    .service-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: var(--primary);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
    }

    .service-card h3 {
      margin-bottom: 15px;
      font-size: 1.3rem;
    }

    .contact {
      padding: 80px 0;
      background: var(--light);
    }

    .contact-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
    }

    .contact-info {
      padding: 30px;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .contact-info h3 {
      margin-bottom: 20px;
      font-size: 1.5rem;
    }

    .contact-info p {
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 25px;
    }

    .social-links a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary);
      color: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }

    .social-links a:hover {
      background: var(--primary-dark);
      transform: translateY(-3px);
    }

    .contact-form {
      padding: 30px;
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--gray);
      border-radius: var(--radius);
      font-family: 'Montserrat', sans-serif;
    }

    .form-group textarea {
      height: 150px;
      resize: vertical;
    }

    footer {
      background: var(--secondary);
      color: var(--white);
      padding: 60px 0 20px;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-logo .logo {
      color: var(--primary);
      font-size: 1.5rem;
    }

    .footer-logo .logo span {
      color: var(--white);
    }

    .footer-logo p {
      margin-top: 15px;
      color: var(--gray);
    }

    .footer-links h3,
    .footer-newsletter h3 {
      font-size: 1.2rem;
      margin-bottom: 20px;
      color: var(--primary);
    }

    .footer-links ul {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: var(--gray);
      transition: var(--transition);
    }

    .footer-links a:hover {
      color: var(--primary);
      padding-left: 5px;
    }

    .footer-newsletter p {
      color: var(--gray);
      margin-bottom: 20px;
    }

    .footer-newsletter form {
      display: flex;
    }

    .footer-newsletter input {
      flex: 1;
      padding: 10px 15px;
      border: none;
      border-radius: 50px 0 0 50px;
    }

    .footer-newsletter button {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 0 20px;
      border-radius: 0 50px 50px 0;
      cursor: pointer;
      transition: var(--transition);
    }

    .footer-newsletter button:hover {
      background: var(--primary-dark);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: var(--gray);
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .contact-grid {
        grid-template-columns: 1fr;
      }
      
      .services-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
  <!-- Script para la ventana modal de login/registro -->
  <script>
    // Obtener elementos del DOM
    const modal = document.getElementById('login-modal');
    const openModalBtn = document.getElementById('open-login-modal');
    const closeModalBtn = document.querySelector('.close-modal');
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    // Abrir modal al hacer clic en el botón de iniciar sesión
    if (openModalBtn) {
      openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
      });
    }
    
    // Cerrar modal al hacer clic en la X
    if (closeModalBtn) {
      closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
    
    // Cambiar entre pestañas de login y registro
    if (loginTab && registerTab) {
      loginTab.addEventListener('click', function() {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
      });
      
      registerTab.addEventListener('click', function() {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
      });
    }
    
    // Función para mostrar la pestaña de login (usada después del registro exitoso)
    function showLoginTab() {
      if (loginTab && registerTab) {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
      }
    }
  </script>
  
  <?php if ($just_logged_in): ?>
  <script>
    // Mostrar un mensaje de bienvenida si el usuario acaba de iniciar sesión
    document.addEventListener('DOMContentLoaded', function() {
      // Cerrar el modal de login si está abierto
      const loginModal = document.getElementById('login-modal');
      if (loginModal) {
        loginModal.style.display = 'none';
      }
      
      // Mostrar mensaje de bienvenida
      alert('¡Bienvenido, <?php echo htmlspecialchars($_SESSION["username"]); ?>!');
    });
  </script>
  <?php endif; ?>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const carrusel = document.querySelector('.carrusel');
    const btnPrev = document.querySelector('.carrusel-btn.prev');
    const btnNext = document.querySelector('.carrusel-btn.next');

    if (btnPrev && btnNext && carrusel) {
      btnPrev.addEventListener('click', () => {
        carrusel.scrollBy({ left: -320, behavior: 'smooth' });
      });

      btnNext.addEventListener('click', () => {
        carrusel.scrollBy({ left: 320, behavior: 'smooth' });
      });
    } else {
      console.warn('No se encontró el carrusel o los botones');
    }
  });
</script>
<script>
  
</script>
</body>
</html>