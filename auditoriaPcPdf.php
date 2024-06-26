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

$id_pc=$_GET['id'];


$sql="SELECT computadoras.*, computadoras.id_pc AS id, fabricante.nombre AS fabricante, tipo_equipo.nombre AS tipo, tipo_almacenamiento.nombre AS almacentipo, red_lan.nombre AS red, COALESCE(personal.nombre, 'Sin asignar') AS asignado, sistema_admin.nombre AS sisadmin, COALESCE(cargo_ruta.nombre, 'Sin cargo') AS cargo, COALESCE(area.nombre, 'Sin área') AS area, COALESCE(sucursal.nombre, 'Sin sucursal') AS sucursal, pc_sis_op.nombre AS s_o
FROM computadoras
INNER JOIN fabricante ON computadoras.id_fabricante = fabricante.id_fabricante
INNER JOIN tipo_almacenamiento ON computadoras.id_almacentipo = tipo_almacenamiento.id_almacentipo
INNER JOIN red_lan ON computadoras.id_red = red_lan.id_red
INNER JOIN tipo_equipo ON computadoras.id_tipo_equipo = tipo_equipo.id_tipo_equipo
INNER JOIN pc_sis_op ON computadoras.id_pcso= pc_sis_op.id_pcso
LEFT JOIN sistema_admin ON computadoras.id_sisadmin= sistema_admin.id_sisadmin
LEFT JOIN personal ON computadoras.id_personal = personal.id_personal
LEFT JOIN cargo_ruta ON personal.id_cargoruta = cargo_ruta.id_cargoruta
LEFT JOIN area ON personal.id_area = area.id_area
LEFT JOIN sucursal ON computadoras.id_sucursal = sucursal.id_sucursal
WHERE computadoras.id_pc = $id_pc"; 



$query = mysqli_query($conexion, $sql);

