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

function check_empty($value, $field) {
    if (empty($value) || is_null($value) || trim($value) === '') {
        if ($field == 'nota') {
            return "Mantiene la configuración inicial.";
        } else {
            return "N/A";
        }
    } else {
        return $value;
    }
  }

$id_telefono = $_POST['id_telefono'];
$fecha_recep = $_POST['fecha_recep'];
$fecha_ult_mant = $_POST['fecha_ult_mant'];
$fecha_ult_rev = $_POST['fecha_ult_rev'];
$modelo = $_POST['modelo'];
$personal = isset($_POST['id_personal']) ? $_POST['id_personal'] : '';
$sisver = $_POST['sisver'];
$operadora = $_POST['operadora'];
$sucursal = $_POST['sucursal'];
$accesorios = $_POST['accesorios'];
$accesorios_string = implode(',', array_map(function($value) {
    return $value;
}, $accesorios));
$apps = $_POST['apps_conf'];
$apps_string = implode(',', array_map(function($value1) {
    return $value1;
}, $apps));
$otra = $_POST['otra_app'];
$imei1 = check_empty($_POST['imei1'], 'imei1');
$imei2 = check_empty($_POST['imei2'], 'imei2');
$imei_adn = check_empty($_POST['imei_adn'], 'imei_adn');
$serial = check_empty($_POST['serial'], 'serial');
$mac_lan = check_empty($_POST['mac_lan'], 'mac_lan');
$mac_wifi = check_empty($_POST['mac_wifi'], 'mac_wifi');
$numero = check_empty($_POST['numero'], 'numero');
$cuenta_google = check_empty($_POST['cuenta_google'], 'cuenta_google');
$clave_google = check_empty($_POST['clave_google'], 'clave_google');
$correo_corporativo = check_empty($_POST['correo_corporativo'], 'correo_corporativo');
$clave_corporativo = check_empty($_POST['clave_corporativo'], 'clave_corporativo');
$anydesk = check_empty($_POST['anydesk'], 'anydesk');
$pin = check_empty($_POST['pin'], 'pin');
$cuenta_mi = check_empty($_POST['cuenta_mi'], 'cuenta_mi');
$clave_mi = check_empty($_POST['clave_mi'], 'clave_mi');
$precio = check_empty($_POST['precio'], 'precio');
$nota = check_empty($_POST['nota'], 'nota');
$almacenamiento = check_empty($_POST['almacenamiento'], 'almacenamiento');
$consumo_datos = $_POST['consumo_datos'];
$vidrio_hidrogel = $_POST['vidrio_hidrogel'];
$forro = $_POST['forro'];
$pantalla = $_POST['pantalla'];
$camara = $_POST['camara'];
$cargador = $_POST['cargador'];
$cable_usb = $_POST['cable_usb'];
$adaptador = $_POST['adaptador'];
$extra = isset($_POST['extra']) ? $_POST['extra'] : 0;


