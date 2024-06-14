<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=not_logged_in");
    exit();
}
include("conexion.php");
require('fpdf/fpdf.php');
$id_telefono=$_GET['id'];


$sql="SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
    FROM telefonos
    INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
    INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
    LEFT JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
    LEFT JOIN personal ON tlf_asignado.id_personal = personal.id_personal
    LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
    LEFT JOIN area ON personal.id_area = area.id_area
    WHERE telefonos.id_telefono = $id_telefono AND tlf_asignado.activo = 1"; 



$query = mysqli_query($conexion, $sql);

$telefonos = [];
while ($row = mysqli_fetch_assoc($query)) {
  $telefonos[] = $row;
}

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
$usuariosPorTelefono = [];
foreach ($telefonos as $row) {
  $idTelefono = $row['id'];
  $nombreUsuario = $row['asignado'];

  if (!isset($usuariosPorTelefono[$idTelefono])) {
    $usuariosPorTelefono[$idTelefono] = [];
  }
  $usuariosPorTelefono[$idTelefono][] = $nombreUsuario;
}
foreach ($usuariosPorTelefono as $idTelefono => $usuarios) {
    $row = current(array_filter($telefonos, function($row) use ($idTelefono) {
      return $row['id'] == $idTelefono;
    }));
    $asignadoA = implode('/', $usuarios);

$apps = explode(',', $row['app_conf']); // explode convierte la cadena en un arreglo

$pdf = new FPDF();
$pdf->AddPage();

// Logo
$pdf->Image('img/logo.png', 10, 10, 30, 20);


$pdf->SetFont('Arial', '', 9);
$pdf->Cell(150);
$pdf->Cell(30, 10, date('d/m/Y'), 0, 0, 'R');
$pdf->SetFont('Arial', 'B', 12);
// Move to the right
$pdf->Cell(80);
// Title
$pdf->Ln(20);
$pdf->Cell(80);
$pdf->Cell(30, 10, iconv('UTF-8', 'ISO-8859-1', 'Revisión Teléfono corporativo'), 0, 0, 'C');
$pdf->Ln(20);

// Arial 10
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 10, 'Modelo:', 0, 0, 'L');
$pdf->Cell(20, 10,$row['marca'].' '.$row['modelo'], 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(15, 10, 'Usuario:', 0, 0, 'L');
$pdf->Cell(20, 10,iconv('UTF-8', 'ISO-8859-1', $asignadoA), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(15, 10, 'Cargo:', 0, 0, 'L');
$pdf->Cell(20, 10,$row['cargo'], 0, 0, 'L');
$pdf->Ln(20);
}
// Table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'Estado de los accesorios', 0, 0, 'L');
$pdf->SetX(100);
$pdf->Cell(20, 10, 'Software y aplicaciones', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetFillColor(173, 255, 47);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.5);
$pdf->Cell(30, 10, 'Accesorios', 1, 0, 'L', true);
$pdf->Cell(50, 10, 'ESTADO', 1, 0, 'C', true);
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'APPS INSTALADAS', 1, 1, 'C', true);
//f (isset($row['forro']) && $row['forro'] != '') {
 // $pdf->Cell(30, 10, 'Forro', 1, 0, 'L');
  //$pdf->Cell(50, 10, $row['forro'], 1, 0, 'C');
//}
// Data
foreach ($apps as $app) {
  switch ($app) {
      case 'whatsapp':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'WHATSAPP', 1, 1, 'C');
          break;
      case 'gmail':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'GMAIL', 1, 1, 'C');
          break;
      case 'adn':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'ADN', 1, 1, 'C');
          break;
      case 'facebook':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'FACEBOOK', 1, 1, 'C');
          break;
      case 'instagram':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'INSTAGRAM', 1, 1, 'C');
          break;
      case 'netflix':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'NETFLIX', 1, 1, 'C');
          break;
      case 'youtube':
          $pdf->SetX(100);
          $pdf->Cell(50, 10, 'YOUTUBE', 1, 1, 'C');
          break;
  }
}

$pdf->SetX(10);
$pdf->SetY(100);
$pdf->Cell(30, 10, 'Forro', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['forro']), 1, 1, 'C');

$pdf->Cell(30, 10, 'Vidrio Templado', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['vidrio']), 1, 1, 'C');

$pdf->Cell(30, 10, 'Pantalla', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['pantalla']), 1, 1, 'C');

$pdf->Cell(30, 10, 'Cargador', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['cargador']), 1, 1, 'C');

$pdf->Cell(30, 10, 'Cable USB', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['cable_usb']), 1, 1, 'C');

$pdf->Cell(30, 10, iconv('UTF-8', 'ISO-8859-1', 'Cámara'), 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['camara']), 1, 1, 'C');

$pdf->Cell(30, 10, 'Adaptador', 1, 0, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $row['adaptador']), 1, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'Configuraciones', 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1', 'Ubicación'), 1, 0, 'C');
if (isset($apps) && in_array('ubicacion', $apps)) {
  $pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Activo'), 1, 1, 'C');
} else {
  $pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Inactivo'), 1, 1, 'C');
}
$pdf->Cell(35, 10, 'Tema por defecto', 1, 0, 'C');
if (isset($apps) && in_array('tema por defecto', $apps)) {
  $pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Sí'), 1, 1, 'C');
}else{
  $pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'No'), 1, 1, 'C');
}
$pdf->Cell(35, 10, 'Consumo de datos', 1, 0, 'C');
$pdf->Cell(20, 10,$row['consumo_datos'], 1, 1, 'C');


// Observations
$pdf->Ln(10);
$pdf->MultiCell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Observaciones:'.' '.$row['nota']), 0, 'L');
$pdf->Ln(20);
$pdf->SetLineWidth(0.5); // Establecer el ancho de la línea en 0.5 puntos
$pdf->Line(10, 265, 45, 265); 
$pdf->SetLineWidth(0.5); // Establecer el ancho de la línea en 0.5 puntos
$pdf->Line(165, 265, 200, 265); 
$pdf->SetY(265);
$pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1', 'Firma'.' '.$row['cargo']), 0, 0, 'C');
$pdf->Cell(120, 10, '', 0, 0, 'C');
$pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1', 'Soporte Técnico'), 0, 0, 'C');


$pdf->Output();