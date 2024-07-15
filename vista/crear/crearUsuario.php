<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../../login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

    include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<header>
<div style="height: 50px;"></div>
    <img src="../../img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="../../controlador/cerrarSesion.php" class="navbtn">Salir</a>
            <a href="../lobby.php" class="navbtn">Inicio</a>
            <a href="../lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="../lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="../indexTelefonos.php">Teléfonos</a>
                     <a href="../indexPc.php">Computadoras</a>
                     <a href="../indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="../idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nuevo usuario</h2>

        <div class="users-form">
            <form id="nuevo" action="../../controlador/crear/crearUsuarioFuncion.php" method="POST">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="user">Usuario</label>
                <input type="text" name="user" id="user" placeholder="Ingrese el usuario" value="" required>
                </div>
                <div class="inputs">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="" required>
                </div>
                <div class="inputs">
                <label for="pass">Clave</label>
                <input type="text" name="pass" id="pass" placeholder="Ingrese la clave" value="" required>
                </div>
                <div class="inputs">
                <label for="permisos">Permisos</label>
                <select name="permisos" id="permisos">
                <option value="0">Usuario</option>
                <option value="1">Administrador</option>
                </select>
                </div>
</div>
                <input type="submit" value="Añadir nuevo usuario">
            </form>
        </div>
        

        </body>
</html>