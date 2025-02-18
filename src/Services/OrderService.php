<?php
namespace Services;

use Models\Order;
use Repositories\OrderRepository;

class OrderService{
    //PROPIEDADES
    private OrderRepository $orderRepository;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    //METODOS
    /**
     * Crea un pedido.
     *
     * Este método se encarga de crear un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param Order $Order El objeto Order que se va a crear.
     * 
     * @return void
     */
    public function OrderUser(Order $Order): void
    {
        $this->orderRepository->Order($Order);
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
    public function getOrderByIdUser($id): array
    {
        return$this->orderRepository->getOrderByIdUser($id);
    }

    /**
     * Obtiene todos los pedidos.
     *
     * Este método se encarga de obtener todos los pedidos, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @return array Devuelve una lista de pedidos.
     */
    public function getAllOrders(): array
    {
        return $this->orderRepository->getAllOrders();
    }

    /**
     * Actualiza el estado de un pedido.
     *
     * Este método se encarga de actualizar el estado de un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $id El id del pedido.
     * @param string $estado El estado del pedido.
     * 
     * @return void 
     */
    public function updateOrderStatus($id, $estado): void
    {
        $this->orderRepository->updateOrderStatus($id, $estado);
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
    public function getDataOrder($id): array
    {
        return $this->orderRepository->getDataOrder($id);
    }
}