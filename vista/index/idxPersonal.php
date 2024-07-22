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
    <script src="js/buttons.js"></script> 
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
                  <?php if ($_SESSION['permisos'] == 1) {
                  echo'<a href="idxUsuarios.php">Usuarios</a>';
                        }
                  ?>
                 </div>
             </div>
            <a href="../documentacion/doc.html" class="navbtn">Documentación</a>
        </div>
    </nav>
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
                echo '<div><a href="../editar/editarPersonal.php?id='. $fila['id']. '" class="users-table--edit" title="Editar"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-pencil"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 7.5l3 3M4 20v-3.5L15.293 5.207a1 1 0 011.414 0l2.086 2.086a1 1 0 010 1.414L7.5 20H4z"/></svg></a></div>';
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
  "ajax": {
    "url": "js/dataTables/i18n/es_ES.json",
    "dataType": "json"
  }
},"responsive": true,
        "columns": [
            { "title": "ID"},
            { "title": "Nombre" },
            { "title": "Cargo", "defaultContent": "N/A" },
            { "title": "Área", "defaultContent": "Sin área" },
            { "title": "Telefono asignado", "defaultContent": "Sin asignar" },
            { "title": "Acciones" }
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
