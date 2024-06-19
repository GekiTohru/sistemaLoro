<?php
// Inicialización de sesión
session_start();

if (isset($_SESSION['user'])) {
    header("Location: indexTelefonos.php");
    exit();
}

include("conexion.php");

// Obtener los datos del formulario
$user = $_POST['user'];
$clave = $_POST['clave'];

$stmt = $conexion->prepare("SELECT * FROM usuario WHERE user =? AND clave =? AND activo = 1");
$stmt->bind_param("ss", $user, $clave);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    
    $_SESSION['user'] = $user;
    $_SESSION['permisos'] = $usuario['permisos'];
    $_SESSION['nombre'] = $usuario['nombre'];

    echo '<script language="javascript">alert("Inicio de sesión exitoso. Bienvenido, '.$_SESSION['nombre']. '!"); window.location.href = "lobby.php";</script>';
    exit();
} else {
    echo '<script language="javascript">alert("Usuario o contraseña incorrectos. Inténtelo de nuevo."); window.location.href = "login.php";</script>';

}
?>
