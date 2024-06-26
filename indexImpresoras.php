<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos


include("conexion.php");

$sql = "SELECT impresoras.*, impresoras.id_impresora AS id, fabricante.nombre AS fabricante, COALESCE(area.nombre, 'Sin área') AS area
FROM impresoras
INNER JOIN fabricante ON impresoras.id_fabricante = fabricante.id_fabricante
LEFT JOIN area ON impresoras.id_area = area.id_area
WHERE impresoras.activo = 1
ORDER BY `impresoras`.`id_impresora` ASC";
$query = mysqli_query($conexion, $sql);

$imp = [];
while ($row = mysqli_fetch_assoc($query)) {
  $imp[] = $row;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/buttons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="js/buttons.js"></script> 
</head>
<header>
    <div style="height: 50px;"></div>
    <img src="img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="cerrarSesion.php" class="navbtn">Salir</a>
            <a href="lobby.php" class="navbtn">Inicio</a>
            <a href="lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Impresoras registradas</h2>
        <input type="hidden" id="filterInput">
        <table id="tablaImp" class="display responsive nowrap" style="width:100%">    
                <?php
                foreach ($imp as $fila) {
  
                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['fabricante']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['serial']. '</td>';
                  echo '<td>'. $fila['mac_lan']. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>';
                  echo '<div><a href="editarImp.php?id='. $fila['id']. '" class="users-table--edit">Editar</a></div>';
                  if ($_SESSION['permisos'] == 1) {
                    echo '<div><a href="eliminarFuncion.php?tabla=impresoras&id_columna=id_impresora&id='. $fila['id']. '&redirect=indexImpresoras.php" class="users-table--edit" onclick="return confirm(\'¿Estás seguro de eliminar este elemento?\')">Eliminar</a></div>';

                  }
                  echo '</td>';
            }
               ?>
               </table>
    </div>

    <script type="text/javascript">
$(document).ready(function() {
   var table = $('#tablaImp').DataTable({
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
        "language": {
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
        "columns": [
            { "title": "ID"},
            { "title": "Fabricante" },
            { "title": "Modelo" },
            { "title": "Serial", "defaultContent": "Sin sucursal" },
            { "title": "Mac LAN" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Acciones" }
        ]
    });
        // Recuperar el valor del filtro del localStorage y aplicarlo
        var filterValue = sessionStorage.getItem('filterValue_imp');
        if (filterValue) {
            table.search(filterValue).draw();
        }

        // Guardar el valor del filtro en localStorage cada vez que se busca
        $('#tablaImp_filter input').on('input', function() {
            sessionStorage.setItem('filterValue_imp', $(this).val());
        });
});
</script>
</body>
</html>