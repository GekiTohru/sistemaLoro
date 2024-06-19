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
    $id_telefono=$_GET['id'];

    $sql="SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
    FROM telefonos
    INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
    INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
    LEFT JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
    LEFT JOIN personal ON tlf_asignado.id_personal = personal.id_personal
    LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
    LEFT JOIN area ON personal.id_area = area.id_area
    WHERE telefonos.id_telefono = $id_telefono";

    $sql2="SELECT * FROM modelo_marca";
    $sql3="SELECT * FROM telefonos";
    $sql4="SELECT * FROM personal";
    $sql5="SELECT * FROM cargo_ruta";
    $sql6="SELECT * FROM area";
    $sql7="SELECT * FROM tlf_sisver";
    $sql8="SELECT * FROM operadora";
    $sql9="SELECT * FROM sucursal";

$query0 = mysqli_query($conexion, $sql);
$query2 = mysqli_query($conexion, $sql2);
$query3 = mysqli_query($conexion, $sql3);
$query4 = mysqli_query($conexion, $sql4);
$query5 = mysqli_query($conexion, $sql5);
$query6 = mysqli_query($conexion, $sql6);
$query7 = mysqli_query($conexion, $sql7);
$query8 = mysqli_query($conexion, $sql8);
$query9 = mysqli_query($conexion, $sql9);

$row0 = mysqli_fetch_assoc($query0);
$id_modelo_seleccionado = $row0['id_modelo'];
$id_sisver_seleccionado = $row0['id_sisver'];
$id_operadora_seleccionado = $row0['id_operadora'];
$id_sucursal_seleccionado = $row0['id_sucursal'];

$almacenamiento_ocupado = $row0['almacenamiento_ocupado'];
$consumo_datos = $row0['consumo_datos'];
$numero_almacenamiento = '';
$unidad_almacenamiento = '';


function separarNumeroYUnidad($valor) {
    $numero = '';
    $unidad = '';

    if (strpos($valor, 'GB') !== false) {
        $numero = str_replace('GB', '', $valor);
        $unidad = 'GB';
    } elseif (strpos($valor, 'MB') !== false) {
        $numero = str_replace('MB', '', $valor);
        $unidad = 'MB';
    }

    return [$numero, $unidad];
}

list($numero_almacenamiento, $unidad_almacenamiento) = separarNumeroYUnidad($row0['almacenamiento_ocupado']);
list($numero_consumo, $unidad_consumo) = separarNumeroYUnidad($row0['consumo_datos']);

$accesorios = explode(',', $row0['accesorios']);
$apps = explode(',', $row0['app_conf']);
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
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<header>
<div style="height: 50px;"></div>
    <img src="img/logo.png" id="logo">
