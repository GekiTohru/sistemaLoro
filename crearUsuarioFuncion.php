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


$user = mysqli_real_escape_string($conexion, $_POST['user']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$pass = mysqli_real_escape_string($conexion, $_POST['pass']);
$permisos = mysqli_real_escape_string($conexion, $_POST['permisos']);

$sql1="INSERT INTO usuario(user,nombre,clave,permisos)
VALUES('$user','$nombre','$pass','$permisos')";

$query1 = mysqli_query($conexion, $sql1);

if ($query1) {
    echo '<script language="javascript">alert("Usuario añadido correctamente"); window.location.href = "idxUsuarios.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el usuario");</script>';
}
