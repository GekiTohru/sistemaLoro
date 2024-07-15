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
    "modelo_marca" => "SELECT modelo_marca.id_modelo AS id, modelo_marca.nombre AS modelo, fabricante.nombre AS fabricante, modelo_marca.ram AS RAM, modelo_marca.rom AS ROM, modelo_marca.tipo AS tipo FROM modelo_marca INNER JOIN fabricante ON modelo_marca.id_fabricante =  fabricante.id_fabricante WHERE modelo_marca.activo = 1",
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
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/buttons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="js/buttons.js"></script> 
</head>
<body>
    <header>
        <div style="height: 50px;"></div>
        <img src="../../img/logo.png" id="logo">
    </header>
    <nav class="navbar">
        <div class="navbar-left">
            <a href="../../controlador/cerrarSesion.php" class="navbtn">Salir</a>
            <a href="../lobby.php" class="navbtn">Inicio</a>
            <a href="../lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="../lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>

    <div class="users-table">
        <h2 style="text-align: center;">Registros</h2>
        <select style="margin-bottom: 1rem" class="selectStyle" id="tableSelector">
            <option value="operadora">Operadoras</option>
            <option value="area">Áreas</option>
            <option value="cargo_ruta">Cargo/Ruta</option>
            <option value="sucursal">Sucursales</option>
            <option value="modelo_marca">Modelos</option>
            <option value="fabricante">Fabricante</option>
            <option value="tipo_almacenamiento">Tipos de almacenamiento</option>
            <option value="tipo_equipo">Tipos de equipo</option>
            <option value="tlf_sisver">S.O. Móvil</option>
            <option value="pc_sis_op">S.O. Desktop</option>
            <option value="sistema_admin">Sistema Administrativo</option>
            <option value="red_lan">Red LAN</option>
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
                                <div><a href="../editar/editar<?= $edit[$table] ?>.php?id=<?= $fila['id'] ?>&redirect=<?= "../vista/index/indexGeneral.php?tabla=$table" ?>" class="users-table--edit">Editar</a></div>
                                    <?php if ($_SESSION['permisos'] == 1): ?>
                                        <div><a href="#" class="users-table--edit" onclick="eliminarFuncion('<?= $fila['id'] ?>', '<?= $table ?>', '<?= $idColumns[$table] ?>')">Eliminar</a></div>
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
                        "url": "js/dataTables/i18n/es_ES.json"
                    },
                    "responsive": true
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
