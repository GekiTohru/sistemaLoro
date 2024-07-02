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
  $id_tipo_equipo = check_empty(mysqli_real_escape_string($conexion, $_POST['id_tipo_equipo']));
  $nombre = check_empty(mysqli_real_escape_string($conexion, $_POST['nombre']));
  $teclado = check_empty(mysqli_real_escape_string($conexion, $_POST['teclado']));



  $sql1="UPDATE tipo_equipo SET nombre = '$nombre', teclado = '$teclado'
WHERE tipo_equipo.id_tipo_equipo = $id_tipo_equipo";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Tipo de equipo editado correctamente"); window.location.href = "indexGeneral.php?tabla=tipo_equipo";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar el tipo de equipo");</script>';
}
