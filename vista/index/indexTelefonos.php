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
mm.ram AS ram,
mm.rom AS rom,
f.nombre AS fabricante,
ISNULL(p.nombre, 'Sin asignar') AS asignado,
ISNULL(cr.nombre, 'Sin cargo') AS cargo,
ISNULL(a.nombre, 'Sin área') AS area,
ISNULL(s.nombre, 'Sin sucursal') AS sucursal
FROM 
telefonos t
INNER JOIN 
modelo_marca mm ON t.id_modelo = mm.id_modelo
LEFT JOIN 
fabricante f ON mm.id_fabricante = f.id_fabricante
LEFT JOIN 
(SELECT * FROM tlf_asignado WHERE activo = 1) AS tlf_asignado_activo ON t.id_telefono = tlf_asignado_activo.id_telefono
LEFT JOIN 
    personal p ON tlf_asignado_activo.id_personal = p.id_personal AND p.activo = 1
LEFT JOIN 
cargo_ruta cr ON p.id_cargoruta = cr.id_cargoruta
LEFT JOIN 
area a ON p.id_area = a.id_area
LEFT JOIN 
sucursal s ON t.id_sucursal = s.id_sucursal
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
                  echo '<div style="display: flex;">';
                  echo '<div><a href="../editar/editarTlf.php?id='. $fila['id']. '" class="users-table--edit" title="Editar"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-pencil"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 7.5l3 3M4 20v-3.5L15.293 5.207a1 1 0 011.414 0l2.086 2.086a1 1 0 010 1.414L7.5 20H4z"/></svg></a></div>';
                if ($_SESSION['permisos'] == 1) {
                  echo '<div><a href="#" class="users-table--edit" title="Eliminar" onclick="eliminarFuncion('.$fila['id'].')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>';
                }
                echo '<div><a href="../../reporte/auditoriaPdf.php?id='. $fila['id']. '" class="users-table--edit" title="Reporte de auditoría"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-eye"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M3 12c5.4-8 12.6-8 18 0-5.4 8-12.6 8-18 0z"/><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></a></div>';
                echo '<div><a href="idxRevTlfOne.php?id='. $fila['id']. '" class="users-table--edit" title="Consumo de datos"><svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 31.2002C16.35 31.2002 15 29.8502 15 28.2002C15 26.5502 16.35 25.2002 18 25.2002C19.65 25.2002 21 26.5502 21 28.2002C21 29.8502 19.65 31.2002 18 31.2002Z" fill="currentColor"/><path d="M23.5509 23.8504C23.2509 23.8504 22.9509 23.7004 22.6509 23.5504C20.1009 21.6004 16.0509 21.6004 13.5009 23.5504C12.9009 24.0004 11.8509 24.0004 11.4009 23.2504C10.9509 22.6504 10.9509 21.6004 11.7009 21.1504C13.3509 19.6504 15.6009 18.9004 18.0009 18.9004C20.4009 18.9004 22.6509 19.6504 24.4509 21.0004C25.0509 21.4504 25.2009 22.5004 24.7509 23.1004C24.3009 23.5504 24.0009 23.8504 23.5509 23.8504Z" fill="currentColor"/><path d="M28.2007 19.2002C27.9007 19.2002 27.4507 19.0502 27.1507 18.7502C24.6007 16.5002 21.3007 15.3002 17.8507 15.3002C14.4007 15.3002 11.2507 16.6502 8.70071 18.9002C8.10071 19.5002 7.20071 19.3502 6.60071 18.7502C6.00071 18.1502 6.15071 17.2502 6.75071 16.6502C9.75071 13.9502 13.8007 12.4502 18.0007 12.4502C22.2007 12.4502 26.2507 13.9502 29.2507 16.6502C29.8507 17.2502 29.8507 18.1502 29.4007 18.7502C29.1007 19.0502 28.6507 19.2002 28.2007 19.2002Z" fill="currentColor"/><path d="M3 14.85C2.55 14.85 2.25 14.7 1.95 14.4C1.35 13.8 1.35 12.9 1.95 12.3C6.3 8.25 12 6 18 6C24 6 29.7 8.25 34.05 12.15C34.65 12.75 34.65 13.65 34.05 14.25C33.45 14.85 32.55 14.85 31.95 14.25C28.2 10.95 23.25 9 18 9C12.75 9 7.8 10.95 4.05 14.4C3.75 14.7 3.3 14.85 3 14.85Z" fill="currentColor"/></svg></a></div>';
                echo '<div><a href="../../reporte/constanciaTlf.php?id='. $fila['id']. '" class="users-table--edit" title="Constancia de entrega"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-print"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 17H5a1 1 0 01-1-1v-5a2 2 0 012-2h12a2 2 0 012 2v5a1 1 0 01-1 1h-3M8 4h8v5H8V4zm0 11h8v4H8v-4z"/><circle xmlns="http://www.w3.org/2000/svg" cx="7" cy="12" r="1" fill="currentColor"/></svg></a></div>';
                echo '</div>';
                echo '</td>';
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
            { "title": "Marca" },
            { "title": "Modelo" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Asignado a", "defaultContent": "Sin asignar" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Sucursal", "defaultContent": "Sin sucursal" },
            { "title": "RAM", "defaultContent": "N/A" },
            { "title": "ROM", "defaultContent": "N/A" },
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

<script>
function eliminarFuncion(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Sí, eliminar!"
  }).then((result) => {
    if (result.isConfirmed) {
      // Realizar la petición AJAX para eliminar el archivo
      $.ajax({
        type: "GET",
        url: "../../controlador/eliminarFuncion.php",
        data: {
          tabla: "telefonos",
          id_columna: "id_telefono",
          id: id,
          redirect: "../vista/index/indexTelefonos.php"
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
            window.location.href = "../index/indexTelefonos.php";
          }
        });
      });
    }
  });
}
</script>
</body>
</html>