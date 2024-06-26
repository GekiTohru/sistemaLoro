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

  $nombre = check_empty(mysqli_real_escape_string($conexion, $_POST['nombre']));
  $equipo = check_empty(mysqli_real_escape_string($conexion, $_POST['equipo']));



$sql1="INSERT INTO fabricante(nombre,equipo)
VALUES('$nombre','$equipo')";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Fabricante añadido correctamente"); window.location.href = "indexTelefonos.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el fabricante");</script>';
}
