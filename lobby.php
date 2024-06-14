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
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <a href="indexTelefonos.php" class="button">Gestionar teléfonos corporativos</a>
        <a href="indexPc.php" class="button">Gestionar computadoras</a>
        <a href="indexImpresoras.php" class="button">Gestionar impresoras</a>
    </div>
</body>
</html>
