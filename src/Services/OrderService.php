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
}