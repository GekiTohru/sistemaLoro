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
    <title>Crear-CT</title>
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                <div class="inputs">
                <label for="tipo_cambio" style="width: 200px">Tipo de cambio realizado</label>
                <select name="tipo_cambio" id="tipo_cambio">
                <option value="Recarga">Recarga</option>
                <option value="Nuevo tóner">Nuevo tóner</option>
                </select>
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
            $.ajax({
                type: 'POST',
                url: '../../controlador/crear/crearCTFuncion.php',
                data: $('#nuevo').serialize(),
                success: function(data) {
            if (data === 'ok') {
                    Swal.fire({
                        icon: "success",
                        title: "Cambio de tóner añadido correctamente",
                        showConfirmButton: false,
                        timer: 3000, 
                        allowOutsideClick: true,
                        willClose: () => {
                            window.location.href = '../../vista/index/idxCT.php';
                        }
                    });
                }else {
                Swal.fire({
                    icon: "error",
                    title: "Error al crear el cambio de tóner",
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