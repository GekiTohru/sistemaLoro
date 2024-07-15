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

function check_empty($value) {
    if (empty($value) || is_null($value) || trim($value) === '') {
        return "N/A";
    } else {
        return $value;
    }
}

try {
    $conexion->beginTransaction();
    
    $id_pc = $_POST['id_pc'];
    $fecha_ult_mant = $_POST['fecha_ult_mant'];
    $fecha_ult_rev = $_POST['fecha_ult_rev'];
    $compra_teclado = $_POST['compra_teclado'];
    $bateria_reemplazada = $_POST['bateria_reemplazada'];
    $id_tipo_equipo = $_POST['tipo_equipo'];
    $id_personal = $_POST['personal'];
    $id_fabricante = $_POST['fabricante'];
    $id_red = $_POST['red_lan'];
    $id_pcso = $_POST['pc_sis_op'];
    $id_almacentipo = $_POST['tipo_almacenamiento'];
    $id_sisadmin = $_POST['sistema_admin'];
    $id_sucursal = $_POST['sucursal'];
    $programas = $_POST['programas'];
    $programas_string = implode(',', $programas);
    $accesorios = $_POST['accesorios'];
    $accesorios_string = implode(',', $accesorios);
    $nombre = check_empty($_POST['nombre']);
    $user_admin = check_empty($_POST['admin']);
    $motherboard = check_empty($_POST['motherboard']);
    $serial = check_empty($_POST['serial']);
    $procesador = check_empty($_POST['procesador']);
    $costo = check_empty($_POST['costo']);
    $clave_win = check_empty($_POST['clave_win']);
    $pin = check_empty($_POST['pin']);
    $resp_seguridad = check_empty($_POST['resp_seguridad']);
    $ups = check_empty($_POST['ups']);
    $potencia_ups = check_empty($_POST['potencia_ups']);
    $bateria_ups = check_empty($_POST['bateria_ups']);
    $anydesk = check_empty($_POST['anydesk']);
    $clave_anydesk = check_empty($_POST['clave_anydesk']);
    $mac_lan = check_empty($_POST['mac_lan']);
    $mac_wifi = check_empty($_POST['mac_wifi']);
    $almacenamiento = check_empty($_POST['rom']);
    $ram = check_empty($_POST['ram']);
    $status = $_POST['status'];
    $prio_sus = $_POST['prio_sus'];
    $estado_ups = $_POST['estado_ups'];
    $mouse = $_POST['mouse'];
    $camara = $_POST['camara'];
    $nota = $_POST['nota'];
    $pantalla = $_POST['pantalla_monitor'];
    $estado_teclado = $_POST['estado_teclado'];
    $cargador = $_POST['cargador'];
    $cable_mickey = $_POST['cable_mickey'];
    $mantenimiento = isset($_POST['mantenimiento']) ? $_POST['mantenimiento'] : 0;
    $realizador = $_SESSION['nombre'];

    $sql1 = "UPDATE computadoras SET
      fecha_ult_mant = :fecha_ult_mant,
      fecha_ult_rev = :fecha_ult_rev,
      compra_teclado = :compra_teclado,
      bateria_reemplazada = :bateria_reemplazada,
      id_tipo_equipo = :id_tipo_equipo,
      id_personal = :id_personal,
      id_fabricante = :id_fabricante,
      id_red = :id_red,
      id_pcso = :id_pcso,
      id_almacentipo = :id_almacentipo,
      id_sisadmin = :id_sisadmin,
      id_sucursal = :id_sucursal,
      programas = :programas,
      accesorios = :accesorios,
      nombre = :nombre,
      user_admin = :user_admin,
      motherboard = :motherboard,
      serial = :serial,
      procesador = :procesador,
      costo = :costo,
      clave_win = :clave_win,
      pin = :pin,
      resp_seguridad = :resp_seguridad,
      ups = :ups,
      potencia_ups = :potencia_ups,
      bateria_ups = :bateria_ups,
      anydesk = :anydesk,
      clave_anydesk = :clave_anydesk,
      mac_lan = :mac_lan,
      mac_wifi = :mac_wifi,
      almacenamiento = :almacenamiento,
      ram = :ram,
      status = :status,
      prio_sus = :prio_sus,
      estado_ups = :estado_ups,
      mouse = :mouse,
      camara = :camara,
      nota = :nota,
      pantalla_monitor = :pantalla_monitor,
      estado_teclado = :estado_teclado,
      cargador = :cargador,
      cable_mickey = :cable_mickey
    WHERE id_pc = :id_pc";

    $stmt1 = $conexion->prepare($sql1);
    $stmt1->execute([
        ':fecha_ult_mant' => $fecha_ult_mant,
        ':fecha_ult_rev' => $fecha_ult_rev,
        ':compra_teclado' => $compra_teclado,
        ':bateria_reemplazada' => $bateria_reemplazada,
        ':id_tipo_equipo' => $id_tipo_equipo,
        ':id_personal' => $id_personal,
        ':id_fabricante' => $id_fabricante,
        ':id_red' => $id_red,
        ':id_pcso' => $id_pcso,
        ':id_almacentipo' => $id_almacentipo,
        ':id_sisadmin' => $id_sisadmin,
        ':id_sucursal' => $id_sucursal,
        ':programas' => $programas_string,
        ':accesorios' => $accesorios_string,
        ':nombre' => $nombre,
        ':user_admin' => $user_admin,
        ':motherboard' => $motherboard,
        ':serial' => $serial,
        ':procesador' => $procesador,
        ':costo' => $costo,
        ':clave_win' => $clave_win,
        ':pin' => $pin,
        ':resp_seguridad' => $resp_seguridad,
        ':ups' => $ups,
        ':potencia_ups' => $potencia_ups,
        ':bateria_ups' => $bateria_ups,
        ':anydesk' => $anydesk,
        ':clave_anydesk' => $clave_anydesk,
        ':mac_lan' => $mac_lan,
        ':mac_wifi' => $mac_wifi,
        ':almacenamiento' => $almacenamiento,
        ':ram' => $ram,
        ':status' => $status,
        ':prio_sus' => $prio_sus,
        ':estado_ups' => $estado_ups,
        ':mouse' => $mouse,
        ':camara' => $camara,
        ':nota' => $nota,
        ':pantalla_monitor' => $pantalla,
        ':estado_teclado' => $estado_teclado,
        ':cargador' => $cargador,
        ':cable_mickey' => $cable_mickey,
        ':id_pc' => $id_pc
    ]);

    if ($mantenimiento == true) {
        $sql2 = "INSERT INTO registro_mantenimiento(fecha_mantenimiento, id_pc, realizador)
        VALUES (:fecha_ult_mant, :id_pc, :realizador)";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->execute([
            ':fecha_ult_mant' => $fecha_ult_mant,
            ':id_pc' => $id_pc,
            ':realizador' => $realizador
        ]);
    }

    $conexion->commit();

    echo '<script language="javascript">alert("Computadora editada correctamente"); window.location.href = "../../vista/index/indexPC.php";</script>';
} catch (PDOException $e) {
    $conexion->rollBack();
    echo '<script language="javascript">alert("Error al editar la computadora: ' . $e->getMessage() . '");</script>';
}
?>
