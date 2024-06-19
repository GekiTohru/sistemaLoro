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

$id_pc=$_GET["id"];

$sql1="UPDATE computadoras SET activo=0 WHERE id_pc='$id_pc'";

$query1 = mysqli_query($conexion,$sql1);

if ($query1) {
    echo '<script language="javascript">alert("Datos eliminados correctamente"); window.location.href = "indexPC.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al eliminar los datos");</script>';
}
