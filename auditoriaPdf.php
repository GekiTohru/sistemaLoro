<?php

include("conexion.php");
require('fpdf/fpdf.php');
$id_telefono=$_GET['id'];


$sql = "SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, modelo_marca.ram AS ram, modelo_marca.rom AS rom, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
FROM telefonos
INNER JOIN modelo_marca ON telefonos.id_modelo = modelo_marca.id_modelo
INNER JOIN marca ON modelo_marca.id_marca = marca.id_marca
INNER JOIN tlf_asignado ON telefonos.id_telefono = tlf_asignado.id_telefono
INNER JOIN personal ON tlf_asignado.id_personal = personal.id_personal
INNER JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
INNER JOIN area ON personal.id_area = area.id_area
WHERE telefonos.id_telefono = $id_telefono";

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

$pdf = new FPDF();
$pdf->AddPage();

// Logo
$pdf->Image('img/logo.png', 10, 10, 30, 20);


$pdf->SetFont('Arial', '', 9);
$pdf->Cell(150);
$pdf->Cell(30, 10, '10/6/2024', 0, 0, 'R');
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
$pdf->Cell(20, 10,$asignadoA, 0, 0, 'L');
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
$pdf->Cell(50, 10, 'APPS', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'INSTALADAS', 1, 1, 'C', true);

// Data
$pdf->Cell(30, 10, 'Forro', 1, 0, 'L');
$pdf->Cell(50, 10, $row['forro'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'WHATSAPP', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(30, 10, 'Vidrio Templado', 1, 0, 'L');
$pdf->Cell(50, 10, $row['vidrio'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'GMAIL', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(30, 10, 'Cargador', 1, 0, 'L');
$pdf->Cell(50, 10, $row['cargador'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'FACEBOOK', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(30, 10, 'Cable USB', 1, 0, 'L');
$pdf->Cell(50, 10, $row['cable_usb'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'INSTAGRAM', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(30, 10, iconv('UTF-8', 'ISO-8859-1', 'Cámara'), 1, 0, 'L');
$pdf->Cell(50, 10, $row['camara'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'NETFLIX', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(30, 10, 'Adaptador', 1, 0, 'L');
$pdf->Cell(50, 10, $row['adaptador'], 1, 0, 'C');
$pdf->Cell(10, 10, '', 0);
$pdf->Cell(50, 10, 'YOUTUBE', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Cell(90, 10, '', 0);
$pdf->Cell(50, 10, 'OTRO', 1, 0, 'C');
$pdf->Cell(50, 10, 'Buen estado', 1, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 10, 'Configuraciones', 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(35, 10, iconv('UTF-8', 'ISO-8859-1', 'Ubicación'), 1, 0, 'C');
$pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Sí'), 1, 1, 'C');
$pdf->Cell(35, 10, 'Tema por defecto', 1, 0, 'C');
$pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Sí'), 1, 1, 'C');
$pdf->Cell(35, 10, 'Consumo de datos', 1, 0, 'C');
$pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'Sí'), 1, 1, 'C');


// Observations
$pdf->Ln(10);
$pdf->Cell(20, 10, 'Observaciones:', 0, 0, 'L');
$pdf->Cell(60, 10, 'Accesorios en buen estado.', 0, 0, 'C');
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