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

$sql = "SELECT * FROM usuario
WHERE usuario.activo = 1
GROUP BY usuario.id_usuario";
$query = mysqli_query($conexion, $sql);

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$user = [];
while ($row = mysqli_fetch_assoc($query)) {
  $user[] = $row;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/buttons.css" rel="stylesheet">
    <script src="js/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/b-3.0.2/date-1.5.2/r-3.0.2/sc-2.4.3/sl-2.0.3/datatables.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/datatables/datatables.min.js"></script>
</head>
<header>
    <div style="height: 50px;"></div>
    <img src="img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="cerrarSesion.php" class="navbtn">Salir</a>
            <a href="lobby.php" class="navbtn">Inicio</a>
            <a href="lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
        </div>
    </nav>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='crearUsuario.php'">Nuevo usuario!</button>    

    <div class="users-table">
        <h2 style="text-align: center;">Usuarios registrados</h2>
        <input type="hidden" id="filterInput">
<table id="tablaPersonal" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Permisos</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($user as $fila): ?>
            <tr>
                <td><?= $fila['id_usuario'] ?></td>
                <td><?= $fila['user'] ?></td>
                <td><?= $fila['nombre'] ?></td>
                <?php if ($fila['permisos'] == 1): ?>
                        <td><?= 'Administrador' ?></td>
                        <?php else: ?>
                        <td><?= 'Usuario' ?></td>
                    <?php endif; ?>
                <td>
                    <div><a href="editarUsuario.php?id=<?= $fila['id_usuario'] ?>" class="users-table--edit">Editar</a></div>
                    <?php if ($_SESSION['permisos'] == 1): ?>
                        <div><a href="eliminarFuncion.php?tabla=usuario&id_columna=id_usuario&id=<?= $fila['id_usuario'] ?>&redirect=idxUsuarios.php" class="users-table--edit" onclick="return confirm('¿Estás seguro de eliminar este elemento?')">Eliminar</a></div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
