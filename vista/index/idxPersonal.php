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

$sql = "SELECT p.*, p.id_personal AS id, 
               ISNULL(cr.nombre, 'Sin cargo') AS cargo, 
               ISNULL(a.nombre, 'Sin área') AS area, 
               ISNULL(mm.nombre, 'Sin asignar') AS asignado
        FROM personal p   
        LEFT JOIN 
            (SELECT * FROM tlf_asignado WHERE activo = 1) tlf_asignado_activo 
            ON p.id_personal = tlf_asignado_activo.id_personal
        LEFT JOIN telefonos t ON tlf_asignado_activo.id_telefono = t.id_telefono
        LEFT JOIN cargo_ruta cr ON p.id_cargoruta = cr.id_cargoruta
        LEFT JOIN area a ON p.id_area = a.id_area
        LEFT JOIN modelo_marca mm ON mm.id_modelo = t.id_modelo
        WHERE p.activo = 1";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$personal = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Personal</title>
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
        <h2 style="text-align: center;">Personal registrado</h2>
<input type="hidden" id="filterInput">
        <table id="tablaPersonal">
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
                  echo '<div style="display: flex;">';
                echo '<div><a href="../editar/editarPersonal.php?id='. $fila['id']. '" class="users-table--edit" title="Editar"><img width="30" height="30" src="../../img/edit.svg" alt="Icono SVG"></a></div>';
                if ($_SESSION['permisos'] == 1) {
                    echo '<div><a href="#" class="users-table--edit" title="Eliminar" onclick="eliminarFuncion('.$fila['id'].')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>';
                }
                echo '</div>';
                echo '</td>';
                }
               ?>
    </div>

    <script type="text/javascript">
$(document).ready(function() {
   var table = $('#tablaPersonal').DataTable({
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
            { "title": "Nombre" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Telefono asignado", "defaultContent": "Sin asignar" },
            { "title": "Acciones" }
        ],
        "dom": 'Blfrtip',
        "buttons": [
    {
        "extend": 'pdf',
        "text": 'Imprimir PDF',
        "title": 'Lista de Personal',
        "exportOptions": {
            "columns": ':not(:last-child)'
        }
    },
    {
        "extend": 'excel',
        "text": 'Imprimir Excel',
        "title": 'Lista de Personal',
        "exportOptions": {
            "columns": ':not(:last-child)'
        }
    }
]
    });
        // Recuperar el valor del filtro del localStorage y aplicarlo
        var filterValue = sessionStorage.getItem('filterValue_personal');
        if (filterValue) {
            table.search(filterValue).draw();
        }

        // Guardar el valor del filtro en localStorage cada vez que se busca
        $('#tablaPersonal_filter input').on('input', function() {
            sessionStorage.setItem('filterValue_personal', $(this).val());
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
          tabla: "personal",
          id_columna: "id_personal",
          id: id,
          redirect: "../vista/index/idxPersonal.php"
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
            window.location.href = "../index/idxPersonal.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>
