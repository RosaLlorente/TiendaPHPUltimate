<?php
namespace Lib;
use PDO;
use PDOException;

class DataBase{
    private $conexion;
    private mixed $result;
    private string $server;
    private string $user;
    private string $pass;
    private string $data_base;

    function __construct() 
    {
        $this->server = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->data_base = $_ENV['DB_DATABASE'];
        $this->pass = $_ENV['DB_PASS'];
        $this->conexion = $this->conexion();
    }

    private function conexion(): PDO
    {
        try{
            $option = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::MYSQL_ATTR_FOUND_ROWS => true,
                PDO::ATTR_ERRMODE => true,
                PDO::ERRMODE_EXCEPTION => true);
                $conexion = new PDO("mysql:host={$this->server};port=3307;dbname={$this->data_base}",$this->user,$this->pass,$option);
                return $conexion;
        }
        catch(PDOException $e){
            echo 'Ha surgido un error y no se puede conectar a la base de datos.Detalle: '.$e->getMessage();
            exit;
        }
    }

    public function query(string $querySQL):void
    {
        $this->result = $this->conexion->query($querySQL);
    }
    public function extract_log():mixed
    {
        return($row = $this->result->fetch(PDO::FETCH_ASSOC))? $row:false;
    }
    public function extract_all(): array
    {
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function Afected_Rows():int
    {
        return $this->result->rowCount();
    }
    public function LastInsertID():int
    {
        return $this->result->rowCount();
    }
    public function prepare($pre)
    {
        return $this->conexion->prepare($pre);
    }

}