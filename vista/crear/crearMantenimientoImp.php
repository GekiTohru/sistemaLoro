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

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear-MantenimientoImp</title>
    <script src="../../package/dist/sweetalert2.all.js"></script>
    <script src="../../package/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="../../css/styles3.css" rel="stylesheet">
    <script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
                }
            }
        </script>

        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            let editorInstance;


            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                })
                .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });

            $(document).ready(function() {
                $('#nuevo').submit(function(event) {
                    event.preventDefault();
                    
                    // Obtener el contenido del editor
                    let content = editorInstance.getData();
                    
                    // Reemplazar hsl por rgb en el contenido
                    content = replaceHslWithRgb(content);

                    // Actualizar el campo de textarea con el contenido modificado
                    editorInstance.setData(content);

                    // Actualizar el textarea oculto antes de enviar el formulario
                    document.querySelector('#editor-hidden').value = content;
                    
                    // Enviar el formulario
                    crear();
            });
        });

        function crear() {
            $.ajax({
                type: 'POST',
                url: '../../controlador/crear/crearMIFuncion.php',
                data: $('#nuevo').serialize(),
                success: function(data) {
            if (data === 'ok') {
                    Swal.fire({
                        icon: "success",
                        title: "Mantenimiento añadido correctamente",
                        showConfirmButton: false,
                        timer: 3000, 
                        allowOutsideClick: true,
                        willClose: () => {
                            window.location.href = '../../vista/index/idxMantenimientosImp.php';
                        }
                    });
                }else {
                Swal.fire({
                    icon: "error",
                    title: "Error al crear el mantenimiento",
                    text: data, // Display the error message returned by the server
                    showConfirmButton: true
                });
            }
        }
    });
}
function hslToRgb(h, s, l) {
s /= 100;
l /= 100;
let c = (1 - Math.abs(2 * l - 1)) * s,
    x = c * (1 - Math.abs((h / 60) % 2 - 1)),
    m = l - c / 2,
    r = 0,
    g = 0,
    b = 0;
if (0 <= h && h < 60) {
    r = c; g = x; b = 0;
} else if (60 <= h && h < 120) {
    r = x; g = c; b = 0;
} else if (120 <= h && h < 180) {
    r = 0; g = c; b = x;
} else if (180 <= h && h < 240) {
    r = 0; g = x; b = c;
} else if (240 <= h && h < 300) {
    r = x; g = 0; b = c;
} else if (300 <= h && h < 360) {
    r = c; g = 0; b = x;
}
r = Math.round((r + m) * 255);
g = Math.round((g + m) * 255);
b = Math.round((b + m) * 255);

return `rgb(${r}, ${g}, ${b})`;
}

function replaceHslWithRgb(content) {
return content.replace(/background-color:hsl\((\d+), (\d+)%, (\d+)%\);/g, function(match, h, s, l) {
    return `background-color:${hslToRgb(h, s, l)};`;
});
}
</script>

</head>
<header>
<nav class="navbar navbar-expand-lg navbar-light bg-success">
<img src="../../img/loro.png" width="30" height="30" alt="">
<a class="navbar-brand text-white" href="../lobby.php">LORO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
    <a class="nav-link text-white" href="../lobby.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../lobbyCrearTlf.php">Añadir</a>
      </li>
      <li class="nav-item">
      <a class="nav-link text-white" href="../lobbyVerTlf.php">Ver y Editar</a>
      </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Gestionar
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../index/indexTelefonos.php">Teléfonos</a>
            <a class="dropdown-item" href="../index/indexPC.php">Computadoras</a>
            <a class="dropdown-item" href="../index/indexImpresoras.php">Impresoras</a>
            <?php if ($_SESSION['permisos'] == 1) {
                      echo'<a class="dropdown-item" href="../index/idxUsuarios.php">Usuarios</a>';
                  }
                  ?>
        </li>
        <li class="nav-item">
      <a class="nav-link text-white" href="../documentacion/doc.html">Documentación</a>
      </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../../controlador/cerrarSesion.php">Salir</a>
        </li>
      </ul>
    </div>
  </nav>
</header>
<body>
    <div class="users-table">
        <h2 style="text-align: center;">Nuevo mantenimiento de impresora</h2>
        <div class="users-form">
            <form id="nuevo">
            <input type="hidden" name="id_imp" value="<?= $_GET['id']?>">
                <div style="display: flex; flex-wrap:wrap">
                <div style="margin: 10px">
                <label for="fecha_mant" style="width: 210px">Fecha del mantenimiento</label>
                <input type="date" name="fecha_mant" id="fecha_mant" style="width: 200px" value="<?= date('Y-m-d') ?>">
                </div>
                <div id="entradas" style="display: flex; flex-wrap: wrap;">
                <div class="inputs">
                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Ingrese el proveedor" value="">
                </div>
                <div class="inputs">
                <label for="costo">Costo</label>
                <input type="text" name="costo" id="costo" placeholder="Ingrese el costo" value="">
                </div>
            </div>
            <div class="notas">
<label for="editor">Descripción</label>
            <textarea type="text" name="descripcion" id="editor" placeholder="" value=""></textarea>
            <input type="hidden" id="editor-hidden" name="descripcion">            
        </div>
        </div>
        
        
                <input type="submit" value="Añadir nuevo mantenimiento">
            </form>
        </div>
        </body>
</html>