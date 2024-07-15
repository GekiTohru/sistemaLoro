<?php
class Cconexion{
function ConexionBD() {

    $host = 'localhost';
    $dbname = 'equip_corp';
    $username = 'phpmyadmin';
    $password = '1234';
    $puerto = '1433';

    try {
        $conexion = new PDO ("sqlsrv:Server=$host,$puerto;Database=$dbname;Encrypt=false",$username,$password);
    }
    catch(PDOException $exp){
        echo ("No se logró conectar correctamente con la BD: $dbname, error: $exp");
    }
    return $conexion;
}
}