<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

include("conexion.php");
function check_empty($value) {
    if (empty($value) || is_null($value) || trim($value) === '') {
            return "N/A";
    } else {
        return $value;
    }
  }

$id_imp = mysqli_real_escape_string($conexion, $_POST['id_imp']);
$id_fabricante = mysqli_real_escape_string($conexion, $_POST['fabricante']);
$id_area = mysqli_real_escape_string($conexion, $_POST['area']);
$modelo = check_empty(mysqli_real_escape_string($conexion, $_POST['modelo']));
$serial = check_empty(mysqli_real_escape_string($conexion, $_POST['serial']));
$mac_lan = check_empty(mysqli_real_escape_string($conexion, $_POST['mac_lan']));
$estado = check_empty(mysqli_real_escape_string($conexion, $_POST['estado']));


$sql1 = "UPDATE impresoras SET
  id_fabricante = '$id_fabricante',
  id_area = '$id_area',
  serial = '$serial',
  modelo = '$modelo',
  mac_lan = '$mac_lan'
WHERE id_impresora = '$id_imp'";


$query1 = mysqli_query($conexion, $sql1);



if ($query1) {
    echo '<script language="javascript">alert("Impresora editada correctamente"); window.location.href = "indexImpresoras.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar la impresora");</script>';
}
