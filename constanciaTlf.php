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


$sql="SELECT telefonos.*, telefonos.id_telefono AS id, modelo_marca.nombre AS modelo, modelo_marca.ram AS ram, modelo_marca.rom AS rom, marca.nombre AS marca, personal.nombre AS asignado, cargo_ruta.nombre AS cargo, area.nombre AS area
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

    $accesorios = explode(',', $row['accesorios']);
    $apps = explode(',', $row['app_conf']);


$style = '<style>
.marker {
    background-color: yellow;
}
</style>';
$notaContent = preg_replace('/<\/?p>/', '', $row['nota']);

// Contenido HTML con los estilos en línea
$html = $style . '<span><b>Observaciones:</b> ' . $notaContent . '</span>';


class MYPDF extends TCPDF {
    public function Footer() {
        // Set the position at the bottom
        $this->SetY(-15); // Position from the bottom of the page

        // Draw the lines for the signatures
        $this->SetLineWidth(0.5);
        $this->Line(60, $this->GetY(), 105, $this->GetY());
        $this->Line(185, $this->GetY(), 230, $this->GetY());

        // Add the text below the lines
        $this->SetFont('helvetica', '', 10);
        $this->Cell(50, 10, '', 0, 0, 'C');
        $this->Cell(35, 10, 'Nombre y apellido', 0, 0, 'C'); // Replace 'Tu Cargo Aquí' with the actual variable
        $this->Cell(90, 10, '', 0, 0, 'C');
        $this->Cell(35, 10, $_SESSION["nombre"], 0, 1, 'C');
        $this->SetY(-10);
        $this->Cell(175, 10, '', 0, 0, 'C');
        $this->Cell(35, 10, 'Soporte Técnico', 0, 0, 'C');
    }
}

// Crear nuevo documento PDF
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar metadatos del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Francisco 2');
$pdf->SetTitle('Auditoría');
$pdf->SetSubject('Auditoría');
$pdf->SetKeywords('TCPDF, PDF, PC, loro, auditoria');

// Eliminar la cabecera y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); // Set auto page break


// Agregar una página
$pdf->AddPage();

// Logo
$pdf->Image('img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);

// Fuente
$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(250, 10);
$pdf->Cell(30, 10, date('d/m/Y'), 0, 0, 'R');

// Título
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Ln(20);
$pdf->Cell(0, 10, 'Constancia de entrega de equipo', 0, 1, 'C', 0, '', 1);

// Detalles del dispositivo
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(250, 10, 'Mediante la presente se hace constar la entrega a ' .$asignadoA.', perteneciente al departamento de '.$row['area'].' del siguiente equipo:', 0, 0);

// Tabla de accesorios y aplicaciones

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(173, 255, 47);
$pdf->SetDrawColor(0, 0, 0);

$pdf->Cell(20, 10, 'MARCA', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'MODELO', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'SERIAL', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'IMEI1', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'IMEI2', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'RAM', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'ROM', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'ACCESORIOS', 1, 0, 'C', 1);
$pdf->Cell(45, 10, 'APPS INSTALADAS', 1, 1, 'C', 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(20, 40, $row['marca'], 1, 'C',0,0);
$pdf->MultiCell(20, 40, $row['modelo'], 1, 'C',0,0);
$pdf->MultiCell(35, 40, $row['serial'], 1,'C',0,0);
$pdf->MultiCell(35, 40, $row['imei1'], 1,'C',0,0);
$pdf->MultiCell(35, 40, $row['imei2'], 1,'C',0,0);
$pdf->MultiCell(20, 40, $row['ram'], 1, 'C',0,0);
$pdf->MultiCell(20, 40, $row['rom'], 1, 'C',0,0);


$accesoriosText = '';
foreach ($accesorios as $acc) {
    switch ($acc) {
        case 'forro':
            $accesoriosText .= "• Forro\n";
            break;
        case 'vidrio templado':
            $accesoriosText .= "• Vidrio templado\n";
            break;
        case 'cabezal cargador':
            $accesoriosText .= "• Cabezal cargador\n";
            break;
        case 'cable usb':
            $accesoriosText .= "• Cable USB\n";
            break;
        case 'estuche':
            $accesoriosText .= "• estuche\n";
            break;
        case 'adaptador':
            $accesoriosText .= "• Adaptador\n";
            break;
        case 'hidrogel':
            $accesoriosText .= "• Hidrogel\n";
            break;
    }
}

$accesoriosText = rtrim($accesoriosText, "\n");

// Output the accessories text in a multi-cell
$pdf->MultiCell(35, 40, $accesoriosText, 1, 'L', 0, 0);


$applicationsHtml = '';

foreach ($apps as $app) {

    switch ($app) {
        case 'whatsapp':
            $applicationsHtml .= "• WhatsApp\n";
            break;
        case 'gmail':
            $applicationsHtml .= "• Gmail\n";
            break;
        case 'adn':
            $applicationsHtml .= "• ADN\n";
            break;
        case 'facebook':
            $applicationsHtml .= "• Facebook\n";
            break;
        case 'instagram':
            $applicationsHtml .= "• Instagram\n";
            break;
        case 'netflix':
            $applicationsHtml .= "• Netflix\n";
            break;
        case 'youtube':
            $applicationsHtml .= "• YouTube\n";
            break;
        case 'tiktok':
            $applicationsHtml .= "• TikTok";
            break;
    }
}
$applicationsHtml = rtrim($applicationsHtml, "\n");
$pdf->MultiCell(45, 40, $applicationsHtml, 1, 'L', 0, 1);






$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(35, 10, 'Correo corporativo: ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 10, $row['correo_corporativo'], 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(32, 10, 'Línea corporativa:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(20, 10, $row['numero'], 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 10, 'Términos y condiciones', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$contador = 1;

$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'Queda prohibida la instalación o desinstalación de cualquier programa sin autorización del área de Soporte Técnico.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
if (stripos($row['cargo'], 'Jefe') === false && stripos($row['cargo'], 'Gerente') === false && stripos($row['cargo'], 'Supervisor') === false && $row['area']!= 'Ventas') {
    $pdf->MultiCell(250, 5, 'La asignación del equipo se hace para uso exclusivo dentro de las instalaciones de la empresa.', 0, 'L',0,1);
    $pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
    $pdf->MultiCell(250, 5, 'Si se requiere el uso del equipo fuera de las instalaciones, debe notificar a su Supervisor o jefe inmediato.', 0, 'L',0,1);
    $pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
}
$pdf->MultiCell(250, 5, 'El usuario debe revisar el estado del equipo al recibirlo del área de Recursos Humanos, una vez recibido será responsabilidad del usuario.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'En caso de daño, extravío o robo del equipo mientras se encuentra fuera de las instalaciones el usuario debe cubrir los gastos de reparación o reposición del equipo.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'Si el usuario cesa sus operaciones como empleado de la organización deberá entregar formalmente el equipo al área de Recursos Humanos para su revisión.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'En caso de que el equipo presente alguna falla o desperfecto es responsabilidad del usuario cubrir con los gastos de reparación.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'La información almacenada en el dispositivo es propiedad exclusiva de la organización. Queda prohibida su divulgación o publicación a terceros.', 0, 'L',0,1);  
// Escribir contenido HTML en el PDF
$pdf->Ln(5);
$pdf->writeHTML($html, true, false, true, false, '');

// Salida del PDF
$pdf->Output();
}