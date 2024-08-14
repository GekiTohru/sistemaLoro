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


$user = $_POST['user'];
$nombre = $_POST['nombre'];
$pass = $_POST['pass'];
$permisos = $_POST['permisos'];

$sql1 = "INSERT INTO usuario (usuario, nombre, clave, permisos) 
          VALUES (:user, :nombre, :pass, :permisos)";

$stmt = $conexion->prepare($sql1);

$stmt->bindParam(':user', $user);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':pass', $pass);
$stmt->bindParam(':permisos', $permisos);

if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
