<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD del inventario</title>
    <link rel="stylesheet" href="crud/inventario/styles.css">
</head>
<style>
    /* Importación de fuentes */


/* Reset general */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Fondo y fuente */
body {
  font-family: 'Dancing Script', cursive;
  background-color: #fffdeb;
  color: #3c2f2f;
  line-height: 1.7;
  scroll-behavior: smooth;
}

/* Fondo decorativo con imagen sutil */
body::before {
  content: "";
  background: url('https://www.google.com/imgres?q=hotel%204k%20fondo&imgurl=https%3A%2F%2Fi.pinimg.com%2F736x%2Fff%2F54%2F89%2Fff5489f401d008f38a1eb13960075380.jpg&imgrefurl=https%3A%2F%2Fes.pinterest.com%2Fpin%2Fdownload-wallpapers-interior-of-hotel-room-modern-design-brown-tone-hotel-room-room-for-three-modern-interior-4k-for-desktop-free-pictures-for-desktop-fr--753930793842020652%2F&docid=mYMyMjcc2htHtM&tbnid=boXBH9GUnVXiWM&vet=12ahUKEwjX-dzluPKOAxXlQjABHWw4KMIQM3oECCYQAA..i&w=710&h=444&hcb=2&ved=2ahUKEwjX-dzluPKOAxXlQjABHWw4KMIQM3oECCYQAA') no-repeat center center/cover;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0.12;
  z-index: -1;
}

/* Encabezado */
header {
  background-color: rgba(217, 107, 107, 0.9);
  color: white;
  padding: 1rem 2rem;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
  font-family: 'Dancing Script', cursive;
}

header h1 {
  font-size: 2rem;
  text-shadow: 2px 2px 5px #8a3c3c;
}

/* Botón login */
.boton-login {
  background-color: #ffdd00;
  border: none;
  border-radius: 20px;
  padding: 10px 20px;
  font-weight: bold;
  cursor: pointer;
  color: #333;
  transition: background-color 0.3s;
}

.boton-login:hover {
  background-color: #ffc107;
}

/* Navegación */
nav {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

nav a {
  text-decoration: none;
  color: #fff5e6;
  font-weight: bold;
  font-size: 1.3rem;
  position: relative;
}

nav a::after {
  content: '';
  position: absolute;
  width: 0%;
  height: 2px;
  left: 0;
  bottom: -4px;
  background-color: #fff;
  transition: width 0.3s ease;
}

nav a:hover {
  color: #ffe0b3;
}

nav a:hover::after {
  width: 100%;
}

/* Contenedor principal */
.container {
  background-color: #fffdeb;
  padding: 2rem;
  margin: 120px auto 2rem auto;
  width: 90%;
  max-width: 800px;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
  border-radius: 10px;
  border: 1px solid #ccc;
}

/* Títulos */
h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
  color: #d96b6b;
  text-align: center;
  border-bottom: 2px solid #d4b28e;
  padding-bottom: 0.5rem;
}

/* Botón inicio */
.btn-inicio {
  display: inline-block;
  background-color: #d96c6c;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
  margin: 20px;
  transition: background-color 0.3s ease;
}

.btn-inicio:hover {
  background-color: #c75b5b;
  background-color: #d65a00;
}

/* Formularios */
input, select, textarea {
  width: 100%;
  padding: 12px;
  margin: 8px 0;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 1em;
  background-color: #fffaf2;
  font-family: 'Dancing Script', cursive;
  box-sizing: border-box;
}

input[type="submit"], button {
  background-color: #d96b6b;
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1em;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

input[type="submit"]:hover, button:hover {
  background-color: #c65353;
}

/* Tablas */
table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-family: Arial, sans-serif;
}

th, td {
  padding: 12px 15px;
  text-align: center;
  border: 1px solid #ddd;
}

th {
  background-color: #c65353;
  color: white;
}

.contenedor-tabla {
  background-color: #fffdeb;
  padding: 40px 30px;
  margin: 30px auto;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
  max-width: 95%;
  border: 1px solid #ccc;
}

/* Botones en tablas */
button {
  padding: 6px 10px;
  margin: 2px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-family: 'Dancing Script', cursive;
  background-color: #8c6e5a;
  color: #fff;
}

button:hover {
  background-color: #a2836c;
}

/* Datos centrados */
.datos {
  text-align: center;
}

/* Footer */
footer {
  background-color: #5c4438;
  color: #fefefe;
  text-align: center;
  padding: 1rem;
  font-size: 0.9rem;
  margin-top: 4rem;
  border-top: 1px solid #3c2f2f;
  font-family: 'Dancing Script', cursive;
}
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Dancing+Script:wght@600&display=swap');
</style>
<body>
    <a href="crud_final_2.0/crud/admin.php" class="btn-inicio">Inicio</a>
    <div class="contenedor-tabla">
        <h2>Crear Nuevo inventario</h2>
        <form action="create.php" method="POST" onsubmit="return validarFormulario()">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" name="cantidad" placeholder="cantidad" required>
            <input type="date" name="fecha_entrada" required>
            <input type="date" name="fecha_salida" required>
            <input type="text" name = "seri" placeholder="Serial" required>
            <input type="number" name="precio" placeholder="Precio en COP" required>
            <input type="text" name="observaciones" placeholder= "observacion" required>
            <button type="submit">Cargar</button>
        </form>
        <h2>inventarios Existentes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>cantidad</th>
                <th>Fechade Entrada</th>
                <th>Fechade Salida</th>
                <th>serial</th>
                <th>Precio</th>
                <th>observaciones</th>
                <th>Acciones</th>
            </tr>
            <?php include 'read.php'; ?>
        </table>
    </div>
    <script src="crud/inventario/script.js"></script>
</body>
</html>