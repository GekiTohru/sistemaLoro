<?php

session_start();

if (isset($_SESSION['user'])) {
    header("Location: vista/lobby.php");
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <link href="css/buttons.css" rel="stylesheet">
    <script src="js/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.8/b-3.0.2/date-1.5.2/r-3.0.2/sc-2.4.3/sl-2.0.3/datatables.min.css" rel="stylesheet">
    <script type="text/javascript" src="lib/datatables/datatables.min.js"></script>
    <script>
        function checkLoginError() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                const error = urlParams.get('error');
                if (error === 'not_logged_in') {
                    alert('Usuario no iniciado');
                }
            }
        }
        window.onload = checkLoginError;
    </script>
</head>
<body>
    <form action="controlador/loginFuncion.php" method="post">
        <label for="user">Usuario:</label>
        <input type="text" id="user" name="user" required><br><br>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required><br><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>