</header>
<body>
<a href="indexTelefonos.php">hola mundo!!!</a>
    <div class="users-table">
        <h2 style="text-align: center;">Editar Información de teléfono</h2>
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
        <h2><?= $row0['cargo']; ?></h2>
            <form id="nuevo" action="editarTlfFuncion.php" method="POST">
            <input type="hidden" name="id_telefono" value="<?= $row0['id']?>">
                <div id="fechas" style="display: flex">
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px;">Fecha de recepción</label>
                <input type="date" name="fecha_recep" id="fecha_recep" style="width: 200px" value="<?= $row0['fecha_recep']?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_ult_mant" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="fecha_ult_mant" id="fecha_ult_mant" style="width: 200px" value="<?= $row0['fecha_ult_mant']?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_ult_rev" style="width: 210px">Fecha de la última revisión</label>
                <input type="date" name="fecha_ult_rev" id="fecha_recep" style="width: 200px" value="<?= $row0['fecha_ult_rev']?>">
                </div>
                </div>
                <div style="display: flex; flex-wrap: wrap;">
                <div id="selecciones" style="display: block; margin-right: 75px">
                <div style="margin: 10px; margin-right: 100px">
                <label for="modelo">Modelo</label>
                <select id="modelo" name="modelo" style="width: 200px" data-placeholder="Seleccione un modelo" required>
                <?php
                while ($row = mysqli_fetch_assoc($query2)) {
                    $selected = ($row['id_modelo'] == $id_modelo_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_modelo']}' $selected>{$row['nombre']}</option>";
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
                <select name="sisver" id="sisver" style="width: 200px" data-placeholder="Seleccione una versión">
                <?php
                while ($row = mysqli_fetch_assoc($query7)) {
                    $selected = ($row['id_sisver'] == $id_sisver_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_sisver']}' $selected>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="operadora">Operadora</label>
                <select name="operadora" id="operadora" style="width: 200px" data-placeholder="Seleccione una operadora">
                <?php
                while ($row = mysqli_fetch_assoc($query8)) {
                    $selected = ($row['id_operadora'] == $id_operadora_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_operadora']}' $selected>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="sucursal">Sucursal</label>
                <select name="sucursal" id="sucursal" style="width: 200px" data-placeholder="Seleccione la sucursal">
                <option value="">Seleccione la sucursal</option>
                <?php
                while ($row = mysqli_fetch_assoc($query9)) {
                    $selected = ($row['id_sucursal'] == $id_sucursal_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_sucursal']}' $selected>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                </div>
                <div style="margin-right: 75px">
                <label class="nopoint" for="cabezal-cargador" style="width: 250px; height: 10px;">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios">     
                <label><input type="checkbox" id="cabezal-cargador" name="accesorios[]" value="cabezal cargador" <?= in_array('cabezal cargador', $accesorios) ? 'checked' : ''; ?>> Cabezal cargador</label>
                <label><input type="checkbox" id="adaptador1" name="accesorios[]" value="adaptador" <?= in_array('adaptador', $accesorios) ? 'checked' : ''; ?>> Adaptador</label>
                <label><input type="checkbox" id="cable-usb" name="accesorios[]" value="cable usb" <?= in_array('cable usb', $accesorios) ? 'checked' : ''; ?>> Cable USB</label>
                <label><input type="checkbox" id="forro1" name="accesorios[]" value="forro" <?= in_array('forro', $accesorios) ? 'checked' : ''; ?>> Forro</label>
                <label><input type="checkbox" id="vidrio-templado" name="accesorios[]" value="vidrio templado" <?= in_array('vidrio templado', $accesorios) ? 'checked' : ''; ?>> Vidrio templado</label>
                <label><input type="checkbox" id="hidrogel" name="accesorios[]" value="hidrogel" <?= in_array('hidrogel', $accesorios) ? 'checked' : ''; ?>> Hidrogel</label>
                <label><input type="checkbox" id="estuche" name="accesorios[]" value="estuche" <?= in_array('estuche', $accesorios) ? 'checked' : ''; ?>> Estuche</label>
                </div>
                </div>
                <div>
                <label class="nopoint" for="whatsapp" style="width: 300px; height: 10px;">Aplicaciones y configuración básica:</label><br>
                <div class="accesorioscheck" id="apps_conf" style="width: 360px; height: 300px; display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="whatsapp" name="apps_conf[]" value="whatsapp" <?= in_array('whatsapp', $apps) ? 'checked' : ''; ?>>WhatsAapp</label>
                    <label><input type="checkbox" id="gmail" name="apps_conf[]" value="gmail" <?= in_array('gmail', $apps) ? 'checked' : ''; ?>>Gmail</label>
                    <label><input type="checkbox" id="adn" name="apps_conf[]" value="adn" <?= in_array('adn', $apps) ? 'checked' : ''; ?>>ADN</label>
                    <label><input type="checkbox" id="facebook" name="apps_conf[]" value="facebook" <?= in_array('facebook', $apps) ? 'checked' : ''; ?>>Facebook</label>
                    <label><input type="checkbox" id="instagram" name="apps_conf[]" value="instagram" <?= in_array('instagram', $apps) ? 'checked' : ''; ?>>Instagram</label>
                    <label><input type="checkbox" id="netflix" name="apps_conf[]" value="netflix" <?= in_array('netflix', $apps) ? 'checked' : ''; ?>>Netflix</label>
                    <label><input type="checkbox" id="youtube" name="apps_conf[]" value="youtube" <?= in_array('youtube', $apps) ? 'checked' : ''; ?>>Youtube</label>
                    <label><input type="checkbox" id="tiktok" name="apps_conf[]" value="tiktok" <?= in_array('tiktok', $apps) ? 'checked' : ''; ?>>TikTok</label>
                    <label><input type="checkbox" id="ubicacion" name="apps_conf[]" value="ubicacion" <?= in_array('ubicacion', $apps) ? 'checked' : ''; ?>>Ubicación</label>
                    <label><input type="checkbox" id="tema_por_defecto" name="apps_conf[]" value="tema por defecto" <?= in_array('tema por defecto', $apps) ? 'checked' : ''; ?>>Tema por defecto</label>
                    <label><input type="checkbox" id="otra" name="apps_conf[]" value="otra" <?= in_array('otra', $apps) ? 'checked' : ''; ?>>Otra</label>
                </div>
                <div id="otra_app_container" style="display: none;">
                    <label for="otra_app">Especifique otras aplicaciones:</label>
                    <input type="text" id="otra_app" name="otra_app" value="<?= $row0['otra_app']?>">
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
                <input type="text" name="imei1" id="imei1" placeholder="Ingrese el IMEI 1" value="<?= $row0['imei1']?>">
                </div>
                <div class="inputs">
                <label for="imei2">IMEI 2</label>
                <input type="text" name="imei2" id="imei2" placeholder="Ingrese el IMEI 2" value="<?= $row0['imei2']?>">
                </div>
                <div class="inputs">
                <label for="imei1">IMEI ADN</label>
                <input type="text" name="imei_adn" id="imei_adn" placeholder="Ingrese el IMEI ADN" value="<?= $row0['imei_adn']?>">
                </div>
                <div class="inputs">
                <label for="serial">Serial</label>
                <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="<?= $row0['serial']?>">
                </div>
                <div class="inputs">
                <label for="imei1">MAC LAN</label>
                <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la MAC LAN" value="<?= $row0['mac_lan']?>">
                </div>
                <div class="inputs">
                <label for="imei1">MAC WIFI</label>
                <input type="text" name="mac_wifi" id="mac_wifi" placeholder="Ingrese la MAC WIFI" value="<?= $row0['mac_wifi']?>">
                </div>
                <div class="inputs">
                <label for="numero">Número telefónico</label>
                <input type="text" name="numero" id="numero" placeholder="Ingrese el número" value="<?= $row0['numero']?>">
                </div>
                <div class="inputs">
                <label for="cuenta_google">Cuenta google</label>
                <input type="text" name="cuenta_google" id="cuenta_google" placeholder="Ingrese el correo" value="<?= $row0['cuenta_google']?>">
                </div>
                <div class="inputs">
                <label for="clave_google">Clave google</label>
                <input type="text" name="clave_google" id="clave_google" placeholder="Ingrese la clave" value="<?= $row0['clave_google']?>">
                </div>
                <div class="inputs">
                <label for="correo_corporativo">Correo corporativo</label>
                <input type="text" name="correo_corporativo" id="correo_corporativo" placeholder="Ingrese el correo" value="<?= $row0['correo_corporativo']?>">
                </div>
                <div class="inputs">
                <label for="clave_corporativo">Clave corporativa</label>
                <input type="text" name="clave_corporativo" id="clave_corporativo" placeholder="Ingrese la clave" value="<?= $row0['clave_corporativo']?>">
                </div>
                <div class="inputs">
                <label for="anydesk">Código Anydesk</label>
                <input type="text" name="anydesk" id="anydesk" placeholder="Ingrese el código" value="<?= $row0['anydesk']?>">
                </div>
                <div class="inputs">
                <label for="pin">Pin de desbloqueo</label>
                <input type="text" name="pin" id="pin" placeholder="Ingrese el pin" value="<?= $row0['pin']?>">
                </div>
                <div class="inputs">
                <label for="cuenta_mi">Cuenta MI</label>
                <input type="text" name="cuenta_mi" id="cuenta_mi" placeholder="Ingrese la cuenta MI" value="<?= $row0['cuenta_mi']?>">
                </div>
                <div class="inputs">
                <label for="clave_mi">Clave MI</label>
                <input type="text" name="clave_mi" id="clave_mi" placeholder="Ingrese la clave MI" value="<?= $row0['clave_mi']?>">
                </div>
                <div class="inputs">
                <label for="precio">Precio</label>
                <input type="text" name="precio" id="precio" placeholder="Ingrese el precio" value="<?= $row0['precio']?>">
                </div>
                <div class="inputs" style="width: 225px">
    <label for="almacenamiento-num" style="width: 200px">Almacenamiento ocupado</label>
    <input type="text" id="almacenamiento-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento" required value="<?= $numero_almacenamiento ?>">
    <select id="unidad" style="width: 80px">
        <option value="GB" <?= $unidad_almacenamiento == 'GB' ? 'selected' : '' ?>>GB</option>
        <option value="MB" <?= $unidad_almacenamiento == 'MB' ? 'selected' : '' ?>>MB</option>
    </select>
</div>
<div class="inputs" style="width: 600px">
    <label for="consumo" style="width: 200px">Consumo de datos</label>
    <input type="text" id="consumo" style="width: 130px; margin-right: -3px" placeholder="Consumo" required value="<?= $numero_consumo ?>">
    <select id="unidad1" style="width: 80px">
        <option value="GB" <?= $unidad_consumo == 'GB' ? 'selected' : '' ?>>GB</option>
        <option value="MB" <?= $unidad_consumo == 'MB' ? 'selected' : '' ?>>MB</option>
    </select>
</div>
<input type="text" name="almacenamiento" id="almacenamiento-hidden" value="<?= $numero_almacenamiento . $unidad_almacenamiento ?>">
<input type="text" name="consumo_datos" id="consumo-hidden" value="<?= $numero_consumo . $unidad_consumo ?>">

<div class="inputs" style="width: 600px">
                <label for="editor">Observación</label>
                <textarea style="width: 1000px; height: 200px" type="text" name="nota" id="editor" placeholder="Mantiene la configuración inicial."><?= $row0['nota']?></textarea>
                </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    const almacenamientoNum = document.getElementById('almacenamiento-num');
    const unidadSelect = document.getElementById('unidad');
    const almacenamientoHidden = document.getElementById('almacenamiento-hidden');

    const consumo = document.getElementById('consumo');
    const unidad1Select = document.getElementById('unidad1');
    const consumoHidden = document.getElementById('consumo-hidden');

    function actualizarAlmacenamientoHidden() {
      const valor = almacenamientoNum.value;
      const unidad = unidadSelect.value;
      const almacenamientoCompleto = `${valor}${unidad}`;
      almacenamientoHidden.value = almacenamientoCompleto;
    }

    function actualizarConsumoHidden() {
      const valor1 = consumo.value;
      const unidad1 = unidad1Select.value;
      const consumoCompleto = `${valor1}${unidad1}`;
      consumoHidden.value = consumoCompleto;
    }

    almacenamientoNum.addEventListener('input', actualizarAlmacenamientoHidden);
    unidadSelect.addEventListener('change', actualizarAlmacenamientoHidden);

    consumo.addEventListener('input', actualizarConsumoHidden);
    unidad1Select.addEventListener('change', actualizarConsumoHidden);

    // Actualizar los valores ocultos al cargar la página
    actualizarAlmacenamientoHidden();
    actualizarConsumoHidden();
  });
</script>
<script>CKEDITOR.replace('editor');</script>

  <div id="statuses" style="display: flex; flex-wrap: wrap;">
  <div class="inputs">
  <label for="vidrio">Estado del vidrio</label>
  <select name="vidrio" id="vidrio">
    <option value="BUENO" <?= $row0['vidrio'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['vidrio'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
    <option value="PARTIDO" <?= $row0['vidrio'] == 'PARTIDO' ? 'selected' : '' ?>>PARTIDO</option>
    <option value="RAYADO" <?= $row0['vidrio'] == 'RAYADO' ? 'selected' : '' ?>>RAYADO</option>
    <option value="NO TIENE" <?= $row0['vidrio'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
    <option value="OTRO" <?= $row0['vidrio'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
  </select>
</div>
  <div class="inputs">
  <label for="forro">Estado del forro</label>
  <select name="forro" id="forro">
    <option value="BUENO" <?= $row0['forro'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['forro'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="NO TIENE" <?= $row0['forro'] == 'NO TIENE'? 'selected' : ''?>>NO TIENE</option>
    <option value="OTRO" <?= $row0['forro'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
  </select>
</div>

<div class="inputs">
  <label for="pantalla">Estado pantalla</label>
  <select name="pantalla" id="pantalla">
    <option value="BUENO" <?= $row0['pantalla'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['pantalla'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="PARTIDO" <?= $row0['pantalla'] == 'PARTIDO'? 'selected' : ''?>>PARTIDO</option>
    <option value="RAYADO" <?= $row0['pantalla'] == 'RAYADO'? 'selected' : ''?>>RAYADO</option>
    <option value="OTRO" <?= $row0['pantalla'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
  </select>
</div>

<div class="inputs">
  <label for="camara">Estado cámara</label>
  <select name="camara" id="camara">
    <option value="BUENO" <?= $row0['camara'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['camara'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="MICA PARTIDA" <?= $row0['camara'] == 'MICA PARTIDA'? 'selected' : ''?>>MICA PARTIDA</option>
    <option value="MICA RAYADA" <?= $row0['camara'] == 'MICA RAYADA'? 'selected' : ''?>>MICA RAYADA</option>
    <option value="OTRO" <?= $row0['camara'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
  </select>
</div>

<div class="inputs">
  <label for="cargador">Estado cargador</label>
  <select name="cargador" id="cargador">
    <option value="BUENO" <?= $row0['cargador'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['cargador'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="NO TIENE" <?= $row0['cargador'] == 'NO TIENE'? 'selected' : ''?>>NO TIENE</option>
    <option value="NO TRAE A REVISIÓN" <?= $row0['cargador'] == 'NO TRAE A REVISIÓN'? 'selected' : ''?>>NO TRAE A REVISIÓN</option>
    <option value="OTRO" <?= $row0['cargador'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
  </select>
</div>

<div class="inputs">
  <label for="cable_usb">Estado cable USB</label>
  <select name="cable_usb" id="cable_usb">
    <option value="BUENO" <?= $row0['cable_usb'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['cable_usb'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="NO TIENE" <?= $row0['cable_usb'] == 'NO TIENE'? 'selected' : ''?>>NO TIENE</option>
    <option value="NO TRAE A REVISIÓN" <?= $row0['cable_usb'] == 'NO TRAE A REVISIÓN'? 'selected' : ''?>>NO TRAE A REVISIÓN</option>
    <option value="OTRO" <?= $row0['cable_usb'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
                </select>
                </div>
                <div class="inputs">
  <label for="cable_usb">Estado Adaptador</label>
  <select name="adaptador" id="adaptador">
    <option value="BUENO" <?= $row0['adaptador'] == 'BUENO'? 'selected' : ''?>>BUENO</option>
    <option value="DAÑADO" <?= $row0['adaptador'] == 'DAÑADO'? 'selected' : ''?>>DAÑADO</option>
    <option value="NO TIENE" <?= $row0['adaptador'] == 'NO TIENE'? 'selected' : ''?>>NO TIENE</option>
    <option value="NO USA" <?= $row0['adaptador'] == 'NO USA'? 'selected' : ''?>>NO USA</option>
    <option value="NO TRAE A REVISIÓN" <?= $row0['adaptador'] == 'NO TRAE A REVISIÓN'? 'selected' : ''?>>NO TRAE A REVISIÓN</option>
    <option value="OTRO" <?= $row0['adaptador'] == 'OTRO'? 'selected' : ''?>>OTRO</option>
                </select>
                </div>
                </div>

<script>
$(document).ready(function() {
    $('#modelo, #personal, #sisver, #operadora, #sucursal').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>
                <input type="submit" value="Actualizar teléfono">
            </form>
        </div>

        </body>
</html>