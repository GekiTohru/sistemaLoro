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
  $fila = current(array_filter($pc, function($row) use ($idPC) {
    return $row['id'] == $idPC;
  }));

// Crear una instancia de TCPDF
$pdf = new TCPDF('P', 'mm', array(100, 220), true, 'UTF-8', false);

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
$pdf->SetAlpha(0.5);
$pdf->Image('../img/loro.png', 25, 60, 50, 50, 'png', 'C', '', false, 300, '', false, false, 0);
// Configurar fuentes
$pdf->SetAlpha(1);
$pdf->SetFont('helvetica', '', 9);

$pdf->setXY(20,30);

// Contenido de la tabla
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(173, 255, 47);
$pdf->Cell(60, 10, 'Ficha técnica', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);

$pdf->setX(20);

$pdf->Cell(60, 10, 'Área: '.$row['area'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Cargo: '.$row['cargo'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Fabricante: '.$row['fabricante'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Sistema operativo: '.$row['s_o'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'RAM: '.$row['ram'], 1, 1, 'L');

$pdf->setX(20);

$pdf->MultiCell(60, 10, 'Placa madre: '.$row['motherboard'], 1, 'V');

$pdf->setX(20);

$pdf->MultiCell(60, 10, 'Procesador: '.$row['procesador'], 1, 'V');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Tipo de disco: '.$row['almacentipo'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Tamaño Disco: '.$row['almacenamiento'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Último mantenimiento: '.date('d/m/Y', strtotime($row['fecha_ult_mant'])), 1, 1, 'L');

$pdf->setX(20);

$prox_mant = date('d/m/Y', strtotime('+6 months', strtotime($row['fecha_ult_mant'])));
$pdf->Cell(60, 10, 'Próximo mantenimiento: '.$prox_mant, 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Realizado por: '.$_SESSION['nombre'], 1, 1, 'L');

$pdf->setX(20);

$pdf->Cell(60, 10, 'Fecha de impresión: '.date('d/m/Y'), 1, 1, 'L');


$pdf->Output();
}