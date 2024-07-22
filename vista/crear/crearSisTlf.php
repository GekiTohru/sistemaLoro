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
        <h2 style="text-align: center;">Añadir nueva versión de Android</h2>

        <div class="users-form">
            <form id="nuevo">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre_sisver">Nombre versión</label>
                <input type="text" name="nombre_sisver" id="nombre_sisver" placeholder="Ingrese el nombre" value="">
                </div>
</div>
                <input type="submit" value="Añadir nueva versión">
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
            title: "Sistema operativo creado correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexGeneral.php?tabla=tlf_sisver';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/crear/crearFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>

        </body>
</html>