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

// Include de la conexión
include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

$idColumns = [
    "area" => "id_area",
    "sucursal" => "id_sucursal",
    "tipo_almacenamiento" => "id_almacentipo",
    "tipo_equipo" => "id_tipo_equipo",
    "tlf_sisver" => "id_sisver",
    "sistema_admin" => "id_sisadmin",
    "red_lan" => "id_red",
    "pc_sis_op" => "id_pcso",
    "cargo_ruta" => "id_cargoruta",
    "operadora" => "id_operadora",
    "modelo_marca" => "id_modelo",
    "fabricante" => "id_fabricante"
];

$edit = [
    "area" => "Area",
    "sucursal" => "Sucursal",
    "tipo_almacenamiento" => "Almacentipo",
    "tipo_equipo" => "TipoEquipo",
    "tlf_sisver" => "Sisver",
    "sistema_admin" => "Sisadmin",
    "red_lan" => "Red",
    "pc_sis_op" => "Pcso",
    "cargo_ruta" => "Cargoruta",
    "operadora" => "Operadora",
    "modelo_marca" => "Modelo",
    "fabricante" => "Fabricante"
];
// Consultas para todas las tablas necesarias
$tables = [
    "area" => "SELECT area.id_area AS id, area.nombre AS nombre FROM area WHERE area.activo = 1",
    "sucursal" => "SELECT sucursal.id_sucursal AS id, sucursal.nombre AS nombre FROM sucursal WHERE sucursal.activo = 1",
    "tipo_almacenamiento" => "SELECT tipo_almacenamiento.id_almacentipo AS id, tipo_almacenamiento.nombre AS nombre  FROM tipo_almacenamiento WHERE tipo_almacenamiento.activo = 1",
    "tipo_equipo" => "SELECT tipo_equipo.id_tipo_equipo AS id, tipo_equipo.nombre AS nombre, tipo_equipo.teclado AS Teclado FROM tipo_equipo WHERE tipo_equipo.activo = 1",
    "tlf_sisver" => "SELECT tlf_sisver.id_sisver AS id, tlf_sisver.nombre AS nombre FROM tlf_sisver WHERE tlf_sisver.activo = 1",
    "sistema_admin" => "SELECT sistema_admin.id_sisadmin AS id, sistema_admin.nombre AS nombre FROM sistema_admin WHERE sistema_admin.activo = 1",
    "red_lan" => "SELECT red_lan.id_red AS id, red_lan.nombre AS nombre FROM red_lan WHERE red_lan.activo = 1",
    "pc_sis_op" => "SELECT pc_sis_op.id_pcso AS id, pc_sis_op.nombre AS nombre FROM pc_sis_op WHERE pc_sis_op.activo = 1",
    "cargo_ruta" => "SELECT cargo_ruta.id_cargoruta AS id, cargo_ruta.nombre AS nombre FROM cargo_ruta WHERE cargo_ruta.activo = 1",
    "operadora" => "SELECT operadora.id_operadora AS id, operadora.nombre AS nombre FROM operadora WHERE operadora.activo = 1",
    "modelo_marca" => "SELECT modelo_marca.id_modelo AS id, modelo_marca.nombre AS modelo, fabricante.nombre AS fabricante, modelo_marca.ram AS RAM, modelo_marca.rom AS ROM, modelo_marca.tipo AS tipo FROM modelo_marca LEFT JOIN fabricante ON modelo_marca.id_fabricante =  fabricante.id_fabricante WHERE modelo_marca.activo = 1",
    "fabricante" => "SELECT fabricante.id_fabricante AS id, fabricante.nombre AS nombre FROM fabricante WHERE fabricante.activo = 1"
];

