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

$sql = "SELECT computadoras.*, computadoras.id_pc AS id, fabricante.nombre AS fabricante, tipo_equipo.nombre AS tipo, tipo_almacenamiento.nombre AS almacentipo, red_lan.nombre AS red, COALESCE(personal.nombre, 'Sin asignar') AS asignado, sistema_admin.nombre AS sisadmin, COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo, COALESCE(area.nombre, 'Sin área') AS area, COALESCE(sucursal.nombre, 'Sin sucursal') AS sucursal, pc_sis_op.nombre AS s_o
FROM computadoras
INNER JOIN fabricante ON computadoras.id_fabricante = fabricante.id_fabricante
INNER JOIN tipo_almacenamiento ON computadoras.id_almacentipo = tipo_almacenamiento.id_almacentipo
LEFT JOIN red_lan ON computadoras.id_red = red_lan.id_red
INNER JOIN tipo_equipo ON computadoras.id_tipo_equipo = tipo_equipo.id_tipo_equipo
LEFT JOIN pc_sis_op ON computadoras.id_pcso= pc_sis_op.id_pcso
LEFT JOIN sistema_admin ON computadoras.id_sisadmin= sistema_admin.id_sisadmin
LEFT JOIN personal ON computadoras.id_personal = personal.id_personal
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
LEFT JOIN area ON personal.id_area = area.id_area
LEFT JOIN sucursal ON computadoras.id_sucursal = sucursal.id_sucursal
WHERE computadoras.activo = 1
ORDER BY `computadoras`.`id_pc` ASC";
$query = mysqli_query($conexion, $sql);

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$pc = [];
while ($fila = mysqli_fetch_assoc($query)) {
  $pc[] = $fila;
}
$usuariosPorPC = [];
foreach ($pc as $fila) {
  $idTelefono = $fila['id'];
  $nombreUsuario = $fila['asignado'];

  if (!isset($usuariosPorPC[$idTelefono])) {
    $usuariosPorPC[$idTelefono] = [];
  }
  $usuariosPorPC[$idTelefono][] = $nombreUsuario;
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
            <a href="lobbyCrearPC.php" class="navbtn">Añadir</a>
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
        <h2 style="text-align: center;">Computadoras registradas</h2>
        <style>

</style>
        <table id="tablaTelefonos" class="display responsive nowrap" style="width:100%">    
                <?php
                foreach ($usuariosPorPC as $idPC => $usuarios) {
                    $fila = current(array_filter($pc, function($fila) use ($idPC) {
                      return $fila['id'] == $idPC;
                    }));
                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['fabricante']. '</td>';
                  echo '<td>'. $fila['tipo']. '</td>';
                  echo '<td>'. $fila['procesador']. '</td>';
                  echo '<td>'. $fila['s_o']. '</td>';
                  echo '<td>'. $fila['asignado']. '</td>';
                  echo '<td>'. $fila['cargo']. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>'. $fila['sucursal']. '</td>';
                  echo '<td>'. $fila['ram']. '</td>';
                  echo '<td>'. $fila['almacenamiento']. '</td>';
                  echo '<td>';
                echo '<div><a href="editarPC.php?id='. $fila['id']. '" class="users-table--edit">Editar</a></div>';
                if ($_SESSION['permisos'] == 1) {
                echo '<div><a href="eliminarPcFuncion.php?id='. $fila['id']. '" class="users-table--edit" onclick="return confirm(\'¿Estás seguro de eliminar este elemento?\')">Eliminar</a></div>';
                }
                echo '<div><a href="auditoriaPcPdf.php?id='. $fila['id']. '" class="users-table--edit">Auditoría</a></div>';
                echo '<div><a href="fichaPcPdf.php?id='. $fila['id']. '" class="users-table--edit">Ficha</a></div>';
                echo '<div><a href="constanciaPc.php?id='. $fila['id']. '" class="users-table--edit">Constancia</a></div>';
                echo '</td>';
            }
               ?>
               </table>
    </div>

    <script type="text/javascript">
$(document).ready(function() {
    $('#tablaTelefonos').DataTable({
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
        "language": {
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
"pageLength": 5,
        "columns": [
            { "title": "ID"},
            { "title": "Fabricante" },
            { "title": "Tipo" },
            { "title": "Procesador", "defaultContent": "N/A" },
            { "title": "S.O.", "defaultContent": "N/A" },
            { "title": "Asignado a", "defaultContent": "Sin asignar" },
            { "title": "Cargo","defaultContent": "N/A" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Sucursal", "defaultContent": "Sin sucursal" },
            { "title": "RAM", "defaultContent": "N/A" },
            { "title": "Almacen.","defaultContent": "N/A" },
            { "title": "Acciones" }
        ]
    });
});
</script>
</body>
</html>