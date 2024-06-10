<?php

include("conexion.php");

$fecha_recep = mysqli_real_escape_string($conexion, $_POST['fecha_recep']);
$fecha_ult_mant = mysqli_real_escape_string($conexion, $_POST['fecha_ult_mant']);
$fecha_ult_rev = mysqli_real_escape_string($conexion, $_POST['fecha_ult_rev']);
$modelo = mysqli_real_escape_string($conexion, $_POST['modelo']);
$personal = mysqli_real_escape_string($conexion, $_POST['id_personal']);
$sisver = mysqli_real_escape_string($conexion, $_POST['sisver']);
$operadora = mysqli_real_escape_string($conexion, $_POST['operadora']);
$sucursal = mysqli_real_escape_string($conexion, $_POST['sucursal']);
$accesorios = $_POST['accesorios'];
$accesorios_string = implode(',', array_map(function($value) use ($conexion) {
    return mysqli_real_escape_string($conexion, $value);
}, $accesorios));
$imei1 = mysqli_real_escape_string($conexion, $_POST['imei1']);
$imei2 = mysqli_real_escape_string($conexion, $_POST['imei2']);
$serial = mysqli_real_escape_string($conexion, $_POST['serial']);
$numero = mysqli_real_escape_string($conexion, $_POST['numero']);
$cuenta_google = mysqli_real_escape_string($conexion, $_POST['cuenta_google']);
$clave_google = mysqli_real_escape_string($conexion, $_POST['clave_google']);
$correo_corporativo = mysqli_real_escape_string($conexion, $_POST['correo_corporativo']);
$clave_corporativo = mysqli_real_escape_string($conexion, $_POST['clave_corporativo']);
$anydesk = mysqli_real_escape_string($conexion, $_POST['anydesk']);
$pin = mysqli_real_escape_string($conexion, $_POST['pin']);
$cuenta_mi = mysqli_real_escape_string($conexion, $_POST['cuenta_mi']);
$clave_mi = mysqli_real_escape_string($conexion, $_POST['clave_mi']);
$precio = mysqli_real_escape_string($conexion, $_POST['precio']);
$nota = mysqli_real_escape_string($conexion, $_POST['nota']);
$almacenamiento = mysqli_real_escape_string($conexion, $_POST['almacenamiento']);
$vidrio = mysqli_real_escape_string($conexion, $_POST['vidrio']);
$forro = mysqli_real_escape_string($conexion, $_POST['forro']);
$pantalla = mysqli_real_escape_string($conexion, $_POST['pantalla']);
$camara = mysqli_real_escape_string($conexion, $_POST['camara']);
$cargador = mysqli_real_escape_string($conexion, $_POST['cargador']);
$cable_usb = mysqli_real_escape_string($conexion, $_POST['cable_usb']);
$adaptador = mysqli_real_escape_string($conexion, $_POST['adaptador']);

$sql1="INSERT INTO telefonos(fecha_recep,fecha_ult_mant,fecha_ult_rev,id_modelo,id_sisver,id_operadora,id_sucursal,accesorios,imei1,imei2,serial,numero,cuenta_google,clave_google,correo_corporativo,clave_corporativo,anydesk,pin,cuenta_mi,clave_mi, precio, nota, almacenamiento_ocupado, vidrio,forro,pantalla,camara,cargador,cable_usb,adaptador)
VALUES('$fecha_recep','$fecha_ult_mant','$fecha_ult_rev','$modelo','$sisver','$operadora','$sucursal','$accesorios_string','$imei1','$imei2','$serial','$numero','$cuenta_google','$clave_google','$correo_corporativo','$clave_corporativo','$anydesk','$pin','$cuenta_mi','$clave_mi','$precio','$nota','$almacenamiento','$vidrio','$forro','$pantalla','$camara','$cargador','$cable_usb','$adaptador')";

$query1 = mysqli_query($conexion, $sql1);



//$query_check = mysqli_query($conexion, "SELECT * FROM tlf_asignado WHERE id_telefono='$id_telefono'");
//$sql2="INSERT INTO tlf_asignado(id_telefono,id_personal)
//VALUES('$id_telefono','$personal')";


if ($personal != '') {
// Primera consulta: obtener la ID del teléfono recién insertado
$sql2 = "SELECT id_telefono FROM telefonos ORDER BY id_telefono DESC LIMIT 1";
$result1 = mysqli_query($conexion, $sql2);
$row1 = mysqli_fetch_assoc($result1);
$id_telefono_nuevo = $row1['id_telefono'];

// Segunda consulta: insertar la asignación en la tabla "asignado"
$sql3 = "INSERT INTO tlf_asignado (id_telefono, id_personal) VALUES (?,?)";
$stmt = mysqli_prepare($conexion, $sql3);
mysqli_stmt_bind_param($stmt, "ii", $id_telefono_nuevo, $personal);
mysqli_stmt_execute($stmt);
}

if ($query1) {
    echo '<script language="javascript">alert("Teléfono añadido correctamente"); window.location.href = "index.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el telefono");</script>';
}
