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

    $sql2="SELECT * FROM modelo_marca";
    $sql3="SELECT * FROM telefonos";
    $sql4="SELECT * FROM personal";
    $sql5="SELECT * FROM cargo_ruta";
    $sql6="SELECT * FROM area";
    $sql7="SELECT * FROM tlf_sisver";
    $sql8="SELECT * FROM operadora";
    $sql9="SELECT * FROM sucursal";

$query2 = mysqli_query($conexion, $sql2);
$query3 = mysqli_query($conexion, $sql3);
$query4 = mysqli_query($conexion, $sql4);
$query5 = mysqli_query($conexion, $sql5);
$query6 = mysqli_query($conexion, $sql6);
$query7 = mysqli_query($conexion, $sql7);
$query8 = mysqli_query($conexion, $sql8);
$query9 = mysqli_query($conexion, $sql9);

$row=mysqli_fetch_array($query3);
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
        <h2 style="text-align: center;">Añadir nuevo teléfono</h2>
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
            <form id="nuevo" action="crearTlfFuncion.php" method="POST">
                <div id="fechas" style="display: flex">
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px;">Fecha de recepción</label>
                <input type="date" name="fecha_recep" id="fecha_recep" style="width: 200px" value="">
                </div>
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="fecha_ult_mant" id="fecha_recep" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha de la última revisión</label>
                <input type="date" name="fecha_ult_rev" id="fecha_recep" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                </div>
                <div style="display: flex; flex-wrap: wrap;">
                <div id="selecciones" style="display: block; margin-right: 75px">
                <div style="margin: 10px; margin-right: 100px">
                <label for="modelo">Modelo</label>
                <select id="modelo" name="modelo" style="width: 200px" data-placeholder="Seleccione un modelo" required>
                <option value="">Seleccione un modelo</option>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {
                    echo "<option value='{$row['id_modelo']}'>{$row['nombre']}</option>";
                }
                ?>
                </select>
                </div>
                <div style="margin: 10px">
                <label for="personal">Asignado a</label>
                <select name="id_personal" id="personal" style="width: 200px" data-placeholder="Seleccione un asignado">
                <option value="">Seleccione un asignado</option>
                <?php
                while ($row = mysqli_fetch_assoc($query4)) {     
                    echo "<option value='{$row['id_personal']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="sisver">Versión del sistema</label>
                <select name="sisver" id="sisver" style="width: 200px" data-placeholder="Seleccione una versión" required>
                <option value="">Seleccione una versión</option>
                <?php
                while ($row = mysqli_fetch_assoc($query7)) {     
                    echo "<option value='{$row['id_sisver']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="operadora">Operadora</label>
                <select name="operadora" id="operadora" style="width: 200px" data-placeholder="Seleccione una operadora">
                <option value="">Seleccione una operadora</option>
                <?php
                while ($row = mysqli_fetch_assoc($query8)) {     
                    echo "<option value='{$row['id_operadora']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="sucursal">Sucursal</label>
                <select name="sucursal" id="sucursal" style="width: 200px" data-placeholder="Seleccione la sucursal">
                <option value="">Seleccione la sucursal</option>
                <?php
                while ($row = mysqli_fetch_assoc($query9)) {     
                    echo "<option value='{$row['id_sucursal']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                </div>
                <input type="checkbox" id="dummy" name="accesorios[]" required style="display:none">
                <div style="margin-right: 75px">
                <label class="nopoint" for="cabezal-cargador" style="width: 250px; height: 10px;">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios">       
                    <label><input type="checkbox" id="cabezal-cargador" name="accesorios[]" value="cabezal cargador"> Cabezal cargador</label>
                    <label><input type="checkbox" id="adaptador1" name="accesorios[]" value="adaptador"> Adaptador</label>
                    <label><input type="checkbox" id="cable-usb" name="accesorios[]" value="cable usb"> Cable USB</label>
                    <label><input type="checkbox" id="forro1" name="accesorios[]" value="forro"> Forro</label>
                    <label><input type="checkbox" id="vidrio-templado" name="accesorios[]" value="vidrio templado"> Vidrio templado</label>
                    <label><input type="checkbox" id="hidrogel" name="accesorios[]" value="hidrogel"> Hidrogel</label>
                    <label><input type="checkbox" id="estuche" name="accesorios[]" value="estuche"> Estuche</label>
                </div>
                </div>
                <input type="checkbox" id="dummy1" name="apps_conf[]" required style="display:none">
                <div>
                <label class="nopoint" for="whatsapp" style="width: 300px; height: 10px;">Aplicaciones y configuración básica:</label><br>
                <div class="accesorioscheck" id="apps_conf" style="width: 360px; height: 300px; display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="whatsapp" name="apps_conf[]" value="whatsapp">WhatsAapp</label>
                    <label><input type="checkbox" id="gmail" name="apps_conf[]" value="gmail">Gmail</label>
                    <label><input type="checkbox" id="adn" name="apps_conf[]" value="adn">ADN</label>
                    <label><input type="checkbox" id="facebook" name="apps_conf[]" value="facebook">Facebook</label>
                    <label><input type="checkbox" id="instagram" name="apps_conf[]" value="instagram">Instagram</label>
                    <label><input type="checkbox" id="netflix" name="apps_conf[]" value="netflix">Netflix</label>
                    <label><input type="checkbox" id="youtube" name="apps_conf[]" value="youtube">Youtube</label>
                    <label><input type="checkbox" id="ubicacion" name="apps_conf[]" value="ubicacion">Ubicación</label>
                    <label><input type="checkbox" id="tema_por_defecto" name="apps_conf[]" value="tema por defecto">Tema por defecto</label>
                    <label><input type="checkbox" id="otra" name="apps_conf[]" value="otra">Otra</label>
                </div>
                <div id="otra_app_container" style="display: none;">
                    <label for="otra_app">Especifique otras aplicaciones:</label>
                    <input type="text" id="otra_app" name="otra_app" value="">
                </div>
                </div>
                <script>
    // Escuchar el cambio de estado de la checkbox "otra"
    document.getElementById('otra').addEventListener('change', function() {
        var otraAppContainer = document.getElementById('otra_app_container');
        if (this.checked) {
            otraAppContainer.style.display = 'block';
        } else {
            otraAppContainer.style.display = 'none';
        }
    });

    // Mostrar el campo de entrada si "otra" ya está seleccionada al cargar la página
    window.addEventListener('DOMContentLoaded', function() {
        var otraCheckbox = document.getElementById('otra');
        var otraAppContainer = document.getElementById('otra_app_container');
        if (otraCheckbox.checked) {
            otraAppContainer.style.display = 'block';
        }
    });
</script>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="imei1">IMEI 1</label>
                <input type="text" name="imei1" id="imei1" placeholder="Ingrese el IMEI 1" value="">
                </div>
                <div class="inputs">
                <label for="imei2">IMEI 2</label>
                <input type="text" name="imei2" id="imei2" placeholder="Ingrese el IMEI 2" value="">
                </div>
                <div class="inputs">
                <label for="imei_adn">IMEI ADN</label>
                <input type="text" name="imei_adn" id="imei_adn" placeholder="Ingrese el IMEI ADN" value="">
                </div>
                <div class="inputs">
                <label for="serial">Serial</label>
                <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="">
                </div>
                <div class="inputs">
                <label for="imei1">MAC LAN</label>
                <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la MAC LAN" value="">
                </div>
                <div class="inputs">
                <label for="imei1">MAC WIFI</label>
                <input type="text" name="mac_wifi" id="mac_wifi" placeholder="Ingrese la MAC WIFI" value="">
                </div>
                <div class="inputs">
                <label for="numero">Número telefónico</label>
                <input type="text" name="numero" id="numero" placeholder="Ingrese el número" value="">
                </div>
                <div class="inputs">
                <label for="cuenta_google">Cuenta google</label>
                <input type="text" name="cuenta_google" id="cuenta_google" placeholder="Ingrese el correo" value="">
                </div>
                <div class="inputs">
                <label for="clave_google">Clave google</label>
                <input type="text" name="clave_google" id="clave_google" placeholder="Ingrese la clave" value="">
                </div>
                <div class="inputs">
                <label for="correo_corporativo">Correo corporativo</label>
                <input type="text" name="correo_corporativo" id="correo_corporativo" placeholder="Ingrese el correo" value="">
                </div>
                <div class="inputs">
                <label for="clave_corporativo">Clave corporativa</label>
                <input type="text" name="clave_corporativo" id="clave_corporativo" placeholder="Ingrese la clave" value="">
                </div>
                <div class="inputs">
                <label for="anydesk">Código Anydesk</label>
                <input type="text" name="anydesk" id="anydesk" placeholder="Ingrese el código" value="">
                </div>
                <div class="inputs">
                <label for="pin">Pin de desbloqueo</label>
                <input type="text" name="pin" id="pin" placeholder="Ingrese el pin" value="">
                </div>
                <div class="inputs">
                <label for="cuenta_mi">Cuenta MI</label>
                <input type="text" name="cuenta_mi" id="cuenta_mi" placeholder="Ingrese la cuenta MI" value="">
                </div>
                <div class="inputs">
                <label for="clave_mi">Clave MI</label>
                <input type="text" name="clave_mi" id="clave_mi" placeholder="Ingrese la clave MI" value="">
                </div>
                <div class="inputs">
                <label for="precio">Precio</label>
                <input type="text" name="precio" id="precio" placeholder="Ingrese el precio" value="">
                </div>
                <div class="inputs" style="width: 225px">
                <label for="almacenamiento-num" style="width: 200px">Almacenamiento ocupado</label>
<input type="text" id="almacenamiento-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento">
<select id="unidad" style="width: 80px">
  <option value="GB">GB</option>
  <option value="MB">MB</option>
</select>
</div>
<div class="inputs" style="width: 600px">
                <label for="consumo" style="width: 200px">Consumo de datos</label>
<input type="text" id="consumo" style="width: 130px; margin-right: -3px" placeholder="Consumo">
<select id="unidad1" style="width: 80px">
  <option value="GB">GB</option>
  <option value="MB">MB</option>
</select>
</div>
<div class="inputs" style="width: 600px">
                <label for="nota">Observación</label>
                <textarea style="width: 1000px; height: 200px" type="text" name="nota" id="nota" placeholder="Mantiene la configuración inicial." value="<?= $row0['nota']?>"></textarea>
                </div>
</div>
<input type="hidden" name="almacenamiento" id="almacenamiento-hidden">
<input type="hidden" name="consumo_datos" id="consumo-hidden">

<script>
  const almacenamientoNum = document.getElementById('almacenamiento-num');
  const unidadSelect = document.getElementById('unidad');
  const almacenamientoHidden = document.getElementById('almacenamiento-hidden');

  almacenamientoNum.addEventListener('input', () => {
    const valor = almacenamientoNum.value;
    const unidad = unidadSelect.value;
    const almacenamientoCompleto = `${valor}${unidad}`;
    almacenamientoHidden.value = almacenamientoCompleto;
  });
</script>

<script>
  const consumo = document.getElementById('consumo');
  const unidad1Select = document.getElementById('unidad1');
  const consumoHidden = document.getElementById('consumo-hidden');

  consumo.addEventListener('input', () => {
    const valor1 = consumo.value;
    const unidad1 = unidad1Select.value;
    const consumoCompleto = `${valor1}${unidad1}`;
    consumoHidden.value = consumoCompleto;
  });
</script>
                <div id="statuses" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="vidrio_hidrogel">Estado del vidrio/hidrogel</label>
                <select name="vidrio_hidrogel" id="vidrio_hidrogel">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
                <option value="ROTO">ROTO</option>
                <option value="RAYADO">RAYADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="forro">Estado del forro</label>
                <select name="forro" id="forro">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="pantalla">Estado pantalla</label>
                <select name="pantalla" id="pantalla">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
                <option value="RAYADO">RAYADO</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="camara">Estado cámara</label>
                <select name="camara" id="camara">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="RAYADO">MICA RAYADA</option>
                <option value="PARTIDO">MICA PARTIDA</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="cargador">Estado cargador</label>
                <select name="cargador" id="cargador">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="NO TRAE A REVISIÓN">NO TRAE A REVISIÓN</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="cable_usb">Estado cable USB</label>
                <select name="cable_usb" id="cable_usb">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="NO TRAE A REVISIÓN">NO TRAE A REVISIÓN</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="adaptador">Estado adaptador</label>
                <select name="adaptador" id="adaptador">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="NO USA">NO USA</option>
                <option value="NO TRAE A REVISIÓN">NO TRAE A REVISIÓN</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                </div>
                <script>
                const checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"][name="accesorios[]"]'));
                const dummy = document.getElementById('dummy');

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                    if (checkboxes.some((cb) => cb.checked)) {
                        dummy.checked = true;
                    } else {
                        dummy.checked = false;
                    }
                    });
                });
                </script>
                <script>
                const checkboxes1 = Array.from(document.querySelectorAll('input[type="checkbox"][name="apps_conf[]"]'));
                const dummy1 = document.getElementById('dummy1');

                checkboxes1.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                    if (checkboxes1.some((cb) => cb.checked)) {
                        dummy1.checked = true;
                    } else {
                        dummy1.checked = false;
                    }
                    });
                });
                </script>

<script>
$(document).ready(function() {
    $('#modelo, #personal, #sisver, #operadora, #sucursal').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>
<script>
$(document).ready(function() {
  // Obtener la fecha actual usando la función "Date" de JavaScript
  var today = new Date();
  
  // Formatear la fecha en el formato "YYYY-MM-DD" usando las propiedades "getFullYear", "getMonth" y "getDate" de la clase Date
  var todayFormatted = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
  
  // Asignar el valor formateado al input
  $('#fecha_ult_mant, #fecha_ult_rev').val(todayFormatted);
});
</script>

                <input type="submit" value="Añadir nuevo teléfono">
            </form>
        </div>
        

        </body>
</html>