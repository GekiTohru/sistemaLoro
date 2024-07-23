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
            <a href="documentacion/doc.html" class="navbtn">Documentación</a> 
        </div>
    </nav>

    <div class="container">
        <h1 style="margin-left: 20%">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
    <button style="width:250px;" class="icon-slide-right" onclick="location.href='index/indexTelefonos.php'">Gestionar Teléfonos corporativos</button>    
    <button style="width:250px;" class="icon-slide-left" onclick="location.href='index/indexPC.php'">Gestionar Computadoras</button>
    <button style="width:250px;" class="icon-slide-right" onclick="location.href='index/indexImpresoras.php'">Gestionar<br>Impresoras</button><br>
    </div>
</body>
</html>
