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

    $sql2="SELECT * FROM tipo_equipo";
    $sql3="SELECT * FROM fabricante WHERE equipo = 'PC'";
    $sql4="SELECT * FROM personal";
    $sql5="SELECT * FROM red_lan";
    $sql6="SELECT * FROM pc_sis_op";
    $sql7="SELECT * FROM tipo_almacenamiento";
    $sql8="SELECT * FROM sistema_admin";
    $sql9="SELECT * FROM sucursal";

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

$row = $result3[0]; // equivalente a mysqli_fetch_array($query3)
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    


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
                     <a href="../indexTelefonos.php">Teléfonos</a>
                     <a href="../indexPc.php">Computadoras</a>
                     <a href="../indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="../idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Añadir nueva PC</h2>
        <div class="users-form">
            <form id="nuevo" onsubmit="return crear()" style="display: flex; flex-wrap: wrap">
                <div style="display: flex">
                <div id="fechas" style="display: block; width: 300px">
                <div style="margin: 10px">
                <label for="fecha_recep" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="fecha_ult_mant" id="fecha_recep" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="margin: 10px">
                <label for="fecha_ult_rev" style="width: 210px">Fecha de la última revisión</label>
                <input type="date" name="fecha_ult_rev" id="fecha_ult_rev" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="margin: 10px">
                <label for="compra_teclado" style="width: 250px">Fecha de compra de teclado</label>
                <input type="date" name="compra_teclado" id="compra_teclado" style="width: 200px" value="">
                </div>
                <div style="margin: 10px">
                <label for="bateria_reemplazada" style="width: 250px">Fecha de reemplazo batería UPS</label>
                <input type="date" name="bateria_reemplazada" id="bateria_reemplazada" style="width: 200px" value="">
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
                    echo "<option value='{$row['id_tipo_equipo']}'>{$row['nombre']}</option>";
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
                    echo "<option value='{$row['id_personal']}'>{$row['nombre']}</option>";
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
                    echo "<option value='{$row['id_fabricante']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_red']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_pcso']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_almacentipo']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_sisadmin']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_sucursal']}'>{$row['nombre']}</option>";
        }
       ?>
    </select>
                </div>
                </div>
                </div>
                <input type="checkbox" id="dummy" name="programas[]" required style="display:none">
                <div style="margin-right: 25px">
                <label class="nopoint" for="anydesk1">Seleccione los programas:</label><br>
                <div class="accesorioscheck" id="accesorios" style="width: 360px; height: 300px; display: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="anydesk1" name="programas[]" value="AnyDesk"> AnyDesk</label>
                    <label><input type="checkbox" id="avg_antivirus" name="programas[]" value="AVG Antivirus"> AVG Antivirus</label>
                    <label><input type="checkbox" id="crystal_reports" name="programas[]" value="Crystal Reports"> Crystal Reports</label>
                    <label><input type="checkbox" id="google_chrome" name="programas[]" value="Google Chrome"> Google Chrome</label>
                    <label><input type="checkbox" id="microsoft_edge" name="programas[]" value="Microsoft Edge"> Microsoft Edge</label>
                    <label><input type="checkbox" id="office" name="programas[]" value="Office"> Office</label>
                    <label><input type="checkbox" id="winrar" name="programas[]" value="WinRAR"> WinRAR</label>
                    <label><input type="checkbox" id="framework" name="programas[]" value="Framework"> Framework</label>
                    <label><input type="checkbox" id="adn" name="programas[]" value="Sistema ADN"> Sistema ADN</label>
                    <label><input type="checkbox" id="adobe_acrobat" name="programas[]" value="Adobe Acrobat"> Adobe Acrobat</label>
                    <label><input type="checkbox" id="int_nomina" name="programas[]" value="INT Nómina"> INT Nómina</label>
                    <label><input type="checkbox" id="int_administrativo" name="programas[]" value="INT Administrativo"> INT Administrativo</label>
                    <label><input type="checkbox" id="whatsapp" name="programas[]" value="WhatsApp"> WhatsApp</label>
                </div>
                </div>
                <input type="checkbox" id="dummy1" name="accesorios[]" required style="display:none">
                <div style="margin-right: 75px" >
                <label class="nopoint" for="cargador">Seleccione los accesorios:</label><br>
                <div class="accesorioscheck" id="accesorios" style="width: 360px; hedisplay: flex; flex-wrap: wrap;">       
                    <label><input type="checkbox" id="cargador" name="accesorios[]" value="Cargador"> Cargador</label>
                    <label><input type="checkbox" id="cable_mickey" name="accesorios[]" value="Cable mickey"> Cable tipo mickey</label>
                    <label><input type="checkbox" id="guaya" name="accesorios[]" value="Guaya de seguridad"> Guaya de seguridad</label>
                    <label><input type="checkbox" id="mouse" name="accesorios[]" value="Mouse"> Mouse</label>
                    <label><input type="checkbox" id="estuche" name="accesorios[]" value="Estuche"> Estuche</label>
                    <label><input type="checkbox" id="adaptador" name="accesorios[]" value="Adaptador red"> Adaptador red</label>
                    <label><input type="checkbox" id="cubreteclado" name="accesorios[]" value="Cubreteclado"> Cubreteclado</label>
                    <label><input type="checkbox" id="funda" name="accesorios[]" value="Funda"> Funda</label>
                </div>
                </div>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Nombre del equipo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="">
                </div>
                <div class="inputs">
                <label for="admin">User Admin</label>
                <input type="text" name="admin" id="admin" placeholder="Ingrese usuario admin" value="Soporte">
                </div>
                <div class="inputs">
                <label for="motherboard">Placa base</label>
                <input type="text" name="motherboard" id="motherboard" placeholder="Ingrese la placa base" value="">
                </div>
                <div class="inputs">
    <label for="serial">Serial</label>
    <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="">
