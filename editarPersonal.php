<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

    include("conexion.php");
    $id_personal=$_GET['id'];


    $sql0="SELECT personal.*, personal.id_personal AS id, cargo_ruta.nombre AS cargo, area.nombre AS area
    FROM personal
    LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
    LEFT JOIN area ON personal.id_area = area.id_area
    WHERE personal.id_personal = $id_personal";

    $sql2="SELECT * FROM cargo_ruta";
    $sql3="SELECT * FROM area";

$query0 = mysqli_query($conexion, $sql0);
$query2 = mysqli_query($conexion, $sql2);
$query3 = mysqli_query($conexion, $sql3);

$row0 = mysqli_fetch_assoc($query0);
$id_cargo_seleccionado = $row0['id_cargoruta'];
$id_area_seleccionado = $row0['id_area'];
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
        <h2 style="text-align: center;">Editar personal</h2>
        <div class="users-form">
            <form id="nuevo" action="editarPersonalFuncion.php" method="POST">
            <input type="hidden" name="id_personal" value="<?= $row0['id']?>">            
            <div style="display: flex; flex-wrap: wrap;">
                <div id="selecciones" style="display: block; margin-right: 75px">
                <div style="margin: 10px; margin-right: 100px">
                <label for="cargoruta">Cargo/Ruta</label>
                <select name="cargoruta" id="cargoruta" style="width: 200px" data-placeholder="Seleccione un cargo/ruta" required>
                <option value=""></option>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {
                    $selected = ($row['id_cargoruta'] == $id_cargo_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_cargoruta']}' $selected>{$row['nombre']}</option>";
                }
                ?></select>
                <label for="area">Área</label>
                <select name="area" id="area" style="width: 200px" data-placeholder="Seleccione una área" required>
                <option value=""></option>
                <?php
                while ($row = mysqli_fetch_assoc($query3)) {
                    $selected = ($row['id_area'] == $id_area_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_area']}' $selected>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                </div>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="<?= $row0['nombre']?>" required>
                </div>
                </div>
<script>
$(document).ready(function() {
    $('#cargoruta, #area').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>
                <input type="submit" value="Editar personal">
            </form>
        </div>
        

        </body>
</html>