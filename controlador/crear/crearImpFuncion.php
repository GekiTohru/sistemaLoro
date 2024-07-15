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

$ult_mant = $_POST['ult_mant'];
$id_fabricante = $_POST['fabricante'];
$id_area = $_POST['area'];
$modelo = check_empty($_POST['modelo']);
$serial = check_empty($_POST['serial']);
$mac_lan = check_empty($_POST['mac_lan']);
$toner = check_empty($_POST['toner']);
$estado = check_empty($_POST['estado']);


$sql1 = "INSERT INTO impresoras (
    id_fabricante,
    id_area,
    modelo,
    serial,
    mac_lan,
    estado,
    ult_mantenimiento
  ) VALUES (
    :id_fabricante,
    :id_area,
    :modelo,
    :serial,
    :mac_lan,
    :estado,
    :ult_mant
  )";
  
  $stmt = $conexion->prepare($sql1);
  
  $stmt->bindParam(':id_fabricante', $id_fabricante);
  $stmt->bindParam(':id_area', $id_area);
  $stmt->bindParam(':modelo', $modelo);
  $stmt->bindParam(':serial', $serial);
  $stmt->bindParam(':mac_lan', $mac_lan);
  $stmt->bindParam(':estado', $estado);
  $stmt->bindParam(':ult_mant', $ult_mant);
  
  $id_imp_nuevo = $conexion->lastInsertId();

if ($toner != '') {
$sql2 = "INSERT INTO toner_asignado (id_impresora, id_toner) VALUES (:id_imp, :toner)";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bindParam(':id_imp', $id_imp_nuevo);
$stmt2->bindParam(':toner', $toner);
$stmt2->execute();
}

  if ($stmt->execute()) {
    echo '<script language="javascript">alert("Impresora añadida correctamente"); window.location.href = "../../vista/index/indexImpresoras.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir la impresora");</script>';
}
