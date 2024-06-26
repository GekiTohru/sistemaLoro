<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Redirigir al formulario de inicio de sesión con un mensaje de error
    header("Location: login.php?error=not_logged_in");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <link href="css/buttons.css" rel="stylesheet">
</head>

<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="cerrarSesion.php" class="navbtn">Salir</a>
            <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="gestionarUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>
    <div style="margin-top: 30%" class="container">
    <h1>Gestionar elementos</h1>
        <div id="tlf">
        <h1 style="margin-left: 5%">Teléfonos</h1>
        <a href="indexTelefonos.php" class="button">Gestionar Teléfonos</a>
        <a href="idxModelo.php" class="button">Gestionar Modelo</a>
        <a href="idxSisTlf.php" class="button">Gestionar Versones de Android</a>
        <a href="idxOperadora.php" class="button">Gestionar Operadoras</a>
        </div>
        <div id="pc">
        <h1 style="margin-left: 5%">Computadoras</h1>
        <a href="indexPC.php" class="button">Gestionar Computadoras</a>
        <a href="idxPcSO.php" class="button">Gestionar Sistemas Operativos (PC)</a>
        <a href="idxTipoEquipo.php" class="button">Gestionar Tipos de equipo (PC)</a>
        <a href="idxAlmacenamiento.php" class="button">Gestionar Tipos de almacenamiento</a>
        </div>
        <div id="impresora">
        <h1 style="margin-left: 5%">Impresoras</h1>
        <a href="indexImpresora.php" class="button">Gestionar Impresoras</a>
        </div>
        <div id="general">
        <h1 style="margin-left: 5%">General</h1>
        <a href="idxPersonal.php" class="button">Gestionar Personal</a>
        <a href="idxFabricante.php" class="button">Gestionar Fabricantes</a>
        <a href="idxArea.php" class="button">Gestionar Áreas</a>
        <a href="idxCargo.php" class="button">Gestionar Cargos/rutas</a>
        <a href="idxSucursal.php" class="button">Gestionar Sucursales</a>
        <a href="idxMantenimientos.php" class="button">Gestionar Mantenimientos</a>
        </div>
    </div>

</body>
</html>
