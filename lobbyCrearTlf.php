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

    <div class="container">
        <h1>Añadir un nuevo elemento</h1>
        <div id="tlf">
        <label for="tlf">Teléfonos</label>
        <a href="crearTlf.php" id="tlf1" class="button">Nuevo Teléfono</a>
        <a href="crearModelo.php" class="button">Nuevo Modelo</a>
        <a href="crearSisTlf.php" class="button">Nueva Versión Android</a>
        <a href="crearOperadora.php" class="button">Nueva Operadora</a>
        </div>
        <div id="pc">
        <label for="pc">Computadoras</label>
        <a href="crearPC.php" id="pc1" class="button">Nueva PC</a>
        <a href="crearPcSO.php" class="button">Nuevo Sistema Operativo (PC)</a>
        <a href="crearTipoEquipo.php" class="button">Nuevo Tipo de equipo (PC)</a>
        <a href="crearAlmacenamiento.php" class="button">Nuevo tipo de almacenamiento</a>
        </div>
        <div id="impresora">
        <label for="impresora1">Impresoras</label>
        <a href="crearImp.php" class="button">Nueva Impresora</a>
        </div>
        <div id="general">
        <label for="general1">General</label>
        <a href="crearPersonal.php" id="general1" class="button">Nuevo Personal</a>
        <a href="crearFabricante.php" class="button">Nuevo Fabricante</a>
        <a href="crearArea.php" class="button">Nueva Área</a>
        <a href="crearCargo.php" class="button">Nuevo Cargo/ruta</a>
        <a href="crearSucursal.php" class="button">Nueva Sucursal</a>
        </div>
    </div>
</body>
</html>
