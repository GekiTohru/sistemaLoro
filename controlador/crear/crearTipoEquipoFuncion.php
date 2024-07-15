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

  $nombre = check_empty($_POST['nombre']);
  $teclado = check_empty($_POST['teclado']);



  $sql1 = "INSERT INTO tipo_equipo(nombre, teclado)
  VALUES(:nombre, :teclado)";
  
  $stmt = $conexion->prepare($sql1);
  $stmt->bindParam(':nombre', $nombre);
  $stmt->bindParam(':teclado', $teclado);
  
  if ($stmt->execute()) {
    echo '<script language="javascript">alert("Tipo de equipo añadido correctamente"); window.location.href = "../../vista/index/indexGeneral.php?tabla=tipo_equipo";</script>';
} else {
    echo '<script language="javascript">alert("Error al añadir el tipo de equipo");</script>';
}
