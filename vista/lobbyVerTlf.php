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
    <li class="nav-item">
        <a class="nav-link text-white" href="lobbyCrearTlf.php">Añadir</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="#">Ver y Editar</a>
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
            <span class="subtitles" data-text="Gestionar elementos"></span>
        </div>
            <div class="columna">
	<div class="wrapper">
		<span class="subtitles" data-text="Telefono"></span>
	</div>
    <div  class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/indexTelefonos.php'">Gestionar Teléfonos</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=modelo_marca'">Gestionar Modelo</button>
    </div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=tlf_sisver'">Gestionar Versiones de Android</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=operadora'">Gestionar Operadoras</button>
    </div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/idxRevTlfAll.php'">Gestionar Consumo de datos</button>
    </div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='../../reporte/requisicion.php'">Requisición de accesorios</button>
    </div>
    </div>
        <div class="columna">
        <div class="wrapper">
		<span class="subtitles" data-text="Computadora"></span>
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/indexPC.php'">Gestionar Computadoras</button>    
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=pc_sis_op'">Gestionar Sistemas operativos (PC)</button>    
	</div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=tipo_equipo'">Gestionar Tipo de Equipo (PC)</button>    
	</div>
    <div class="col-button">
    <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=tipo_almacenamiento'">Gestionar Tipo de Almacenamiento</button>    
    </div>
    <div class="col-button">
    <button class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=red_lan'">Gestionar Tipos de red</button>    
    </div>
    <div class="col-button">  
    <button class="icon-slide-left" onclick="location.href='index/idxMantenimientosPC.php'">Gestionar Mantenimientos (PC)</button>    
    </div>
    </div>
    <div class="columna">
            <div class="wrapper">
            <span class="subtitles" data-text="Impresora"></span>
        </div>
        <div class="col-button">
        <button class="icon-slide-right" onclick="location.href='index/indexImpresoras.php'">Gestionar Impresoras</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-left" onclick="location.href='index/idxMantenimientosImp.php'">Gestionar Mantenimientos (Imp)</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-right" onclick="location.href='index/idxToner.php'">Gestionar Tóner</button>  
        </div>
        <div class="col-button">
        <button class="icon-slide-left" onclick="location.href='index/idxCT.php'">Gestionar cambios de tóner</button>
        </div>
        </div>
    <div class="columna">
            <div class="wrapper">
            <span class="subtitles" data-text="General"></span>
        </div>
        <div class="col-button">
        <button class="icon-slide-right" onclick="location.href='index/idxPersonal.php'">Gestionar Personal</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=fabricante'">Gestionar Fabricantes</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=area'">Gestionar Áreas</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=cargo_ruta'">Gestionar Cargos/Rutas</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-right" onclick="location.href='index/indexGeneral.php?tabla=sucursal'">Gestionar Sucursales</button>
        </div>
        <div class="col-button">
        <button class="icon-slide-left" onclick="location.href='index/indexGeneral.php?tabla=sistema_admin'">Gestionar Sistemas Administrativos</button>
        </div>
        </div>
        </div>
</body>
</html>
