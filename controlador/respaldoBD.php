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
$conexion = $conexionObj->ConexionBD();

$response = [];

if ($conexion) {
    $fecha = date("Ymd_His");
    $archivo = "E:\\DATASQL\\MSSQL12.MSSQLSERVER\\MSSQL\\Backup\\BACKUP equip_corp\\equip_corp_" . $fecha . ".bak";
    
    $cmd = "sqlcmd -S localhost -U phpmyadmin -P 1234 -Q \"BACKUP DATABASE equip_corp TO DISK = '$archivo' WITH FORMAT, MEDIANAME = 'SQLServerBackups1', NAME = 'Full Backup equip_corp'\"";
    exec($cmd, $output, $return_var);

    if ($return_var === 0) {
        $response['success'] = true;
        $response['message'] = "Backup realizado con éxito";
    } else {
        $response['success'] = false;
        $response['message'] = "Error al realizar el backup. Código de error: " . $return_var;
        $response['output'] = implode("\n", $output); // Incluye el output en la respuesta para más detalles
    }
} else {
    $response['success'] = false;
    $response['message'] = "Error al conectar a la base de datos";
}

echo json_encode($response);
  ?>
