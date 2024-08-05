<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
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
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos
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

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-success">
  <img src="../img/loro.png" width="30" height="30" alt="">
  <a class="navbar-brand text-white" href="#">LORO</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link text-white" href="#">Inicio<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="lobbyCrearTlf.php">Añadir</a>
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



    <div class="container">
        <h1 >Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
        <div style="display: flex; flex-wrap:wrap; justify-content:center">
    <button style="width:250px; margin:5%;" class="icon-slide-right" onclick="location.href='index/indexTelefonos.php'">Gestionar Teléfonos corporativos</button>    
    <button style="width:250px; margin:5%" class="icon-slide-left" onclick="location.href='index/indexPC.php'">Gestionar Computadoras</button>
    <button style="width:250px; margin:5%" class="icon-slide-right" onclick="location.href='index/indexImpresoras.php'">Gestionar<br>Impresoras</button><br>
    </div>
  </div>
</body>
</html>
