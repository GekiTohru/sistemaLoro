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


$cargoruta = mysqli_real_escape_string($conexion, $_POST['cargoruta']);
$area = mysqli_real_escape_string($conexion, $_POST['area']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);

$sql1="INSERT INTO personal(id_cargoruta,id_area,nombre)
VALUES('$cargoruta','$area','$nombre')";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Personal añadido correctamente"); window.location.href = "indexTelefonos.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el personal");</script>';
}
