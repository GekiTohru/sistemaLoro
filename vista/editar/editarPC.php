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
    $id_PC=$_GET['id'];

    $sql = "SELECT computadoras.*, computadoras.id_pc AS id, fabricante.nombre AS fabricante, tipo_equipo.nombre AS tipo, tipo_almacenamiento.nombre AS almacentipo, red_lan.nombre AS red, COALESCE(personal.nombre, 'Sin asignar') AS asignado, sistema_admin.nombre AS sisadmin, COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo, COALESCE(area.nombre, 'Sin área') AS area, COALESCE(sucursal.nombre, 'Sin sucursal') AS sucursal, pc_sis_op.nombre AS s_o
FROM computadoras
INNER JOIN fabricante ON computadoras.id_fabricante = fabricante.id_fabricante
INNER JOIN tipo_almacenamiento ON computadoras.id_almacentipo = tipo_almacenamiento.id_almacentipo
LEFT JOIN red_lan ON computadoras.id_red = red_lan.id_red
INNER JOIN tipo_equipo ON computadoras.id_tipo_equipo = tipo_equipo.id_tipo_equipo
LEFT JOIN pc_sis_op ON computadoras.id_pcso= pc_sis_op.id_pcso
LEFT JOIN sistema_admin ON computadoras.id_sisadmin= sistema_admin.id_sisadmin
LEFT JOIN personal ON computadoras.id_personal = personal.id_personal
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
LEFT JOIN area ON personal.id_area = area.id_area
LEFT JOIN sucursal ON computadoras.id_sucursal = sucursal.id_sucursal
WHERE computadoras.id_pc = $id_PC";

$sql2="SELECT * FROM tipo_equipo WHERE activo = 1";
$sql3="SELECT * FROM fabricante WHERE equipo = 'PC' AND activo = 1";
$sql4="SELECT * FROM personal WHERE activo = 1";
$sql5="SELECT * FROM red_lan WHERE activo = 1";
$sql6="SELECT * FROM pc_sis_op WHERE activo = 1";
$sql7="SELECT * FROM tipo_almacenamiento WHERE activo = 1";
$sql8="SELECT * FROM sistema_admin WHERE activo = 1";
$sql9="SELECT * FROM sucursal WHERE activo = 1";


$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $conexion->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt3 = $conexion->prepare($sql3);
$stmt3->execute();
$result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

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

$row0 = $result[0];
$id_tipo_seleccionado = $row0['id_tipo_equipo'];
$id_personal_seleccionado = $row0['id_personal'];
$id_fabricante_seleccionado = $row0['id_fabricante'];
$id_red_seleccionado = $row0['id_red'];
$id_pcso_seleccionado = $row0['id_pcso'];
$id_almacentipo_seleccionado = $row0['id_almacentipo'];
$id_sisadmin_seleccionado = $row0['id_sisadmin'];
$id_sucursal_seleccionado = $row0['id_sucursal'];


$almacenamiento = $row0['almacenamiento'];
$ram = $row0['ram'];
$numero_almacenamiento = '';
$unidad_almacenamiento = '';




function separarNumeroYUnidad($valor) {
    $numero = '';
    $unidad = '';

    if (strpos($valor, 'GB') !== false) {
        $numero = str_replace('GB', '', $valor);
        $unidad = 'GB';
    } elseif (strpos($valor, 'TB') !== false) {
        $numero = str_replace('TB', '', $valor);
        $unidad = 'TB';
    }

    return [$numero, $unidad];
}

list($numero_almacenamiento, $unidad_almacenamiento) = separarNumeroYUnidad($row0['almacenamiento']);
list($numero_ram, $unidad_ram) = separarNumeroYUnidad($row0['ram']);

