<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../../login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

    include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

$sql2="SELECT * FROM modelo_marca WHERE activo = 1";
$sql4="SELECT * FROM personal WHERE activo = 1";
$sql5="SELECT * FROM cargo_ruta WHERE activo = 1";
$sql6="SELECT * FROM area WHERE activo = 1";
$sql7="SELECT * FROM tlf_sisver WHERE activo = 1";
$sql8="SELECT * FROM operadora WHERE activo = 1";
$sql9="SELECT * FROM sucursal WHERE activo = 1";

$stmt2 = $conexion->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt4 = $conexion->prepare($sql4);
$stmt4->execute();
$result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$stmt5 = $conexion->prepare($sql5);
$stmt5->execute();
$result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);

$stmt6 = $conexion->prepare($sql6);
$stmt6->execute();
$result6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);

$stmt7 = $conexion->prepare($sql7);
$stmt7->execute();
$result7 = $stmt7->fetchAll(PDO::FETCH_ASSOC);

$stmt8 = $conexion->prepare($sql8);
$stmt8->execute();
$result8 = $stmt8->fetchAll(PDO::FETCH_ASSOC);

$stmt9 = $conexion->prepare($sql9);
$stmt9->execute();
$result9 = $stmt9->fetchAll(PDO::FETCH_ASSOC);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear-Tlf</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
            }
        }
    </script>

    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Font,
            Paragraph
        } from 'ckeditor5';

        let editorInstance;

        ClassicEditor
            .create(document.querySelector('#editor'), {
                plugins: [Essentials, Bold, Italic, Font, Paragraph],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                    ]
                }
            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

        $(document).ready(function() {
            $('#nuevo').submit(function(event) {
                event.preventDefault();

                // Obtener el contenido del editor
                let content = editorInstance.getData();

                // Reemplazar hsl por rgb en el contenido
                content = replaceHslWithRgb(content);

                // Actualizar el campo de textarea con el contenido modificado
                editorInstance.setData(content);

                // Actualizar el textarea oculto antes de enviar el formulario
                document.querySelector('#editor-hidden').value = content;

                // Enviar el formulario
                crear();
            });
        });

        function crear() {
            $.ajax({
                type: 'POST',
                url: '../../controlador/crear/crearTlfFuncion.php',
                data: $('#nuevo').serialize(),
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Teléfono creado correctamente",
                        showConfirmButton: false,
                        timer: 3000, 
                        allowOutsideClick: true,
                        willClose: () => {
                            window.location.href = '../../vista/index/indexTelefonos.php';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        }

        function hslToRgb(h, s, l) {
            s /= 100;
            l /= 100;
            let c = (1 - Math.abs(2 * l - 1)) * s,
                x = c * (1 - Math.abs((h / 60) % 2 - 1)),
                m = l - c / 2,
                r = 0,
                g = 0,
                b = 0;
            if (0 <= h && h < 60) {
                r = c; g = x; b = 0;
            } else if (60 <= h && h < 120) {
                r = x; g = c; b = 0;
            } else if (120 <= h && h < 180) {
                r = 0; g = c; b = x;
            } else if (180 <= h && h < 240) {
                r = 0; g = x; b = c;
            } else if (240 <= h && h < 300) {
                r = x; g = 0; b = c;
            } else if (300 <= h && h < 360) {
                r = c; g = 0; b = x;
            }
            r = Math.round((r + m) * 255);
            g = Math.round((g + m) * 255);
            b = Math.round((b + m) * 255);

            return `rgb(${r}, ${g}, ${b})`;
        }

        function replaceHslWithRgb(content) {
            return content.replace(/background-color:hsl\((\d+), (\d+)%, (\d+)%\);/g, function(match, h, s, l) {
                return `background-color:${hslToRgb(h, s, l)};`;
            });
        }
    </script>
</head>
<header>
<div style="height: 50px;"></div>
    <img src="../../img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="../../controlador/cerrarSesion.php" class="navbtn">Salir</a>
            <a href="../lobby.php" class="navbtn">Inicio</a>
            <a href="../lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="../lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="../index/indexTelefonos.php">Teléfonos</a>
                     <a href="../index/indexPc.php">Computadoras</a>
                     <a href="../index/indexImpresoras.php">Impresoras</a>
                     <?php if ($_SESSION['permisos'] == 1) {
                  echo'<a href="../index/idxUsuarios.php">Usuarios</a>';
                        }
                  ?>
                 </div>
                </div>
            <a href="../documentacion/doc.html" class="navbtn">Documentación</a>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nuevo teléfono</h2>

        <div class="users-form">
            <form id="nuevo">
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
                foreach ($result2 as $row) {
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
                foreach ($result4 as $row) {
                    echo "<option value='{$row['id_personal']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="sisver">Versión del sistema</label>
                <select name="sisver" id="sisver" style="width: 200px" data-placeholder="Seleccione una versión" required>
                <option value="">Seleccione una versión</option>
                <?php
                foreach ($result7 as $row) {
                    echo "<option value='{$row['id_sisver']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="operadora">Operadora</label>
                <select name="operadora" id="operadora" style="width: 200px" data-placeholder="Seleccione una operadora">
                <option value="">Seleccione una operadora</option>
                <?php
                foreach ($result8 as $row) {
                    echo "<option value='{$row['id_operadora']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                <div style="margin: 10px">
                <label for="sucursal">Sucursal</label>
                <select name="sucursal" id="sucursal" style="width: 200px" data-placeholder="Seleccione la sucursal">
                <option value="">Seleccione la sucursal</option>
                <?php
                foreach ($result9 as $row) {
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
                    <label><input type="checkbox" id="tiktok" name="apps_conf[]" value="tiktok">TikTok</label>
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
<label for="editor">Observación</label>
                <textarea style="width: 1000px; height: 200px" type="text" name="nota" id="editor" placeholder="" value=""></textarea>
                <input type="hidden" id="editor-hidden" name="nota">        
            </div>
</div>
<input type="hidden" name="almacenamiento" id="almacenamiento-hidden">
<input type="hidden" name="consumo_datos" id="consumo-hidden">

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
                <option value="MICA RAYADA">MICA RAYADA</option>
                <option value="MICA PARTIDA">MICA PARTIDA</option>
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