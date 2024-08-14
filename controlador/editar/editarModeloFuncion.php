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

  $id_modelo = $_POST['id_modelo'];
  $marca = check_empty($_POST['fabricante']);
  $nombre = check_empty($_POST['nombre']);
  $ram = check_empty($_POST['ram']);
  $rom = check_empty($_POST['rom']);
  $tipo = check_empty($_POST['dispositivo']);



  $sql1 = "UPDATE modelo_marca SET 
  id_fabricante = :marca,
  nombre = :nombre,
  ram = :ram,
  rom = :rom,
  tipo = :tipo
WHERE id_modelo = :id_modelo";

$stmt = $conexion->prepare($sql1);
$stmt->bindParam(':marca', $marca);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':ram', $ram);
$stmt->bindParam(':rom', $rom);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':id_modelo', $id_modelo);

if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
