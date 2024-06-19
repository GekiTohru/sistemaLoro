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


$id_pc = mysqli_real_escape_string($conexion, $_POST['id_pc']);
$fecha_ult_mant = mysqli_real_escape_string($conexion, $_POST['fecha_ult_mant']);
$fecha_ult_rev = mysqli_real_escape_string($conexion, $_POST['fecha_ult_rev']);
$compra_teclado = mysqli_real_escape_string($conexion, $_POST['compra_teclado']);
$bateria_reemplazada = mysqli_real_escape_string($conexion, $_POST['bateria_reemplazada']);
$id_tipo_equipo = mysqli_real_escape_string($conexion, $_POST['tipo_equipo']);
$id_personal = mysqli_real_escape_string($conexion, $_POST['personal']);
$id_fabricante = mysqli_real_escape_string($conexion, $_POST['fabricante']);
$id_red = mysqli_real_escape_string($conexion, $_POST['red_lan']);
$id_pcso = mysqli_real_escape_string($conexion, $_POST['pc_sis_op']);
$id_almacentipo = mysqli_real_escape_string($conexion, $_POST['tipo_almacenamiento']);
$id_sisadmin = mysqli_real_escape_string($conexion, $_POST['sistema_admin']);
$id_sucursal = mysqli_real_escape_string($conexion, $_POST['sucursal']);
$programas = $_POST['programas'];
$programas_string = implode(',', array_map(function($value) use ($conexion) {
    return mysqli_real_escape_string($conexion, $value);
}, $programas));
$accesorios = $_POST['accesorios'];
$accesorios_string = implode(',', array_map(function($value) use ($conexion) {
    return mysqli_real_escape_string($conexion, $value);
}, $accesorios));
$nombre = check_empty(mysqli_real_escape_string($conexion, $_POST['nombre']));
$user_admin = check_empty(mysqli_real_escape_string($conexion, $_POST['admin']));
$motherboard = check_empty(mysqli_real_escape_string($conexion, $_POST['motherboard']));
$serial = check_empty(mysqli_real_escape_string($conexion, $_POST['serial']));
$procesador = check_empty(mysqli_real_escape_string($conexion, $_POST['procesador']));
$clave_win = check_empty(mysqli_real_escape_string($conexion, $_POST['clave_win']));
$pin = check_empty(mysqli_real_escape_string($conexion, $_POST['pin']));
$resp_seguridad = check_empty(mysqli_real_escape_string($conexion, $_POST['resp_seguridad']));
$ups = check_empty(mysqli_real_escape_string($conexion, $_POST['ups']), 'ups');
$potencia_ups = check_empty(mysqli_real_escape_string($conexion, $_POST['potencia_ups']));
$bateria_ups = check_empty(mysqli_real_escape_string($conexion, $_POST['bateria_ups']));
$anydesk = check_empty(mysqli_real_escape_string($conexion, $_POST['anydesk']));
$clave_anydesk = check_empty(mysqli_real_escape_string($conexion, $_POST['clave_anydesk']));
$mac_lan = check_empty(mysqli_real_escape_string($conexion, $_POST['mac_lan']));
$mac_wifi = check_empty(mysqli_real_escape_string($conexion, $_POST['mac_wifi']));
$almacenamiento = check_empty(mysqli_real_escape_string($conexion, $_POST['rom']));
$ram = check_empty(mysqli_real_escape_string($conexion, $_POST['ram']));
$status = mysqli_real_escape_string($conexion, $_POST['status']);
$prio_sus = mysqli_real_escape_string($conexion, $_POST['prio_sus']);
$estado_ups = mysqli_real_escape_string($conexion, $_POST['estado_ups']);
$mouse = mysqli_real_escape_string($conexion, $_POST['mouse']);
$camara = mysqli_real_escape_string($conexion, $_POST['camara']);
$nota = mysqli_real_escape_string($conexion, $_POST['nota']);
$pantalla = mysqli_real_escape_string($conexion, $_POST['pantalla/monitor']);
$estado_teclado = mysqli_real_escape_string($conexion, $_POST['estado_teclado']);
$cargador = mysqli_real_escape_string($conexion, $_POST['cargador']);
$cable_mickey = mysqli_real_escape_string($conexion, $_POST['cable_mickey']);




$sql1 = "UPDATE computadoras SET
  fecha_ult_mant = '$fecha_ult_mant',
  fecha_ult_rev = '$fecha_ult_rev',
  compra_teclado = '$compra_teclado',
  bateria_reemplazada = '$bateria_reemplazada',
  id_tipo_equipo = '$id_tipo_equipo',
  id_personal = '$id_personal',
  id_fabricante = '$id_fabricante',
  id_red = '$id_red',
  id_pcso = '$id_pcso',
  id_almacentipo = '$id_almacentipo',
  id_sisadmin = '$id_sisadmin',
  id_sucursal = '$id_sucursal',
  programas = '$programas_string',
  accesorios = '$accesorios_string',
  nombre = '$nombre',
  user_admin = '$user_admin',
  motherboard = '$motherboard',
  serial = '$serial',
  procesador = '$procesador',
  clave_win = '$clave_win',
  pin = '$pin',
  resp_seguridad = '$resp_seguridad',
  ups = '$ups',
  potencia_ups = '$potencia_ups',
  bateria_ups = '$bateria_ups',
  anydesk = '$anydesk',
  clave_anydesk = '$clave_anydesk',
  mac_lan = '$mac_lan',
  mac_wifi = '$mac_wifi',
  almacenamiento = '$almacenamiento',
  ram = '$ram',
  status = '$status',
  prio_sus = '$prio_sus',
  estado_ups = '$estado_ups',
  mouse = '$mouse',
  camara = '$camara',
  nota = '$nota',
  pantalla_monitor = '$pantalla',
  estado_teclado = '$estado_teclado',
  cargador = '$cargador',
  cable_mickey = '$cable_mickey'
WHERE id_pc = '$id_pc'";


$query1 = mysqli_query($conexion, $sql1);



if ($query1) {
    echo '<script language="javascript">alert("Computadora editada correctamente"); window.location.href = "indexPC.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar la computadora");</script>';
}
