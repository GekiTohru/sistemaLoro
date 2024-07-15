<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

function eliminarRegistro($conexion, $tabla, $id_columna, $id) {
    $sql = "UPDATE $tabla SET activo=0 WHERE $id_columna=:id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($tabla == 'telefonos') {
        $sql2 = "UPDATE tlf_asignado SET activo=0 WHERE id_telefono=:id";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();
    }
    if ($tabla == 'personal') {
        $sql2 = "UPDATE tlf_asignado SET activo=0 WHERE id_personal=:id";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();
    }
    
    return $stmt->execute();
}

if (isset($_GET["tabla"]) && isset($_GET["id_columna"]) && isset($_GET["id"]) && isset($_GET["redirect"])) {
    $tabla = $_GET["tabla"];
    $id_columna = $_GET["id_columna"];
    $id = $_GET["id"];
    $redirect = $_GET["redirect"];

    if (eliminarRegistro($conexion, $tabla, $id_columna, $id)) {
        echo '<script language="javascript">alert("Datos eliminados correctamente"); window.location.href = "'.$redirect.'";</script>';
    } else {
        echo '<script language="javascript">alert("Error al eliminar los datos");</script>';
    }
} else {
    echo '<script language="javascript">alert("Parámetros insuficientes");</script>';
}

$sql2 = "UPDATE tlf_asignado SET activo=0 WHERE id_asignado=:id";
