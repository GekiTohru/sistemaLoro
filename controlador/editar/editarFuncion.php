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

// Validar y capturar el ID del elemento a editar
$id = isset($_POST['id'])? (int) $_POST['id'] : null;

$form_data = [
    'nombre_marca' => isset($_POST['nombre_marca']) ? check_empty($_POST['nombre_marca']) : null,
    'nombre_operadora' => isset($_POST['nombre_operadora']) ? check_empty($_POST['nombre_operadora']) : null,
    'nombre_sucursal' => isset($_POST['nombre_sucursal']) ? check_empty($_POST['nombre_sucursal']) : null,
    'nombre_cargo' => isset($_POST['nombre_cargo']) ? check_empty($_POST['nombre_cargo']) : null,
    'nombre_area' => isset($_POST['nombre_area']) ? check_empty($_POST['nombre_area']) : null,
    'nombre_sisver' => isset($_POST['nombre_sisver']) ? check_empty($_POST['nombre_sisver']) : null,
    'nombre_pcso' => isset($_POST['nombre_pcso']) ? check_empty($_POST['nombre_pcso']) : null,
    'nombre_almacentipo' => isset($_POST['nombre_almacentipo']) ? check_empty($_POST['nombre_almacentipo']) : null,
    'nombre_fabricante' => isset($_POST['nombre_fabricante']) ? check_empty($_POST['nombre_fabricante']) : null,
    'nombre_sisadmin' => isset($_POST['nombre_sisadmin']) ? check_empty($_POST['nombre_sisadmin']) : null,
    'nombre_red' => isset($_POST['nombre_red']) ? check_empty($_POST['nombre_red']) : null,
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
                $sql = "UPDATE marca SET nombre = :nombre WHERE id_marca = :id";
                break;
            case 'nombre_operadora':
                $sql = "UPDATE operadora SET nombre = :nombre WHERE id_operadora = :id";
                break;
            case 'nombre_sucursal':
                $sql = "UPDATE sucursal SET nombre = :nombre WHERE id_sucursal = :id";
                break;
            case 'nombre_cargo':
                $sql = "UPDATE cargo_ruta SET nombre = :nombre WHERE id_cargoruta = :id";
                break;
            case 'nombre_area':
                $sql = "UPDATE area SET nombre = :nombre WHERE id_area = :id";
                break;
            case 'nombre_sisver':
                $sql = "UPDATE tlf_sisver SET nombre = :nombre WHERE id_sisver = :id";
                break;
            case 'nombre_pcso':
                $sql = "UPDATE pc_sis_op SET nombre = :nombre WHERE id_pcso = :id";
                break;
            case 'nombre_almacentipo':
                $sql = "UPDATE tipo_almacenamiento SET nombre = :nombre WHERE id_almacentipo = :id";
                break;
            case 'nombre_sisadmin':
                $sql = "UPDATE sistema_admin SET nombre = :nombre WHERE id_sisadmin = :id";
                break;
            case 'nombre_red':
                $sql = "UPDATE red_lan SET nombre = :nombre WHERE id_red = :id";
                break;
            default:
                continue 2;
        }
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $value);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
            if ($stmt) {
                echo '<script language="javascript">alert("Registro editado correctamente"); window.location.href = "../../vista/index/indexGeneral.php?tabla='. $_GET["redirect"].'";</script>';
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
