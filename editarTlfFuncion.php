<<<<<<< HEAD
<?php

include("conexion.php");

$id_telefono=$_POST["id_telefono"];
$fecha_recep = $_POST['fecha_recep'];
$modelo = $_POST['modelo'];
$vidrio = $_POST['vidrio'];
$personal = $_POST['id_personal'];
$accesorios = $_POST['accesorios'];
$accesorios_string = implode(',', $accesorios);

$sql1="UPDATE telefonos SET vidrio='$vidrio', accesorios = '$accesorios_string' WHERE id_telefono='$id_telefono'";
$sql3="UPDATE tlf_asignado SET activo=0 WHERE id_telefono='$id_telefono'";
$sql2="INSERT INTO tlf_asignado(id_telefono,id_personal)
VALUES('$id_telefono','$personal')";
//$sql="UPDATE cliente SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono' WHERE id_cliente='$id_cliente'";
$query_check = mysqli_query($conexion, "SELECT * FROM tlf_asignado WHERE id_telefono='$id_telefono'");


$ok = true;

if ($personal != '') {
    if (mysqli_num_rows($query_check) > 0) {
        $query3 = mysqli_query($conexion, $sql3);
        $query2 = mysqli_query($conexion, $sql2);
        if (!$query2) {
            $ok = false;
        }
    } else {
        $query2 = mysqli_query($conexion, $sql2);
        if (!$query2) {
            $ok = false;
        }
    }
}

if ($modelo != '') {
    $sql = "UPDATE telefonos SET id_modelo='$modelo' WHERE id_telefono='$id_telefono'";
    $query = mysqli_query($conexion, $sql);
    if (!$query) {
        $ok = false;
    }
}

$query1 = mysqli_query($conexion,$sql1);

if ($query1) {
    echo '<script language="javascript">alert("Datos editados correctamente"); window.location.href = "index.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar los datos");</script>';
}
=======
<?php

include("conexion.php");

$id_telefono=$_POST["id_telefono"];
$fecha_recep = $_POST['fecha_recep'];
$modelo = $_POST['modelo'];
$vidrio = $_POST['vidrio'];
$personal = $_POST['id_personal'];
$accesorios = $_POST['accesorios'];
$accesorios_string = implode(',', $accesorios);

$sql1="UPDATE telefonos SET vidrio='$vidrio', accesorios = '$accesorios_string' WHERE id_telefono='$id_telefono'";
$sql3="UPDATE tlf_asignado SET activo=0 WHERE id_telefono='$id_telefono'";
$sql2="INSERT INTO tlf_asignado(id_telefono,id_personal)
VALUES('$id_telefono','$personal')";
//$sql="UPDATE cliente SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono' WHERE id_cliente='$id_cliente'";
$query_check = mysqli_query($conexion, "SELECT * FROM tlf_asignado WHERE id_telefono='$id_telefono'");


$ok = true;

if ($personal != '') {
    if (mysqli_num_rows($query_check) > 0) {
        $query3 = mysqli_query($conexion, $sql3);
        $query2 = mysqli_query($conexion, $sql2);
        if (!$query2) {
            $ok = false;
        }
    } else {
        $query2 = mysqli_query($conexion, $sql2);
        if (!$query2) {
            $ok = false;
        }
    }
}

if ($modelo != '') {
    $sql = "UPDATE telefonos SET id_modelo='$modelo' WHERE id_telefono='$id_telefono'";
    $query = mysqli_query($conexion, $sql);
    if (!$query) {
        $ok = false;
    }
}

$query1 = mysqli_query($conexion,$sql1);

if ($query1) {
    echo '<script language="javascript">alert("Datos editados correctamente"); window.location.href = "index.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar los datos");</script>';
}
>>>>>>> 559cab82091e040721218c428ee4f663ae3dd8c8
