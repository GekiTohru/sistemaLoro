<?php
include("conexion.php");

$sql = "SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, modelo_marca.ram AS ram, modelo_marca.rom AS rom, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
FROM telefonos
INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
INNER JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
INNER JOIN personal ON tlf_asignado.id_personal = personal.id_personal
INNER JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
INNER JOIN area ON personal.id_area = area.id_area
WHERE telefonos.activo = 1 AND tlf_asignado.activo = 1
ORDER BY `telefonos`.`id_telefono` ASC";
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
    <script src="js/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/b-3.0.2/date-1.5.2/r-3.0.2/sc-2.4.3/sl-2.0.3/datatables.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/datatables/datatables.min.js"></script>
</head>
<header>
    <img src="img/logo.png" id="logo">
</header>
<body>
    <!--<h1 id="hola">hola mundo!!!</h1>-->
    <div class="wrap" style="width: 75%">
    <button class="icon-slide-left" onclick="location.href='crearTlf.php'">Añadir un nuevo teléfono</button>
    <button class="icon-slide-right" onclick="location.href='crearPersonal.php'">Añadir un nuevo miembro del personal</button>    
</div>
    <div class="users-table">
        <h2 style="text-align: center;">Teléfonos registrados</h2>
        <style>
/* Estilo para la tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

/* Estilo para las celdas de encabezado */
th {
    background-color: greenyellow;
    color: black;
    padding: 10px;
    text-align: left;
}

/* Estilo para las celdas de datos */
td {    
    padding: 10px;
    text-align: left;
}

/* Estilo para los enlaces dentro de las celdas */
a.users-table--edit,
a.users-table--delete {
    color: green;
    text-decoration: none;
    margin-right: 10px;
}

/* Estilo para los enlaces de editar */
a.users-table--edit:hover {
    text-decoration: underline;
}

/* Estilo para los enlaces de eliminar */
a.users-table--delete:hover {
    text-decoration: underline;
    color: red;
}
</style>
        <table id="tablaTelefonos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Serial</th>       
                    <th>Asignado a</th>
                    <th>IMEI1</th>
                    <th>IMEI2</th>
                    <th>RAM</th>
                    <th>ROM</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
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
                  echo '<td>'. $fila['marca']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['serial']. '</td>';
                  echo '<td>'. $asignadoA. '</td>';
                  echo '<td>'. $fila['imei1']. '</td>';
                  echo '<td>'. $fila['imei2']. '</td>';
                  echo '<td>'. $fila['ram']. '</td>';
                  echo '<td>'. $fila['rom']. '</td>';
                  echo '<td><a href="editarTlf.php?id='. $fila['id']. '" class="users-table--edit">Editar</a>
                  <a href="eliminarTlfFuncion.php?id='. $fila['id']. '" class="users-table--edit" onclick="return confirm(\'¿Estás seguro de eliminar este elemento?\')">Eliminar</a>
                  <a href="auditoriaPdf.php?id='. $fila['id']. '" class="users-table--edit">Auditoría</a>';
                  echo '</tr>';
                }
               ?>
            </tbody>
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
},
            "columns": [
                { "title": "ID", "width": "10%" },
                { "title": "Marca", "width": "10%" },
                { "title": "Modelo", "width": "10%" },
                { "title": "Serial", "width": "10%" },
                { "title": "Asignado a", "width": "15%" },
                { "title": "IMEI1", "width": "15%" },
                { "title": "IMEI2", "width": "10%" },
                { "title": "RAM", "width": "10%" },
                { "title": "ROM", "width": "10%" },
                { "title": "Acciones", "width": "10%" }
            ]
        });
    });
    </script>
</body>
</html>