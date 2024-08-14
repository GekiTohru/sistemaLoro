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
  $id_tipo_equipo = check_empty($_POST['id_tipo_equipo']);
  $nombre = check_empty($_POST['nombre']);
  $teclado = check_empty($_POST['teclado']);


  $sql1 = "UPDATE tipo_equipo SET nombre = :nombre, teclado = :teclado
  WHERE tipo_equipo.id_tipo_equipo = :id_tipo_equipo";
  
  $stmt = $conexion->prepare($sql1);
  $stmt->bindParam(':nombre', $nombre);
  $stmt->bindParam(':teclado', $teclado);
  $stmt->bindParam(':id_tipo_equipo', $id_tipo_equipo);
  
if ($stmt->execute()) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}
