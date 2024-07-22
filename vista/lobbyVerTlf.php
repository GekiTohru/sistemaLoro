<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Redirigir al formulario de inicio de sesión con un mensaje de error
    header("Location: ../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../login.php?error=timeout");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
    <link href="../css/buttons.css" rel="stylesheet">
</head>

<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="../controlador/cerrarSesion.php" class="navbtn">Salir</a>
            <a href="lobby.php" class="navbtn">Inicio</a>
            <a href="lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="index/indexTelefonos.php">Teléfonos</a>
                     <a href="index/indexPc.php">Computadoras</a>
                     <a href="index/indexImpresoras.php">Impresoras</a>
                     <?php if ($_SESSION['permisos'] == 1) {
                  echo'<a href="index/idxUsuarios.php">Usuarios</a>';
                        }
                  ?>
                 </div>
             </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 14%">
    <div class="wrapper" style="margin-top: 3%; margin-left: 35%; width: 400px">
		<span data-text="Gestionar elementos"></span>
		<span data-text=""></span>
	</div>
    <div class="container" style="display: flex; flex-wrap; margin-left: 5%">

        <div id="tlf">
	<div class="wrapper" style="margin-bottom: -10%; margin-left: 1%">
		<span data-text="Telefono"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexTelefonos.php'">Gestionar Teléfonos</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=modelo_marca'">Gestionar Modelo</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=tlf_sisver'">Gestionar Versiones de Android</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=operadora'">Gestionar Operadoras</button>
        </div>
        <div id="pc" style="margin-left: 3%">
        <div class="wrapper" style="margin-bottom: -9%; margin-left: 1%">
		<span data-text="Computadora"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexPC.php'">Gestionar Computadoras</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=pc_sis_op'">Gestionar Sistemas operativos (PC)</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=tipo_equipo'">Gestionar Tipo de Equipo (PC)</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=tipo_almacenamiento'">Gestionar Tipo de Almacenamiento</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=red_lan'">Gestionar Tipos de red</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/idxMantenimientosPC.php'">Gestionar Mantenimientos (PC)</button>
        </div>
        <div id="impresora" style="margin-right: 3%">
        <div class="wrapper" style="margin-bottom: -10%; margin-left: 1%">
		<span data-text="Impresora"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexImpresoras.php'">Gestionar Impresoras</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/idxMantenimientosImp.php'">Gestionar Mantenimientos (Imp)</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/idxToner.php'">Gestionar Toner</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/idxCT.php'">Gestionar cambios de tóner</button>  
    </div>
        <div id="general">
        <div class="wrapper" style="margin-bottom: -6%; margin-left: 1%">
		<span data-text="General"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/idxPersonal.php'">Gestionar Personal</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=fabricante'">Gestionar Fabricantes</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=area'">Gestionar Áreas</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=cargo_ruta'">Gestionar Cargos/Rutas</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=sucursal'">Gestionar Sucursales</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=sistema_admin'">Gestionar Sistemas Administrativos</button>
        </div>
    </div>
</body>
</html>
