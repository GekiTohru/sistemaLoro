<?php
// archivo login.php

// Inicialización de sesión
session_start();

if (isset($_SESSION['user'])) {
    header("Location: ../vista/lobby.php");
    exit();
}

// Incluir archivo de conexión y asignar la conexión a la variable $conexion
include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");

$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

// Obtener los datos del formulario
$user = $_POST['user'];
$clave = $_POST['clave'];
$conexion->query("USE equip_corp");
$stmt = $conexion->prepare("SELECT [id_usuario], [usuario], [nombre], [clave], [permisos], [activo] 
                            FROM [dbo].[usuario] 
                            WHERE usuario = :user AND clave = :clave AND activo = 1");

$stmt->bindParam(':user', $user);
$stmt->bindParam(':clave', $clave);

$stmt->execute();
$result = $stmt->fetchAll();

if (count($result) > 0) {
    $usuario = $result[0]; // Accedemos al primer elemento del array
    
    $_SESSION['user'] = $user;
    $_SESSION['permisos'] = $usuario['permisos'];
    $_SESSION['nombre'] = $usuario['nombre'];

    echo '<script language="javascript">alert("Inicio de sesión exitoso. Bienvenido, '.$_SESSION['nombre']. '!"); window.location.href = "../vista/lobby.php";</script>';
    exit();
} else {
    echo '<script language="javascript">alert("Usuario o contraseña incorrectos. Inténtelo de nuevo."); window.location.href = "../login.php";</script>';

}
?>
