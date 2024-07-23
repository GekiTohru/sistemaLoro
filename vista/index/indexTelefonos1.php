<?php
header('Content-Type: application/json');
session_start();

include("C:/xampp/htdocs/sistemaLoro/controlador/conexion.php");

$conexionObj = new Cconexion();

// Llamar al método ConexionBD para obtener la conexión
$conexion = $conexionObj->ConexionBD();

// $sql = "SELECT 
// t.*,
// t.id_telefono AS id,
// mm.nombre AS modelo,
// mm.ram AS ram,
// mm.rom AS rom,
// f.nombre AS fabricante,
// ISNULL(p.nombre, 'Sin asignar') AS asignado,
// ISNULL(cr.nombre, 'Sin cargo') AS cargo,
// ISNULL(a.nombre, 'Sin área') AS area,
// ISNULL(s.nombre, 'Sin sucursal') AS sucursal
// FROM 
// telefonos t
// INNER JOIN 
// modelo_marca mm ON t.id_modelo = mm.id_modelo
// LEFT JOIN 
// fabricante f ON mm.id_fabricante = f.id_fabricante
// LEFT JOIN 
// (SELECT * FROM tlf_asignado WHERE activo = 1) AS tlf_asignado_activo ON t.id_telefono = tlf_asignado_activo.id_telefono
// LEFT JOIN 
//     personal p ON tlf_asignado_activo.id_personal = p.id_personal AND p.activo = 1
// LEFT JOIN 
// cargo_ruta cr ON p.id_cargoruta = cr.id_cargoruta
// LEFT JOIN 
// area a ON p.id_area = a.id_area
// LEFT JOIN 
// sucursal s ON t.id_sucursal = s.id_sucursal
// WHERE 
// t.activo = 1
// ORDER BY 
// t.id_telefono ASC;
// ";
$stmt = $conexion->prepare("SELECT id_usuario, usuario, nombre, clave FROM usuario");

// Execute the query
$stmt->execute();

// Fetch the results
$data = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array(
        'id_usuario' => $row['id_usuario'],
        'usuario' => $row['usuario'],
        'nombre' => $row['nombre'],
        'clave' => $row['clave']
    );
}

// Encode the array as JSON
$json = json_encode($data);

// Output the JSON string
echo $json;