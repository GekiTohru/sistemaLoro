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

$sql = "SELECT toner.*, toner.id_toner AS id
FROM toner
WHERE toner.activo = 1
ORDER BY toner.id_toner ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$imp = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/buttons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="js/buttons.js"></script> 
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
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Toner registrados</h2>
        <input type="hidden" id="filterInput">
        <table id="tablaToner" class="display responsive nowrap" style="width:100%">    
                <?php
                foreach ($imp as $fila) {
  
                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['color']. '</td>';
                  echo '<td>';
                  echo '<div><a href="../editar/editarToner.php?id='. $fila['id']. '" class="users-table--edit">Editar</a></div>';
                  if ($_SESSION['permisos'] == 1) {
                    echo '<div><a href="#" class="users-table--edit" onclick="eliminarFuncion('.$fila['id'].')">Eliminar</a></div>';
                  }
                  echo '</td>';
            }
               ?>
               </table>
    </div>

    <script type="text/javascript">
$(document).ready(function() {
   var table = $('#tablaToner').DataTable({
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
        "language": {
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
        "columns": [
            { "title": "ID"},
            { "title": "Modelo" },
            { "title": "Color" },
            { "title": "Acciones" }
        ]
    });
        // Recuperar el valor del filtro del localStorage y aplicarlo
        var filterValue = sessionStorage.getItem('filterValue_toner');
        if (filterValue) {
            table.search(filterValue).draw();
        }

        // Guardar el valor del filtro en localStorage cada vez que se busca
        $('#tablaToner_filter input').on('input', function() {
            sessionStorage.setItem('filterValue_toner', $(this).val());
        });
});
</script>
<script>
function eliminarFuncion(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
      // Realizar la petición AJAX para eliminar el archivo
      $.ajax({
        type: "GET",
        url: "../../controlador/eliminarFuncion.php",
        data: {
          tabla: "toner",
          id_columna: "id_toner",
          id: id,
          redirect: "../vista/index/idxToner.php"
        }
      }).done(function() {
        Swal.fire({
          title: "Eliminado!",
          text: "El archivo ha sido eliminado.",
          icon: "success",
          confirmButtonText: "OK",
          allowOutsideClick: false
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../index/idxToner.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>