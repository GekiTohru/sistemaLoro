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
$sql10="SELECT * FROM plan_tlf WHERE activo = 1";

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

$stmt10 = $conexion->prepare($sql10);
$stmt10->execute();
$result10 = $stmt10->fetchAll(PDO::FETCH_ASSOC);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear-Tlf</title>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="../../css/styles3.css" rel="stylesheet">
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
            if (data === 'ok') {
                    Swal.fire({
                        icon: "success",
                        title: "Teléfono añadido correctamente",
                        showConfirmButton: false,
                        timer: 3000, 
                        allowOutsideClick: true,
                        willClose: () => {
                            window.location.href = '../../vista/index/indexTelefonos.php';
                        }
                    });
                }else {
                Swal.fire({
                    icon: "error",
                    title: "Error al crear el teléfono",
                    text: data, // Display the error message returned by the server
                    showConfirmButton: true
                });
            }
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
<nav class="navbar navbar-expand-lg navbar-light bg-success">
    <img src="../../img/loro.png" width="30" height="30" alt="">
    <a class="navbar-brand text-white" href="../lobby.php">LORO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="../lobby.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../lobbyCrearTlf.php">Añadir</a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white" href="../lobbyVerTlf.php">Ver y Editar</a>
      </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Gestionar
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../index/indexTelefonos.php">Teléfonos</a>
            <a class="dropdown-item" href="../index/indexPC.php">Computadoras</a>
            <a class="dropdown-item" href="../index/indexImpresoras.php">Impresoras</a>
            <?php if ($_SESSION['permisos'] == 1) {
                      echo'<a class="dropdown-item" href="../index/idxUsuarios.php">Usuarios</a>';
                  }
                  ?>
        </li>
        <li class="nav-item">
      <a class="nav-link text-white" href="../documentacion/doc.html">Documentación</a>
      </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../../controlador/cerrarSesion.php">Salir</a>
        </li>
      </ul>
    </div>
  </nav>
</header>
<body>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nuevo teléfono</h2>

        <div class="users-form">
            <form id="nuevo">
                <div id="fechas" style="display: flex; flex-wrap:wrap">
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
                <div style="margin: 10px">
                <label for="plan">Plan de datos</label>
                <select name="plan" id="plan" style="width: 200px" data-placeholder="Seleccione el plan">
                <option value="">Seleccione el plan</option>
                <?php
                foreach ($result10 as $row) {
                    echo "<option value='{$row['id_plan']}'>{$row['nombre']}</option>";
                }
                ?></select>
                </div>
                </div>
                <div>
                <label class="nopoint" for="sin-accesorios" style="width: 250px; height: 10px;">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios">     
    <label><input type="checkbox" id="sin-accesorios" name="accesorios[]" value="sin accesorios" onclick="desmarcarAccesorios(this)" checked> Sin accesorios</label>
    <label><input type="checkbox" id="cabezal-cargador" name="accesorios[]" value="cabezal cargador" onclick="desmarcarSinAccesorios(this)"> Cabezal cargador</label>
    <label><input type="checkbox" id="adaptador1" name="accesorios[]" value="adaptador" onclick="desmarcarSinAccesorios(this)"> Adaptador</label>
    <label><input type="checkbox" id="cable-usb" name="accesorios[]" value="cable usb" onclick="desmarcarSinAccesorios(this)"> Cable USB</label>
    <label><input type="checkbox" id="forro1" name="accesorios[]" value="forro" onclick="desmarcarSinAccesorios(this)"> Forro</label>
    <label><input type="checkbox" id="vidrio-templado" name="accesorios[]" value="vidrio templado" onclick="desmarcarSinAccesorios(this)"> Vidrio templado</label>
    <label><input type="checkbox" id="hidrogel" name="accesorios[]" value="hidrogel" onclick="desmarcarSinAccesorios(this)"> Hidrogel</label>
    <label><input type="checkbox" id="estuche" name="accesorios[]" value="estuche" onclick="desmarcarSinAccesorios(this)"> Estuche</label>
</div>

<script>
  function desmarcarAccesorios(checkbox) {
    if (checkbox.checked) {
      var accesorios = document.getElementsByName('accesorios[]');
      for (var i = 0; i < accesorios.length; i++) {
        if (accesorios[i].id !== 'sin-accesorios') {
          accesorios[i].checked = false;
        }
      }
    }
  }

  function desmarcarSinAccesorios(checkbox) {
    if (checkbox.checked) {
      document.getElementById('sin-accesorios').checked = false;
    }
  }
