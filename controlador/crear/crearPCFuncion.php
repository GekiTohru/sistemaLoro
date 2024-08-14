<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../../login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

    $sql1 = "INSERT INTO computadoras (
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
      :fecha_ult_mant,
      :fecha_ult_rev,
      :compra_teclado,
      :bateria_reemplazada,
      :id_tipo_equipo,
      :id_personal,
      :id_fabricante,
      :id_red,
      :id_pcso,
      :id_almacentipo,
      :id_sisadmin,
      :id_sucursal,
      :programas,
      :accesorios,
      :nombre,
      :user_admin,
      :motherboard,
      :serial,
      :procesador,
      :costo,
      :clave_win,
      :pin,
      :resp_seguridad,
      :ups,
      :potencia_ups,
      :bateria_ups,
      :anydesk,
      :clave_anydesk,
      :mac_lan,
      :mac_wifi,
      :almacenamiento,
      :ram,
      :status,
      :prio_sus,
      :estado_ups,
      :mouse,
      :camara,
      :nota,
      :pantalla_monitor,
      :estado_teclado,
      :cargador,
      :cable_mickey
    )";

    $stmt1 = $conexion->prepare($sql1);

    $programas = 'sin programas';
if (isset($_POST['programas']) && is_array($_POST['programas'])) {
    $programas = implode(',', $_POST['programas']);
}

    $accesorios = 'sin accesorios';
if (isset($_POST['accesorios']) && is_array($_POST['accesorios'])) {
    $accesorios = implode(',', $_POST['accesorios']);
}

$stmt1->execute([
    ':fecha_ult_mant' => $_POST['fecha_ult_mant'],
    ':fecha_ult_rev' => $_POST['fecha_ult_rev'],
    ':compra_teclado' => $_POST['compra_teclado'],
    ':bateria_reemplazada' => $_POST['bateria_reemplazada'],
    ':id_tipo_equipo' => $_POST['tipo_equipo'],
    ':id_personal' => $_POST['personal'],
    ':id_fabricante' => $_POST['fabricante'],
    ':id_red' => $_POST['red_lan'],
    ':id_pcso' => $_POST['pc_sis_op'],
    ':id_almacentipo' => $_POST['tipo_almacenamiento'],
    ':id_sisadmin' => $_POST['sistema_admin'],
    ':id_sucursal' => $_POST['sucursal'],
    ':programas' => $programas,
    ':accesorios' => $accesorios,
    ':nombre' => $_POST['nombre'],
    ':user_admin' => $_POST['admin'],
    ':motherboard' => $_POST['motherboard'],
    ':serial' => $_POST['serial'],
    ':procesador' => $_POST['procesador'],
    ':costo' => $_POST['costo'],
    ':clave_win' => $_POST['clave_win'],
    ':pin' => $_POST['pin'],
    ':resp_seguridad' => $_POST['resp_seguridad'],
    ':ups' => $_POST['ups'],
    ':potencia_ups' => $_POST['potencia_ups'],
    ':bateria_ups' => $_POST['bateria_ups'],
    ':anydesk' => $_POST['anydesk'],
    ':clave_anydesk' => $_POST['clave_anydesk'],
    ':mac_lan' => $_POST['mac_lan'],
    ':mac_wifi' => $_POST['mac_wifi'],
    ':almacenamiento' => $_POST['rom'],
    ':ram' => $_POST['ram'],
    ':status' => $_POST['status'],
    ':prio_sus' => $_POST['prio_sus'],
    ':estado_ups' => $_POST['estado_ups'],
    ':mouse' => $_POST['mouse'],
    ':camara' => $_POST['camara'],
    ':nota' => $_POST['nota'],
    ':pantalla_monitor' => $_POST['pantalla_monitor'],
    ':estado_teclado' => $_POST['estado_teclado'],
    ':cargador' => $_POST['cargador'],
    ':cable_mickey' => $_POST['cable_mickey']
]);

if ($stmt1->rowCount() > 0) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
?>
