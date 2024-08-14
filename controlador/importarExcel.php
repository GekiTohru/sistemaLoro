<?php
include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");

$tipo       = $_FILES['dataCliente']['type'];
$tamanio    = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas = file($archivotmp);
array_shift($lineas); 

foreach ($lineas as $linea) {
    $datos = explode(";", $linea);

    if (!empty($datos[0])) {
        $numero = substr_replace($datos[0], '0', 0, 2);
        $numero = substr($numero, 0, 4) . '-' . substr($numero, 4);
        $cuota = number_format(floatval(str_replace(',', '', $datos[1])), 5, '.', '');
        $diferenciaFormato = number_format(floatval(str_replace(',', '', $datos[2])), 5, '.', '');
        $diferenciaTotal = $diferenciaFormato - $cuota;
        $diferencia = number_format(floatval(str_replace(',', '', $diferenciaTotal)), 5, '.', '');
        $fecha               = date('Y-m-d');
        $activo               = 1;

        try {
            $conexionObj = new Cconexion();
            $conexion = $conexionObj->ConexionBD();
        
            $insertarData = "INSERT INTO reporte_digitel( 
                numero,
                cuota,
                diferencia,
                fecha,
                activo
            ) VALUES(
                :numero,
                :cuota,
                :diferencia,
                :fecha,
                :activo
            )";
            $stmt = $conexion->prepare($insertarData);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':cuota', $cuota);
            $stmt->bindParam(':diferencia', $diferencia);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':activo', $activo);
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                header('Location: ../vista/index/idxReporteDigitel.php');
            } else {
                echo "Error al insertar registro";
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        } finally {
            $conexion = null;
        }
    }
}
?>