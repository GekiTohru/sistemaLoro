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

$id_personal = $_POST['id_personal'];
$cargoruta = $_POST['cargoruta'];
$area = $_POST['area'];
$nombre = $_POST['nombre'];


$sql1 = "UPDATE personal SET id_cargoruta = :cargoruta, id_area = :area, nombre = :nombre
WHERE personal.id_personal = :id_personal";

$stmt = $conexion->prepare($sql1);
$stmt->bindParam(':area', $area);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':id_personal', $id_personal);
$stmt ->bindParam(':cargoruta', $cargoruta);    

if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
