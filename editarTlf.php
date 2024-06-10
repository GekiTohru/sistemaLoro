<?php 
    include("conexion.php");
    $id_telefono=$_GET['id'];

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
    <img src="img/logo.png" id="logo">
</header>
<body>
<a href="index.php">hola mundo!!!</a>
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
        <h2>Editar</h2>
            <form action="editarTlfFuncion.php" method="POST">
                <input type="hidden" name="id_telefono" value="<?= $row['id_telefono']?>">
                <input type="date" name="fecha_recep" value="<?= $row['fecha_recep']?>">
                <select name="vidrio" id="vidrio">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
                <option value="RAYADO">RAYADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                <input type="checkbox" id="dummy" name="accesorios[]" required style="display:none">
                <div id="accesorios">
  <label>Seleccione los valores:</label>
  <label><input type="checkbox" id="cabezal-cargador" name="accesorios[]" value="cabezal cargador"> Cabezal cargador</label>
  <label><input type="checkbox" id="adaptador" name="accesorios[]" value="adaptador"> Adaptador</label>
  <label><input type="checkbox" id="cable-usb" name="accesorios[]" value="cable usb"> Cable USB</label>
  <label><input type="checkbox" id="forro" name="accesorios[]" value="forro"> Forro</label>
  <label><input type="checkbox" id="vidrio-templado" name="accesorios[]" value="vidrio templado"> Vidrio templado</label>
  <label><input type="checkbox" id="hidrogel" name="accesorios[]" value="hidrogel"> Hidrogel</label>
  <label><input type="checkbox" id="estuche" name="accesorios[]" value="estuche"> Estuche</label>
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

                <input type="submit" value="Actualizar">
            </form>
        </div>
        <div class="users-form">
        <h2>Editar</h2>
            <form id="nuevo" action="editarTlfFuncion.php" method="POST">
                <div id="fechas" style="display: flex">
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px;">Fecha de recepción</label>
                <input type="date" name="fecha_recep" id="fecha_recep" style="width: 200px" value="<?= $row['fecha_recep']?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="fecha_ult_mant" id="fecha_recep" style="width: 200px" value="<?= $row['fecha_ult_mant']?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha de la última revisión</label>
                <input type="date" name="fecha_ult_rev" id="fecha_recep" style="width: 200px" value="<?= $row['fecha_ult_rev']?>">
                </div>
                </div>
                <div style="display: flex; flex-wrap: wrap">
                <div id="selecciones" style="display: block;">
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
                <select name="sisver" id="sisver" style="width: 200px" data-placeholder="Seleccione una versión">
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
                <div>
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
                <label for="serial">Serial</label>
                <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="">
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
                <div class="inputs">
                <label for="nota">Nota</label>
                <input type="text" name="nota" id="nota" placeholder="Ingrese una nota" value="">
                </div>
                <div class="inputs" style="width: 350px">
                <label for="almacenamiento-num" style="width: 200px">Almacenamiento ocupado</label>
<input type="text" id="almacenamiento-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento">
<select id="unidad" style="width: 80px">
  <option value="GB">GB</option>
  <option value="MB">MB</option>
</select>
</div>
</div>
<input type="hidden" name="almacenamiento" id="almacenamiento-hidden">

<script>
  const almacenamientoNum = document.getElementById('almacenamiento-num');
  const unidadSelect = document.getElementById('unidad');
  const almacenamientoHidden = document.getElementById('almacenamiento-hidden');

  almacenamientoNum.addEventListener('input', () => {
    const valor = almacenamientoNum.value;
    const unidad = unidadSelect.value;
    const almacenamientoCompleto = `${valor} ${unidad}`;
    almacenamientoHidden.value = almacenamientoCompleto;
  });
</script>
                <div id="statuses" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="vidrio">Estado del vidrio</label>
                <select name="vidrio" id="vidrio">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
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
                <option value="PARTIDO">PARTIDO</option>
                <option value="RAYADO">RAYADO</option>
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