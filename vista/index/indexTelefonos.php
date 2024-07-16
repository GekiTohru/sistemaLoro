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
INNER JOIN 
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
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/buttons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="../../js/buttons.js"></script> 
</head>
<header>
    <div style="height: 50px;"></div>
    <img src="../../img/logo.png" id="logo">
</header>
<body>
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
    <div class="wrap" style="width: 75%">
</div>
<button style="width:250px; margin-top: -20px" class="icon-slide-right" onclick="location.href='../../reporte/requisicion.php'">Requisición de accesorios</button>    
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
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "Todos"]],
        "language": {
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
        "columns": [
            { "title": "ID"},
            { "title": "Marca" },
            { "title": "Modelo" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Asignado a", "defaultContent": "Sin asignar" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Sucursal", "defaultContent": "Sin sucursal" },
            { "title": "RAM", "defaultContent": "N/A" },
            { "title": "ROM","defaultContent": "N/A" },
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