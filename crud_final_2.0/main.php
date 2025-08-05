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
  <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <title>Landing Page</title>
  <style>
    .login-link {
      margin-left: auto;
      padding: 8px 15px;
      background-color: #a2836c;
      color: white;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    
    .login-link:hover {
      background-color: #8c6e5a;
    }
    /* --- Estilo minimalista solo para el botón-usuario --- */
    .user-menu {
        position: relative;
        margin-left: auto;          /* lo empuja a la derecha sin mover el resto */
    }
    
    .user-menu-btn {
        background: #a2836c;
        color: #fff;
        padding: 6px 14px;
        border-radius: 10px;        /* rectángulo redondeado */
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
    }
    
    .user-menu-content {
        display: none;
        position: absolute;
        right: 0;
        top: 110%;
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 8px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        min-width: 160px;
        z-index: 1000;
    }
    
    .user-menu-content a {
        display: block;
        padding: 8px 14px;
        color: #5c4438;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .user-menu-content a:hover {
        background: #f5f5f5;
    }
    
    .user-menu:hover .user-menu-content {
        display: block;
    }
    /* --- Fin de estilos del usuario --- */
    
    
    .user-menu-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      border-radius: 4px;
      margin-top: 5px;
    }
    
    .user-menu-content a {
      color: #5c4438;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }
    
    .user-menu-content a:hover {
      background-color: #f1f1f1;
    }
    
    .user-menu:hover .user-menu-content {
      display: block;
    }
    
    nav {
      display: flex;
      align-items: center;
    }
    /* Estilos para la ventana modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    
    .modal-content {
      background-color: #ffffff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      width: 400px;
      position: relative;
      animation: modalopen 0.4s;
    }
    
    @keyframes modalopen {
      from {opacity: 0; transform: translateY(-60px);}
      to {opacity: 1; transform: translateY(0);}
    }
    
    .close-modal {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 24px;
      font-weight: bold;
      color: #999;
      cursor: pointer;
    }
    
    .close-modal:hover {
      color: #5c4438;
    }
    
    .modal-header {
      text-align: center;
      color: #5c4438;
      margin-bottom: 20px;
      font-family: 'Georgia', serif;
    }
    
    .modal-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    
    .modal-btn {
      width: 100%;
      padding: 10px;
      background-color: #8c6e5a;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }
    
    .modal-btn:hover {
      background-color: #a2836c;
    }
    
    .modal-footer {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    
    .modal-footer a {
      color: #5c4438;
      text-decoration: underline;
      cursor: pointer;
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
    
    .modal-tabs {
      display: flex;
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
    }
    
    .modal-tab {
      padding: 10px 20px;
      cursor: pointer;
      background-color: #f9f9f9;
      border-radius: 4px 4px 0 0;
      margin-right: 5px;
    }
    
    .modal-tab.active {
      background-color: #8c6e5a;
      color: white;
    }
    
    #register-form {
      display: none;
    }
  </style>
</head>
<body>
  <!-- Encabezado con navegación y logo -->
  <header>
    <div class="logo">
      <span>Explora la Elegancia</span>
    </div>
    <nav>
      <ul>
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#sobre-nosotros">Sobre Nosotros</a></li>
        <li><a href="#servicios">Servicios</a></li>
        <li><a href="#precios">Precios</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>
      <?php if (isset($_SESSION['user_id'])): ?>
          <div class="user-menu">
              <div class="user-menu-btn"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
              <div class="user-menu-content">
                  <a href="profile.php">Perfil</a>
                  <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                      <a href="admin.php">Administración</a>
                  <?php endif; ?>
                  <a href="logout.php">Cerrar Sesión</a>
              </div>
          </div>
      <?php else: ?>
          <span class="login-link" id="open-login-modal">Iniciar Sesión</span>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Ventana Modal para Login/Registro -->
  <div id="login-modal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      
      <div class="modal-tabs">
        <div class="modal-tab active" id="login-tab">Iniciar Sesión</div>
        <div class="modal-tab" id="register-tab">Registrarse</div>
      </div>
      
      <!-- Formulario de Login -->
      <div id="login-form">
        <h2 class="modal-header">Iniciar Sesión</h2>
        
        <?php if (isset($login_error)): ?>
          <div class="error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>
        
        <form class="modal-form" method="post" action="">
          <input type="text" name="username" placeholder="Usuario" required>
          <input type="password" name="password" placeholder="Contraseña" required>
          <button type="submit" name="login-submit" class="modal-btn">Entrar</button>
        </form>
      </div>
      
      <!-- Formulario de Registro -->
      <div id="register-form">
        <h2 class="modal-header">Registro</h2>
        
        <?php if (isset($register_error)): ?>
          <div class="error-message"><?php echo $register_error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($register_success)): ?>
          <div class="success-message"><?php echo $register_success; ?></div>
        <?php endif; ?>
        
        <form class="modal-form" method="post" action="">
          <input type="text" name="reg-username" placeholder="Usuario" required>
          <input type="email" name="reg-email" placeholder="Correo electrónico" required>
          <input type="password" name="reg-password" placeholder="Contraseña" required>
          <input type="password" name="reg-confirm-password" placeholder="Confirmar contraseña" required>
          <button type="submit" name="register-submit" class="modal-btn">Registrarse</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Contenido principal -->
  <main>
    <section id="inicio">
      <h2>Bienvenidos</h2>
      <img src="imagines/hotel por fuera.jpg">
      <p>Descubre una experiencia inolvidable, donde el confort, la elegancia y la innovación se fusionan para brindarte momentos únicos.</p>
    </section>

    <section id="sobre-nosotros">
      <h2>Sobre Nosotros</h2>
      <img src="imagines/familas.jpg">
      <p>Nos apasiona crear ambientes que inspiran. Diseñamos cada espacio con exclusividad, buen gusto y un enfoque en el detalle, ofreciendo un servicio cálido y personalizado.</p>
    </section>

    <section id="servicios">
      <h2>Servicios</h2>
      <ul>
        <li>Diseño y lujo en cada rincón.</li>
        <img src="imagines/hotel por fuera2.jpg">
        <li>Ambientes pensados para tu descanso y bienestar.</li>
        <img src="imagines/zona de descanso.jpg">
        <li>Atención personalizada las 24 horas, todos los días.</li>
        <img src="imagines/atencion al cliente.jpg">
      </ul>
    </section>

     <!-- Sección Galería -->
    <section id="galeria">
      <h2>Galería de Imágenes</h2>
      <div class="galeria-contenedor">
        <img src="imagines/habitacion1.jpg" alt="Habitación rústica con decoración elegante" />
        <img src="imagines/habitacion2.jpg" alt="Vista panorámica desde la suite" />
        <img src="imagines/spa.jpg" alt="Área de spa y relajación" />
        <img src="imagines/estaurante.jpg" alt="Restaurante gourmet del hotel" />
      </div>    
    </section>

  <section id="precios">
  <h2>Precios</h2>

  <table class="tabla-precios">
    <thead>
      <tr>
        <th>Imagen</th>
        <th>Tipo de Habitación</th>
        <th>Número de Personas</th>
        <th>Precio por Noche</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><img src="imagines/habitacion individual1.jpg" alt="Habitación Individual" class="img-precio" /></td>
        <td>Habitación Individual</td>
        <td>1</td>
        <td>$90.000</td>
      </tr>
      <tr>
        <td><img src="imagines/habitacion doble,jpg.webp" alt="Habitación Doble" class="img-precio" /></td>
        <td>Habitación Doble</td>
        <td>2</td>
        <td>$100.000</td>
      </tr>
      <tr>
        <td><img src="imagines/habitacion familiar.jpg" alt="Habitación Familiar" class="img-precio" /></td>
        <td>Habitación Familiar</td>
        <td>4</td>
        <td>$150.000</td>
      </tr>
      <tr>
        <td><img src="imagines/suite.jpg" alt="Suite de lujo" class="img-precio" /></td>
        <td>Suite</td>
        <td>2</td>
        <td>$300.000</td>
      </tr>
    </tbody>
  </table>
  <!-- Botón para ir al CRUD de reservaciones -->
  <a href="crud/reservaciones/index.php" class="btn-precio">Reservar ahora</a>
  </section>

   <section id="contacto">
  <div class="form-container">
    <h2>Contáctanos</h2>
    <form action="mailto:eldarkar995@gmail.com" method="post" enctype="text/plain">
      <input type="text" id="nombre" placeholder="Nombre" required>
      <input type="email" id="email" placeholder="Correo" required>
      <textarea placeholder="Mensaje"></textarea>
     <a href="crud/index.php" style="display: inline-block;">
        <button type="button">Enviar</button>
      </a>
    </form>
  </div>
</section>
  </main>

  <!-- Pie de página -->
  <footer>
    <p>&copy; 2025 Elegancia. Todos los derechos reservados.</p>
  </footer>

  <!-- Script de scroll suave -->
  <script>
    document.querySelectorAll('nav ul li a').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        window.scrollTo({
          top: targetSection.offsetTop - 60,
          behavior: 'smooth'
        });
      });
    });
  </script>

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
</body>
</html>