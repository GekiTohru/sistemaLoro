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

$sql = "SELECT *, ISNULL(personal.nombre, 'Sin asignar') AS asignado,
ISNULL(cargo_ruta.nombre, 'Sin cargo') AS cargo, telefonos.consumo_datos AS consumo
FROM 
  reporte_digitel
LEFT JOIN telefonos ON reporte_digitel.numero = telefonos.numero
LEFT JOIN tlf_asignado ON tlf_asignado.id_telefono = telefonos.id_telefono
LEFT JOIN personal ON personal.id_personal = tlf_asignado.id_personal
LEFT JOIN cargo_ruta ON cargo_ruta.id_cargoruta = personal.id_cargoruta 
WHERE 
  reporte_digitel.activo = 1 AND telefonos.activo = 1 AND personal.activo = 1 AND tlf_asignado.activo = 1";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$rep = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usuariosPorTelefono = [];
foreach ($rep as $fila) {
  $idRep = $fila['id_reporteDigitel'];
  $nombreUsuario = $fila['asignado'];

  if (!isset($usuariosPorTelefono[$idRep])) {
    $usuariosPorTelefono[$idRep] = [];
  }
  $usuariosPorTelefono[$idRep][] = $nombreUsuario;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte digitel</title>
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
          <a class="dropdown-item" href="indexTeléfonos.php">Teléfonos</a>
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
    <h2 style="text-align: center;">Reporte Digitel</h2>
      <form action="../../controlador/importarExcel.php" method="POST" enctype="multipart/form-data"/>
        <div class="file-input">
            <input  type="file" name="dataCliente" id="file-input" class="file-input__input"/>
            <label class="file-input__label" for="file-input">
              <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
              <span>Elegir Archivo Excel</span></label>
          </div>
      <div>
          <input type="submit" name="subir" class="btn-enviar" value="Subir Excel"/>
      </div>
      </form>
    <input type="hidden" id="filterInput">
    
    <!-- Filtros de fecha -->
    <div style="margin-bottom: 10px; ">
        <label for="minDate">Desde:</label>
        <input type="date" id="minDate">
        <label for="maxDate">Hasta:</label>
        <input type="date" id="maxDate">
    </div>

    <table id="tablaImp" class="display responsive nowrap" style="width:100%">    
        <?php
        foreach ($usuariosPorTelefono as $idRep => $usuarios) {
          $fila = current(array_filter($rep, function($fila) use ($idRep) {
            return $fila['id_reporteDigitel'] == $idRep;
          }));

        // Concatenar los nombres de los usuariosConcatenar los nombres de los usuarios
        $asignadoA = implode('/', $usuarios);
            echo '<tr>';
            echo '<td>'. $fila['id_reporteDigitel']. '</td>';
            echo '<td>'. $fila['numero']. '</td>';
            echo '<td>'. $asignadoA. '</td>';
            echo '<td>'. $fila['cargo']. '</td>';
            echo '<td>'. $fila['cuota']. '</td>';
            echo '<td>'. $fila['consumo']. '</td>';
            echo '<td>'. $fila['diferencia']. '</td>';
            echo '<td>'. $fila['fecha']. '</td>';
            echo '<td>';
                  echo '<div style="display: flex;">';
                if ($_SESSION['permisos'] == 1) {
                  echo '<div><a href="#" class="users-table--edit" title="Eliminar" onclick="eliminarFuncion('.$fila['id_reporteDigitel'].')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>';
                }
                echo '</div>';
                echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#tablaImp').DataTable({
        "lengthMenu": [[10, 25, -1], [10, 25, "Todos"]],
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
            { "title": "Número" },
            { "title": "Nombre" },
            { "title": "Cargo" },
            { "title": "Cuota" },
            { "title": "Consumo" },
            { "title": "Diferencia" },
            { "title": "Fecha" },
            { "title": "Acciones" }
        ],
        "dom": 'Blfrtip',
        "buttons": [
            {
                "extend": 'pdf',
                "text": 'Imprimir PDF',
                "title": 'Lista de Impresoras',
                "exportOptions": {
                    "columns": ':not(:last-child)'
                }
            },
            {
                "extend": 'excel',
                "text": 'Imprimir Excel',
                "title": 'Lista de Impresoras',
                "exportOptions": {
                    "columns": ':not(:last-child)'
                }
            }
        ]
    });

    // Filtro de fecha
    $('#minDate, #maxDate').on('change', function() {
        table.draw();
    });

    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = data[7]; // Índice de la columna de fechas (ajustar si es necesario)

            if ((min == "" || max == "") || (date >= min && date <= max)) {
                return true;
            }
            return false;
        }
    );

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
          tabla: "reporte_digitel",
          id_columna: "id_reporteDigitel",
          id: id,
          redirect: "../vista/index/idxReporteDigitel.php"
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
            window.location.href = "../index/idxReporteDigitel.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>