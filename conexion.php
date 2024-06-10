<?php

    $conexion = mysqli_connect("localhost", "root", "", "equip_corp");
    class Conexion1{
    private $conect;

    public function __construct(){
       $servidor="localhost";
       $usuario="root";
       $clave="";
       $bd="equip_cop";
        try{
            $this->conect = new PDO("mysql:host=$servidor;dbname=$bd",$usuario,$clave);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "conexión exitosa";
        }catch(PDOException $e){
            $this->conect = 'Error de conexión';
            echo "ERROR: " . $e->getMessage();
        }
    }

    public function conect(){
        return $this->conect;
    }
}