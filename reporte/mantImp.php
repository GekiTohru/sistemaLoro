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
$id_imp=$_GET['id'];


$sql="SELECT *
FROM mant_imp
WHERE id_impresora = $id_imp AND activo = 1";

$sql2="SELECT impresoras.modelo as nombre, fabricante.nombre as fabricante, area.nombre as area
FROM impresoras
LEFT JOIN area ON impresoras.id_area = area.id_area
LEFT JOIN fabricante ON impresoras.id_fabricante = fabricante.id_fabricante
WHERE id_impresora = $id_imp"; 


$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $conexion->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$row0 = $result2[0];


$contador = 0;
$gasto = 0;


// Crear una instancia de TCPDF
$pdf = new TCPDF();

// Configurar información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Soporte');
$pdf->SetTitle('Ficha PC');
$pdf->SetSubject('Ficha PC');
$pdf->SetKeywords('TCPDF, PDF, pc, ficha');


// No agregar cabecera ni pie de página
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Agregar una página
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(60, 10, '', 0, 0, 'C');
$pdf->Cell(60, 10, 'Registro de mantenimientos', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(20, 10, 'Impresora:', 0, 0, 'L');
$pdf->Cell(20, 10, $row0['fabricante'].' '.$row0['nombre'], 0, 1, 'L');
$pdf->Cell(10, 10, 'Area:', 0, 0, 'L');
$pdf->Cell(20, 10, $row0['area'], 0, 1, 'L');
$pdf->Ln(5);

// Contenido de la tabla
foreach ($result as $row){
  $contador++;
$pdf->SetFillColor(173, 255, 47);
$pdf->Cell(60, 10, '', 0, 0, 'C');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 10, 'Mantenimiento Nro. '.$contador, 0, 1, 'C');
$pdf->Cell(100, 10, 'Descripción', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Proveedor', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Costo', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fecha', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);

$pdf->MultiCell(100, 10, preg_replace('/<\/?p>/', '', $row['descripcion']), 1, 'C', 0, 0);

$pdf->Cell(30, 10, $row['proveedor'], 1, 0, 'C');

$pdf->Cell(20, 10, $row['costo'].'$', 1, 0, 'C');

$pdf->Cell(30, 10, $row['fecha_mant'], 1, 1, 'C');
$pdf->Cell(10, 10, '', 0, 1, 'C');

$gasto = $gasto + $row['costo'];
}

$pdf->SetFillColor(173, 255, 47);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(100, 10, 'TOTAL DE MANTENIMIENTOS REALIZADOS: '.$contador, 1, 1,'C',1);
$pdf->Cell(100, 10, 'GASTOS TOTALES: '.$gasto.'$', 1, 1,'C',1);


$pdf->Output('reporte_mantenimientoImp'.date('d_m_Y').'.pdf');