</div>
<div class="inputs">
    <label for="procesador">Procesador</label>
    <input type="text" name="procesador" id="procesador" placeholder="Ingrese el procesador" value="">
</div>
<div class="inputs">
    <label for="costo">Costo</label>
    <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="">
</div>
<div class="inputs">
    <label for="clave_win">Clave de Windows</label>
    <input type="text" name="clave_win" id="clave_win" placeholder="Ingrese la clave de Windows" value="LoroLara23**">
</div>
<div class="inputs">
    <label for="pin">PIN</label>
    <input type="text" name="pin" id="pin" placeholder="Ingrese el PIN" value="">
</div>
<div class="inputs">
    <label for="resp_seguridad">Resp. de Seguridad</label>
    <input type="text" name="resp_seguridad" id="resp_seguridad" placeholder="Ingrese la respuesta de seguridad" value="loro">
</div>
<div class="inputs">
    <label for="ups">UPS</label>
    <input type="text" name="ups" id="ups" placeholder="Ingrese la información del UPS" value="">
</div>
<div class="inputs">
    <label for="potencia_ups">Potencia del UPS</label>
    <input type="text" name="potencia_ups" id="potencia_ups" placeholder="Ingrese la potencia del UPS" value="">
</div>
<div class="inputs">
    <label for="bateria_ups">Batería del UPS</label>
    <input type="text" name="bateria_ups" id="bateria_ups" placeholder="Ingrese la información de la batería del UPS" value="">
</div>
<div class="inputs">
    <label for="anydesk">AnyDesk</label>
    <input type="text" name="anydesk" id="anydesk" placeholder="Ingrese la información de AnyDesk" value="">
</div>
<div class="inputs">
    <label for="clave_anydesk">Clave de AnyDesk</label>
    <input type="text" name="clave_anydesk" id="clave_anydesk" placeholder="Ingrese la clave de AnyDesk" value="LoroLara22**">
</div>
<div class="inputs">
    <label for="mac_lan">MAC LAN</label>
    <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la dirección MAC LAN" value="">
</div>
<div class="inputs">
    <label for="mac_wifi">MAC WiFi</label>
    <input type="text" name="mac_wifi" id="mac_wifi" placeholder="Ingrese la dirección MAC WiFi" value="">
</div>
                <div class="inputs" style="width: 225px">
                <label for="rom-num" style="width: 200px">Almacenamiento</label>
<input type="text" id="rom-num" style="width: 130px; margin-right: -3px" placeholder="Almacenamiento">
<select id="unidad" style="width: 80px">
  <option value="GB">GB</option>
  <option value="TB">TB</option>
