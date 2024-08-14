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

  function check_empty($value) {
    return !empty($value) ? $value : null;
}
  // Función para ejecutar la consulta basada en el valor
  function execute_query($conexion, $form_data) {
      foreach ($form_data as $key => $value) {
          if (!is_null($value)) {
              switch ($key) {
                  case 'nombre_marca':
                      $sql = "INSERT INTO marca(nombre) VALUES('$value')";
                      break;
                  case 'nombre_operadora':
                      $sql = "INSERT INTO operadora(nombre) VALUES('$value')";
                      break;
                  case 'nombre_sucursal':
                      $sql = "INSERT INTO sucursal(nombre) VALUES('$value')";
                      break;
                  case 'nombre_cargo':
                      $sql = "INSERT INTO cargo_ruta(nombre) VALUES('$value')";
                      break;
                  case 'nombre_area':
                      $sql = "INSERT INTO area(nombre) VALUES('$value')";
                      break;
                  case 'nombre_sisver':
                      $sql = "INSERT INTO tlf_sisver(nombre) VALUES('$value')";
                      break;
                  case 'nombre_pcso':
                      $sql = "INSERT INTO pc_sis_op(nombre) VALUES('$value')";
                      break;
                  case 'nombre_almacentipo':
                      $sql = "INSERT INTO tipo_almacenamiento(nombre) VALUES('$value')";
                      break;
                  case 'nombre_fabricante':
                      $sql = "INSERT INTO fabricante(nombre) VALUES('$value')";
                      break;
                  case 'nombre_sisadmin':
                      $sql = "INSERT INTO sistema_admin(nombre) VALUES('$value')";
                      break;
                  case 'nombre_red':
                      $sql = "INSERT INTO red_lan(nombre) VALUES('$value')";
                      break;
                  default:
                      continue 2;
              }
  
              $stmt = $conexion->prepare($sql);
              $stmt->execute();
              if ($stmt) {
                echo 'ok';
            } else {
                echo 'Error: ' . $stmt->errorInfo()[2];
            }
            
              return;
          }
      }
      echo '<script language="javascript">alert("No se proporcionaron datos válidos");</script>';
  }
  
  // Llamar a la función para ejecutar la consulta
  execute_query($conexion, $form_data);
