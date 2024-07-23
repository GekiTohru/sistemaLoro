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


$stmt = $conexion->prepare($sql);
$stmt->execute();

// Iniciar el arreglo para agrupar los usuarios por ID de teléfono
// Iterar sobre los resultados de la consulta
$pc = $stmt->fetchAll(PDO::FETCH_ASSOC); 
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
$pdf->SetTitle('Constancia PC');
$pdf->SetSubject('Constancia');
$pdf->SetKeywords('TCPDF, PDF, PC, loro, constancia');

// Eliminar la cabecera y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); // Set auto page break


// Agregar una página
$pdf->AddPage();

// Logo
$pdf->Image('../img/logo.png', 10, 10, 40, 20, '', '', '', true, 300, '', false, false, 0, false, false, false);

// Fuente
$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(245, 10);
$pdf->Cell(30, 10, date('d/m/Y'), 0, 0, 'R');

// Título
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Ln(20);
$pdf->Cell(0, 10, 'Constancia de entrega de equipo', 0, 1, 'C', 0, '', 1);

// Detalles del dispositivo
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(250, 10, 'Mediante la presente se hace constar la entrega a ' .$row['asignado'].', perteneciente al departamento de '.$row['area'].' del siguiente equipo:', 0, 0);

// Tabla de accesorios y aplicaciones

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(173, 255, 47);
$pdf->SetDrawColor(0, 0, 0);

$pdf->Cell(10, 10, '', 0, 0, 'C', 0);
$pdf->Cell(30, 10, 'MARCA', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'MODELO/SERIAL', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'TIPO', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'RAM', 1, 0, 'C', 1);
$pdf->Cell(20, 10, $row['almacentipo'], 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'ACCESORIOS', 1, 0, 'C', 1);
$pdf->Cell(60, 10, 'PROGRAMAS INSTALADOS', 1, 1, 'C', 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(10, 10, '', 0, 0, 'C', 0);
$pdf->MultiCell(30, 40, $row['fabricante'], 1, 'C',0,0);
$pdf->MultiCell(40, 40, $row['motherboard'].' / '.$row['serial'], 1, 'C',0,0);
$pdf->MultiCell(30, 40, $row['tipo'], 1,'C',0,0);
$pdf->MultiCell(20, 40, $row['ram'], 1, 'C',0,0);
$pdf->MultiCell(20, 40, $row['almacenamiento'], 1, 'C',0,0);


$accesoriosText = '';
foreach ($accesorios as $acc) {
    switch ($acc) {
        case 'Cargador':
            $accesoriosText .= "• Cargador\n";
            break;
        case 'Cable mickey':
            $accesoriosText .= "• Cable AC tipo Mickey\n";
            break;
        case 'Guaya de seguridad':
            $accesoriosText .= "• Guaya de seguridad\n";
            break;
        case 'Mouse':
            $accesoriosText .= "• Mouse\n";
            break;
        case 'Estuche':
            $accesoriosText .= "• Estuche\n";
            break;
        case 'Adaptador red':
            $accesoriosText .= "• Adaptador de red\n";
            break;
        case 'Cubreteclado':
            $accesoriosText .= "• Cubreteclado\n";
            break;
        case 'Funda':
            $accesoriosText .= "• Funda\n";
            break;
    }
}

// Remove trailing newline character
$accesoriosText = rtrim($accesoriosText, "\n");

// Output the accessories text in a multi-cell
$pdf->MultiCell(50, 40, $accesoriosText, 1, 'L', 0, 0);


$applicationsHtml = '<table>';
$counter = 0;
foreach ($apps as $app) {
    // Open a new row for every two items
    if ($counter % 2 == 0) {
        $applicationsHtml .= '<tr>';
    }
    
    switch ($app) {
        case 'AnyDesk':
            $applicationsHtml .= '<td>• AnyDesk</td>';
            break;
        case 'AVG Antivirus':
            $applicationsHtml .= '<td>• AVG Antivirus</td>';
            break;
        case 'Crystal Reports':
            $applicationsHtml .= '<td>• Crystal Reports</td>';
            break;
        case 'Google Chrome':
            $applicationsHtml .= '<td>• Google Chrome</td>';
            break;
        case 'Microsoft Edge':
            $applicationsHtml .= '<td>• Microsoft Edge</td>';
            break;
        case 'Office':
            $applicationsHtml .= '<td>• Office</td>';
            break;
        case 'WinRAR':
            $applicationsHtml .= '<td>• WinRAR</td>';
            break;
        case 'Framework':
            $applicationsHtml .= '<td>• Framework</td>';
            break;
        case 'Sistema ADN':
            $applicationsHtml .= '<td>• Sistema ADN</td>';
            break;
        case 'Adobe Acrobat':
            $applicationsHtml .= '<td>• Adobe Acrobat</td>';
            break;
        case 'INT Nómina':
            $applicationsHtml .= '<td>• INT Nómina</td>';
            break;
        case 'INT Administrativo':
            $applicationsHtml .= '<td>• INT Administrativo</td>';
            break;
    }
    
    // Close the row after every two items
    if ($counter % 2 == 1) {
        $applicationsHtml .= '</tr>';
    }
    
    $counter++;
}

if ($counter % 2 != 0) {
    $applicationsHtml .= '<td></td></tr>';
}

$applicationsHtml .= '</table>';

// Output the HTML formatted applications text in a multi-cell
$pdf->writeHTMLCell(60, 40, '', '', $applicationsHtml, 1, 1, 0, true, 'L', true);




$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 10, 'Términos y condiciones', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$contador = 1;

$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
$pdf->MultiCell(250, 5, 'Queda prohibida la instalación o desinstalación de cualquier programa sin autorización del área de Soporte Técnico.', 0, 'L',0,1);
$pdf->MultiCell(10, 5, $contador++.'.', 0, 'C', 0, 0);
if (stripos($row['cargo'], 'Jefe') === false && stripos($row['cargo'], 'Gerente') === false && stripos($row['cargo'], 'Supervisor') === false) {
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
$pdf->Output('Constancia_'.$row['cargo'].'.pdf');
}