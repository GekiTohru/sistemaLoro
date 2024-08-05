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

<!DOCTYPE html>
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
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Cargo</th>
                    <th>Asignado a</th>
                    <th>Área</th>
                    <th>Sucursal</th>
                    <th>RAM</th>
                    <th>ROM</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuariosPorTelefono as $idTelefono => $usuarios) {
                    $fila = current(array_filter($telefonos, function($fila) use ($idTelefono) {
                      return $fila['id'] == $idTelefono;
                    }));

                    $asignadoA = implode('/', $usuarios);

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
                    echo '<button class="btn btn-primary edit-btn mr-2" data-id="' . $fila['id'] . '" data-toggle="modal" data-target="#editModal" data-telefono=\'' . json_encode($fila) . '\'>Editar</button>';
                    echo '<form method="POST" action="eliminarTelefono.php" onsubmit="return confirm(\'¿Estás seguro de que deseas eliminar este teléfono?\')">';
                    echo '<input type="hidden" name="id_telefono" value="' . $fila['id'] . '">';
                    echo '<button type="submit" class="btn btn-danger">Eliminar</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        var table = $('#tablaTelefonos').DataTable({

        });

        var filter = sessionStorage.getItem('tableFilter');
        if (filter) {
            table.search(filter).draw();
        }

        $('#tablaTelefonos_filter input').on('keyup', function() {
            sessionStorage.setItem('tableFilter', $(this).val());
        });
    });
    </script>
</body>
</html>
