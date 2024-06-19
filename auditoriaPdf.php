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

$style = '<style>
.marker {
    background-color: yellow;
}
</style>';
$notaContent = preg_replace('/<\/?p>/', '', $row['nota']);

// Contenido HTML con los estilos en línea
$html = $style . '<span>Observaciones: ' . $notaContent . '</span>';


// Crear nuevo documento PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar metadatos del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Francisco 2');
$pdf->SetTitle('Auditoría');
$pdf->SetSubject('Auditoría');
$pdf->SetKeywords('TCPDF, PDF, PC, loro, auditoria');

// Eliminar la cabecera y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Agregar una página
$pdf->AddPage();

// Logo
$pdf->Image('img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);

// Fuente
$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(150, 10);
$pdf->Cell(30, 10, date('d/m/Y'), 0, 0, 'R');

// Título
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Ln(20);
$pdf->Cell(0, 10, 'Revisión Teléfono corporativo', 0, 1, 'C', 0, '', 1);

// Detalles del dispositivo
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(15, 10, 'Modelo:', 0, 0, 'L');
$pdf->Cell(20, 10, $row['marca'].' '.$row['modelo'], 0, 1, 'L');
$pdf->Cell(15, 10, 'Usuario:', 0, 0, 'L');
$pdf->Cell(20, 10, $asignadoA, 0, 1, 'L');
$pdf->Cell(15, 10, 'Cargo:', 0, 0, 'L');
$pdf->Cell(20, 10, $row['cargo'], 0, 1, 'L');
$pdf->Ln(10);

// Tabla de accesorios y aplicaciones
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 10, 'Estado de los accesorios', 0, 0, 'L');
$pdf->SetX(100);
$pdf->Cell(20, 10, 'Software y aplicaciones', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(173, 255, 47);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.5);
$pdf->Cell(30, 10, 'Accesorios', 1, 0, 'L', 1);
$pdf->Cell(50, 10, 'ESTADO', 1, 0, 'C', 1);
$pdf->Cell(10, 10, '', 0, 0, 'C');
$pdf->Cell(50, 10, 'APPS INSTALADAS', 1, 1, 'C', 1);

foreach ($apps as $app) {
    $pdf->SetX(100);
    switch ($app) {
        case 'whatsapp':
            $pdf->Cell(50, 10, 'WHATSAPP', 1, 1, 'C');
            break;
        case 'gmail':
            $pdf->Cell(50, 10, 'GMAIL', 1, 1, 'C');
            break;
        case 'adn':
            $pdf->Cell(50, 10, 'ADN', 1, 1, 'C');
            break;
        case 'facebook':
            $pdf->Cell(50, 10, 'FACEBOOK', 1, 1, 'C');
            break;
        case 'instagram':
            $pdf->Cell(50, 10, 'INSTAGRAM', 1, 1, 'C');
            break;
        case 'netflix':
            $pdf->Cell(50, 10, 'NETFLIX', 1, 1, 'C');
            break;
        case 'youtube':
            $pdf->Cell(50, 10, 'YOUTUBE', 1, 1, 'C');
            break;
        case 'tiktok':
            $pdf->Cell(50, 10, 'TIKTOK', 1, 1, 'C');
            break;
        case 'otra':
            $pdf->Cell(50, 10, 'OTRA', 1, 1, 'C');
            break;
    }
}
if (!empty($row['otra_app'])) {
    // Celda para "Aplicaciones adicionales"
    $pdf->setX(100);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 10, 'Aplicaciones adicionales:', 0, 1); // 0 indica ancho automático
    $pdf->SetFont('helvetica', '', 10);
    
    // Celda para mostrar el valor de $row['otra_app']
    $pdf->setX(100);
    $pdf->Cell(0, 10, $row['otra_app'], 0, 1);
}

// Detalles de los accesorios
$pdf->SetXY(10, 100);
$accessories = [
    'Forro' => $row['forro'],
    'Vidrio Templado' => $row['vidrio'],
    'Pantalla' => $row['pantalla'],
    'Cargador' => $row['cargador'],
    'Cable USB' => $row['cable_usb'],
    'Cámara' => $row['camara'],
    'Adaptador' => $row['adaptador']
];

foreach ($accessories as $name => $value) {
    $pdf->Cell(30, 10, $name, 1, 0, 'L');
    $pdf->Cell(50, 10, $value, 1, 1, 'C');
}

// Configuraciones
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 10, 'Configuraciones', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(35, 10, 'Ubicación', 1, 0, 'C');
$pdf->Cell(20, 10, in_array('ubicacion', $apps) ? 'Activo' : 'Inactivo', 1, 1, 'C');
$pdf->Cell(35, 10, 'Tema por defecto', 1, 0, 'C');
$pdf->Cell(20, 10, in_array('tema por defecto', $apps) ? 'Sí' : 'No', 1, 1, 'C');
$pdf->Cell(35, 10, 'Consumo de datos', 1, 0, 'C');
$pdf->Cell(20, 10, $row['consumo_datos'], 1, 1, 'C');

// Observaciones
$pdf->Ln(10);   
// Escribir contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');
// Firmas
$pdf->Ln(20);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 265, 45, 265);
$pdf->Line(165, 265, 200, 265);
$pdf->SetY(265);
$pdf->Cell(35, 10, 'Firma ' . $row['cargo'], 0, 0, 'C');
$pdf->Cell(120, 10, '', 0, 0, 'C');
$pdf->Cell(35, 10, 'Soporte Técnico', 0, 0, 'C');

// Salida del PDF
$pdf->Output();
}