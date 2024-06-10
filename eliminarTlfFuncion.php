<?php

include("conexion.php");

$id_telefono=$_GET["id"];

$sql1="UPDATE telefonos SET activo=0 WHERE id_telefono='$id_telefono'";
$sql2="UPDATE tlf_asignado SET activo=0 WHERE id_telefono='$id_telefono'";

$query1 = mysqli_query($conexion,$sql1);
$query2 = mysqli_query($conexion,$sql2);

if ($query1) {
    echo '<script language="javascript">alert("Datos eliminados correctamente"); window.location.href = "index.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al eliminar los datos");</script>';
}
