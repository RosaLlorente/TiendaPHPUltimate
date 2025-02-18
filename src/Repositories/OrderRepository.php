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
    
    /**
     * Obtiene todos los pedidos de un usuario.
     *
     * Este método se encarga de obtener todos los pedidos de un usuario, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $id El id del usuario.
     * 
     * @return array Devuelve una lista de pedidos del usuario especificado.
     */
    public function getOrderByIdUser($id): ?array
    {
        try
        {
            $sql = $this->db->prepare('SELECT * FROM pedidos WHERE usuario_id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result  ?: [];
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

    /**
     * Obtiene todos los pedidos.
     *
     * Este método se encarga de obtener todos los pedidos, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @return array Devuelve una lista de pedidos.
     */
    public function getAllOrders(): ?array
    {
        try
        {
            $sql = $this->db->prepare('SELECT * FROM pedidos ORDER BY usuario_id ASC');
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result  ?: [];
        }
        catch(PDOException $err)
        {
            error_log("Error al crear el pedido: " . $err->getMessage());
            return [];
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * Este método se encarga de actualizar el estado de un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $id El id del pedido.
     * @param string $estado El estado del pedido.
     * 
     * @return bool Devuelve true si la actualización fue exitosa, de lo contrario false.
     */
    public function updateOrderStatus($id, $estado): bool
    {
        try {
            $sql = $this->db->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id');
            $sql->bindParam(':estado', $estado, PDO::PARAM_STR);  
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            return true;
        } 
        catch (PDOException $err) 
        {
            error_log("Error al actualizar el estado del pedido: " . $err->getMessage());
            return false;
        } 
        finally 
        {
            if (isset($selectQuery)) 
            {
                $selectQuery->closeCursor();
            }
            if (isset($sql)) 
            {
                $sql->closeCursor();
            }
        }
    }   

    /**
     * Obtiene los datos de un pedido.
     *
     * Este método se encarga de obtener los datos de un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $id El id del pedido.
     * 
     * @return array|null Devuelve los datos del pedido especificado, o null si no se encuentra ningún pedido con ese id.
     */
    public function getDataOrder($id): ?array
    {
        try 
        {
            $sql = $this->db->prepare("SELECT estado,usuario_id FROM pedidos WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result  ?: [];
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
        }
    }
}