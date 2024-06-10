<<<<<<< HEAD
<?php 
    include("conexion.php");

    $sql="SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
    FROM telefonos
    INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
    INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
    INNER JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
    INNER JOIN personal ON tlf_asignado.id_personal = personal.id_personal
    INNER JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
    INNER JOIN area ON personal.id_area = area.id_area
    WHERE telefonos.activo = 1
    ORDER BY `telefonos`.`id_telefono` ASC";

    $sql1="SELECT * FROM personal";
    $sql2="SELECT * FROM cargo_ruta";
    $sql3="SELECT * FROM area";

$query = mysqli_query($conexion, $sql);
$query2 = mysqli_query($conexion, $sql2);
$query3 = mysqli_query($conexion, $sql3);

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
    <img src="img/logo.png" id="logo">
</header>
<body>
<a href="index.php">hola mundo!!!</a>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nuevo personal</h2>
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
        <h2>Añadir</h2>
            <form id="nuevo" action="crearPersonalFuncion.php" method="POST">
                <label for="cargoruta">Cargo/Ruta</label>
                <select name="cargoruta" id="cargoruta" style="width: 200px">
                <option value="">Seleccione un cargo/ruta</option>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {     
                    echo "<option value='{$row['id_cargoruta']}'>{$row['nombre']}</option>";
                }
                ?></select>
                <label for="area">Área</label>
                <select name="area" id="area" style="width: 200px">
                <option value="">Seleccione una área</option>
                <?php
                while ($row = mysqli_fetch_assoc($query3)) {     
                    echo "<option value='{$row['id_area']}'>{$row['nombre']}</option>";
                }
                ?></select>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="">
<script>
$(document).ready(function() {
    $('#cargoruta, #area').select2({
        minimumInputLength: 0,
        debug: true,
    });
});</script>
                <input type="submit" value="Añadir">
            </form>
        </div>
        

        </body>
=======
<?php 
    include("conexion.php");

    $sql="SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
    FROM telefonos
    INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
    INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
    INNER JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
    INNER JOIN personal ON tlf_asignado.id_personal = personal.id_personal
    INNER JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
    INNER JOIN area ON personal.id_area = area.id_area
    WHERE telefonos.activo = 1
    ORDER BY `telefonos`.`id_telefono` ASC";

    $sql1="SELECT * FROM personal";
    $sql2="SELECT * FROM cargo_ruta";
    $sql3="SELECT * FROM area";

$query = mysqli_query($conexion, $sql);
$query2 = mysqli_query($conexion, $sql2);
$query3 = mysqli_query($conexion, $sql3);

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
    <img src="img/logo.png" id="logo">
</header>
<body>
<a href="index.php">hola mundo!!!</a>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nuevo personal</h2>
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
        <h2>Añadir</h2>
            <form id="nuevo" action="crearPersonalFuncion.php" method="POST">
                <label for="cargoruta">Cargo/Ruta</label>
                <select name="cargoruta" id="cargoruta" style="width: 200px">
                <option value="">Seleccione un cargo/ruta</option>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {     
                    echo "<option value='{$row['id_cargoruta']}'>{$row['nombre']}</option>";
                }
                ?></select>
                <label for="area">Área</label>
                <select name="area" id="area" style="width: 200px">
                <option value="">Seleccione una área</option>
                <?php
                while ($row = mysqli_fetch_assoc($query3)) {     
                    echo "<option value='{$row['id_area']}'>{$row['nombre']}</option>";
                }
                ?></select>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="">
<script>
$(document).ready(function() {
    $('#cargoruta, #area').select2({
        minimumInputLength: 0,
        debug: true,
    });
});</script>
                <input type="submit" value="Añadir">
            </form>
        </div>
        

        </body>
>>>>>>> 559cab82091e040721218c428ee4f663ae3dd8c8
</html>