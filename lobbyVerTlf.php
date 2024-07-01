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
                    <a href="idxUsuarios.php">Gestionar usuarios</a>
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
        <a href="indexGeneral.php?tabla=modelo" class="button">Gestionar Modelo</a>
        <a href="indexGeneral.php?tabla=tlf_sisver" class="button">Gestionar Versiones de Android</a>
        <a href="indexGeneral.php?tabla=operadora" class="button">Gestionar Operadoras</a>
        </div>
        <div id="pc">
        <h1 style="margin-left: 5%">Computadoras</h1>
        <a href="indexPC.php" class="button">Gestionar Computadoras</a>
        <a href="indexGeneral.php?tabla=pc_sis_op" class="button">Gestionar Sistemas operativos (PC)</a>
        <a href="indexGeneral.php?tabla=tipo_equipo" class="button">Gestionar Tipo de Equipo (PC)</a>
        <a href="indexGeneral.php?tabla=tipo_almacenamiento" class="button">Gestionar Tipo de Almacenamiento</a>
        <a href="indexGeneral.php?tabla=red_lan" class="button">Gestionar Tipos de red</a>
        </div>
        <div id="impresora">
        <h1 style="margin-left: 5%">Impresoras</h1>
        <a href="indexImpresora.php" class="button">Gestionar Impresoras</a>
        </div>
        <div id="general">
        <h1 style="margin-left: 5%">General</h1>
        <a href="idxPersonal.php" class="button">Gestionar Personal</a>
        <a href="indexGeneral.php?tabla=fabricante" class="button">Gestionar Fabricantes</a>
        <a href="indexGeneral.php?tabla=area" class="button">Gestionar Áreas</a>
        <a href="indexGeneral.php?tabla=cargo_ruta" class="button">Gestionar Cargos/Rutas</a>
        <a href="indexGeneral.php?tabla=sucursales" class="button">Gestionar Sucursales</a>
        <a href="indexGeneral.php?tabla=sistema_admin" class="button">Gestionar Sistemas Administrativos</a>
        <a href="idxMantenimientos.php" class="button">Gestionar Mantenimientos</a>
        </div>
    </div>

</body>
<!-- <a href="indexGeneral.php?tabla=modelos" class="button">Gestionar Modelo</a>
<a href="indexGeneral.php?tabla=sucursales" class="button">Gestionar Sucursales</a>
<a href="indexGeneral.php?tabla=tipo_almacenamiento" class="button">Gestionar Tipo de Almacenamiento</a>
<a href="indexGeneral.php?tabla=tipo_equipo" class="button">Gestionar Tipo de Equipo</a>
<a href="indexGeneral.php?tabla=tlf_sisver" class="button">Gestionar Tlf Sisver</a>
<a href="indexGeneral.php?tabla=sistema_admin" class="button">Gestionar Sistema Admin</a>
<a href="indexGeneral.php?tabla=red_lan" class="button">Gestionar Red LAN</a>
<a href="indexGeneral.php?tabla=pc_sis_op" class="button">Gestionar PC Sis OP</a>
<a href="indexGeneral.php?tabla=cargo_ruta" class="button">Gestionar Cargo Ruta</a>
<a href="indexGeneral.php?tabla=area" class="button">Gestionar Área</a> -->
</html>
