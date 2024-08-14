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

  $marca = check_empty($_POST['fabricante']);
  $nombre = check_empty($_POST['nombre']);
  $ram = check_empty($_POST['ram']);
  $rom = check_empty($_POST['rom']);
  $tipo = check_empty($_POST['dispositivo']);



  $sql1 = "INSERT INTO modelo_marca(id_fabricante, nombre, ram, rom, tipo)
  VALUES(:marca, :nombre, :ram, :rom, :tipo)";
  
  $stmt = $conexion->prepare($sql1);
  $stmt->bindParam(':marca', $marca);
  $stmt->bindParam(':nombre', $nombre);
  $stmt->bindParam(':ram', $ram);
  $stmt->bindParam(':rom', $rom);
  $stmt->bindParam(':tipo', $tipo);

  if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
