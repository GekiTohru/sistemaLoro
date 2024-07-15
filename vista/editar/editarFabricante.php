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

    $id_fabricante=$_GET['id'];
    


    $sql="SELECT fabricante.*, fabricante.id_fabricante AS id
    FROM fabricante
    WHERE fabricante.id_fabricante = $id_fabricante";
       $stmt = $conexion->prepare($sql);
       $stmt->execute();
       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);       
       $row0 = $result[0];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistemaloro</title>
    <link href="../../css/styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                     <a href="../indexTelefonos.php">Teléfonos</a>
                     <a href="../indexPc.php">Computadoras</a>
                     <a href="../indexImpresoras.php">Impresoras</a>
                 </div>
             </div>
             <?php if ($_SESSION['permisos'] == 1) {
           echo'<div class="dropdown">
                <button class="dropbtn">Administrar</button>
                <div class="dropdown-content">
                    <a href="../idxUsuarios.php">Gestionar usuarios</a>
                    <a href="#">Opción de prueba</a>
                </div>
            </div>';
                }
                ?>
        </div>
    </nav>
    <div class="users-table">
        <h2 style="text-align: center;">Editar fabricante</h2>
        <div class="users-form">
            <form id="nuevo" action="../../controlador/editar/editarFuncion.php?redirect=fabricante" method="POST">
            <input type="hidden" name="id_fabricante" value="<?= $row0['id']?>">
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="nombre">Fabricante</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese el fabricante" value="<?= $row0['nombre']?>" required>
                </div>
                <div class="inputs">
                <label for="equipo">Tipo de equipo</label>
                <select name="equipo" id="equipo">
                <option value="PC" <?= $row0['equipo'] == 'PC'? 'selected' : ''?>>PC</option>
                <option value="Teléfono" <?= $row0['equipo'] == 'Teléfono'? 'selected' : ''?>>Teléfono</option>
                <option value="Impresora" <?= $row0['equipo'] == 'Impresora'? 'selected' : ''?>>Impresora</option>
                </select>
                </div>
                </div>
                <input type="submit" value="Editar fabricante">
            </form>
        </div>
        

        </body>
</html>