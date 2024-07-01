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
$costo = check_empty(mysqli_real_escape_string($conexion, $_POST['costo']));
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




$sql1="INSERT INTO computadoras (
  fecha_ult_mant,
  fecha_ult_rev,
  compra_teclado,
  bateria_reemplazada,
  id_tipo_equipo,
  id_personal,
  id_fabricante,
  id_red,
  id_pcso,
  id_almacentipo,
  id_sisadmin,
  id_sucursal,
  programas,
  accesorios,
  nombre,
  user_admin,
  motherboard,
  serial,
  procesador,
  costo,
  clave_win,
  pin,
  resp_seguridad,
  ups,
  potencia_ups,
  bateria_ups,
  anydesk,
  clave_anydesk,
  mac_lan,
  mac_wifi,
  almacenamiento,
  ram,
  status,
  prio_sus,
  estado_ups,
  mouse,
  camara,
  nota,
  pantalla_monitor,
  estado_teclado,
  cargador,
  cable_mickey
) VALUES (
  '$fecha_ult_mant',
  '$fecha_ult_rev',
  '$compra_teclado',
  '$bateria_reemplazada',
  '$id_tipo_equipo',
  '$id_personal',
  '$id_fabricante',
  '$id_red',
  '$id_pcso',
  '$id_almacentipo',
  '$id_sisadmin',
  '$id_sucursal',
  '$programas_string',
  '$accesorios_string',
  '$nombre',
  '$user_admin',
  '$motherboard',
  '$serial',
  '$procesador',
  '$costo',
  '$clave_win',
  '$pin',
  '$resp_seguridad',
  '$ups',
  '$potencia_ups',
  '$bateria_ups',
  '$anydesk',
  '$clave_anydesk',
  '$mac_lan',
  '$mac_wifi',
  '$almacenamiento',
  '$ram',
  '$status',
  '$prio_sus',
  '$estado_ups',
  '$mouse',
  '$camara',
  '$nota',
  '$pantalla',
  '$estado_teclado',
  '$cargador',
  '$cable_mickey'
)";

$query1 = mysqli_query($conexion, $sql1);



if ($query1) {
    echo '<script language="javascript">alert("Computadora añadida correctamente"); window.location.href = "indexPC.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir la computadora");</script>';
}
