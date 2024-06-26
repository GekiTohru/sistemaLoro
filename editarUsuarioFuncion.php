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
$id_user=$_POST['id_user']; 
$user = mysqli_real_escape_string($conexion, $_POST['user']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$pass = mysqli_real_escape_string($conexion, $_POST['pass']);
$permisos = mysqli_real_escape_string($conexion, $_POST['permisos']);

$sql1="UPDATE usuario SET user = '$user', nombre = '$nombre', clave = '$pass', permisos = '$permisos'
WHERE id_usuario = $id_user";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Usuario editado correctamente"); window.location.href = "idxUsuarios.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar el usuario");</script>';
}
