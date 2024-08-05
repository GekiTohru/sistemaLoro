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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="../css/buttons.css" rel="stylesheet">
    <link href="../css/styles2.css" rel="stylesheet">
</head>
<header>
<nav class="navbar navbar-expand-lg navbar-light bg-success">
  <img src="../img/loro.png" width="30" height="30" alt="">
  <a class="navbar-brand text-white" href="lobby.php">LORO</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
    <a class="nav-link text-white" href="lobby.php">Inicio</a>
    </li>
      <li class="nav-item active">
        <a class="nav-link text-white" href="#">Añadir<span class="sr-only">(current)</span></a>
      </li>
    <li class="nav-item">
    <a class="nav-link text-white" href="lobbyVerTlf.php">Ver y Editar</a>
    </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Gestionar
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="index/indexTelefonos.php">Teléfonos</a>
          <a class="dropdown-item" href="index/indexPC.php">Computadoras</a>
          <a class="dropdown-item" href="index/indexImpresoras.php">Impresoras</a>
          <?php if ($_SESSION['permisos'] == 1) {
                    echo'<a class="dropdown-item" href="index/idxUsuarios.php">Usuarios</a>';
                }
                ?>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white" href="documentacion/doc.html">Documentación</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="../controlador/cerrarSesion.php">Salir</a>
      </li>
    </ul>
  </div>
</nav>
</header>
<body>
    <div class="contenedor">
        <div class="wrapper">
            <span class="subtitles" data-text="Añadir un elemento"></span>
        </div>
            <div class="columna">
	<div class="wrapper">
		<span class="subtitles" data-text="Telefono"></span>
	</div>
    <div  class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearTlf.php'">Nuevo Teléfono</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearModelo.php'">Nuevo Modelo</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearSisTlf.php'">Nueva Versión Android</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearOperadora.php'">Nueva Operadora</button>
    </div>
    </div>
        <div class="columna">
        <div class="wrapper">
		<span class="subtitles" data-text="Computadora"></span>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearPC.php'">Nueva PC</button>    
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearPcSO.php'">Nuevo Sistema Operativo (PC)</button>    
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearTipoEquipo.php'">Nuevo Tipo de equipo (PC)</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearAlmacenamiento.php'">Nuevo tipo de almacenamiento</button>
    </div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearRed.php'">Nuevo tipo de red</button>    
    </div>
</div>
<div class="columna">
        <div class="wrapper">
		<span class="subtitles" data-text="Impresora"></span>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearImp.php'">Nueva Impresora</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearToner.php'">Nuevo Tóner</button>
	</div>
	</div>
<div class="columna">
        <div class="wrapper">
		<span class="subtitles" data-text="General"></span>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearPersonal.php'">Nuevo Personal</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearFabricante.php'">Nuevo Fabricante</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearArea.php'">Nueva Área</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearCargo.php'">Nuevo Cargo/ruta</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='crear/crearSucursal.php'">Nueva Sucursal</button>
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='crear/crearSisadmin.php'">Nuevo Sistema Administrativo</button>
	</div>
	</div>
    </div>
</body>
</html>
