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
    $id_imp=$_GET['id'];

    $sql = "SELECT impresoras.*, impresoras.id_impresora AS id, fabricante.nombre AS fabricante, COALESCE(area.nombre, 'Sin área') AS area, COALESCE(toner.modelo, 'N/A') AS toner, COALESCE(toner.color, 'N/A') AS color
    FROM impresoras
    INNER JOIN fabricante ON impresoras.id_fabricante = fabricante.id_fabricante
    LEFT JOIN area ON impresoras.id_area = area.id_area
    LEFT JOIN toner_asignado ON impresoras.id_impresora = toner_asignado.id_impresora
    LEFT JOIN toner ON toner_asignado.id_toner = toner.id_toner
    WHERE impresoras.id_impresora = $id_imp";
    
    
    $sql2="SELECT * FROM fabricante WHERE equipo = 'Impresora' AND activo = 1";
    $sql3="SELECT * FROM area WHERE activo = 1";
    $sql4="SELECT * FROM toner WHERE activo = 1";
    
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

$row0 = $result[0];

$id_fabricante_seleccionado = $row0['id_fabricante'];
$id_area_seleccionado = $row0['id_area'];

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

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
        <h2 style="text-align: center;">Editar Impresora</h2>
        <div class="users-form">
            <form id="nuevo" onsubmit="return editar()">
            <input type="hidden" name="id_imp" value="<?= $row0['id']?>">
                <div style="display: flex">
                <div style="display: block">
                <div style="margin: 10px">
                <label for="ult_mant" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="ult_mant" id="ult_mant" style="width: 200px" value="<?= $row0['ult_mantenimiento']?>">
                </div>
                <div id="selecciones">
                <div style="margin: 10px">
                <label for="fabricante">Fabricante</label>
                <select name="fabricante" id="fabricante" style="width: 200px" data-placeholder="Seleccione un fabricante" required>
                <option value="">Seleccione un fabricante</option>
                <?php
        foreach ($result2 as $row) {
            $selected = ($row['id_fabricante'] == $id_fabricante_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_fabricante']}'$selected>{$row['nombre']}</option>";
        }
       ?>
                </select>
                </div>
                </div>
                <div style="margin: 10px">
    <label for="area">Área</label>
    <select name="area" id="area" style="width: 200px" data-placeholder="Seleccione una área">
        <option value="">Seleccione una área</option>
        <?php
        foreach ($result3 as $row) {
            $selected = ($row['id_area'] == $id_area_seleccionado)? 'selected' : '';
            echo "<option value='{$row['id_area']}'$selected>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>
    <div style="margin: 10px">
    <label for="toner">Tóner</label>
    <select name="toner" id="toner" style="width: 200px" data-placeholder="Seleccione un tóner">
    <option value="Sin cambios">Sin cambios</option>
        <?php
        foreach ($result4 as $row) {
            echo "<option value='{$row['id_toner']}'>{$row['modelo']} {$row['color']}</option>";
        }
       ?>
    </select>
    <label style="margin-bottom:20px"><input type="checkbox" id="extra" name="extra"> Tóner adicional?</label>
    </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" placeholder="Ingrese el modelo" value="<?= $row0['modelo']?>">
                </div>
                <div class="inputs">
                <label for="mac_lan">MAC LAN</label>
                <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la MAC LAN" value="<?= $row0['mac_lan']?>">
                </div>
                <div class="inputs">
    <label for="serial">Serial</label>
    <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="<?= $row0['serial']?>">
</div>
<div class="inputs">
    <label for="costo">Costo</label>
    <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="<?= $row0['costo']?>">
</div>
<div class="inputs">
                <label style="width: 200px" for="estado">Estado de la impresora</label>
                <select name="estado" id="estado">
                <option value="Operativa" <?= $row0['estado'] == 'Operativa' ? 'selected' : '' ?>>Operativa</option>
                <option value="Dañada" <?= $row0['estado'] == 'Dañada' ? 'selected' : '' ?>>Dañada</option>
                </select>
                </div>
</div>
<script>
$(document).ready(function() {
    $('#fabricante, #area, #toner').select2({
        minimumInputLength: 0,
        allowClear: true,
        debug: true,
    });
});</script>


                <input type="submit" value="Editar Impresora">
            </form>
        </div>
        <script>
    $(document).ready(function() {
        $('#nuevo').submit(function(event) {
            event.preventDefault();
        });
    });

    function editar() {
        Swal.fire({
            icon: "success",
            title: "Impresora editada correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexImpresoras.php';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/editar/editarImpFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>
        </body>
</html>