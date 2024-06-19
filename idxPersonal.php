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

$sql = "SELECT personal.*, personal.id_personal AS id, COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo, COALESCE(area.nombre, 'Sin área') AS area, COALESCE(modelo_marca.nombre, 'Sin asignar') AS asignado
FROM personal   
LEFT JOIN tlf_asignado ON personal.id_personal = tlf_asignado.id_personal
LEFT JOIN telefonos ON tlf_asignado.id_telefono = telefonos.id_telefono
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
LEFT JOIN area ON personal.id_area = area.id_area
LEFT JOIN modelo_marca ON modelo_marca.id_modelo = telefonos.id_modelo
WHERE personal.activo = 1
GROUP BY personal.id_personal";
$query = mysqli_query($conexion, $sql);

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$personal = [];
while ($fila = mysqli_fetch_assoc($query)) {
  $personal[] = $fila;
}

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
//$usuariosPorTelefono = [];
foreach ($personal as $fila) {
  $idPersonal = $fila['id'];
  $nombreTlf = $fila['asignado'];

  if (!isset($telefonosPorPersonal[$idPersonal])) {
    $telefonosPorPersonal[$idPersonal] = [];
  }
$telefonosPorPersonal[$idPersonal][] = $nombreTlf;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/buttons.css" rel="stylesheet">
    <script src="js/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/b-3.0.2/date-1.5.2/r-3.0.2/sc-2.4.3/sl-2.0.3/datatables.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/datatables/datatables.min.js"></script>
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
        <h2 style="text-align: center;">Personal registrado</h2>
        <table id="tablaTelefonos">
                <?php
                foreach ($telefonosPorPersonal as $idPersonal => $usuario) {
                    $fila = current(array_filter($personal, function($fila) use ($idPersonal) {
                      return $fila['id'] == $idPersonal;
                    }));

                  // Concatenar los nombres de los usuariosConcatenar los nombres de los usuarios
                  $asignado = implode('/', $usuario);

                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['nombre']. '</td>';
                  echo '<td>'. $fila['cargo']. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>'. $asignado. '</td>';
                  echo '<td>';
                echo '<div><a href="editarTlf.php?id='. $fila['id']. '" class="users-table--edit">Editar</a></div>';
                if ($_SESSION['permisos'] == 1) {
                echo '<div><a href="eliminarTlfFuncion.php?id='. $fila['id']. '" class="users-table--edit" onclick="return confirm(\'¿Estás seguro de eliminar este elemento?\')">Eliminar</a></div>';
                }
                echo '<div><a href="auditoriaPdf.php?id='. $fila['id']. '" class="users-table--edit">Auditoría</a></div>';
                echo '</td>';
                }
               ?>
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
            { "title": "Nombre" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Telefono asignado", "defaultContent": "Sin asignar" },
            { "title": "Acciones" }
        ]
    });
});
</script>
</body>
</html>