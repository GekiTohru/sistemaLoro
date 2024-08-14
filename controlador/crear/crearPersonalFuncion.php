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


$cargoruta = $_POST['cargoruta'];
$area = $_POST['area'];
$nombre = $_POST['nombre'];

$sql1 = "INSERT INTO personal(id_cargoruta, id_area, nombre)
VALUES(:cargoruta, :area, :nombre)";

$stmt = $conexion->prepare($sql1);
$stmt->bindParam(':cargoruta', $cargoruta);
$stmt->bindParam(':area', $area);
$stmt->bindParam(':nombre', $nombre);

if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
