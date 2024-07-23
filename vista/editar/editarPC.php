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
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
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
                editar();
            });
        });

        function editar() {
            $.ajax({
                type: 'POST',
                url: '../../controlador/editar/editarPCFuncion.php',
                data: $('#nuevo').serialize(),
                success: function(data) {
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
        <h2 style="text-align: center;">Editar Información de PC</h2>
<div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id_pc" value="<?= $row0['id']?>">
                <div style="display: flex">
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
                <div style="margin-right: 25px">
                <label class="nopoint" for="anydesk1">Seleccione los programas:</label><br>
                <div class="accesorioscheck" id="accesorios" style="width: 360px; height: 300px; display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="anydesk1" name="programas[]" value="AnyDesk" <?= in_array('AnyDesk', $programas) ? 'checked' : ''; ?>> AnyDesk</label>
                    <label><input type="checkbox" id="avg_antivirus" name="programas[]" value="AVG Antivirus" <?= in_array('AVG Antivirus', $programas) ? 'checked' : ''; ?>> AVG Antivirus</label>
                    <label><input type="checkbox" id="crystal_reports" name="programas[]" value="Crystal Reports" <?= in_array('Crystal Reports', $programas) ? 'checked' : ''; ?>> Crystal Reports</label>
                    <label><input type="checkbox" id="google_chrome" name="programas[]" value="Google Chrome" <?= in_array('Google Chrome', $programas) ? 'checked' : ''; ?>> Google Chrome</label>
                    <label><input type="checkbox" id="microsoft_edge" name="programas[]" value="Microsoft Edge" <?= in_array('Microsoft Edge', $programas) ? 'checked' : ''; ?>> Microsoft Edge</label>
                    <label><input type="checkbox" id="office" name="programas[]" value="Office" <?= in_array('Office', $programas) ? 'checked' : ''; ?>> Office</label>
                    <label><input type="checkbox" id="winrar" name="programas[]" value="WinRAR" <?= in_array('WinRAR', $programas) ? 'checked' : ''; ?>> WinRAR</label>
                    <label><input type="checkbox" id="framework" name="programas[]" value="Framework" <?= in_array('Framework', $programas) ? 'checked' : ''; ?>> Framework</label>
                    <label><input type="checkbox" id="adn" name="programas[]" value="Sistema ADN" <?= in_array('Sistema ADN', $programas) ? 'checked' : ''; ?>> Sistema ADN</label>
                    <label><input type="checkbox" id="adobe_acrobat" name="programas[]" value="Adobe Acrobat" <?= in_array('Adobe Acrobat', $programas) ? 'checked' : ''; ?>> Adobe Acrobat</label>
                    <label><input type="checkbox" id="int_nomina" name="programas[]" value="INT Nómina" <?= in_array('INT Nómina', $programas) ? 'checked' : ''; ?>> INT Nómina</label>
                    <label><input type="checkbox" id="int_administrativo" name="programas[]" value="INT Administrativo" <?= in_array('INT Administrativo', $programas) ? 'checked' : ''; ?>> INT Administrativo</label>
                </div>
                </div>
                <div style="margin-right: 75px">
                <label class="nopoint" for="cargador">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios" style="width: 300px; display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="cargador" name="accesorios[]" value="Cargador" <?= in_array('Cargador', $accesorios) ? 'checked' : ''; ?>> Cargador</label>
                    <label><input type="checkbox" id="cable_mickey" name="accesorios[]" value="Cable mickey" <?= in_array('Cable mickey', $accesorios) ? 'checked' : ''; ?>> Cable tipo mickey</label>
                    <label><input type="checkbox" id="guaya" name="accesorios[]" value="Guaya de seguridad" <?= in_array('Guaya de seguridad', $accesorios) ? 'checked' : ''; ?>> Guaya de seguridad</label>
                    <label><input type="checkbox" id="mouse" name="accesorios[]" value="Mouse" <?= in_array('Mouse', $accesorios) ? 'checked' : ''; ?>> Mouse</label>
                    <label><input type="checkbox" id="estuche" name="accesorios[]" value="Estuche" <?= in_array('Estuche', $accesorios) ? 'checked' : ''; ?>> Estuche</label>
                    <label><input type="checkbox" id="adaptador" name="accesorios[]" value="Adaptador red" <?= in_array('Adaptador red', $accesorios) ? 'checked' : ''; ?>> Adaptador red</label>
                    <label><input type="checkbox" id="cubreteclado" name="accesorios[]" value="Cubreteclado" <?= in_array('Cubreteclado', $accesorios) ? 'checked' : ''; ?>> Cubreteclado</label>
                    <label><input type="checkbox" id="funda" name="accesorios[]" value="Funda" <?= in_array('Funda', $accesorios) ? 'checked' : ''; ?>> Funda</label>
                </div>
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
    <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="<?= $row0['costo']?>">
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
                <div class="inputs" style="width: 225px">
                <label for="rom-num" style="width: 200px">Almacenamiento</label>
<input type="text" id="rom-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento" value="<?= $numero_almacenamiento ?>" required>
<select id="unidad" style="width: 80px">
<option value="TB" <?= $unidad_almacenamiento == 'TB' ? 'selected' : '' ?>>TB</option>
<option value="GB" <?= $unidad_almacenamiento == 'GB' ? 'selected' : '' ?>>GB</option>
</select>
</div> 
<div class="inputs" style="width: 225px">
<label for="ram-num" style="width: 200px">RAM</label>
    <input type="text" id="ram-num" style="width: 130px; margin-right: -3px" placeholder="RAM" value="<?= $numero_ram ?>" required>
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
                <div class="inputs" style="width: 600px">
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
                <div style="display:flex">
                <input type="submit" value="Actualizar computadora">  
                <div style="margin-left: 25px; margin-top: 10px">
                <label><input type="checkbox" id="mantenimiento" name="mantenimiento" value="mantenimiento"> Se le realizó mantenimiento?</label>
                </div>
                </div> 
            </form>
        </div>
        </body>
</html>