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


$nombre = $_POST['nombre'];
$gb = $_POST['gb'];
$cuota = $_POST['cuota'];

$sql1 = "INSERT INTO plan_tlf (nombre, gb, cuota) 
          VALUES (:nombre, :gb, :cuota)";

$stmt = $conexion->prepare($sql1);

$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':gb', $gb);
$stmt->bindParam(':cuota', $cuota);

if ($stmt->execute()) {
    echo 'ok';
  } else {
    echo 'Error: ' . $stmt->errorInfo()[2];
  }