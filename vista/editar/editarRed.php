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

    $id_red=$_GET['id'];
    


    $sql="SELECT red_lan.*, red_lan.id_red AS id
    FROM red_lan
    WHERE red_lan.id_red = $id_red";
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
        <h2 style="text-align: center;">Editar Red LAN</h2>
        <div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id" value="<?= $row0['id']?>">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre_red">Red LAN</label>
                <input type="text" name="nombre_red" id="nombre_red" placeholder="Ingrese la red" value="<?= $row0['nombre']?>" required>
                </div>
</div>
                <input type="submit" value="Editar Red LAN">
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
            title: "Tipo de red editado correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexGeneral.php?tabla=red_lan';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/editar/editarFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }
</script>    

        </body>
</html>