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
        <h2 style="text-align: center;">Añadir nuevo modelo</h2>
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
            <form id="nuevo" action="crearModeloFuncion.php" method="POST">
                <div style="display: flex; flex-wrap: wrap;">
                <div id="selecciones" style="display: block; margin-right: 75px">
                <div style="margin: 10px; margin-right: 100px">
                <label for="marca">Marca</label>
                <select id="marca" name="marca" style="width: 200px" data-placeholder="Seleccione una marca" required>
                <option value="">Seleccione una marca</option>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {
                    echo "<option value='{$row['id_marca']}'>{$row['nombre']}</option>";
                }
                ?>
                </select>
                </div>
                </div>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Modelo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="" required>
                </div>
                <div class="inputs" style="width: 225px">
    <label for="ram-num" style="width: 200px">RAM</label>
    <input type="text" id="ram-num" style="width: 130px; margin-right: -3px" placeholder="RAM" required>
    <select id="unidad" style="width: 80px">
        <option value="GB">GB</option>
        <option value="TB">TB</option>
    </select>
</div>
<div class="inputs" style="width: 600px">
    <label for="rom-num" style="width: 200px">ROM</label>
    <input type="text" id="rom-num" style="width: 130px; margin-right: -3px" placeholder="ROM" required>
    <select id="unidad1" style="width: 80px">
        <option value="GB">GB</option>
        <option value="TB">TB</option>
    </select>
</div>
<input type="hidden" name="ram" id="ram-hidden">
<input type="hidden" name="rom" id="rom-hidden">

<script>
  const ramNum = document.getElementById('ram-num');
  const unidadSelect = document.getElementById('unidad');
  const ramHidden = document.getElementById('ram-hidden');

  const updateRamHidden = () => {
    const valor = ramNum.value;
    const unidad = unidadSelect.value;
    const ramCompleto = `${valor} ${unidad}`;
    ramHidden.value = ramCompleto;
  };

  ramNum.addEventListener('input', updateRamHidden);
  unidadSelect.addEventListener('change', updateRamHidden);
</script>

<script>
  const romNum = document.getElementById('rom-num');
  const unidad1Select = document.getElementById('unidad1');
  const romHidden = document.getElementById('rom-hidden');

  const updateRomHidden = () => {
    const valor1 = romNum.value;
    const unidad1 = unidad1Select.value;
    const romCompleto = `${valor1} ${unidad1}`;
    romHidden.value = romCompleto;
  };

  romNum.addEventListener('input', updateRomHidden);
  unidad1Select.addEventListener('change', updateRomHidden);
</script>
</div>
                <div id="statuses" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="dispositivo">Tipo de dispositivo</label>
                <select name="dispositivo" id="dispositivo">
                <option value="Smartphone">Smartphone</option>
                <option value="Tablet">Tablet</option>
                </select>
                </div>
                </div>
                
                
<script>
$(document).ready(function() {
    $('#marca').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>

                <input type="submit" value="Añadir nuevo modelo">
            </form>
        </div>
        

        </body>
</html>