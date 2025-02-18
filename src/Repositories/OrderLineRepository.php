<?php
namespace Repositories;

use Lib\DataBase;
use Models\OrderLine;
use PDOException;
use PDO;

class OrderLineRepository
{
    //PROPIEDADES
    private DataBase $db;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DataBase();
    }

    //METODOS
    /**
     * Crea una linea de pedido.
     *
     * Este método se encarga de crear una linea de pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param OrderLine $OrderLine El objeto OrderLine que se va a crear.
     * 
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function OrderLine(OrderLine $OrderLine): bool
    {
        try
        {
            $sql = $this->db->prepare('INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades)
                                    VALUES (:pedido_id, :producto_id, :unidades)');
            $sql->bindValue(':pedido_id', $OrderLine->getPedidoId(), PDO::PARAM_INT);
            $sql->bindValue(':producto_id', $OrderLine->getProductoId(), PDO::PARAM_INT);
            $sql->bindValue(':unidades', $OrderLine->getUnidades(), PDO::PARAM_INT);
            $sql->execute();
            return true;
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