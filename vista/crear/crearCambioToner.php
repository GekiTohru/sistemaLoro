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

$sql2="SELECT toner_asignado.*, toner_asignado.id_toner_asignado AS id, toner.modelo AS toner, toner.color AS color
FROM toner_asignado
INNER JOIN toner ON toner_asignado.id_toner = toner.id_toner
WHERE toner_asignado.activo = 1 AND id_impresora = $id_imp";    

$stmt2 = $conexion->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$row0 = $result2[0];

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
                     <a href="../index/indexTelefonos.php">Teléfonos</a>
                     <a href="../index/indexPc.php">Computadoras</a>
                     <a href="../index/indexImpresoras.php">Impresoras</a>
                     <?php if ($_SESSION['permisos'] == 1) {
                  echo'<a href="../index/idxUsuarios.php">Usuarios</a>';
                        }
                  ?>
                 </div>
             
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Nuevo cambio de tóner</h2>
        <div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id_imp" value="<?= $_GET['id']?>">
                <div style="display: flex">
                <div style="display: block">
                <div style="margin: 10px">
                <label for="fecha" style="width: 210px">Fecha del cambio</label>
                <input type="date" name="fecha" id="fecha" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div style="margin: 10px">
                <label for="toner">Tóner reemplazado</label>
                <select name="toner" id="toner" style="width: 200px" data-placeholder="Seleccione una toner">
                <?php
                foreach ($result2 as $row) {
                    echo "<option value='{$row['id_toner']}'>{$row['toner']} {$row['color']}</option>";
                }
                ?>
                </select>
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="contador">Contador</label>
                <input type="text" name="contador" id="contador" placeholder="Ingrese el contador" value="">
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="costo">Costo tóner</label>
                <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="">
                </div>
            </div>
            </div>

                <input type="submit" value="Añadir nuevo cambio">
            </form>
        </div>
        <script>
        $(document).ready(function() {
        $('#nuevo').submit(function(event) {
            event.preventDefault();
            crear();
        });
    });

    function crear() {
        Swal.fire({
            icon: "success",
            title: "Tóner creado correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/idxToner';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/editar/editarTonerFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>
        </body>
</html>