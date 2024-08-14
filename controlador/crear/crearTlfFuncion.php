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

  $fecha_recep = $_POST['fecha_recep'];
  $fecha_ult_mant = $_POST['fecha_ult_mant'];
  $fecha_ult_rev = $_POST['fecha_ult_rev'];
  $modelo = $_POST['modelo'];
  $personal = $_POST['id_personal'];
  $sisver = $_POST['sisver'];
  $operadora = $_POST['operadora'];
  $sucursal = $_POST['sucursal'];
  $plan = $_POST['plan'];
  if (isset($_POST['accesorios']) && is_array($_POST['accesorios'])) {
      $accesorios = $_POST['accesorios'];
      $accesorios_string = implode(',', array_map(function($value) {
          return $value;
      }, $accesorios));
  } else {
      $accesorios_string = 'sin accesorios'; // o cualquier otro valor predeterminado
  }
  if (isset($_POST['apps_conf']) && is_array($_POST['apps_conf'])) {
    $apps = $_POST['apps_conf'];
    $apps_string = implode(',', array_map(function($value1) {
        return $value1;
    }, $apps));
} else {
    $apps_string = 'sin aplicaciones'; // o cualquier otro valor predeterminado
}
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



  $sql1 = "INSERT INTO telefonos (fecha_recep, fecha_ult_mant, fecha_ult_rev, id_modelo, id_sisver, id_operadora, id_sucursal, id_plan, accesorios, app_conf, otra_app, imei1, imei2, imei_adn, serial, mac_lan, mac_wifi, numero, cuenta_google, clave_google, correo_corporativo, clave_corporativo, anydesk, pin, cuenta_mi, clave_mi, precio, nota, almacenamiento_ocupado, consumo_datos, vidrio_hidrogel, forro, pantalla, camara, cargador, cable_usb, adaptador)
  VALUES (:fecha_recep, :fecha_ult_mant, :fecha_ult_rev, :modelo, :sisver, :operadora, :sucursal, :plan, :accesorios_string, :apps_string, :otra_app, :imei1, :imei2, :imei_adn, :serial, :mac_lan, :mac_wifi, :numero, :cuenta_google, :clave_google, :correo_corporativo, :clave_corporativo, :anydesk, :pin, :cuenta_mi, :clave_mi, :precio, :nota, :almacenamiento, :consumo_datos, :vidrio_hidrogel, :forro, :pantalla, :camara, :cargador, :cable_usb, :adaptador)";

$stmt = $conexion->prepare($sql1);
$stmt->bindParam(':fecha_recep', $fecha_recep);
$stmt->bindParam(':fecha_ult_mant', $fecha_ult_mant);
$stmt->bindParam(':fecha_ult_rev', $fecha_ult_rev);
$stmt->bindParam(':modelo', $modelo);
$stmt->bindParam(':sisver', $sisver);
$stmt->bindParam(':operadora', $operadora);
$stmt->bindParam(':sucursal', $sucursal);
$stmt->bindParam(':plan', $plan);
$stmt->bindParam(':accesorios_string', $accesorios_string);
$stmt->bindParam(':apps_string', $apps_string);
$stmt->bindParam(':otra_app', $otra);
$stmt->bindParam(':imei1', $imei1);
$stmt->bindParam(':imei2', $imei2);
$stmt->bindParam(':imei_adn', $imei_adn);
$stmt->bindParam(':serial', $serial);
$stmt->bindParam(':mac_lan', $mac_lan);
$stmt->bindParam(':mac_wifi', $mac_wifi);
$stmt->bindParam(':numero', $numero);
$stmt->bindParam(':cuenta_google', $cuenta_google);
$stmt->bindParam(':clave_google', $clave_google);
$stmt->bindParam(':correo_corporativo', $correo_corporativo);
$stmt->bindParam(':clave_corporativo', $clave_corporativo);
$stmt->bindParam(':anydesk', $anydesk);
$stmt->bindParam(':pin', $pin);
$stmt->bindParam(':cuenta_mi', $cuenta_mi);
$stmt->bindParam(':clave_mi', $clave_mi);
$stmt->bindParam(':precio', $precio);
$stmt->bindParam(':nota', $nota);
$stmt->bindParam(':almacenamiento', $almacenamiento);
$stmt->bindParam(':consumo_datos', $consumo_datos);
$stmt->bindParam(':vidrio_hidrogel', $vidrio_hidrogel);
$stmt->bindParam(':forro', $forro);
$stmt->bindParam(':pantalla', $pantalla);
$stmt->bindParam(':camara', $camara);
$stmt->bindParam(':cargador', $cargador);
$stmt->bindParam(':cable_usb', $cable_usb);
$stmt->bindParam(':adaptador', $adaptador);

$stmt->execute();

$id_telefono_nuevo = $conexion->lastInsertId();

if ($personal != '') {
$sql2 = "INSERT INTO tlf_asignado (id_telefono, id_personal) VALUES (:id_telefono, :personal)";
$stmt2 = $conexion->prepare($sql2);
$stmt2->bindParam(':id_telefono', $id_telefono_nuevo);
$stmt2->bindParam(':personal', $personal);
$stmt2->execute();
}

if ($stmt->rowCount() > 0) {
    echo 'ok';
} else {
    echo 'Error: ' . $stmt->errorInfo()[2];
}