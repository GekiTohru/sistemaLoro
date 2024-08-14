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

  $id_fabricante = check_empty($_POST['id_fabricante']);
  $nombre = check_empty($_POST['nombre']);
  $equipo = check_empty($_POST['equipo']);



$sql="UPDATE fabricante SET nombre = '$nombre', equipo = '$equipo' WHERE id_fabricante = '$id_fabricante'";
$stmt = $conexion->prepare($sql);
$stmt->execute();
if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}

