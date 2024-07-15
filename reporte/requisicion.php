<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php?error=timeout");
    exit();
}
if (isset($_SESSION["timeout"])) {
    if ($_SESSION["timeout"] < time()) {
        session_destroy();
        header("Location: ../login.php?error=timeout");
        exit();
    }
}
$_SESSION["timeout"] = time() + (30 * 60); // 30 minutos
include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");
$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();
require('../fpdf/fpdf.php');
require_once('../TCPDF/tcpdf.php');

$sql = "SELECT 
  t.id_modelo, 
  ISNULL(cr.nombre, 'Sin cargo') AS cargo, 
  mm.nombre AS modelo, 
  f.nombre AS fabricante,
  CASE WHEN t.vidrio_hidrogel IN ('DAÑADO', 'PARTIDO') THEN 1 ELSE 0 END AS vidrio,
  CASE WHEN t.pantalla IN ('DAÑADO', 'PARTIDO') THEN 1 ELSE 0 END AS pantalla,
  CASE WHEN t.forro IN ('DAÑADO', 'NO TIENE') THEN 1 ELSE 0 END AS forro,
  CASE WHEN t.cargador IN ('DAÑADO', 'NO TIENE') THEN 1 ELSE 0 END AS cargador,
  CASE WHEN t.cable_usb IN ('DAÑADO', 'NO TIENE') THEN 1 ELSE 0 END AS cable_usb,
  CASE WHEN t.adaptador IN ('DAÑADO', 'NO TIENE') THEN 1 ELSE 0 END AS adaptador
FROM 
  telefonos t
INNER JOIN 
  modelo_marca mm ON t.id_modelo = mm.id_modelo
INNER JOIN 
  fabricante f ON mm.id_fabricante = f.id_fabricante
LEFT JOIN 
  tlf_asignado ta ON t.id_telefono = ta.id_telefono
LEFT JOIN 
  personal p ON ta.id_personal = p.id_personal
LEFT JOIN 
  cargo_ruta cr ON p.id_cargoruta = cr.id_cargoruta
WHERE 
  t.vidrio_hidrogel IN ('DAÑADO', 'PARTIDO') OR t.pantalla IN ('DAÑADO', 'PARTIDO') OR
  t.forro IN ('DAÑADO', 'NO TIENE') OR t.cargador IN ('DAÑADO', 'NO TIENE') OR
  t.cable_usb IN ('DAÑADO', 'NO TIENE') OR t.adaptador IN ('DAÑADO', 'NO TIENE')
  AND t.activo = 1
ORDER BY 
  t.id_modelo";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función personalizada para comprobar si se necesita un salto de página
function checkPageBreak($pdf, $cellHeight) {
    // Comprueba si es necesario hacer un salto de página
    if($pdf->GetY() + $cellHeight > $pdf->getPageHeight() - $pdf->getBreakMargin()) {
        $pdf->AddPage();
    }
}

// Crear nuevo documento PDF
if ($stmt->rowCount() > 0) {
    // Inicializar TCPDF
    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->Image('../img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);
    $pdf->Cell(70, 10, '', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(173, 255, 47);

    $current_modelo = '';
    $pdf->Cell(80, 10, '', 0, 0, 'C');
    $pdf->Cell(15, 10, 'REQUISICIÓN DE ACCESORIOS', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);

    $total_vidrio = $total_pantalla = $total_forro = $total_cargador = $total_cable_usb = $total_adaptador = 0;

    foreach ($result as $telefono) {
        // Verificar si cambiamos de modelo
        if ($telefono['modelo'] != $current_modelo) {
            if ($current_modelo != '') {
                // Imprimir totales del modelo anterior
                checkPageBreak($pdf, 10);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(30, 10, 'Totales', 1, 0, 'C', 1);
                $pdf->Cell(15, 10, $total_vidrio, 1, 0, 'C');
                $pdf->Cell(25, 10, $total_pantalla, 1, 0, 'C');
                $pdf->Cell(15, 10, $total_forro, 1, 0, 'C');
                $pdf->Cell(25, 10, $total_cargador, 1, 0, 'C');
                $pdf->Cell(25, 10, $total_cable_usb, 1, 0, 'C');
                $pdf->Cell(25, 10, $total_adaptador, 1, 0, 'C');
                  $pdf->SetFont('helvetica', '', 10);

                $pdf->Ln();

                // Reiniciar los totales para el siguiente modelo
                $total_vidrio = $total_pantalla = $total_forro = $total_cargador = $total_cable_usb = $total_adaptador = 0;
            }
            // Nuevo modelo
            $current_modelo = $telefono['modelo'];
            $total_damaged = 0;
            checkPageBreak($pdf, 20);
            $pdf->Ln(10);
            $pdf->Cell(70, 10, '', 0, 0, 'C');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(40, 10, $telefono['fabricante'].' '.$current_modelo, 0, 1, 'C');
            $pdf->Cell(30, 10, 'CARGO', 1, 0, 'C', 1);
            $pdf->Cell(15, 10, 'VIDRIO', 1, 0, 'C', 1);
            $pdf->Cell(25, 10, 'PANTALLA', 1, 0, 'C', 1);
            $pdf->Cell(15, 10, 'FORRO', 1, 0, 'C', 1);
            $pdf->Cell(25, 10, 'CARGADOR', 1, 0, 'C', 1);
            $pdf->Cell(25, 10, 'CABLE USB', 1, 0, 'C', 1);
            $pdf->Cell(25, 10, 'ADAPTADOR', 1, 1, 'C', 1);
            $pdf->SetFont('helvetica', '', 10);
        }

        checkPageBreak($pdf, 10);
        $pdf->MultiCell(30, 10, $telefono['cargo'], 1, 'C', 0, 0);
        $pdf->Cell(15, 10, $telefono['vidrio'], 1, 0, 'C');
        $pdf->Cell(25, 10, $telefono['pantalla'], 1, 0, 'C');
        $pdf->Cell(15, 10, $telefono['forro'], 1, 0, 'C');
        $pdf->Cell(25, 10, $telefono['cargador'], 1, 0, 'C');
        $pdf->Cell(25, 10, $telefono['cable_usb'], 1, 0, 'C');
        $pdf->Cell(25, 10, $telefono['adaptador'], 1, 1, 'C');

        $total_vidrio += $telefono['vidrio'];
        $total_pantalla += $telefono['pantalla'];
        $total_forro += $telefono['forro'];
        $total_cargador += $telefono['cargador'];
        $total_cable_usb += $telefono['cable_usb'];
        $total_adaptador += $telefono['adaptador'];
        
    }

    // Imprimir el total del último modelo
    checkPageBreak($pdf, 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(30, 10, 'Totales', 1, 0, 'C', 1);
    $pdf->Cell(15, 10, $total_vidrio, 1, 0, 'C');
    $pdf->Cell(25, 10, $total_pantalla, 1, 0, 'C');
    $pdf->Cell(15, 10, $total_forro, 1, 0, 'C');
    $pdf->Cell(25, 10, $total_cargador, 1, 0, 'C');
    $pdf->Cell(25, 10, $total_cable_usb, 1, 0, 'C');
    $pdf->Cell(25, 10, $total_adaptador, 1, 1, 'C');

    // Output PDF
    $pdf->Output('reporte_telefonos.pdf', 'I');
} else {
    echo "No se encontraron resultados.";
}

$conexion->close();