</select>
</div>
<div class="inputs" style="width: 225px">
<label for="ram-num" style="width: 200px">RAM</label>
    <input type="text" id="ram-num" style="width: 130px; margin-right: -3px" placeholder="RAM" required>
    <select id="unidad1" style="width: 80px">
        <option value="GB">GB</option>
</select>
</div>
<input type="hidden" name="rom" id="rom-hidden">
<input type="hidden" name="ram" id="ram-hidden">

<script>
  const romNum = document.getElementById('rom-num');
  const unidad1Select = document.getElementById('unidad');
  const romHidden = document.getElementById('rom-hidden');

  const updateRomHidden = () => {
    const valor = romNum.value;
    const unidad1 = unidad1Select.value;
    const romCompleto = `${valor} ${unidad1}`;
    romHidden.value = romCompleto;
  };

  romNum.addEventListener('input', updateRomHidden);
  unidad1Select.addEventListener('change', updateRomHidden);
</script>

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
                <div class="inputs">
                <label for="status">Estado de la PC</label>
                <select name="status" id="status">
                <option value="Operativo">Operativo</option>
                <option value="Descontinuado">Descontinuado</option>
                <option value="Dañado">Dañado</option>
                </select>
                </div>
                <div class="inputs">
                <label for="prio_sus">Prio. de sustitución</label>
                <select name="prio_sus" id="prio_sus">
                <option value="BAJA">BAJA</option>
                <option value="MEDIA">MEDIA</option>
                <option value="ALTA">ALTA</option>
                </select>
                </div>
                <div class="inputs">
                <label for="estado_ups">Estado del UPS</label>
                <select name="estado_ups" id="estado_ups">
                <option value="BACKUP">BACKUP</option>
                <option value="SIN BACKUP">SIN BACKUP</option>
                <option value="NO TIENE">NO TIENE</option>
                </select>
                </div>
                <div class="inputs">
                <label for="mouse1">Estado mouse</label>
                <select name="mouse" id="mouse1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="pantalla/monitor1">Pantalla/monitor</label>
                <select name="pantalla_monitor" id="pantalla/monitor1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
                <option value="RAYADO">RAYADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="estado_teclado1">Estado teclado</label>
                <select name="estado_teclado" id="estado_teclado1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="PARTIDO">PARTIDO</option>
                <option value="NO SE VEN TECLAS">NO SE VEN TECLAS</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label for="cargador1">Estado cargador</label>
                <select name="cargador" id="cargador1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="NO USA">NO USA</option>
                <option value="OTRO">OTRO</option>
                </select>
                </div>
                <div class="inputs">
                <label style="width: 200px" for="cable_mickey1">Estado cable mickey</label>
                <select name="cable_mickey" id="cable_mickey1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="NO USA">NO USA</option>
                <option value="OTRO">OTRO</option>
                </select> 
                </div>
                <div class="inputs">
                <label style="width: 200px" for="camara1">Estado cámara</label>
                <select name="camara" id="camara1">
                <option value="BUENO">BUENO</option>
                <option value="DAÑADO">DAÑADO</option>
                <option value="NO TIENE">NO TIENE</option>
                <option value="OTRO">OTRO</option>
                </select>
              
                </div>
                <script>
                const checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"][name="programas[]"]'));
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
                const checkboxes1 = Array.from(document.querySelectorAll('input[type="checkbox"][name="accesorios[]"]'));
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
                <div class="inputs" style="width: 600px">
                <label for="editor">Observación</label>
                <textarea style="width: 1000px; height: 200px" type="text" name="nota" id="editor" placeholder="" value=""></textarea>
                </div>
                </div>
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

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
<script>
$(document).ready(function() {
    $('#tipo_equipo, #personal, #fabricante, #red_lan, #pc_sis_op , #tipo_almacenamiento, #sistema_admin, #sucursal').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>


                <input type="submit" value="Añadir nueva PC">
                
            </form>
        </div>
        <script>
    $(document).ready(function() {
        $('#nuevo').submit(function(event) {
            event.preventDefault();
        });
    });

    function crear() {
        Swal.fire({
            icon: "success",
            title: "PC creada correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexPC.php';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/crear/crearPCFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>
        </body>
</html>