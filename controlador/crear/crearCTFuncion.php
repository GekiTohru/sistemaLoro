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
function check_empty($value) {
    if (empty($value) || is_null($value) || trim($value) === '') {
            return "N/A";
    } else {
        return $value;
    }
  }

$id_imp = $_POST['id_imp'];
$fecha= check_empty($_POST['fecha']);
$contador = check_empty($_POST['contador']);
$costo = check_empty($_POST['costo']);
$toner = check_empty($_POST['toner']);
$cambio = check_empty($_POST['tipo_cambio']);

$sql1 = "INSERT INTO cambio_toner(
    id_impresora,
    id_toner,
    fecha,
    contador,
    costo,
    tipo_cambio
  ) VALUES (
    :id_impresora,
    :id_toner,
    :fecha,
    :contador,
    :costo,
    :cambio
  )";
  
  $stmt = $conexion->prepare($sql1);
  
  $stmt->bindParam(':id_impresora', $id_imp);
  $stmt->bindParam(':id_toner', $toner);
  $stmt->bindParam(':fecha', $fecha);
  $stmt->bindParam(':contador', $contador);
  $stmt->bindParam(':costo', $costo);
  $stmt->bindParam(':cambio', $cambio);
  
  
  if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}