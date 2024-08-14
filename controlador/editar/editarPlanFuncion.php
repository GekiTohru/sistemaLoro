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
$id_plan=$_POST['id_plan']; 
$nombre = $_POST['nombre'];
$gb = $_POST['gb'];
$cuota = $_POST['cuota'];

$sql1 = "UPDATE plan_tlf SET 
  nombre = :nombre, 
  gb = :gb, 
  cuota = :cuota 
WHERE id_plan = :id_plan";

$stmt = $conexion->prepare($sql1);

$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':gb', $gb);
$stmt->bindParam(':cuota', $cuota);
$stmt->bindParam(':id_plan', $id_plan);

if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