$pc = [];
while ($row = mysqli_fetch_assoc($query)) {
  $pc[] = $row;
}
$usuariosPorPC = [];
foreach ($pc as $row) {
  $idTelefono = $row['id'];
  $nombreUsuario = $row['asignado'];

  if (!isset($usuariosPorPC[$idTelefono])) {
    $usuariosPorPC[$idTelefono] = [];
  }
  $usuariosPorPC[$idTelefono][] = $nombreUsuario;
}
foreach ($usuariosPorPC as $idPC => $usuarios) {
  $row = current(array_filter($pc, function($row) use ($idPC) {
    return $row['id'] == $idPC;
  }));
  

  $apps = explode(',', $row['programas']);
  $accesorios = explode(',', $row['accesorios']); 


$style = '<style>
.marker {
    background-color: yellow;
}
</style>';
$notaContent = preg_replace('/<\/?p>/', '', $row['nota']);

// Contenido HTML con los estilos en línea
$html = $style . '<span><b>Observaciones:</b> ' . $notaContent . '</span>';



// Crear nuevo documento PDF
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar metadatos del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Francisco 2');
$pdf->SetTitle('Auditoría PC');
$pdf->SetSubject('Auditoría PC');
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
$pdf->Cell(0, 10, 'Revisión Equipo corporativo', 0, 1, 'C', 0, '', 1);

// Detalles del dispositivo
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(15, 10, 'Tipo:', 0, 0, 'L');
$pdf->Cell(20, 10, $row['tipo'], 0, 1, 'L');
$pdf->Cell(15, 10, 'Modelo:', 0, 0, 'L');
$pdf->Cell(20, 10, $row['fabricante'].' '.$row['motherboard'], 0, 1, 'L');
$pdf->Cell(15, 10, 'Usuario:', 0, 0, 'L');
$pdf->Cell(20, 10, $row['asignado'], 0, 1, 'L');
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
$pdf->Cell(50, 10, 'SOFTWARE INSTALADO', 1, 1, 'C', 1);
$pdf->Cell(90, 10, '', 0, 0, 'C');


/*foreach ($apps as $app) {
    $pdf->SetX(100);
    switch ($app) {
        case 'AnyDesk':
            $pdf->Cell(50, 10, 'AnyDesk', 1, 1, 'C');
            break;
        case 'AVG Antivirus':
            $pdf->Cell(50, 10, 'AVG Antivirus', 1, 1, 'C');
            break;
        case 'Crystal Reports':
            $pdf->Cell(50, 10, 'Crystal Reports', 1, 1, 'C');
            break;
        case 'Google Chrome':
            $pdf->Cell(50, 10, 'Google Chrome', 1, 1, 'C');
            break;
        case 'Microsoft Edge':
            $pdf->Cell(50, 10, 'Microsoft Edge', 1, 1, 'C');
            break;
        case 'Office':
            $pdf->Cell(50, 10, 'Office', 1, 1, 'C');
            break;
        case 'WinRAR':
            $pdf->Cell(50, 10, 'WinRAR', 1, 1, 'C');
            break;
        case 'Framework':
            $pdf->Cell(50, 10, 'Framework', 1, 1, 'C');
            break;
        case 'Adobe Acrobat':
            $pdf->Cell(50, 10, 'Adobe Acrobat', 1, 1, 'C');
            break;
        case 'Sistema ADN':
            $pdf->Cell(50, 10, 'Sistema ADN', 1, 1, 'C');
            break;
        case 'INT Nómina':
            $pdf->Cell(50, 10, 'INT Nómina', 1, 1, 'C');
            break;
        case 'INT Administrativo':
            $pdf->Cell(50, 10, 'INT Administrativo', 1, 1, 'C');
            break;
        case 'WhatsApp':
            $pdf->Cell(50, 10, 'WhatsApp', 1, 1, 'C');
            break;
    }
}*/
$applicationsHtml = '';
foreach ($apps as $app) {

    switch ($app) {
        case 'AnyDesk':
            $applicationsHtml .= "• AnyDesk\n";
            break;
        case 'AVG Antivirus':
            $applicationsHtml .= "• AVG Antivirus\n";
            break;
        case 'Crystal Reports':
            $applicationsHtml .= "• Crystal Reports\n";
            break;
        case 'Google Chrome':
            $applicationsHtml .= "• Google Chrome\n";
            break;
        case 'Microsoft Edge':
            $applicationsHtml .= "• Microsoft Edge\n";
            break;
        case 'Office':
            $applicationsHtml .= "• Office\n";
            break;
        case 'WinRAR':
            $applicationsHtml .= "• WinRAR\n";
            break;
        case 'Framework':
            $applicationsHtml .= "• Framework\n";
            break;
        case 'Adobe Acrobat':
            $applicationsHtml .= "• Adobe Acrobat\n";
            break;
        case 'Sistema ADN':
            $applicationsHtml .= "• Sistema ADN\n";
            break;
        case 'INT Nómina':
            $applicationsHtml .= "• INT Nómina\n";
            break;
        case 'INT Administrativo':
            $applicationsHtml .= "• INT Administrativo\n";
            break;
        case 'WhatsApp':
            $applicationsHtml .= "• WhatsApp\n";
            break;
        }

}
$applicationsHtml = rtrim($applicationsHtml, "\n");
$pdf->MultiCell(50, 40, $applicationsHtml, 1, 'L', 0, 1);


// Detalles de los accesorios
$pdf->SetXY(10, 110);
$accessories = [
    'Mouse'=> $row['mouse'],
    'Pantalla/Monitor' => $row['pantalla_monitor'],
    'Teclado' => $row['estado_teclado'],
    'Cargador' => $row['cargador'],
    'Cable Mickey' => $row['cable_mickey'],
    'Cámara' => $row['camara'],
    'UPS' => $row['estado_ups']
];

foreach ($accessories as $name => $value) {
    if($value != 'NO USA' && $value != 'NO TIENE' && $value != '') {
        $pdf->Cell(30, 10, $name, 1, 0, 'L');
        $pdf->Cell(50, 10, $value, 1, 1, 'C');
    }
}


// Observaciones
$pdf->SetY(190);   
// Escribir contenido HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');
// Firmas

$pdf->SetLineWidth(0.5);
$pdf->Line(20, 265, 55, 265);
$pdf->Line(145, 265, 190, 265);
$pdf->SetY(265);
$pdf->Cell(10, 10, '', 0, 0, 'C');
$pdf->Cell(35, 10, 'Firma ' . $row['cargo'], 0, 0, 'C');
$pdf->Cell(95, 10, '', 0, 0, 'C');
$pdf->Cell(35, 10, 'Soporte Técnico', 0, 0, 'C');

// Salida del PDF
$pdf->Output();
}