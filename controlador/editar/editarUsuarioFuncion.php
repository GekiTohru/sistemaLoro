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
$id_user=$_POST['id_user']; 
$user = $_POST['user'];
$nombre = $_POST['nombre'];
$pass = $_POST['pass'];
$permisos = $_POST['permisos'];

$sql1 = "UPDATE usuario SET 
  usuario = :user, 
  nombre = :nombre, 
  clave = :pass, 
  permisos = :permisos
WHERE id_usuario = :id_user";

$stmt = $conexion->prepare($sql1);

$stmt->bindParam(':user', $user);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':pass', $pass);
$stmt->bindParam(':permisos', $permisos);
$stmt->bindParam(':id_user', $id_user);

if ($stmt->execute()) {
    echo '<script language="javascript">alert("Usuario editado correctamente"); window.location.href = "../../vista/index/idxUsuarios.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar el usuario");</script>';
}
