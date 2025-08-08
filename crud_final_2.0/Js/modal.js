
// Scroll suave
document.querySelectorAll('nav ul li a[href^="#"]').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        if (!targetSection) return; // seguridad
        window.scrollTo({
            top: targetSection.offsetTop - 60,
            behavior: 'smooth'
        });
    });
});

// Modal de login/registro
const modal = document.getElementById('login-modal');
const openModalBtn = document.getElementById('open-login-modal');
const closeModalBtn = document.querySelector('.close-modal');
const loginTab = document.getElementById('login-tab');
const registerTab = document.getElementById('register-tab');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

// Abrir modal
if (openModalBtn) {
    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });
}

// Cerrar modal
if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Cambiar entre pestañas
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

// Función para mostrar la pestaña de login
function showLoginTab() {
    if (loginTab && registerTab) {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
    }
}

// Validación del formulario de login
const loginFormEl = document.querySelector('#login-form form');
const loginErrorDiv = document.querySelector('#login-form .error-message');

// El formulario de login se enviará normalmente

// Validación del formulario de registro
const registerFormEl = document.querySelector('#register-form form');
const registerErrorDiv = document.querySelector('#register-form .error-message');

registerFormEl.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const password = this.querySelector('input[name="reg-password"]').value;
    const confirmPassword = this.querySelector('input[name="reg-confirm-password"]').value;
    
    if (password !== confirmPassword) {
        registerErrorDiv.style.display = 'block';
        registerErrorDiv.textContent = 'Las contraseñas no coinciden';
        return;
    }
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('main.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.text();
        
        if (data.includes('register_success')) {
            registerErrorDiv.style.display = 'block';
            registerErrorDiv.style.color = '#5cb85c';
            registerErrorDiv.textContent = 'Registro exitoso. Ahora puedes iniciar sesión.';
            setTimeout(() => {
                showLoginTab();
                registerErrorDiv.style.display = 'none';
            }, 2000);
        } else if (data.includes('register_error')) {
            registerErrorDiv.style.display = 'block';
            registerErrorDiv.textContent = 'Error en el registro. Por favor, intenta con otros datos.';
        }
    } catch (error) {
        registerErrorDiv.style.display = 'block';
        registerErrorDiv.textContent = 'Error al procesar la solicitud';
    }
});