</script>
                </div>
  <img id="bg_img" src="../../img/lorobandera.png" width="30%" height="30%" alt="">

                <div>
                <label class="nopoint" for="whatsapp" style="width: 300px; height: 10px;">Aplicaciones y configuración básica:</label><br>
                <div class="accesorioscheck" id="apps_conf" style="display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="sin-programas" name="apps_conf[]" value="sin programas" onclick="desmarcarProgramas(this)" checked> Sin aplicaciones</label>
                    <label><input type="checkbox" id="whatsapp" name="apps_conf[]" value="whatsapp" onclick="desmarcarSinProgramas(this)">WhatsAapp</label>
                    <label><input type="checkbox" id="gmail" name="apps_conf[]" value="gmail" onclick="desmarcarSinProgramas(this)">Gmail</label>
                    <label><input type="checkbox" id="adn" name="apps_conf[]" value="adn" onclick="desmarcarSinProgramas(this)">ADN</label>
                    <label><input type="checkbox" id="facebook" name="apps_conf[]" value="facebook" onclick="desmarcarSinProgramas(this)">Facebook</label>
                    <label><input type="checkbox" id="instagram" name="apps_conf[]" value="instagram" onclick="desmarcarSinProgramas(this)">Instagram</label>
                    <label><input type="checkbox" id="netflix" name="apps_conf[]" value="netflix" onclick="desmarcarSinProgramas(this)">Netflix</label>
                    <label><input type="checkbox" id="youtube" name="apps_conf[]" value="youtube" onclick="desmarcarSinProgramas(this)">Youtube</label>
                    <label><input type="checkbox" id="tiktok" name="apps_conf[]" value="tiktok" onclick="desmarcarSinProgramas(this)">TikTok</label>
                    <label><input type="checkbox" id="ubicacion" name="apps_conf[]" value="ubicacion" onclick="desmarcarSinProgramas(this)">Ubicación</label>
                    <label><input type="checkbox" id="tema_por_defecto" name="apps_conf[]" value="tema por defecto" onclick="desmarcarSinProgramas(this)">Tema por defecto</label>
                    <label><input type="checkbox" id="otra" name="apps_conf[]" value="otra" onclick="desmarcarSinProgramas(this)">Otra</label>
                </div>
                <div id="otra_app_container" style="display: none; width: 80%">
                    <label for="otra_app">Especifique otras aplicaciones:</label>
                    <input type="text" id="otra_app" name="otra_app" value="">
                </div>
                <script>
  function desmarcarProgramas(checkbox) {
    if (checkbox.checked) {
      var accesorios = document.getElementsByName('apps_conf[]');
      for (var i = 0; i < accesorios.length; i++) {
        if (accesorios[i].id !== 'sin-programas') {
          accesorios[i].checked = false;
        }
      }
    }
  }

  function desmarcarSinProgramas(checkbox) {
    if (checkbox.checked) {
      document.getElementById('sin-programas').checked = false;
    }
  }
</script>
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
                <input type="text" name="numero" id="numero" placeholder="Ingrese el número" value="" pattern="[0-9]{4}-[0-9]{7}" title="Formato incorrecto. Debe ser xxxx-xxxxxxx">
                <script>
                const input = document.getElementById('numero');

                input.addEventListener('input', () => {
                    const valor = input.value;
                    const regex = /^[0-9]{4}-[0-9]{7}$/;
                    if (!regex.test(valor)) {
                    input.setCustomValidity('Formato incorrecto. Debe ser xxxx-xxxxxxx');
                    } else {
                    input.setCustomValidity('');
                    }
                });
                </script>
                </div>
                <div class="inputs">
                <label for="cuenta_google">Cuenta google</label>
                <input type="text" name="cuenta_google" id="cuenta_google" placeholder="Ingrese el correo" value="">
                </div>
                <div class="inputs">
                <label for="clave_google">Clave google</label>
                <input type="text" name="clave_google" id="clave_google" placeholder="Ingrese la clave" value="@Loro1234">
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
                <input type="text" name="pin" id="pin" placeholder="Ingrese el pin" value="1234">
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
                <div class="inputs" style="width: 600px">
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
<div class="notas" style="width: 600px">
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
$(document).ready(function() {
    $('#modelo, #personal, #sisver, #operadora, #sucursal, #plan').select2({
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