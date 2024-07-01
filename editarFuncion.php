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

// Validar y capturar el ID del elemento a editar
$id = isset($_POST['id']) ? mysqli_real_escape_string($conexion, $_POST['id']) : null;

$form_data = [
    'nombre_marca' => isset($_POST['nombre_marca']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_marca'])) : null,
    'nombre_fabricante' => isset($_POST['nombre_fabricante']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_fabricante'])) : null,
    'nombre_operadora' => isset($_POST['nombre_operadora']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_operadora'])) : null,
    'nombre_sucursal' => isset($_POST['nombre_sucursal']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_sucursal'])) : null,
    'nombre_cargo' => isset($_POST['nombre_cargo']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_cargo'])) : null,
    'nombre_area' => isset($_POST['nombre_area']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_area'])) : null,
    'nombre_sisver' => isset($_POST['nombre_sisver']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_sisver'])) : null,
    'nombre_pcso' => isset($_POST['nombre_pcso']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_pcso'])) : null,
    'nombre_almacentipo' => isset($_POST['nombre_almacentipo']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_almacentipo'])) : null,
];
// Función para verificar si un valor está vacío
function check_empty($value) {
    return !empty($value) ? $value : null;
}

// Función para ejecutar la consulta basada en el valor
function execute_query($conexion, $id, $form_data) {
    if (is_null($id)) {
        echo '<script language="javascript">alert("ID del elemento no proporcionado");</script>';
        return;
    }

    foreach ($form_data as $key => $value) {
        if (!is_null($value)) {
            switch ($key) {
                case 'nombre_marca':
                    $sql = "UPDATE marca SET nombre='$value' WHERE id_marca='$id'";
                    break;
                case 'nombre_fabricante':
                    $sql = "UPDATE fabricante SET nombre='$value' WHERE id_fabricante='$id'";
                    break;
                case 'nombre_operadora':
                    $sql = "UPDATE operadora SET nombre='$value' WHERE id_operadora='$id'";
                    break;
                case 'nombre_sucursal':
                    $sql = "UPDATE sucursal SET nombre='$value' WHERE id_sucursal='$id'";
                    break;
                case 'nombre_cargo':
                    $sql = "UPDATE cargo_ruta SET nombre='$value' WHERE id_cargoruta='$id'";
                    break;
                case 'nombre_area':
                    $sql = "UPDATE area SET nombre='$value' WHERE id_area='$id'";
                    break;
                case 'nombre_sisver':
                    $sql = "UPDATE tlf_sisver SET nombre='$value' WHERE id_sisver='$id'";
                    break;
                case 'nombre_pcso':
                    $sql = "UPDATE pc_sis_op SET nombre='$value' WHERE id_pcso='$id'";
                    break;
                case 'nombre_almacentipo':
                    $sql = "UPDATE tipo_almacenamiento SET nombre='$value' WHERE id_almacentipo='$id'";
                    break;
                default:
                    continue 2;
            }

            $query = mysqli_query($conexion, $sql);
            if ($query) {
                echo '<script language="javascript">alert("Registro editado correctamente"); window.location.href = "indexGeneral.php?tabla='. $_GET["redirect"].'";</script>';
            } else {
                echo '<script language="javascript">alert("Error al editar el registro");</script>';
            }
            return;
        }
    }
    echo '<script language="javascript">alert("No se proporcionaron datos válidos");</script>';
}

// Llamar a la función para ejecutar la consulta
execute_query($conexion, $id, $form_data);
