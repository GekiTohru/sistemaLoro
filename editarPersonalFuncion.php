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

$id_personal = mysqli_real_escape_string($conexion, $_POST['id_personal']);
$cargoruta = mysqli_real_escape_string($conexion, $_POST['cargoruta']);
$area = mysqli_real_escape_string($conexion, $_POST['area']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

$sql1="UPDATE personal SET id_cargoruta = '$cargoruta', id_area = '$area', nombre = '$nombre'
WHERE personal.id_personal = $id_personal";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Personal editado correctamente"); window.location.href = "idxPersonal.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar el personal");</script>';
}