$sql1 = "UPDATE telefonos SET 
        fecha_recep = :fecha_recep, 
        fecha_ult_mant = :fecha_ult_mant, 
        fecha_ult_rev = :fecha_ult_rev, 
        id_modelo = :id_modelo,  
        id_sisver = :id_sisver, 
        id_operadora = :id_operadora, 
        id_sucursal = :id_sucursal, 
        accesorios = :accesorios, 
        app_conf = :app_conf,
        otra_app = :otra_app,
        imei1 = :imei1, 
        imei2 = :imei2, 
        imei_adn = :imei_adn, 
        serial = :serial, 
        mac_lan = :mac_lan, 
        mac_wifi = :mac_wifi, 
        numero = :numero, 
        cuenta_google = :cuenta_google, 
        clave_google = :clave_google, 
        correo_corporativo = :correo_corporativo, 
        clave_corporativo = :clave_corporativo, 
        anydesk = :anydesk, 
        pin = :pin, 
        cuenta_mi = :cuenta_mi, 
        clave_mi = :clave_mi, 
        precio = :precio, 
        nota = :nota, 
        almacenamiento_ocupado = :almacenamiento_ocupado, 
        consumo_datos = :consumo_datos, 
        vidrio_hidrogel = :vidrio_hidrogel, 
        forro = :forro, 
        pantalla = :pantalla, 
        camara = :camara, 
        cargador = :cargador, 
        cable_usb = :cable_usb, 
        adaptador = :adaptador 
    WHERE id_telefono = :id_telefono";

    $stmt1 = $conexion->prepare($sql1);
    $stmt1->execute([
        ':fecha_recep' => $fecha_recep,
        ':fecha_ult_mant' => $fecha_ult_mant,
        ':fecha_ult_rev' => $fecha_ult_rev,
        ':id_modelo' => $modelo,
        ':id_sisver' => $sisver,
        ':id_operadora' => $operadora,
        ':id_sucursal' => $sucursal,
        ':accesorios' => $accesorios_string,
        ':app_conf' => $apps_string,
        ':otra_app' => $otra,
        ':imei1' => $imei1,
        ':imei2' => $imei2,
        ':imei_adn' => $imei_adn,
        ':serial' => $serial,
        ':mac_lan' => $mac_lan,
        ':mac_wifi' => $mac_wifi,
        ':numero' => $numero,
        ':cuenta_google' => $cuenta_google,
        ':clave_google' => $clave_google,
        ':correo_corporativo' => $correo_corporativo,
        ':clave_corporativo' => $clave_corporativo,
        ':anydesk' => $anydesk,
        ':pin' => $pin,
        ':cuenta_mi' => $cuenta_mi,
        ':clave_mi' => $clave_mi,
        ':precio' => $precio,
        ':nota' => $nota,
        ':almacenamiento_ocupado' => $almacenamiento,
        ':consumo_datos' => $consumo_datos,
        ':vidrio_hidrogel' => $vidrio_hidrogel,
        ':forro' => $forro,
        ':pantalla' => $pantalla,
        ':camara' => $camara,
        ':cargador' => $cargador,
        ':cable_usb' => $cable_usb,
        ':adaptador' => $adaptador,
        ':id_telefono' => $id_telefono
    ]);

    // Consulta para desactivar el teléfono asignado
    $sql3 = "UPDATE tlf_asignado SET activo=0 WHERE id_telefono=:id_telefono";
    $stmt3 = $conexion->prepare($sql3);
    
    // Consulta para insertar en tlf_asignado
    $sql2 = "INSERT INTO tlf_asignado (id_telefono, id_personal) VALUES (:id_telefono, :id_personal)";
    $stmt2 = $conexion->prepare($sql2);

    // Consulta para verificar asignación existente
    $sql_check = "SELECT * FROM tlf_asignado WHERE id_telefono = :id_telefono";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->execute([':id_telefono' => $id_telefono]);
$resultados = $stmt_check->fetchAll();
$num_filas = count($resultados);
$asignacion_existente = $num_filas > 0;


    $ok = true;

    if ($personal != 'Sin cambios') {
        if (empty($personal)) {
            // Si $personal está vacío, solo ejecuta query3
            $stmt3->execute([':id_telefono' => $id_telefono]);
            if (!$stmt3) {
                $ok = false;
            }
        } else {
            if ($asignacion_existente && $extra != "on") {
                // Si hay una asignación existente y $extra es diferente de "on"
                $stmt3->execute([':id_telefono' => $id_telefono]);
                if (!$stmt3) {
                    $ok = false;
                }
                $stmt2->execute([':id_telefono' => $id_telefono, ':id_personal' => $personal]);
                if (!$stmt2) {
                    $ok = false;
                }
            } else {
                // Si no hay una asignación existente o $extra es igual a "on"
                $stmt2->execute([':id_telefono' => $id_telefono, ':id_personal' => $personal]);
                if (!$stmt2) {
                    $ok = false;
                }
            }
        }
    }
    

    if ($ok) {
        echo 'ok';
    } else {
        echo 'error';
    }
    