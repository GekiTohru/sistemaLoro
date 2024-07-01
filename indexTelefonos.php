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

$sql = "SELECT
telefonos.*,
telefonos.id_telefono AS id,
modelo_marca.nombre AS modelo,
modelo_marca.ram AS ram,
modelo_marca.rom AS rom,
fabricante.nombre AS fabricante,
COALESCE(personal.nombre, 'Sin asignar') AS asignado,
COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo,
COALESCE(area.nombre, 'Sin área') AS area,
COALESCE(sucursal.nombre, 'Sin sucursal') AS sucursal
FROM telefonos
INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
INNER JOIN fabricante ON modelo_marca.id_fabricante = fabricante.id_fabricante
LEFT JOIN (
SELECT *
FROM tlf_asignado
WHERE activo = 1
) AS tlf_asignado_activo ON telefonos.id_telefono = tlf_asignado_activo.id_telefono
LEFT JOIN personal ON tlf_asignado_activo.id_personal = personal.id_personal
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
LEFT JOIN area ON personal.id_area = area.id_area
LEFT JOIN sucursal ON telefonos.id_sucursal = sucursal.id_sucursal
WHERE telefonos.activo = 1
ORDER BY telefonos.id_telefono ASC;
";
$query = mysqli_query($conexion, $sql);

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$telefonos = [];
while ($fila = mysqli_fetch_assoc($query)) {
  $telefonos[] = $fila;
}

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
$usuariosPorTelefono = [];
foreach ($telefonos as $fila) {
  $idTelefono = $fila['id'];
  $nombreUsuario = $fila['asignado'];

  if (!isset($usuariosPorTelefono[$idTelefono])) {
    $usuariosPorTelefono[$idTelefono] = [];
  }
  $usuariosPorTelefono[$idTelefono][] = $nombreUsuario;
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
    <div class="wrap" style="width: 75%">
</div>
    <div class="users-table">
        <h2 style="text-align: center;">Teléfonos registrados</h2>
<input type="hidden" id="filterInput">
        <table id="tablaTelefonos" class="display responsive nowrap" style="width:100%">
                <?php
                foreach ($usuariosPorTelefono as $idTelefono => $usuarios) {
                    $fila = current(array_filter($telefonos, function($fila) use ($idTelefono) {
                      return $fila['id'] == $idTelefono;
                    }));

                  // Concatenar los nombres de los usuariosConcatenar los nombres de los usuarios
                  $asignadoA = implode('/', $usuarios);

                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['fabricante']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['cargo']. '</td>';
                  echo '<td>'. $asignadoA. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>'. $fila['sucursal']. '</td>';
                  echo '<td>'. $fila['ram']. '</td>';
                  echo '<td>'. $fila['rom']. '</td>';
                  echo '<td>';
                echo '<div><a href="editarTlf.php?id='. $fila['id']. '" class="users-table--edit">Editar</a></div>';
                if ($_SESSION['permisos'] == 1) {
                echo '<div><a href="eliminarFuncion.php?tabla=telefonos&id_columna=id_telefono&id='.$fila['id'].'&redirect=indexTelefonos.php" class="users-table--edit" onclick="return confirm(\'¿Estás seguro de eliminar este elemento?\')">Eliminar</a></div>';
                }
                echo '<div><a href="auditoriaPdf.php?id='. $fila['id']. '" class="users-table--edit">Auditoría</a></div>';
                echo '<div><a href="constanciaTlf.php?id='. $fila['id']. '" class="users-table--edit">Constancia</a></div>';
                echo '</td>';
                }
               ?>
        </table>
    </div>
    <script type="text/javascript">
$(document).ready(function() {
    var table = $('#tablaTelefonos').DataTable({
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
        "language": {
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
        "columns": [
            { "title": "ID"},
            { "title": "Marca" },
            { "title": "Modelo" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Asignado a", "defaultContent": "Sin asignar" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Sucursal", "defaultContent": "Sin sucursal" },
            { "title": "RAM", "defaultContent": "N/A" },
            { "title": "ROM","defaultContent": "N/A" },
            { "title": "Acciones" }
        ]
    });
    // Recuperar el valor del filtro del localStorage y aplicarlo
    var filterValue = sessionStorage.getItem('filterValue_telefono');
        if (filterValue) {
            table.search(filterValue).draw();
        }

        // Guardar el valor del filtro en localStorage cada vez que se busca
        $('#tablaTelefonos_filter input').on('input', function() {
            sessionStorage.setItem('filterValue_telefono', $(this).val());
        });
});
</script>
</body>
</html>