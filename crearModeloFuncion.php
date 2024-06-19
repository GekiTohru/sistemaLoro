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

  $marca = check_empty(mysqli_real_escape_string($conexion, $_POST['marca']));
  $nombre = check_empty(mysqli_real_escape_string($conexion, $_POST['nombre']));
  $ram = check_empty(mysqli_real_escape_string($conexion, $_POST['ram']));
  $rom = check_empty(mysqli_real_escape_string($conexion, $_POST['rom']));
  $tipo = check_empty(mysqli_real_escape_string($conexion, $_POST['dispositivo']));



$sql1="INSERT INTO modelo_marca(id_marca,nombre,ram,rom,tipo)
VALUES('$marca','$nombre','$ram','$rom','$tipo')";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Modelo añadido correctamente"); window.location.href = "indexTelefonos.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el modelo");</script>';
}
