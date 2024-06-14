<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
    include("conexion.php");

    $sql2="SELECT * FROM marca";
    $query2 = mysqli_query($conexion, $sql2);
    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<header>
<div style="height: 50px;"></div>
    <img src="img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="cerrarSesion.php" class="navbtn">Salir</a>
            <a href="lobby.php" class="navbtn">Inicio</a>
            <a href="lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nueva operadora</h2>
<style>
/* Estilo para centrar elementos dentro del contenedor */
.users-form {
    text-align: center;
}

/* Estilo para el formulario dentro del contenedor */
.users-form form {
    display: inline-block;
    text-align: left;
    margin: 0 auto;
}

/* Estilo para el formulario */
.users-form h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

/* Estilo para los campos de entrada de texto */
.users-form input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Estilo para el botón */
.users-form input[type="submit"] {
    background-color: greenyellow;
    color: black;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Estilo para el botón al pasar el mouse sobre él */
.users-form input[type="submit"]:hover {
    background-color: green;
}
</style>
        <div class="users-form">
            <form id="nuevo" action="crearFuncion.php" method="POST">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre_operadora">Operadora</label>
                <input type="text" name="nombre_operadora" id="nombre_operadora" placeholder="Ingrese el nombre" value="" required>
                </div>
</div>
                <input type="submit" value="Añadir nueva operadora">
            </form>
        </div>
        

        </body>
</html>