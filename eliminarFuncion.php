<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos

include("conexion.php");

function eliminarRegistro($conexion, $tabla, $id_columna, $id) {
    $sql = "UPDATE $tabla SET activo=0 WHERE $id_columna='$id'";
    return mysqli_query($conexion, $sql);
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
