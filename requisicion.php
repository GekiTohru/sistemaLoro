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
require('fpdf/fpdf.php');
require_once('TCPDF/tcpdf.php');

$sql = "SELECT telefonos.id_modelo, COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo, modelo_marca.nombre AS modelo, fabricante.nombre AS fabricante,
               IF(vidrio_hidrogel IN ('DAÑADO', 'PARTIDO'), 1, 0) AS vidrio,
               IF(pantalla IN ('DAÑADO', 'PARTIDO'), 1, 0) AS pantalla,
               IF(forro IN ('DAÑADO', 'NO TIENE'), 1, 0) AS forro,
               IF(cargador IN ('DAÑADO', 'NO TIENE'), 1, 0) AS cargador,
               IF(cable_usb IN ('DAÑADO', 'NO TIENE'), 1, 0) AS cable_usb,
               IF(adaptador IN ('DAÑADO', 'NO TIENE'), 1, 0) AS adaptador
        FROM telefonos
INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
INNER JOIN fabricante ON modelo_marca.id_fabricante = fabricante.id_fabricante
LEFT JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
LEFT JOIN personal ON tlf_asignado.id_personal = personal.id_personal
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
        WHERE vidrio_hidrogel IN ('DAÑADO', 'PARTIDO') OR pantalla IN ('DAÑADO', 'PARTIDO') OR
              forro IN ('DAÑADO', 'NO TIENE') OR cargador IN ('DAÑADO', 'NO TIENE') OR
              cable_usb IN ('DAÑADO', 'NO TIENE') OR adaptador IN ('DAÑADO', 'NO TIENE')
        ORDER BY id_modelo";

$result = $conexion->query($sql);

// Función personalizada para comprobar si se necesita un salto de página
function checkPageBreak($pdf, $cellHeight) {
    // Comprueba si es necesario hacer un salto de página
    if($pdf->GetY() + $cellHeight > $pdf->getPageHeight() - $pdf->getBreakMargin()) {
        $pdf->AddPage();
    }
}

// Crear nuevo documento PDF
if ($result->num_rows > 0) {
    // Inicializar TCPDF
    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->Image('img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);
    $pdf->Cell(70, 10, '', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(173, 255, 47);

    $current_modelo = '';
    $pdf->Cell(80, 10, '', 0, 0, 'C');
    $pdf->Cell(15, 10, 'REQUISICIÓN DE ACCESORIOS', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);

    $total_vidrio = $total_pantalla = $total_forro = $total_cargador = $total_cable_usb = $total_adaptador = 0;

    while($telefono = $result->fetch_assoc()) {
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
