<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Redirigir al formulario de inicio de sesión con un mensaje de error
    header("Location: ../login.php?error=timeout");
    exit();
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
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="index/idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>

    <div class="container" style="margin-top: 5%">
    <div class="wrapper" style="margin-bottom: 2%; margin-left: 35%; width: 400px">
		<span data-text="Añadir un nuevo elemento"></span>
		<span data-text=""></span>
	</div>
    <div class="container" style="display: flex; flex-wrap; margin-left: 5%">

        <div id="tlf">
	<div class="wrapper" style="margin-bottom: -10%; margin-left: 1%">
		<span data-text="Telefono"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearTlf.php'">Nuevo Teléfono</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearModelo.php'">Nuevo Modelo</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearSisTlf.php'">Nueva Versión Android</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearOperadora.php'">Nueva Operadora</button>
        </div>
        <div id="pc">
        <div class="wrapper" style="margin-bottom: -8%; margin-left: 1%">
		<span data-text="Computadora"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearPC.php'">Nueva PC</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearPcSO.php'">Nuevo Sistema Operativo (PC)</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearTipoEquipo.php'">Nuevo Tipo de equipo (PC)</button>    
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearAlmacenamiento.php'">Nuevo tipo de almacenamiento</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearRed.php'">Nuevo tipo de red</button>    
        </div>
        <div id="impresora" style="margin-right: 3%; margin-left: -6%">
        <div class="wrapper" style="margin-bottom: -11%; margin-left: 1%">
		<span data-text="Impresora"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearImp.php'">Nueva Impresora</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearToner.php'">Nuevo Toner</button>
        </div>
        <div id="general">
        <div class="wrapper" style="margin-bottom: -7%; margin-left: 1%">
		<span data-text="General"></span>
		<span data-text=""></span>
	</div>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearPersonal.php'">Nuevo Personal</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearFabricante.php'">Nuevo Fabricante</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearArea.php'">Nueva Área</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearCargo.php'">Nuevo Cargo/ruta</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crear/crearSucursal.php'">Nueva Sucursal</button>
    <button style="width:250px; margin-top: 20px" class="icon-slide-left" onclick="location.href='crear/crearSisadmin.php'">Nuevo Sistema Administrativo</button>
        </div>
    </div>
</body>
</html>
