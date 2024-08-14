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

$sql = "SELECT 
t.*,
t.id_telefono AS id,
mm.nombre AS modelo,
ISNULL(p.nombre, 'Sin asignar') AS asignado,
ISNULL(a.nombre, 'Sin área') AS area,
pf.nombre AS nombre_plan,
pf.gb AS limite,
pf.cuota AS cuota
FROM 
telefonos t
INNER JOIN 
modelo_marca mm ON t.id_modelo = mm.id_modelo
LEFT JOIN 
(SELECT * FROM tlf_asignado WHERE activo = 1) AS tlf_asignado_activo ON t.id_telefono = tlf_asignado_activo.id_telefono
LEFT JOIN 
personal p ON tlf_asignado_activo.id_personal = p.id_personal AND p.activo = 1
LEFT JOIN 
area a ON p.id_area = a.id_area
LEFT JOIN 
plan_tlf pf ON t.id_plan = pf.id_plan
WHERE 
t.activo = 1
ORDER BY 
t.id_telefono ASC;
";
$stmt = $conexion->prepare($sql);
$stmt->execute();

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$telefonos = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
    <title>Teléfonos</title>
    <link href="../../css/styles3.css" rel="stylesheet">
    <link href="../../css/buttons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
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
          <a class="dropdown-item" href="#">Teléfonos</a>
          <a class="dropdown-item" href="indexPC.php">Computadoras</a>
          <a class="dropdown-item" href="indexImpresoras.php">Impresoras</a>
          <?php if ($_SESSION['permisos'] == 1) {
                    echo'<a class="dropdown-item" href="idxUsuarios.php">Usuarios</a>';
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

                  // Eliminar unidades y convertir a int
                  $valor_consumo = floatval(substr($fila['consumo_datos'], 0, strlen($fila['consumo_datos']) - 2));

                  // Convertir unidad a MB si es necesario
                  if (substr($fila['consumo_datos'], -2) == "GB") {
                      $valor_consumo *= 1000;
                  }

                  // Convertir límite a MB
                  $limite_mb = $fila['limite'] * 1000;

                  // Calcular diferencia
                  $diferencia = number_format($limite_mb - $valor_consumo, 2); // Formatea el resultado con 2 decimales

                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>'. $asignadoA. '</td>';
                  echo '<td>'. $fila['numero']. '</td>';
                  echo '<td>'. $fila['consumo_datos']. '</td>';
                  echo '<td>'. $fila['limite'].' '.'GB'. '</td>';
                  echo '<td>'. $fila['nombre_plan']. '</td>';
                  echo '<td>'. $fila['cuota']. '</td>';
                  echo '<td>'. $diferencia.' '.'MB'.'</td>';
                }
               ?>
        </table>
    </div>
    <script type="text/javascript">
$(document).ready(function() {
    var table = $('#tablaTelefonos').DataTable({
      "language": {
    "sEmptyTable": "No hay datos disponibles en la tabla",
    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
    "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
    "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
    "sLengthMenu": "Mostrar _MENU_ entradas",
    "sLoadingRecords": "Cargando...",
    "sProcessing": "Procesando...",
    "sSearch": "Buscar:",
    "sZeroRecords": "No se encontraron resultados",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    }
  },
        "responsive": true,
        "lengthMenu": [[10, 25,  -1], [10, 25, "Todos"]],
        "columns": [
            { "title": "ID" },
            { "title": "Modelo" },
            { "title": "Área", "defaultContent": "N/A" },
            { "title": "Asignado a", "defaultContent": "Sin asignar" },
            { "title": "Número" },
            { "title": "Consumo datos", "defaultContent": "N/A" },
            { "title": "Limite datos" },
            { "title": "Plan" },
            { "title": "Cuota" },
            { "title": "Diferencia" }
        ],
        "dom": 'Blfrtip',
        "buttons": [
    {
        "extend": 'pdf',
        "text": 'Imprimir PDF',
        "title": 'Lista Usuarios que exceden el plan',
        "exportOptions": {
          "columns": ':not(:eq(1))'

        },
    },
    {
        "extend": 'excel',
        "text": 'Imprimir Excel',
        "title": 'Lista Usuarios que exceden el plan',
        "customize": function(xlsx) {
  // Accede a la hoja de Excel
  var sheet = xlsx.xl.worksheets['sheet1.xml'];
  
  // Definir un nuevo estilo en la hoja de estilos (rojo de fondo con bordes)
  var styles = xlsx.xl['styles.xml'];

  // Agregar un nuevo fill (fondo rojo)
  var newFillId = '<fill><patternFill patternType="solid"><fgColor rgb="FFFF0000"/><bgColor indexed="64"/></patternFill></fill>';
  var lastFillIndex = $('fills fill', styles).length;
  $('fills', styles).append(newFillId);

  // Agregar un nuevo border (bordes por todos lados)
  var newBorderId = '<border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border>';
  var lastBorderIndex = $('borders border', styles).length;
  $('borders', styles).append(newBorderId);

  // Agregar un nuevo estilo de celda (xf) que use el nuevo fill y border
  var newStyleId = '<xf numFmtId="0" fontId="0" fillId="' + lastFillIndex + '" borderId="' + lastBorderIndex + '" xfId="0" />';
  var lastStyleIndex = $('cellXfs xf', styles).length;
  $('cellXfs', styles).append(newStyleId);

  // Array para almacenar las filas a eliminar
  var rowsToDelete = [];

  // Itera sobre las filas de la hoja
  $('row', sheet).each(function(index) {
    if (index >= 2) {
      var cellJ = $(this).find('c[r^="J"]');
      var value = parseFloat(cellJ.text().replace(/[a-zA-Z]+/, ''));

      if (value < 0) {
        // Aplica el nuevo estilo de fondo rojo con bordes a la celda J
        cellJ.attr('s', lastStyleIndex);
      } else {
        // Agrega la fila al array de filas a eliminar
        rowsToDelete.push(this);
      }
    }
  });

  // Eliminar las filas recolectadas en el array y ajustar la numeración de filas
  $(rowsToDelete).each(function() {
    $(this).remove();
  });

  // Recalcular la numeración de las filas
  var rowNum = 1;
  $('row', sheet).each(function() {
    $(this).attr('r', rowNum);
    $(this).find('c').each(function() {
      var cellRef = $(this).attr('r');
      var newRef = cellRef.replace(/\d+/, rowNum);
      $(this).attr('r', newRef);
    });
    rowNum++;
  });
}


    }
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