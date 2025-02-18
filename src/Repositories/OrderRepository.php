<?php
namespace Repositories;

use Lib\DataBase;
use Models\Order;
use PDOException;
use PDO;

class OrderRepository{
    //PROPIEDADES
    private DataBase $db;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DataBase();
    }

    //METODOS
    /**
     * Crea un pedido.
     *
     * Este método se encarga de crear un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param Order $Order El objeto Order que se va a crear.
     * 
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa. 
     */
    public function Order(Order $Order): bool
    {
        try
        {
            $fecha = $Order->getFecha()->format('Y-m-d H:i:s');

            $sql = $this->db->prepare('INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora) 
                                VALUES (:usuario_id, :provincia, :localidad, :direccion, :coste, :estado, :fecha, :hora)');
            $sql->bindValue(':usuario_id', $Order->getUsuarioId(), PDO::PARAM_INT);
            $sql->bindValue(':provincia', $Order->getProvincia(), PDO::PARAM_STR);
            $sql->bindValue(':localidad', $Order->getLocalidad(), PDO::PARAM_STR);
            $sql->bindValue(':direccion', $Order->getDireccion(), PDO::PARAM_STR);
            $sql->bindValue(':coste', $Order->getCoste(), PDO::PARAM_STR);
            $sql->bindValue(':estado', $Order->getEstado(), PDO::PARAM_STR);
            $sql->bindValue(':fecha', $fecha, PDO::PARAM_STR);
            $sql->bindValue(':hora', $Order->getHora(), PDO::PARAM_STR);
    
            $sql->execute();

            $sql = $this->db->prepare('SELECT MAX(id) AS id FROM pedidos WHERE usuario_id = :usuario_id');
            $sql->bindValue(':usuario_id', $Order->getUsuarioId(), PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $orderId = $result['id'];
            $Order->setId($orderId);

            return $orderId;
        }
        catch(PDOException $err)
        {
            error_log("Error al crear el pedido: " . $err->getMessage());
            return false;
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
    }
    
}