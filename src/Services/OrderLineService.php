<?php
namespace Services;

use Models\OrderLine;
use Repositories\OrderLineRepository;

class OrderLineService{
    //PROPIEDADES
    private OrderLineRepository $orderLineRepository;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->orderLineRepository = new OrderLineRepository();
    }

    //METODOS
    /**
     * Crea una linea de pedido.
     *
     * Este método se encarga de crear una linea de pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param OrderLine $OrderLine El objeto OrderLine que se va a crear.
     * 
     * @return void
     */
    public function OrderLine(OrderLine $OrderLine): void
    {
        $this->orderLineRepository->OrderLine($OrderLine);
    }

    /**
     * Obtiene las líneas de pedido de un pedido.
     *
     * Este método se encarga de obtener las líneas de pedido de un pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $orderId El id del pedido.
     * 
     * @return array Devuelve una lista de líneas de pedido del pedido especificado.
     */
    public function getOrderLineById(int $orderId): array
    {
        return $this->orderLineRepository->getOrderLineById($orderId);
    }
}