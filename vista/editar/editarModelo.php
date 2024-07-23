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
    $id_modelo=$_GET['id'];


    $sql="SELECT modelo_marca.*, modelo_marca.id_modelo AS id, fabricante.nombre AS fabricante
    FROM modelo_marca
    LEFT JOIN fabricante ON modelo_marca.id_fabricante = fabricante.id_fabricante
    WHERE modelo_marca.id_modelo = $id_modelo";

    $sql2="SELECT * FROM fabricante WHERE activo = 1";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt2 = $conexion->prepare($sql2);
    $stmt2->execute();
    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    $row0 = $result[0];
$id_fabricante_seleccionado = $row0['id_fabricante'];

$rom = $row0['rom'];
$ram = $row0['ram'];
$numero_rom = '';
$unidad_rom = '';

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

list($numero_rom, $unidad_rom) = separarNumeroYUnidad($row0['rom']);
list($numero_ram, $unidad_ram) = separarNumeroYUnidad($row0['ram']);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar-Modelo</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
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
        <h2 style="text-align: center;">Editar Modelo</h2>

        <div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id_modelo" value="<?= $row0['id']?>">
                <div style="display: flex; flex-wrap: wrap;">
                <div id="selecciones" style="display: block; margin-right: 75px">
                <div style="margin: 10px; margin-right: 100px">
                <label for="fabricante">Marca</label>
                <select id="fabricante" name="fabricante" style="width: 200px" data-placeholder="Seleccione una marca" required>
                <option value="">Seleccione una marca</option>
                <?php
        foreach ($result2 as $row) {
            $selected = ($row['id_fabricante'] == $id_fabricante_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_fabricante']}'$selected>{$row['nombre']}</option>";
        }
                ?>
                </select>
                </div>
                </div>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Modelo</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre" value="<?= $row0['nombre']?>" required>
                </div>
                <div class="inputs" style="width: 225px">
    <label for="ram-num" style="width: 200px">RAM</label>
    <input type="text" id="ram-num" style="width: 130px; margin-right: -3px" placeholder="RAM" value="<?= $numero_ram?>" required>
    <select id="unidad" style="width: 80px">
    <option value="GB" <?= $unidad_ram == 'GB' ? 'selected' : '' ?>>GB</option>
    </select>
</div>
<div class="inputs" style="width: 600px">
    <label for="rom-num" style="width: 200px">ROM</label>
    <input type="text" id="rom-num" style="width: 130px; margin-right: -3px" placeholder="ROM" value="<?= $numero_rom?>" required>
    <select id="unidad1" style="width: 80px">
    <option value="GB" <?= $unidad_rom == 'GB' ? 'selected' : '' ?>>GB</option>
    <option value="TB" <?= $unidad_rom == 'TB' ? 'selected' : '' ?>>TB</option>
    </select>
</div>
<input type="hidden" name="ram" id="ram-hidden" value="<?= $numero_ram . $unidad_ram ?>">
<input type="hidden" name="rom" id="rom-hidden" value="<?= $numero_rom . $unidad_rom ?>">

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
                <option value="Smartphone" <?= $row0['tipo'] == 'Smartphone' ? 'selected' : '' ?>>Smartphone</option>
                <option value="Tablet" <?= $row0['tipo'] == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
                </select>
                </div>
                </div>
                
                
<script>
$(document).ready(function() {
    $('#fabricante').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>

                <input type="submit" value="Editar modelo">
            </form>
        </div>
        <script>
        $(document).ready(function() {
        $('#nuevo').submit(function(event) {
            event.preventDefault();
            editar();
        });
    });

    function editar() {
        Swal.fire({
            icon: "success",
            title: "Modelo editado correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexGeneral.php?tabla=modelo_marca';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/editar/editarModeloFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>    

        </body>
</html>