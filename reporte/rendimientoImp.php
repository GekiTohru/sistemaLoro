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


$sql = "SELECT cambio_toner.id_cambiotoner AS id, cambio_toner.fecha AS fecha, cambio_toner.contador AS contador, cambio_toner.costo AS costo, toner.modelo AS toner, toner.color AS color 
FROM cambio_toner
INNER JOIN toner ON cambio_toner.id_toner = toner.id_toner
WHERE cambio_toner.id_impresora = $id_imp AND cambio_toner.activo = 1
ORDER BY fecha ASC";


$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;



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
$pdf->Cell(60, 10, 'HISTORIAL CAMBIOS DE TÓNER', 0, 1, 'C');
$pdf->Ln(5);

function calcularDias($fecha1, $fecha2) {
    $date1 = new DateTime($fecha1);
    $date2 = new DateTime($fecha2);
    $interval = $date1->diff($date2);
    return $interval->days;
}

$tonersPorColor = array(
    'Negro' => array(),
    'Amarillo' => array(),
    'Cian' => array(),
    'Magenta' => array()
);

// Agrupar registros por color
foreach ($result as $row) {
    $tonersPorColor[$row['color']][] = $row;
}

$count = 0; // inicializar contador de cambios
$total_rendimiento = 0; // variable para almacenar la suma total de los rendimientos
$total_cambios = 0; // variable para contar el número total de cambios

foreach ($tonersPorColor as $color => $toners) {
    for ($i = 0; $i < count($toners) - 1; $i++) {
        $row1 = $toners[$i];
        $row2 = $toners[$i + 1];

        $dias = calcularDias($row1['fecha'], $row2['fecha']);
        $rendimiento = $row2['contador'] - $row1['contador'];
        $costopag = $row2['costo'] / $rendimiento;

        $total_rendimiento += $rendimiento; // Sumar al total de rendimientos
        $total_cambios++;

        // Calcular el promedio de rendimientos hasta este punto
        $promedio_rendimiento = $total_rendimiento / $total_cambios;

        // Agregar encabezado antes de imprimir cada par de registros
        $count++;
        $pdf->SetFillColor(173, 255, 47);
        $pdf->Cell(60, 10, '', 0, 0, 'C');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 10, 'Cambio Nro. ' . $count, 0, 1, 'C');
        $pdf->Cell(30, 10, 'Toner', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Días', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Contador', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Rendimiento', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Promedio', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Costo/pág', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 10);

        // Imprimir el primer registro del par
        $pdf->Cell(30, 10, $row1['toner'] . ' ' . $row1['color'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row1['fecha'], 1, 0, 'C');
        $pdf->Cell(30, 20, $dias, 1, 0, 'C');
        $pdf->Cell(20, 10, $row1['contador'], 1, 0, 'C');
        $pdf->Cell(30, 20, $rendimiento, 1, 0, 'C');
        $pdf->Cell(20, 20, number_format($promedio_rendimiento, 2, '.', ''), 1, 0, 'C');
        $pdf->Cell(30, 20, number_format($costopag, 3, '.', '').'$', 1, 1, 'C');

        // Imprimir el segundo registro del par
        $posY = $pdf->GetY();
        $pdf->SetY($posY-10);
        $pdf->Cell(30, 10, $row2['toner'] . ' ' . $row2['color'], 1, 0, 'C');
        $pdf->SetX(40); // mover el cursor a la posición x de la celda de fecha
        $pdf->Cell(30, 10, $row2['fecha'], 1, 0, 'C'); // celda de fecha
        $pdf->SetX(100); // mover el cursor a la posición x de la celda de contador
        $pdf->Cell(20, 10, $row2['contador'], 1, 0, 'C'); // celda de contador
        $pdf->Ln(20);
    }

    // En caso de que haya un número impar de registros, imprimir el último
    if (count($toners) % 2 != 0) {
        $last_row = $toners[count($toners) - 1];
        $toner_key = $last_row['toner'] . ' ' . $last_row['color'];

        // Calcular el promedio de rendimientos hasta este punto
        $total_cambios++;
        $promedio_rendimiento = $total_rendimiento / $total_cambios;

        // Agregar encabezado antes de imprimir el último registro
        $count++;
        $pdf->SetFillColor(173, 255, 47);
        $pdf->Cell(60, 10, '', 0, 0, 'C');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 10, 'Cambio Nro. ' . $count, 0, 1, 'C');
        $pdf->Cell(30, 10, 'Toner', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Días', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Contador', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Rendimiento', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Promedio', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Costo/pág', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 10);

        $pdf->Cell(30, 20, $toner_key, 1, 0, 'C');
        $pdf->Cell(30, 10, $last_row['fecha'], 1, 0, 'C');
        $pdf->Cell(30, 20, 'N/A', 1, 0, 'C');
        $pdf->Cell(20, 10, $last_row['contador'], 1, 0, 'C');
        $pdf->Cell(30, 20, 'N/A', 1, 0, 'C');
        $pdf->Cell(20, 20, 'N/A', 1, 0, 'C');
        $pdf->Cell(30, 20, 'N/A', 1, 1, 'C');
        $pdf->Ln(10);
    }
}



$pdf->SetFillColor(173, 255, 47);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(100, 10, 'TOTAL DE CAMBIOS REALIZADOS: '.$count, 1, 1,'C',1);

$pdf->Output();