$programas = explode(',', $row0['programas']);
$accesorios = explode(',', $row0['accesorios']);


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar-PC</title>
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
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
                editar();
            });
        });

        function editar() {
    $.ajax({
        type: 'POST',
        url: '../../controlador/editar/editarPCFuncion.php',
        data: $('#nuevo').serialize(),
        success: function(data) {
            if (data === 'ok') {
                Swal.fire({
                    icon: "success",
                    title: "PC editado correctamente",
                    showConfirmButton: false,
                    timer: 3000, 
                    allowOutsideClick: true,
                    willClose: () => {
                        window.location.href = '../../vista/index/indexPC.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error al editar el PC",
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
        <h2 style="text-align: center;">Editar Información de PC</h2>
        <h3 style="text-align: center;"><?= $row0['cargo']; ?></h3>
<div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id_pc" value="<?= $row0['id']?>">
                <div style="display: flex; flex-wrap:wrap">
                <div id="fechas" style="display: block; width: 300px">
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="fecha_ult_mant" id="fecha_recep" style="width: 200px" value="<?= $row0['fecha_ult_mant']?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_ult_rev" style="width: 210px">Fecha de la última revisión</label>
                <input type="date" name="fecha_ult_rev" id="fecha_ult_rev" style="width: 200px" value="<?= $row0['fecha_ult_rev']?>">
                </div>
                <div style="margin: 10px">
                <label for="compra_teclado" style="width: 250px">Fecha de compra de teclado</label>
                <input type="date" name="compra_teclado" id="compra_teclado" style="width: 200px" value="<?= $row0['compra_teclado']?>">
                </div>
                <div style="margin: 10px">
                <label for="bateria_reemplazada" style="width: 250px">Fecha de reemplazo batería UPS</label>
                <input type="date" name="bateria_reemplazada" id="bateria_reemplazada" style="width: 200px" value="<?= $row0['bateria_reemplazada']?>">
                </div>
                </div>
                <div style="display: block">
                <div id="selecciones">
                <div style="margin: 10px;">
                <label for="tipo_equipo">Tipo de equipo</label>
                <select id="tipo_equipo" name="tipo_equipo" style="width: 200px" data-placeholder="Seleccione un tipo" required>
                <option value="">Seleccione un tipo</option>
                <?php
                foreach ($result2 as $row) {
                    $selected = ($row['id_tipo_equipo'] == $id_tipo_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_tipo_equipo']}'$selected>{$row['nombre']}</option>";
                }
                ?>
                </select>
                </div>
                <div style="margin: 10px">
                <label for="personal">Asignado a</label>
                <select name="personal" id="personal" style="width: 200px" data-placeholder="Seleccione un asignado">
                <option value="">Seleccione un asignado</option>
                <?php
                foreach ($result4 as $row) {     
                    $selected = ($row['id_personal'] == $id_personal_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_personal']}'$selected>{$row['nombre']}</option>";
                }
                ?>
                </select>
                </div>
                <div style="margin: 10px">
                <label for="fabricante">Fabricante</label>
                <select name="fabricante" id="fabricante" style="width: 200px" data-placeholder="Seleccione un fabricante" required>
                <option value="">Seleccione un fabricante</option>
                <?php
                foreach ($result3 as $row) {
                    $selected = ($row['id_fabricante'] == $id_fabricante_seleccionado)? 'selected' : '';
                    echo "<option value='{$row['id_fabricante']}'$selected>{$row['nombre']}</option>";
                }
                ?>
                </select>
                </div>

                <div style="margin: 10px">
    <label for="red_lan">Tipo de red</label>
    <select name="red_lan" id="red_lan" style="width: 200px" data-placeholder="Seleccione una red">
        <option value="">Seleccione una red</option>
        <?php
        foreach ($result5 as $row) {
            $selected = ($row['id_red'] == $id_red_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_red']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>

<div style="margin: 10px">
    <label for="pc_sis_op">Sistema Operativo</label>
    <select name="pc_sis_op" id="pc_sis_op" style="width: 200px" data-placeholder="Seleccione un sistema operativo" required>
        <option value="">Seleccione un sistema operativo</option>
        <?php
        foreach ($result6 as $row) {
            $selected = ($row['id_pcso'] == $id_pcso_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_pcso']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>
<div style="margin: 10px">
    <label for="tipo_almacenamiento">Tipo de Almacenamiento</label>
    <select name="tipo_almacenamiento" id="tipo_almacenamiento" style="width: 200px" data-placeholder="Seleccione un tipo de almacenamiento" required>
        <option value="">Seleccione un tipo de almacenamiento</option>
        <?php
        foreach ($result7 as $row) {
            $selected = ($row['id_almacentipo'] == $id_almacentipo_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_almacentipo']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>

<div style="margin: 10px">
    <label for="sistema_admin">Sistema Administrativo</label>
    <select name="sistema_admin" id="sistema_admin" style="width: 200px" data-placeholder="Seleccione un sistema administrativo">
        <option value="">Seleccione un sistema administrativo</option>
        <?php
        foreach ($result8 as $row) {
            $selected = ($row['id_sisadmin'] == $id_sisadmin_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_sisadmin']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>

<div style="margin: 10px">
    <label for="sucursal">Sucursal</label>
    <select name="sucursal" id="sucursal" style="width: 200px" data-placeholder="Seleccione una sucursal" required>
        <option value="">Seleccione una sucursal</option>
        <?php
        foreach ($result9 as $row) {
            $selected = ($row['id_sucursal'] == $id_sucursal_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_sucursal']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
                </div>
                </div>
                </div>
<img style id="bg_img" src="../../img/lorobandera.png" width="35%" height="50%" alt="">

                <div>
                <label class="nopoint" for="sin-accesorios">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios" style="display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="sin-accesorios" name="accesorios[]" value="sin accesorios" onclick="desmarcarAccesorios(this)" <?= in_array('sin accesorios', $accesorios) ? 'checked' : ''; ?>> Sin accesorios</label>      
                    <label><input type="checkbox" id="cargador" name="accesorios[]" value="Cargador" onclick="desmarcarSinAccesorios(this)" <?= in_array('Cargador', $accesorios) ? 'checked' : ''; ?>> Cargador</label>
                    <label><input type="checkbox" id="cable_mickey" name="accesorios[]" value="Cable mickey" onclick="desmarcarSinAccesorios(this)" <?= in_array('Cable mickey', $accesorios) ? 'checked' : ''; ?>> Cable tipo mickey</label>
                    <label><input type="checkbox" id="guaya" name="accesorios[]" value="Guaya de seguridad" onclick="desmarcarSinAccesorios(this)" <?= in_array('Guaya de seguridad', $accesorios) ? 'checked' : ''; ?>> Guaya de seguridad</label>
                    <label><input type="checkbox" id="mouse" name="accesorios[]" value="Mouse" onclick="desmarcarSinAccesorios(this)" <?= in_array('Mouse', $accesorios) ? 'checked' : ''; ?>> Mouse</label>
                    <label><input type="checkbox" id="estuche" name="accesorios[]" value="Estuche" onclick="desmarcarSinAccesorios(this)" <?= in_array('Estuche', $accesorios) ? 'checked' : ''; ?>> Estuche</label>
                    <label><input type="checkbox" id="adaptador" name="accesorios[]" value="Adaptador red" onclick="desmarcarSinAccesorios(this)" <?= in_array('Adaptador red', $accesorios) ? 'checked' : ''; ?>> Adaptador red</label>
                    <label><input type="checkbox" id="cubreteclado" name="accesorios[]" value="Cubreteclado" onclick="desmarcarSinAccesorios(this)" <?= in_array('Cubreteclado', $accesorios) ? 'checked' : ''; ?>> Cubreteclado</label>
                    <label><input type="checkbox" id="funda" name="accesorios[]" value="Funda" onclick="desmarcarSinAccesorios(this)" <?= in_array('Funda', $accesorios) ? 'checked' : ''; ?>> Funda</label>
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
                <div>
                <label class="nopoint" for="sin-programas">Seleccione los programas:</label><br>
                <div class="accesorioscheck" id="accesorios" style="display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="sin-programas" name="programas[]" value="sin programas" <?= in_array('sin programas', $programas) ? 'checked' : ''; ?> onclick="desmarcarProgramas(this)" > Sin programas</label>      
                    <label><input type="checkbox" id="anydesk1" name="programas[]" value="AnyDesk" onclick="desmarcarSinProgramas(this)" <?= in_array('AnyDesk', $programas) ? 'checked' : ''; ?>> AnyDesk</label>
                    <label><input type="checkbox" id="avg_antivirus" name="programas[]" value="AVG Antivirus" onclick="desmarcarSinProgramas(this)" <?= in_array('AVG Antivirus', $programas) ? 'checked' : ''; ?>> AVG Antivirus</label>
                    <label><input type="checkbox" id="crystal_reports" name="programas[]" value="Crystal Reports" onclick="desmarcarSinProgramas(this)" <?= in_array('Crystal Reports', $programas) ? 'checked' : ''; ?>> Crystal Reports</label>
                    <label><input type="checkbox" id="google_chrome" name="programas[]" value="Google Chrome" onclick="desmarcarSinProgramas(this)" <?= in_array('Google Chrome', $programas) ? 'checked' : ''; ?>> Google Chrome</label>
                    <label><input type="checkbox" id="microsoft_edge" name="programas[]" value="Microsoft Edge" onclick="desmarcarSinProgramas(this)" <?= in_array('Microsoft Edge', $programas) ? 'checked' : ''; ?>> Microsoft Edge</label>
                    <label><input type="checkbox" id="office" name="programas[]" value="Office" onclick="desmarcarSinProgramas(this)" <?= in_array('Office', $programas) ? 'checked' : ''; ?>> Office</label>
                    <label><input type="checkbox" id="winrar" name="programas[]" value="WinRAR" onclick="desmarcarSinProgramas(this)" <?= in_array('WinRAR', $programas) ? 'checked' : ''; ?>> WinRAR</label>
                    <label><input type="checkbox" id="framework" name="programas[]" value="Framework" onclick="desmarcarSinProgramas(this)" <?= in_array('Framework', $programas) ? 'checked' : ''; ?>> Framework</label>
                    <label><input type="checkbox" id="adn" name="programas[]" value="Sistema ADN" onclick="desmarcarSinProgramas(this)" <?= in_array('Sistema ADN', $programas) ? 'checked' : ''; ?>> Sistema ADN</label>
                    <label><input type="checkbox" id="adobe_acrobat" name="programas[]" value="Adobe Acrobat" onclick="desmarcarSinProgramas(this)" <?= in_array('Adobe Acrobat', $programas) ? 'checked' : ''; ?>> Adobe Acrobat</label>
                    <label><input type="checkbox" id="int_nomina" name="programas[]" value="INT Nómina" onclick="desmarcarSinProgramas(this)" <?= in_array('INT Nómina', $programas) ? 'checked' : ''; ?>> INT Nómina</label>
                    <label><input type="checkbox" id="int_administrativo" name="programas[]" value="INT Administrativo" onclick="desmarcarSinProgramas(this)" <?= in_array('INT Administrativo', $programas) ? 'checked' : ''; ?>> INT Administrativo</label>
                </div>
                <script>
  function desmarcarProgramas(checkbox) {
    if (checkbox.checked) {
      var accesorios = document.getElementsByName('programas[]');
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
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Nombre del equipo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="<?= $row0['nombre']?>">
                </div>
                <div class="inputs">
                <label for="admin">User Admin</label>
                <input type="text" name="admin" id="admin" placeholder="Ingrese usuario admin" value="<?= $row0['user_admin']?>">
                </div>
                <div class="inputs">
                <label for="motherboard">Placa base</label>
                <input type="text" name="motherboard" id="motherboard" placeholder="Ingrese la placa base" value="<?= $row0['motherboard']?>">
                </div>
                <div class="inputs">
    <label for="serial">Serial</label>
    <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="<?= $row0['serial']?>">
</div>
<div class="inputs">
    <label for="procesador">Procesador</label>
    <input type="text" name="procesador" id="procesador" placeholder="Ingrese el procesador" value="<?= $row0['procesador']?>">
</div>
<div class="inputs">
    <label for="costo">Costo</label>
    <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="<?= $row0['costo']?>" pattern="(N/A|[0-9\.]+)">
                <script>
                const input1 = document.getElementById('costo');

                input1.addEventListener('input', () => {
                  const valor1 = input1.value;
                  const regex1 = /^[0-9\.]+$/;
                  if (valor1 === 'N/A') {
                    input1.setCustomValidity('');
                  } else if (!regex1.test(valor1)) {
                    input1.setCustomValidity('Solo se permiten números y puntos (.) o N/A');
                  } else {
                    input1.setCustomValidity('');
                  }
                });
                </script>
</div>
<div class="inputs">
    <label for="clave_win">Clave de Windows</label>
    <input type="text" name="clave_win" id="clave_win" placeholder="Ingrese la clave de Windows" value="<?= $row0['clave_win']?>">
</div>
<div class="inputs">
    <label for="pin">PIN</label>
    <input type="text" name="pin" id="pin" placeholder="Ingrese el PIN" value="<?= $row0['pin']?>">
</div>
<div class="inputs">
    <label for="resp_seguridad">Resp. de Seguridad</label>
    <input type="text" name="resp_seguridad" id="resp_seguridad" placeholder="Ingrese la respuesta de seguridad" value="<?= $row0['resp_seguridad']?>">
</div>
<div class="inputs">
    <label for="ups">UPS</label>
    <input type="text" name="ups" id="ups" placeholder="Ingrese la información del UPS" value="<?= $row0['ups']?>">
</div>
<div class="inputs">
    <label for="potencia_ups">Potencia del UPS</label>
    <input type="text" name="potencia_ups" id="potencia_ups" placeholder="Ingrese la potencia del UPS" value="<?= $row0['potencia_ups']?>">
</div>
<div class="inputs">
    <label for="bateria_ups">Batería del UPS</label>
    <input type="text" name="bateria_ups" id="bateria_ups" placeholder="Ingrese la información de la batería del UPS" value="<?= $row0['bateria_ups']?>">
</div>
<div class="inputs">
    <label for="anydesk">AnyDesk</label>
    <input type="text" name="anydesk" id="anydesk" placeholder="Ingrese la información de AnyDesk" value="<?= $row0['anydesk']?>">
</div>
<div class="inputs">
    <label for="clave_anydesk">Clave de AnyDesk</label>
    <input type="text" name="clave_anydesk" id="clave_anydesk" placeholder="Ingrese la clave de AnyDesk" value="<?= $row0['clave_anydesk']?>">
</div>
<div class="inputs">
    <label for="mac_lan">MAC LAN</label>
    <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la dirección MAC LAN" value="<?= $row0['mac_lan']?>">
</div>
<div class="inputs">
    <label for="mac_wifi">MAC WiFi</label>
    <input type="text" name="mac_wifi" id="mac_wifi" placeholder="Ingrese la dirección MAC WiFi" value="<?= $row0['mac_wifi']?>">
</div>
                <div class="inputs" style="width: 100%">
                <label for="rom-num" style="width: 200px">Almacenamiento</label>
<input type="text" id="rom-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento" value="<?= $numero_almacenamiento ?>" required pattern="[0-9\.]+">
                <script>
                const input2 = document.getElementById('rom-num');

                input2.addEventListener('input', () => {
                  const valor2 = input2.value;
                  const regex2 = /^[0-9\.]+$/;
                  if (!regex2.test(valor2)) {
                    input2.setCustomValidity('Solo se permiten números y puntos (.)');
                  } else {
                    input2.setCustomValidity('');
                  }
                });
                </script>
<select id="unidad" style="width: 80px">
<option value="TB" <?= $unidad_almacenamiento == 'TB' ? 'selected' : '' ?>>TB</option>
<option value="GB" <?= $unidad_almacenamiento == 'GB' ? 'selected' : '' ?>>GB</option>
</select>
</div> 
<div class="inputs" style="width: 100%">
<label for="ram-num" style="width: 200px">RAM</label>
    <input type="text" id="ram-num" style="width: 130px; margin-right: -3px" placeholder="RAM" value="<?= $numero_ram ?>" required pattern="[0-9\.]+">
                <script>
                const input3 = document.getElementById('ram-num');

                input3.addEventListener('input', () => {
                  const valor3 = input3.value;
                  const regex3 = /^[0-9\.]+$/;
                  if (!regex3.test(valor3)) {
                    input3.setCustomValidity('Solo se permiten números y puntos (.)');
                  } else {
                    input3.setCustomValidity('');
                  }
                });
                </script>
    <select id="unidad1" style="width: 80px">
    <option value="GB" <?= $unidad_ram == 'GB' ? 'selected' : '' ?>>GB</option>

</select>
</div>
<input type="hidden" name="rom" id="rom-hidden" value="<?= $numero_almacenamiento . $unidad_almacenamiento ?>">
<input type="hidden" name="ram" id="ram-hidden" value="<?= $numero_ram . $unidad_ram ?>">

<script>
  document.addEventListener('DOMContentLoaded', (event) => {
    const romNum = document.getElementById('rom-num');
    const unidadSelect = document.getElementById('unidad');
    const romHidden = document.getElementById('rom-hidden');

    const ramNum = document.getElementById('ram-num');
    const unidad1Select = document.getElementById('unidad1');
    const ramHidden = document.getElementById('ram-hidden');

    function actualizarRomHidden() {
      const valor = romNum.value;
      const unidad = unidadSelect.value;
      const romCompleto = `${valor}${unidad}`;
      romHidden.value = romCompleto;
    }

    function actualizarRamHidden() {
      const valor1 = ramNum.value;
      const unidad1 = unidad1Select.value;
      const ramCompleto = `${valor1}${unidad1}`;
      ramHidden.value = ramCompleto;
    }

    romNum.addEventListener('input', actualizarRomHidden);
    unidadSelect.addEventListener('change', actualizarRomHidden);

    ramNum.addEventListener('input', actualizarRamHidden);
    unidad1Select.addEventListener('change', actualizarRamHidden);

    // Actualizar los valores ocultos al cargar la página
    actualizarRomHidden();
    actualizarRamHidden();
  });
</script>
</script>
                <div class="inputs">
                <label for="status">Estado de la PC</label>
                <select name="status" id="status">
                <option value="Operativo" <?= $row0['status'] == 'Operativo' ? 'selected' : '' ?>>Operativo</option>
                <option value="Descontinuado" <?= $row0['status'] == 'Descontinuado' ? 'selected' : '' ?>>Descontinuado</option>
                <option value="Dañado" <?= $row0['status'] == 'Dañado' ? 'selected' : '' ?>>Dañado</option>
                </select>
                </div>
                <div class="inputs">
                <label for="prio_sus">Prio. de sustitución</label>
                <select name="prio_sus" id="prio_sus">
                <option value="BAJA" <?= $row0['prio_sus'] == 'BAJA' ? 'selected' : '' ?>>BAJA</option>
                <option value="MEDIA" <?= $row0['prio_sus'] == 'MEDIA' ? 'selected' : '' ?>>MEDIA</option>
                <option value="ALTA" <?= $row0['prio_sus'] == 'ALTA' ? 'selected' : '' ?>>ALTA</option>
                </select>
                </div>
                <div class="inputs">
                <label for="estado_ups">Estado del UPS</label>
                <select name="estado_ups" id="estado_ups">
                <option value="BACKUP" <?= $row0['estado_ups'] == 'BACKUP' ? 'selected' : '' ?>>BACKUP</option>
                <option value="SIN BACKUP" <?= $row0['estado_ups'] == 'SIN BACKUP' ? 'selected' : '' ?>>SIN BACKUP</option>
                <option value="NO TIENE" <?= $row0['estado_ups'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
                </select>
                </div>
                <div class="inputs">
    <label for="mouse">Estado mouse</label>
    <select name="mouse" id="mouse">
        <option value="BUENO" <?= $row0['mouse'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
        <option value="DAÑADO" <?= $row0['mouse'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
        <option value="NO TIENE" <?= $row0['mouse'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
        <option value="OTRO" <?= $row0['mouse'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
    </select>
</div>
<div class="inputs">
    <label for="pantalla/monitor">Pantalla/monitor</label>
    <select name="pantalla_monitor" id="pantalla/monitor">
        <option value="BUENO" <?= $row0['pantalla_monitor'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
        <option value="DAÑADO" <?= $row0['pantalla_monitor'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
        <option value="PARTIDO" <?= $row0['pantalla_monitor'] == 'PARTIDO' ? 'selected' : '' ?>>PARTIDO</option>
        <option value="RAYADO" <?= $row0['pantalla_monitor'] == 'RAYADO' ? 'selected' : '' ?>>RAYADO</option>
        <option value="NO TIENE" <?= $row0['pantalla_monitor'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
        <option value="OTRO" <?= $row0['pantalla_monitor'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
    </select>
</div>
<div class="inputs">
    <label for="estado_teclado">Estado teclado</label>
    <select name="estado_teclado" id="estado_teclado">
        <option value="BUENO" <?= $row0['estado_teclado'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
        <option value="DAÑADO" <?= $row0['estado_teclado'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
        <option value="PARTIDO" <?= $row0['estado_teclado'] == 'PARTIDO' ? 'selected' : '' ?>>PARTIDO</option>
        <option value="NO SE VEN TECLAS" <?= $row0['estado_teclado'] == 'NO SE VEN TECLAS' ? 'selected' : '' ?>>NO SE VEN TECLAS</option>
        <option value="NO TIENE" <?= $row0['estado_teclado'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
        <option value="OTRO" <?= $row0['estado_teclado'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
    </select>
</div>
<div class="inputs">
    <label for="cargador">Estado cargador</label>
    <select name="cargador" id="cargador">
        <option value="BUENO" <?= $row0['cargador'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
        <option value="DAÑADO" <?= $row0['cargador'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
        <option value="NO TIENE" <?= $row0['cargador'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
        <option value="NO USA" <?= $row0['cargador'] == 'NO USA' ? 'selected' : '' ?>>NO USA</option>
        <option value="OTRO" <?= $row0['cargador'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
    </select>
</div>
<div class="inputs">
    <label style="width: 200px" for="cable_mickey">Estado cable mickey</label>
    <select name="cable_mickey" id="cable_mickey">
        <option value="BUENO" <?= $row0['cable_mickey'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
        <option value="DAÑADO" <?= $row0['cable_mickey'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
        <option value="NO TIENE" <?= $row0['cable_mickey'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
        <option value="NO USA" <?= $row0['cable_mickey'] == 'NO USA' ? 'selected' : '' ?>>NO USA</option>
        <option value="OTRO" <?= $row0['cable_mickey'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
    </select>
    </div>
    <div class="inputs">
                <label style="width: 200px" for="camara1">Estado cámara</label>
                <select name="camara" id="camara1">
                <option value="BUENO" <?= $row0['camara'] == 'BUENO' ? 'selected' : '' ?>>BUENO</option>
                <option value="DAÑADO" <?= $row0['camara'] == 'DAÑADO' ? 'selected' : '' ?>>DAÑADO</option>
                <option value="NO TIENE" <?= $row0['camara'] == 'NO TIENE' ? 'selected' : '' ?>>NO TIENE</option>
                <option value="OTRO" <?= $row0['camara'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
                </select>
              
              
                </div>
                </div>
                <div class="notas" style="width: 100%">
                <label for="editor">Observación</label>
                <textarea style="width: 1000px; height: 200px" type="text" name="nota" id="editor" placeholder="" value=""><?= $row0['nota']?></textarea>
                <input type="hidden" id="editor-hidden" name="nota">     
            </div>

<script>
$(document).ready(function() {
    $('#tipo_equipo, #personal, #fabricante, #red_lan, #pc_sis_op , #tipo_almacenamiento, #sistema_admin, #sucursal').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>    
                <div style="display:flex; flex-wrap:wrap">
                <input type="submit" value="Actualizar computadora">  
                <div style="margin-left: 25px; margin-top: 10px">
                <label><input type="checkbox" id="mantenimiento" name="mantenimiento" value="mantenimiento" checked> Se le realizó mantenimiento?</label>
                </div>
                </div> 
            </form>
        </div>
        </body>
</html>