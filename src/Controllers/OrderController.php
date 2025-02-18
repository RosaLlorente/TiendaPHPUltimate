<?php
namespace Controllers;

use Lib\Pages;
use Lib\Utils;
use Models\OrderLine;
use Models\Order;
use Services\OrderService;
use Services\OrderLineService;
use Services\UserService;

class OrderController{

    //PROPIEDADES
    private Pages $page;
    private OrderService $orderService;
    private OrderLineService $orderLineService;
    private UserService $userService;
    private Utils $utils;

    //CONSTRUCTOR
    function __construct()
    {
        $this->page = new Pages();
        $this->orderService = new OrderService();
        $this->orderLineService = new OrderLineService();
        $this->userService = new UserService();
        $this->utils = new Utils();
    }

    //METODOS
    /**
     * Realizar un pedido.
     *
     * Este método maneja la solicitud de realizar un pedido. Si la solicitud es de tipo POST y contiene datos,
     * intenta realizar el pedido en la base de datos después de validar que los datos sean correctos. 
     * Si el pedido es exitoso, redirige al usuario a la página de pedidos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de realizar un pedido nuevamente con los errores.
     *
     * @return void
     */
    public function Order(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $data = $_POST['data'];
                $data['fecha'] = date('Y-m-d');
                $data['hora'] = date('H:i');
                $Order = Order::fromArray($_POST['data']);
                $Order->sanitate();
                if($Order->ValidateOrder())
                {
                    try
                    {
                        $orderSuccess = $this->orderService->OrderUser($Order);

                        if (!$orderSuccess)
                        {
                            $_SESSION['order'] = 'Complete';
                            $pedido_id = $Order->getId();
                            foreach ($_SESSION['cart'] as $item) 
                            {
                                $orderLine = OrderLine::fromArray([
                                    'pedido_id' => $pedido_id,
                                    'producto_id' => $item['id'],
                                    'unidades' => $item['quantity'],
                                ]);
                                $orderLine->sanitateOrderLines();
                                if ($orderLine->ValidateOrderLine()) 
                                {
                                    $this->orderLineService->OrderLine($orderLine);
                                }
                                else 
                                {
                                    $_SESSION['order'] = 'Fail';
                                    $_SESSION['errores'] = OrderLine::getErrores();
                                    $this->page->render('Cart/Cart');
                                    return;
                                }
                            }
                            $emailData = $this->userService->getUserById((INT)$_SESSION['user_id']);
                            if ($emailData) 
                            {
                                $email = $emailData;
                                $this->utils->enviarCorreoConfirmacion($email);
                            } 
                            else 
                            {
                                $_SESSION['error'] = 'No se ha podido enviar el correo de confirmación.';
                            }
                            unset($_SESSION['cartTotals']);
                            unset($_SESSION['errores']);
                            $this->page->render('Order/OrderSucessful');
                        } 
                        else 
                        {
                            $_SESSION['order'] = 'Fail';
                            $error = 'No se ha podido realizar el pedido.';
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    catch(\Exception $e)
                    {
                        $_SESSION['order'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else
                {
                    $_SESSION['order'] = 'Fail';
                    $_SESSION['errores'] = Order::getErrores();
                    $_SESSION['old_data'] = $_POST['data'];
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error){
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->page->render('Cart/Cart');
                }
            }
            else
            {
                $_SESSION['order'] = 'Fail';
            }
        }
        else
        {
            $_SESSION['order'] = 'Fail';
            $this->page->render('Cart/Cart');
        }
    }

}