<?php
session_start();
// Solo administradores
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: main.php');
    exit();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        /* Paleta suave anaranjada */
        body{
            font-family:Arial,Helvetica,sans-serif;
            margin:0;
            background:linear-gradient(180deg,#fde6cf 0%,#fbd9b3 100%);
        }

        /* ---------- TOP BAR ---------- */
        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:18px 32px;
            background:#fff;
            box-shadow:0 2px 6px rgba(0,0,0,.15);
            border-radius:8px;
            max-width:1200px;
            margin:28px auto 20px;
        }
        .topbar h1{
            font-size:28px;
            color:#d65a00;
            margin:0;
            display:flex;
            align-items:center;
            gap:8px;
        }
        .topbar .btn{
            background:#fff;
            color:#d65a00;
            border:2px solid #d65a00;
            padding:8px 20px;
            border-radius:6px;
            font-weight:600;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:6px;
            transition:.25s;
        }
        .topbar .btn:hover{
            background:#d65a00;
            color:#fff;
        }

        /* ---------- GRID DE CARDS ---------- */
        .cards{
            max-width:1200px;
            margin:auto;
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:26px;
        }
        .card{
            background:#fff;
            border-radius:10px;
            padding:30px 26px 36px;
            box-shadow:0 4px 10px rgba(0,0,0,.12);
            text-align:center;
            transition:.2s;
        }
        .card:hover{transform:translateY(-6px);}
        .card .icon{
            font-size:48px;
            color:#d65a00;
            margin-bottom:14px;
        }
        .card h3{margin:0 0 10px;font-size:20px;color:#333;}
        .card p{margin:0 0 22px;color:#666;}
        .action-btn{
            background:linear-gradient(90deg,#ff9b3e 0%,#ff6d00 100%);
            color:#fff;
            padding:10px 20px;
            border:none;
            border-radius:6px;
            font-weight:bold;
            cursor:pointer;
            text-decoration:none;
        }
        .action-btn:hover{filter:brightness(.95);}
    </style>
</head>
<body>
    <div class="topbar">
        <h1>📊 Panel de Administración</h1>
        <div style="display:flex;align-items:center;gap:18px;">
            <span>Bienvenido, <?php echo htmlspecialchars($username); ?></span>
            <a href="main.php" class="btn">🏠 Volver a la Principal</a>
        </div>
    </div>

    <section class="cards">
        <!-- Inventario -->
        <div class="card">
            <div class="icon">📦</div>
            <h3>Gestionar Inventario</h3>
            <p>Añade, edita o elimina productos del menú</p>
            <a class="action-btn" href="crud/inventario/index.php">Administrar Inventario</a>
        </div>

        <!-- Empleados -->
        <div class="card">
            <div class="icon">👥</div>
            <h3>Gestionar Empleados</h3>
            <p>Administra los empleados registrados</p>
            <a class="action-btn" href="crud/empleados/index.php">Administrar Empleados</a>
        </div>
    </section>
</body>
</html>