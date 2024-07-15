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
function check_empty($value) {
    if (empty($value) || is_null($value) || trim($value) === '') {
            return "N/A";
    } else {
        return $value;
    }
  }

$id_imp = $_POST['id_imp'];
$ult_mant = $_POST['ult_mant'];
$id_fabricante = $_POST['fabricante'];
$id_area = $_POST['area'];
$toner = isset($_POST['toner']) ? $_POST['toner'] : '';
$modelo = check_empty($_POST['modelo']);
$serial = check_empty($_POST['serial']);
$mac_lan = check_empty($_POST['mac_lan']);
$costo = check_empty($_POST['costo']);
$estado = check_empty($_POST['estado']);
$extra = isset($_POST['extra']) ? $_POST['extra'] : 0;



$sql1 = "UPDATE impresoras SET
  id_fabricante = :id_fabricante,
  id_area = :id_area,
  serial = :serial,
  modelo = :modelo,
  mac_lan = :mac_lan,
  costo = :costo,
  estado = :estado,
  ult_mantenimiento = :ult_mant
WHERE id_impresora = :id_imp";

$stmt = $conexion->prepare($sql1);

// Bind parameters
$stmt->bindParam(':id_fabricante', $id_fabricante);
$stmt->bindParam(':id_area', $id_area);
$stmt->bindParam(':serial', $serial);
$stmt->bindParam(':modelo', $modelo);
$stmt->bindParam(':mac_lan', $mac_lan);
$stmt->bindParam(':costo', $costo);
$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':ult_mant', $ult_mant);
$stmt->bindParam(':id_imp', $id_imp);
$stmt->execute();


    // Consulta para desactivar el teléfono asignado
    $sql3 = "UPDATE toner_asignado SET activo=0 WHERE id_impresora=:id_imp";
    $stmt3 = $conexion->prepare($sql3);
    
    // Consulta para insertar en tlf_asignado
    $sql2 = "INSERT INTO toner_asignado (id_impresora, id_toner) VALUES (:id_imp, :id_toner)";
    $stmt2 = $conexion->prepare($sql2);

    // Consulta para verificar asignación existente
    $sql_check = "SELECT * FROM toner_asignado WHERE id_impresora = :id_imp";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->execute([':id_imp' => $id_imp]);
    $resultados = $stmt_check->fetchAll();
    $num_filas = count($resultados);
    $asignacion_existente = $num_filas > 0;


    $ok = true;

    if ($toner != 'Sin cambios') {
        if (empty($toner)) {
            // Si $toner está vacío, solo ejecuta query3
            $stmt3->execute([':id_imp' => $id_imp]);
            if (!$stmt3) {
                $ok = false;
            }
        } else {
            if ($asignacion_existente && $extra != "on") {
                // Si hay una asignación existente y $extra es diferente de "on"
                $stmt3->execute([':id_imp' => $id_imp]);
                if (!$stmt3) {
                    $ok = false;
                }
                $stmt2->execute([':id_imp' => $id_imp, ':id_toner' => $toner]);
                if (!$stmt2) {
                    $ok = false;
                }
            } else {
                // Si no hay una asignación existente o $extra es igual a "on"
                $stmt2->execute([':id_imp' => $id_imp, ':id_toner' => $toner]);
                if (!$stmt2) {
                    $ok = false;
                }
            }
        }
    }
    


if ($ok) {
    echo '<script language="javascript">alert("Impresora editada correctamente"); window.location.href = "../../vista/index/indexImpresoras.php";</script>';
} else {
    echo '<script language="javascript">alert("Error al editar la impresora");</script>';
}
