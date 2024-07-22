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

$sql = "SELECT * FROM usuario
WHERE usuario.activo = 1";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <link href="../../css/buttons.css" rel="stylesheet">
    <script src="../../js/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/b-3.0.2/date-1.5.2/r-3.0.2/sc-2.4.3/sl-2.0.3/datatables.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/datatables/datatables.min.js"></script>
</head>
<header>
    <div style="height: 50px;"></div>
    <img src="../../img/logo.png" id="logo">
</header>
<body>
<nav class="navbar">
        <div class="navbar-left">
            <a href="../../controlador/cerrarSesion.php" class="navbtn">Salir</a>
            <a href="../lobby.php" class="navbtn">Inicio</a>
            <a href="../lobbyCrearTlf.php" class="navbtn">Añadir</a>
            <a href="../lobbyVerTlf.php" class="navbtn">Ver y editar</a>
            <div class="dropdown">
                 <button class="dropbtn">Gestionar</button>
                 <div class="dropdown-content">
                     <a href="indexTelefonos.php">Teléfonos</a>
                     <a href="indexPc.php">Computadoras</a>
                     <a href="indexImpresoras.php">Impresoras</a>
                  <?php if ($_SESSION['permisos'] == 1) {
                  echo'<a href="idxUsuarios.php">Usuarios</a>';
                        }
                  ?>
                 </div>
             </div>
             
        </div>
    </nav>
    <button style="width:250px; margin-top: 20px" class="icon-slide-right" onclick="location.href='../crear/crearUsuario.php'">Nuevo usuario!</button>    

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
                <td><?= $fila['usuario'] ?></td>
                <td><?= $fila['nombre'] ?></td>
                <?php if ($fila['permisos'] == 1): ?>
                        <td><?= 'Administrador' ?></td>
                        <?php else: ?>
                        <td><?= 'Usuario' ?></td>
                    <?php endif; ?>
                <td>
                    <div style="display: flex;">
                    <div><a href="../editar/editarUsuario.php?id=<?= $fila['id_usuario'] ?>" class="users-table--edit" title="Editar"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-pencil"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 7.5l3 3M4 20v-3.5L15.293 5.207a1 1 0 011.414 0l2.086 2.086a1 1 0 010 1.414L7.5 20H4z"/></svg></a></div>
                    <?php if ($_SESSION['permisos'] == 1): ?>
                        <div><a href="../../controlador/eliminarFuncion.php?tabla=usuario&id_columna=id_usuario&id=<?= $fila['id_usuario'] ?>&redirect=idxUsuarios.php" class="users-table--edit" onclick="return confirm('¿Estás seguro de eliminar este elemento?')"><svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="humbleicons hi-trash"><path xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l.934 13.071A1 1 0 007.93 20h8.138a1 1 0 00.997-.929L18 6m-6 5v4m8-9H4m4.5 0l.544-1.632A2 2 0 0110.941 3h2.117a2 2 0 011.898 1.368L15.5 6"/></svg></a></div>
                        <?php endif; ?>
                    </div>
                    </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
