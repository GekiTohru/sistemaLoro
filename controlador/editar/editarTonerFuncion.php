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
function check_empty($value) {
    if (empty($value) || is_null($value) || trim($value) === '') {
            return "N/A";
    } else {
        return $value;
    }
  }

$id_toner = $_POST['id_toner'];
$modelo = check_empty($_POST['modelo']);
$color = check_empty($_POST['color']);



$sql1 = "UPDATE toner SET
  modelo = :modelo,
  color = :color,
WHERE id_toner = :id_toner";

$stmt = $conexion->prepare($sql1);

// Bind parameters
$stmt->bindParam(':modelo', $modelo);
$stmt->bindParam(':color', $color);
$stmt->bindParam(':id_toner', $id_toner);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'error';
}