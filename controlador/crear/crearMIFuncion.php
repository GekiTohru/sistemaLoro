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
$fecha_mant = check_empty($_POST['fecha_mant']);
$descripcion = check_empty($_POST['descripcion']);
$proveedor = check_empty($_POST['proveedor']);
$costo = check_empty($_POST['costo']);



$sql1 = "INSERT INTO mant_imp (
    id_impresora,
    fecha_mant,
    descripcion,
    proveedor,
    costo
  ) VALUES (
    :id_impresora,
    :fecha_mant,
    :descripcion,
    :proveedor,
    :costo
  )";
  
  $stmt = $conexion->prepare($sql1);
  
  $stmt->bindParam(':id_impresora', $id_imp);
  $stmt->bindParam(':fecha_mant', $fecha_mant);
  $stmt->bindParam(':descripcion', $descripcion);
  $stmt->bindParam(':proveedor', $proveedor);
  $stmt->bindParam(':costo', $costo);
  
  
  if ($stmt->execute()) {
    echo 'ok';
  } else {
    echo 'error';
  }