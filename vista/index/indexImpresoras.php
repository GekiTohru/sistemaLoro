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
  impresoras.*, 
  impresoras.id_impresora AS id, 
  fabricante.nombre AS fabricante, 
  COALESCE(area.nombre, 'Sin área') AS area, 
  COALESCE(toner.modelo, 'Sin tóner') AS toner,
  toner.color AS color
FROM 
  impresoras
INNER JOIN 
  fabricante ON impresoras.id_fabricante = fabricante.id_fabricante
LEFT JOIN 
  area ON impresoras.id_area = area.id_area
LEFT JOIN 
  (SELECT * FROM toner_asignado WHERE activo = 1) AS toner_asignado_activo 
  ON impresoras.id_impresora = toner_asignado_activo.id_impresora
LEFT JOIN 
  toner ON toner_asignado_activo.id_toner = toner.id_toner
WHERE 
  impresoras.activo = 1
ORDER BY 
  impresoras.id_impresora ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$imp = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tonerPorImpresora = [];
foreach ($imp as $fila) {
  $id_imp = $fila['id'];
  $toner = $fila['toner'] .' '. $fila['color'];

  if (!isset($tonerPorImpresora[$id_imp])) {
    $tonerPorImpresora[$id_imp] = [];
  }
  $tonerPorImpresora[$id_imp][] = $toner;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresoras</title>
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
          <a class="dropdown-item" href="indexTelefonos.php">Teléfonos</a>
          <a class="dropdown-item" href="indexPC.php">Computadoras</a>
          <a class="dropdown-item" href="#">Impresoras</a>
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
        <h2 style="text-align: center;">Impresoras registradas</h2>
        <input type="hidden" id="filterInput">
        <table id="tablaImp" class="display responsive nowrap" style="width:100%">    
                <?php
                foreach ($tonerPorImpresora as $id_imp => $toners) {
                  $fila = current(array_filter($imp, function($fila) use ($id_imp) {
                    return $fila['id'] == $id_imp;
                  }));

                // Concatenar los nombres de los usuariosConcatenar los nombres de los usuarios
                $tonerAsingado = implode('/', $toners);
  
                  // Mostrar la fila de la tabla con los datos del teléfono y los nombres de los usuarios
                  echo '<tr>';
                  echo '<td>'. $fila['id']. '</td>';
                  echo '<td>'. $fila['fabricante']. '</td>';
                  echo '<td>'. $fila['modelo']. '</td>';
                  echo '<td>'. $fila['serial']. '</td>';
                  echo '<td>'. $tonerAsingado. '</td>';
                  echo '<td>'. $fila['mac_lan']. '</td>';
                  echo '<td>'. $fila['area']. '</td>';
                  echo '<td>';
                  echo '<div style="display: flex;">';
                  echo '<div><a href="../editar/editarImp.php?id='. $fila['id']. '" class="users-table--edit" title="Editar"><img width="30" height="30" src="../../img/edit.svg" alt="Icono SVG"></a></div>';
                  echo '<div><a href="../crear/crearMantenimientoImp.php?id='. $fila['id']. '" class="users-table--edit" title="Realizar mantenimiento"><svg width="30" height="30" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M33.6721 10.4042C33.5204 9.94952 33.0651 9.49488 32.6098 9.49488C32.1546 9.34334 31.5476 9.49488 31.2441 9.94952L26.3879 14.799C25.7809 15.4052 24.8703 15.7083 23.9598 15.8598C23.0493 15.8598 22.2905 15.5567 21.5317 14.9505C20.9247 14.3444 20.4694 13.4351 20.4694 12.5258C20.4694 11.465 20.9247 10.4042 21.6835 9.64643L26.2361 5.10004C26.5396 4.79695 26.6914 4.19077 26.6914 3.73613C26.6914 3.28149 26.0844 2.52376 25.6291 2.37221C19.8624 0.250566 13.4887 2.06912 9.69479 7.07014C7.2667 10.2526 6.50793 14.3444 7.41846 18.2846L2.71404 22.9825C1.95527 23.7402 1.5 24.9526 1.5 26.0134C1.5 27.0742 1.95527 28.2866 2.71404 29.0443L6.65968 33.2876C7.41846 34.0454 8.6325 34.5 9.69479 34.5C10.7571 34.5 11.9711 34.0454 12.7299 33.2876L17.4343 28.5897C21.987 29.6505 26.8431 28.2866 30.3335 24.9526C34.2792 21.1639 35.4932 15.4052 33.6721 10.4042ZM28.3607 22.8309C25.6291 25.5588 21.5317 26.6196 17.7378 25.5588C17.1308 25.4072 16.6755 25.5588 16.2203 26.0134L10.9088 31.3175C10.4536 31.7722 9.54303 31.7722 8.93601 31.3175L4.83862 27.2258C4.53511 26.7711 4.38335 26.4681 4.38335 26.165C4.38335 25.8619 4.53511 25.4072 4.83862 25.2557L10.1501 19.9516C10.6053 19.4969 10.7571 19.0423 10.4536 18.4361C9.54303 15.1021 10.1501 11.465 12.1229 8.8887C14.2475 6.00932 17.4343 4.49386 20.7729 4.49386C21.2282 4.49386 21.6835 4.49386 22.1387 4.6454L19.4071 7.37324C18.0413 8.58561 17.2826 10.5557 17.2826 12.3743C17.2826 14.1928 18.0413 15.7083 19.2554 16.9206C20.4694 17.9815 21.987 18.5877 23.6563 18.5877C23.6563 18.5877 23.6563 18.5877 23.808 18.5877C25.4773 18.5877 27.1467 17.9815 28.3607 16.6176L31.2441 13.7382C31.8511 17.0722 30.637 20.4062 28.3607 22.8309Z" fill="currentColor"/></svg></a></div>';
                  echo '<div><a href="../../reporte/mantImp.php?id='. $fila['id']. '" class="users-table--edit" title="Ver mantenimientos"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-print"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 17H5a1 1 0 01-1-1v-5a2 2 0 012-2h12a2 2 0 012 2v5a1 1 0 01-1 1h-3M8 4h8v5H8V4zm0 11h8v4H8v-4z"/><circle xmlns="http://www.w3.org/2000/svg" cx="7" cy="12" r="1" fill="currentColor"/></svg></a></div>';
                  if ($fila['toner'] != "Sin tóner") {
                  echo '<div><a href="../crear/crearCambioToner.php?id='. $fila['id']. '" class="users-table--edit" title="Cambio de tóner"><svg width="30" height="30" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.6992 34.5004C19.9492 34.5004 16.4992 32.5504 14.5492 29.4004C12.5992 26.2504 12.4492 22.3504 14.0992 18.9004L22.3492 2.25039C22.7992 1.20039 24.4492 1.20039 25.0492 2.25039L33.2992 18.9004C34.9492 22.2004 34.7992 26.2504 32.8492 29.4004C30.8992 32.5504 27.4492 34.5004 23.6992 34.5004ZM23.6992 6.30039L16.7992 20.2504C15.5992 22.6504 15.7492 25.5004 17.0992 27.7504C18.4492 30.0004 20.9992 31.5004 23.6992 31.5004C26.3992 31.5004 28.9492 30.1504 30.2992 27.7504C31.7992 25.5004 31.7992 22.6504 30.5992 20.2504L23.6992 6.30039Z" fill="currentColor"/><path d="M7.20072 18.0004C5.25072 18.0004 3.45072 16.9504 2.40072 15.3004C1.35072 13.6504 1.20072 11.5504 2.10072 9.75039L5.85072 2.25039C6.30072 1.20039 8.10072 1.20039 8.55072 2.25039L12.3007 9.75039C13.2007 11.5504 13.0507 13.6504 12.0007 15.3004C11.1007 17.1004 9.15072 18.0004 7.20072 18.0004ZM7.20072 6.30039L4.80072 11.1004C4.35072 12.0004 4.35072 12.9004 4.95072 13.8004C5.40072 14.5504 6.30072 15.0004 7.20072 15.0004C8.10072 15.0004 9.00072 14.5504 9.60072 13.8004C10.0507 12.9004 10.0507 12.0004 9.60072 11.1004L7.20072 6.30039Z" fill="currentColor"/></svg></a></div>';
                  echo '<div><a href="../../reporte/rendimientoImp.php?id='. $fila['id']. '" class="users-table--edit" title="Rendimiento tóners"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-chart"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M17 5v15m-5-9v9m-5-6v6"/></svg></a></div>';
                  }
                  if ($_SESSION['permisos'] == 1) {
                    echo '<div><a href="#" class="users-table--edit" title="Eliminar" onclick="eliminarFuncion('.$fila['id'].')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>';
                  }
                  echo '</div>';
                  echo '</td>';
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
            { "title": "Fabricante" },
            { "title": "Modelo" },
            { "title": "Serial", "defaultContent": "Sin sucursal" },
            { "title": "Tóner", "defaultContent": "Sin tóner" },
            { "title": "Mac LAN" },
            { "title": "Área", "defaultContent": "Sin área" },
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
          tabla: "impresoras",
          id_columna: "id_impresora",
          id: id,
          redirect: "../vista/index/indexImpresoras.php"
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
            window.location.href = "../index/indexImpresoras.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>