// Consultar todas las tablas de una sola vez
$results = [];
foreach ($tables as $key => $sql) {
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $results[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Variable para controlar la pestaña activa en el JavaScript
$activeTab = '';

// Verificar si se recibió el parámetro 'tabla' en la URL
if (isset($_GET['tabla'])) {
    $tabla = $_GET['tabla'];
    // Validar que el valor de 'tabla' exista en el array $tables
    if (array_key_exists($tabla, $tables)) {
        $activeTab = $tabla; // Activar la pestaña correspondiente en el JavaScript
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema LORO</title>
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
        <h2 style="text-align: center;">Registros</h2>
        <select style="margin-bottom: 1rem" class="selectStyle" id="tableSelector">
            <option class="opt_general" value="operadora">Operadoras</option>
            <option class="opt_general" value="area">Áreas</option>
            <option class="opt_general" value="cargo_ruta">Cargo/Ruta</option>
            <option class="opt_general" value="sucursal">Sucursales</option>
            <option class="opt_general" value="modelo_marca">Modelos</option>
            <option class="opt_general" value="fabricante">Fabricante</option>
            <option class="opt_general" value="tipo_almacenamiento">Tipos de almacenamiento</option>
            <option class="opt_general" value="tipo_equipo">Tipos de equipo</option>
            <option class="opt_general" value="tlf_sisver">S.O. Móvil</option>
            <option class="opt_general" value="pc_sis_op">S.O. Desktop</option>
            <option class="opt_general" value="sistema_admin">Sistema Administrativo</option>
            <option class="opt_general" value="red_lan">Red LAN</option>
        </select>
        <input type="hidden" id="filterInput">

        <?php foreach ($results as $table => $data): ?>
            <div id="<?= $table ?>Container" style="display:none;">
                <table id="tabla<?= ucfirst($table) ?>" class="display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <?php foreach ($data[0] as $column => $value): ?>
                                <th><?= ucfirst($column) ?></th>
                            <?php endforeach; ?>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $column => $value): ?>
                                    <td><?= $value ?></td>
                                <?php endforeach; ?>
                                <td>
                                <div style="display: flex;">
                                <div><a href="../editar/editar<?= $edit[$table] ?>.php?id=<?= $fila['id'] ?>&redirect=<?= "../vista/index/indexGeneral.php?tabla=$table" ?>" class="users-table--edit" title="Editar"><img width="30" height="30" src="../../img/edit.svg" alt="Icono SVG"></a></div>
                                    <?php if ($_SESSION['permisos'] == 1): ?>
                                    <div><a href="#" class="users-table--edit" onclick="eliminarFuncion('<?= $fila['id'] ?>', '<?= $table ?>', '<?= $idColumns[$table] ?>')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>
                                </div>
                                <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $(document).ready(function() {
            // Configuración de DataTables para cada tabla
            <?php foreach ($results as $table => $data): ?>
                var table<?= ucfirst($table) ?> = $('#tabla<?= ucfirst($table) ?>').DataTable({
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
        "dom": 'Blfrtip',
        "buttons": [
    {
        "extend": 'pdf',
        "text": 'Imprimir PDF',
        "title": 'Lista General',
        "exportOptions": {
            "columns": ':not(:last-child)'
        }
    },
    {
        "extend": 'excel',
        "text": 'Imprimir Excel',
        "title": 'Lista General',
        "exportOptions": {
            "columns": ':not(:last-child)'
        }
    }
]
                });
            <?php endforeach; ?>

            // Mostrar la tabla seleccionada al cargar la página
            var activeTab = '<?= $activeTab ?>';
            if (activeTab !== '') {
                $('#'+activeTab+'Container').show();
                $('#tableSelector').val(activeTab);
            }

            // Cambiar de tabla al seleccionar desde el dropdown
            $('#tableSelector').change(function() {
                var selectedTable = $(this).val();
                $('.users-table > div').hide();
                $('#'+selectedTable+'Container').show();
                $('#filterInput').val(selectedTable);
            });
        });
    </script>
    <script>
function eliminarFuncion(id, table, idColumn) {
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
          tabla: table,
          id_columna: idColumn,
          id: id,
          redirect: "../vista/index/indexGeneral.php?tabla=" + table
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
            window.location.href = "../index/indexGeneral.php?tabla=" + table;
          }
        });
      });
    }
  });
}
</script>
</body>
</html>
