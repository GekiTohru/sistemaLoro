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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'])) {
    $data = json_decode($_POST['data'], true);
    $desde = isset($_POST['desde']) ? $_POST['desde'] : '';
    $hasta = isset($_POST['hasta']) ? $_POST['hasta'] : '';
    $contador = count($data);

    if (!empty($desde)) {
        $desde = DateTime::createFromFormat('Y-m-d', $desde)->format('d-m-Y');
    }
    if (!empty($hasta)) {
        $hasta = DateTime::createFromFormat('Y-m-d', $hasta)->format('d-m-Y');
    }
    function checkPageBreak($pdf, $cellHeight) {
        // Comprueba si es necesario hacer un salto de página
        if($pdf->GetY() + $cellHeight > $pdf->getPageHeight() - $pdf->getBreakMargin()) {
            $pdf->AddPage();
        }
    }
    
    // Crea un nuevo documento PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Soporte');
    $pdf->SetTitle('Mantenimientos PC');
    $pdf->SetSubject('Mantenimientos');
    $pdf->SetKeywords('TCPDF, PDF, pc, mantenimientos');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->Image('../img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);
    $pdf->Cell(70, 10, '', 0, 1);

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(70, 10, '', 0, 0);
    $pdf->Cell(10, 10, 'REGISTRO DE MANTENIMIENTOS', 0, 1);
    $pdf->Ln();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Títulos de las columnas
    $pdf->SetFillColor(173, 255, 47);
    checkPageBreak($pdf, 10);
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Fabricante', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Modelo', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Tipo', 1, 0, 'C', 1);
    $pdf->Cell(40, 10, 'Asignado', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Fecha mant.', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Realizado por', 1, 0, 'C', 1);
    $pdf->Ln();
    $pdf->SetFont('helvetica', '', 10);

    foreach ($data as $row) {
        checkPageBreak($pdf, 10);
        $fecha_mantenimiento = DateTime::createFromFormat('Y-m-d', $row[5]);
        $fecha_formateada = $fecha_mantenimiento->format('d-m-Y');
        $pdf->Cell(10, 10, $row[0], 1,0,'C');
        $pdf->Cell(25, 10, $row[1], 1,0,'C');
        $pdf->MultiCell(30, 10, $row[2], 1,'C',0,0);
        $pdf->Cell(30, 10, $row[3], 1,0,'C');
        $pdf->MultiCell(40, 10, $row[4], 1,'C',0,0);
        $pdf->Cell(25, 10, $fecha_formateada, 1,0,'C');
        $pdf->Cell(30, 10, $row[6], 1,0,'C');
        $pdf->Ln();
    }
    $filtro_fechas = '';
    if (empty($desde) && empty($hasta)) {
        $filtro_fechas = 'desde: El principio hasta: Hoy';
    } elseif (empty($desde)) {
        $filtro_fechas = 'desde: El principio hasta: '.$hasta;
    } elseif (empty($hasta)) {
        $filtro_fechas = 'desde: '.$desde.' hasta: Hoy';
    } else {
        $filtro_fechas = 'desde: '.$desde.' hasta: '.$hasta;
    }
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(95, 10, 'TOTAL DE MANTENIMIENTOS REALIZADOS: '.$contador, 1, 1,'C',1);
    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetX(170);
    $pdf->MultiCell(25, 10, $filtro_fechas, 0, 'L');

    
    $pdf->Output('reporte_mantenimientosPC'.date('d_m_Y').'.pdf');

} else {
    echo 'Invalid request';
}
