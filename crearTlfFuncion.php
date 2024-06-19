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

function check_empty($value, $field) {
    if (empty($value) || is_null($value) || trim($value) === '') {
        if ($field == 'nota') {
            return "Mantiene la configuración inicial.";
        } else {
            return "N/A";
        }
    } else {
        return $value;
    }
  }

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
$apps = $_POST['apps_conf'];
$apps_string = implode(',', array_map(function($value1) use ($conexion) {
    return mysqli_real_escape_string($conexion, $value1);
}, $apps));
$imei1 = check_empty(mysqli_real_escape_string($conexion, $_POST['imei1']), 'imei1');
$imei2 = check_empty(mysqli_real_escape_string($conexion, $_POST['imei2']), 'imei2');
$imei_adn = check_empty(mysqli_real_escape_string($conexion, $_POST['imei_adn']), 'imei_adn');
$serial = check_empty(mysqli_real_escape_string($conexion, $_POST['serial']), 'serial');
$mac_lan = check_empty(mysqli_real_escape_string($conexion, $_POST['mac_lan']), 'mac_lan');
$mac_wifi = check_empty(mysqli_real_escape_string($conexion, $_POST['mac_wifi']), 'mac_wifi');
$numero = check_empty(mysqli_real_escape_string($conexion, $_POST['numero']), 'numero');
$cuenta_google = check_empty(mysqli_real_escape_string($conexion, $_POST['cuenta_google']), 'cuenta_google');
$clave_google = check_empty(mysqli_real_escape_string($conexion, $_POST['clave_google']), 'clave_google');
$correo_corporativo = check_empty(mysqli_real_escape_string($conexion, $_POST['correo_corporativo']), 'correo_corporativo');
$clave_corporativo = check_empty(mysqli_real_escape_string($conexion, $_POST['clave_corporativo']), 'clave_corporativo');
$anydesk = check_empty(mysqli_real_escape_string($conexion, $_POST['anydesk']), 'anydesk');
$pin = check_empty(mysqli_real_escape_string($conexion, $_POST['pin']), 'pin');
$cuenta_mi = check_empty(mysqli_real_escape_string($conexion, $_POST['cuenta_mi']), 'cuenta_mi');
$clave_mi = check_empty(mysqli_real_escape_string($conexion, $_POST['clave_mi']), 'clave_mi');
$precio = check_empty(mysqli_real_escape_string($conexion, $_POST['precio']), 'precio');
$nota = check_empty(mysqli_real_escape_string($conexion, $_POST['nota']), 'nota');
$almacenamiento = check_empty(mysqli_real_escape_string($conexion, $_POST['almacenamiento']), 'almacenamiento');
$consumo_datos = mysqli_real_escape_string($conexion, $_POST['consumo_datos']);
$vidrio = mysqli_real_escape_string($conexion, $_POST['vidrio']);
$forro = mysqli_real_escape_string($conexion, $_POST['forro']);
$pantalla = mysqli_real_escape_string($conexion, $_POST['pantalla']);
$camara = mysqli_real_escape_string($conexion, $_POST['camara']);
$cargador = mysqli_real_escape_string($conexion, $_POST['cargador']);
$cable_usb = mysqli_real_escape_string($conexion, $_POST['cable_usb']);
$adaptador = mysqli_real_escape_string($conexion, $_POST['adaptador']);




$sql1="INSERT INTO telefonos(fecha_recep,fecha_ult_mant,fecha_ult_rev,id_modelo,id_sisver,id_operadora,id_sucursal,accesorios,app_conf,imei1,imei2,imei_adn,serial,mac_lan,mac_wifi,numero,cuenta_google,clave_google,correo_corporativo,clave_corporativo,anydesk,pin,cuenta_mi,clave_mi, precio, nota, almacenamiento_ocupado, consumo_datos, vidrio,forro,pantalla,camara,cargador,cable_usb,adaptador)
VALUES('$fecha_recep','$fecha_ult_mant','$fecha_ult_rev','$modelo','$sisver','$operadora','$sucursal','$accesorios_string','$apps_string','$imei1','$imei2','$imei_adn','$serial','$mac_lan','$mac_wifi','$numero','$cuenta_google','$clave_google','$correo_corporativo','$clave_corporativo','$anydesk','$pin','$cuenta_mi','$clave_mi','$precio','$nota','$almacenamiento','$consumo_datos','$vidrio','$forro','$pantalla','$camara','$cargador','$cable_usb','$adaptador')";

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
    echo '<script language="javascript">alert("Teléfono añadido correctamente"); window.location.href = "indexTelefonos.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el telefono");</script>';
}
