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

    $sql2="SELECT * FROM fabricante WHERE equipo = 'Impresora' AND activo = 1";
    $sql3="SELECT * FROM area WHERE activo = 1";
    $sql4="SELECT * FROM toner WHERE activo = 1";    

    $stmt2 = $conexion->prepare($sql2);
    $stmt2->execute();
    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt3 = $conexion->prepare($sql3);
    $stmt3->execute();
    $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    $stmt4 = $conexion->prepare($sql4);
    $stmt4->execute();
    $result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$row1 = $result4[0];


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear-Imp</title>
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="../../css/styles3.css" rel="stylesheet">

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
        <h2 style="text-align: center;">Añadir nueva Impresora</h2>
        <div class="users-form">
            <form id="nuevo">
                <div style="display: flex">
                <div style="display: block">
                <div style="margin: 10px">
                <label for="ult_mant" style="width: 210px">Fecha del últ. mant.</label>
                <input type="date" name="ult_mant" id="ult_mant" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div id="selecciones">
                <div style="margin: 10px">
                <label for="fabricante">Fabricante</label>
                <select name="fabricante" id="fabricante" style="width: 200px" data-placeholder="Seleccione un fabricante" required>
                <option value="">Seleccione un fabricante</option>
                <?php
                foreach ($result2 as $row) {
                    echo "<option value='{$row['id_fabricante']}'>{$row['nombre']}</option>";
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
            echo "<option value='{$row['id_area']}'>{$row['nombre']}</option>";
        }
       ?>
    </select>
    </div>
    <div style="margin: 10px">
    <label for="toner">Tóner</label>
    <select name="toner" id="toner" style="width: 200px" data-placeholder="Seleccione un tóner">
        <option value="">Seleccione un tóner</option>
        <?php
        foreach ($result4 as $row) {
            echo "<option value='{$row['id_toner']}'>{$row['modelo']} {$row['color']}</option>";
        }
       ?>
    </select>
    </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="modelo">Modelo</label>
                <input type="text" name="modelo" id="modelo" placeholder="Ingrese el modelo" value="">
                </div>
                <div class="inputs">
                <label for="mac_lan">MAC LAN</label>
                <input type="text" name="mac_lan" id="mac_lan" placeholder="Ingrese la MAC LAN" value="">
                </div>
                <div class="inputs">
    <label for="serial">Serial</label>
    <input type="text" name="serial" id="serial" placeholder="Ingrese el serial" value="">
</div>
<div class="inputs">
    <label for="costo">Costo</label>
    <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="">
</div>
<div class="inputs">
                <label style="width: 200px" for="estado">Estado de la impresora</label>
                <select name="estado" id="estado">
                <option value="Operativa">Operativa</option>
                <option value="Dañada">Dañada</option>
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


                <input type="submit" value="Añadir nueva Impresora">
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
            title: "Impresora creada correctamente",
            showConfirmButton: false,
            timer: 3000, 
            allowOutsideClick: true,
            willClose: () => {
                window.location.href = '../../vista/index/indexImpresoras.php';
            }
        });
        $.ajax({
            type: 'POST',
            url: '../../controlador/crear/crearImpFuncion.php',
            data: $('#nuevo').serialize(),
            success: function(data) {
            }
        });
    }

    function crear() {
            $.ajax({
                type: 'POST',
                url: '../../controlador/crear/crearImpFuncion.php',
                data: $('#nuevo').serialize(),
                success: function(data) {
            if (data === 'ok') {
                    Swal.fire({
                        icon: "success",
                        title: "Impresora añadida correctamente",
                        showConfirmButton: false,
                        timer: 3000, 
                        allowOutsideClick: true,
                        willClose: () => {
                            window.location.href = '../../vista/index/indexImpresoras.php';
                        }
                    });
                }else {
                Swal.fire({
                    icon: "error",
                    title: "Error al crear la impresora",
                    text: data, // Display the error message returned by the server
                    showConfirmButton: true
                });
            }
        }
    });
} 
</script>
        </body>
</html>