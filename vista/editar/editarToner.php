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
    $id_toner=$_GET['id'];

    $sql = "SELECT toner.*, toner.id_toner AS id
    FROM toner
    WHERE toner.id_toner = $id_toner";
    
    
$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row0 = $result[0];


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
        <h2 style="text-align: center;">Editar Tóner</h2>
        <div class="users-form">
        <form id="nuevo">
                <input type="hidden" name="id_toner" value="<?= $row0['id']?>">
                <div style="display: flex">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" placeholder="Ingrese el modelo" value="<?= $row0['modelo']?>">
                </div>
                <div class="inputs">
                <label for="color">Color</label>
                <input type="text" name="color" id="color" placeholder="Ingrese el color" value="<?= $row0['color']?>">
                </div>
                </div>
                </div>
                <input type="submit" value="Editar tóner">
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
            title: "Tóner editado correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/idxToner.php';
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