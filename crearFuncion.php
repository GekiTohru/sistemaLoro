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

  $form_data = [
      'nombre_marca' => isset($_POST['nombre_marca']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_marca'])) : null,
      'nombre_operadora' => isset($_POST['nombre_operadora']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_operadora'])) : null,
      'nombre_sucursal' => isset($_POST['nombre_sucursal']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_sucursal'])) : null,
      'nombre_cargo' => isset($_POST['nombre_cargo']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_cargo'])) : null,
      'nombre_area' => isset($_POST['nombre_area']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_area'])) : null,
      'nombre_sisver' => isset($_POST['nombre_sisver']) ? check_empty(mysqli_real_escape_string($conexion, $_POST['nombre_sisver'])) : null,
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
                  default:
                      continue 2;
              }
  
              $query = mysqli_query($conexion, $sql);
              if ($query) {
                  echo '<script language="javascript">alert("Registro añadido correctamente"); window.location.href = "indexTelefonos.php";</script>';
              } else {
                  echo '<script language="javascript">alert("Error al añadir el registro");</script>';
              }
              return;
          }
      }
      echo '<script language="javascript">alert("No se proporcionaron datos válidos");</script>';
  }
  
  // Llamar a la función para ejecutar la consulta
  execute_query($conexion, $form_data);
