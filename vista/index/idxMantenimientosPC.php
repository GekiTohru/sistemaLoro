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

$sql = "SELECT registro_mantenimiento.*, registro_mantenimiento.id_mantenimiento  AS id, tipo_equipo.nombre AS tipo, fabricante.nombre AS fabricante, COALESCE(personal.nombre, 'Sin asignado') AS asignado, computadoras.motherboard as motherboard
FROM registro_mantenimiento
LEFT JOIN computadoras ON registro_mantenimiento.id_pc = computadoras.id_pc
LEFT JOIN personal ON computadoras.id_personal = personal.id_personal
INNER JOIN tipo_equipo ON computadoras.id_tipo_equipo = tipo_equipo.id_tipo_equipo
INNER JOIN fabricante ON computadoras.id_fabricante = fabricante.id_fabricante
WHERE registro_mantenimiento.activo = 1
ORDER BY registro_mantenimiento.fecha_mantenimiento  DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$mant = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MantenimientosPC</title>
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
          <a class="dropdown-item" href="indexTelefonos.php">Teléfonos</a>
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
        <h2 style="text-align: center;">Registro de mantenimientos</h2>
        <input type="hidden" id="filterInput">
        <label class="form-date__label" for="min-date">Filtrar Desde:</label>
        <input style="margin-bottom:20px;" class="form-date__input" type="date" id="min-date">
        <br>
        <label class="form-date__label" id="label_max-date" for="max-date">Hasta:</label>
        <input class="form-date__input" type="date" id="max-date">
        <button class="icon-slide-right" id="generate-pdf">Generar PDF</button>

        <table id="tablaMant" class="display responsive nowrap" style="width:100%">    
                <?php
                foreach ($mant as $fila) {
  
                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['fabricante']. '</td>';
                  echo '<td>'. $fila['motherboard']. '</td>';
                  echo '<td>'. $fila['tipo']. '</td>';
                  echo '<td>'. $fila['asignado']. '</td>';
                  echo '<td>'. $fila['fecha_mantenimiento']. '</td>';
                  echo '<td>'. $fila['realizador']. '</td>';
                  echo '<td>';
                  if ($_SESSION['permisos'] == 1) {
                    echo '<div><a href="#" class="users-table--edit" title="Eliminar" onclick="eliminarFuncion('.$fila['id'].')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>';

                  }
                  echo '</td>';
            }
               ?>
               </table>
    </div>

    <script type="text/javascript">
$(document).ready(function() {
   var table = $('#tablaMant').DataTable({
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
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
        "columns": [
            { "title": "ID"},
            { "title": "Fabricante" },
            { "title": "Modelo" },
            { "title": "Tipo" },
            { "title": "Asignado", "defaultContent": "Sin asignado" },
            { "title": "Fecha mantenimiento" },
            { "title": "Realizado por" },
            { "title": "Acciones" }
        ]
    });
        // Recuperar el valor del filtro del localStorage y aplicarlo
        var filterValue = sessionStorage.getItem('filterValue_mant');
        if (filterValue) {
            table.search(filterValue).draw();
        }

        // Guardar el valor del filtro en localStorage cada vez que se busca
        $('#tablaMant_filter input').on('input', function() {
            sessionStorage.setItem('filterValue_mant', $(this).val());
        });
         // Filtro por fecha
         $('#min-date, #max-date').on('change', function() {
        var minDate = $('#min-date').val();
        var maxDate = $('#max-date').val();

        // Si se cambia el min-date, actualizar el atributo max de max-date
        if ($(this).attr('id') === 'min-date') {
            $('#max-date').attr('min', minDate);
        }

        // Si se cambia el max-date, actualizar el atributo min de min-date
        if ($(this).attr('id') === 'max-date') {
            $('#min-date').attr('max', maxDate);
        }

        table.draw();
    });

    // Filtro personalizado para las fechas
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#min-date').val();
            var max = $('#max-date').val();
            var date = data[5]; // Fecha mantenimiento está en la columna 5

            if (min && date < min) {
                return false;
            }
            if (max && date > max) {
                return false;
            }
            return true;
        }
    );
    $('#generate-pdf').on('click', function() {
    var filteredData = table.rows({ filter: 'applied' }).data().toArray();
    var desde = $('#min-date').val();
    var hasta = $('#max-date').val();

    var form = $('<form>', {
        'method': 'POST',
        'action': '../../reporte/reporteMant.php',
        'target': '_blank'
    }).append($('<input>', {
        'type': 'hidden',
        'name': 'data',
        'value': JSON.stringify(filteredData)
    })).append($('<input>', {
        'type': 'hidden',
        'name': 'desde',
        'value': desde
    })).append($('<input>', {
        'type': 'hidden',
        'name': 'hasta',
        'value': hasta
    }));

    $('body').append(form);
    form.submit();
    form.remove();
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
          tabla: "registro_mantenimiento",
          id_columna: "id_mantenimiento",
          id: id,
          redirect: "../vista/index/idxMantenimientosPC.php"
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
            window.location.href = "../index/idxMantenimientosPC.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>