<?php
namespace Controllers;

use Lib\Pages;
use Lib\PDF;
use Lib\PHPMailerClass;
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
    private PHPMailerClass $phpmailer;
    private PDF $pdf;

    //CONSTRUCTOR
    function __construct()
    {
        $this->page = new Pages();
        $this->orderService = new OrderService();
        $this->orderLineService = new OrderLineService();
        $this->userService = new UserService();
        $this->phpmailer = new PHPMailerClass();
        $this->pdf = new PDF();
    }

    //METODOS
    /**
     * Realizar un pedido.
     *
     * Este método maneja la solicitud de realizar un pedido. Si la solicitud es de tipo POST y contiene datos,
     * intenta realizar el pedido en la base de datos después de validar que los datos sean correctos. 
     * Si el pedido es exitoso, envía un correo de confirmación al usuario y redirige a la página de éxito.
     * En caso de error, almacena los mensajes de error en la sesión y renderiza la página del carrito con los errores.
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
                            $Orderlines = [];
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
                                    $Orderlines[] = $this->orderLineService->OrderLine($orderLine);
                                }
                                else 
                                {
                                    $_SESSION['order'] = 'Fail';
                                    $_SESSION['errores'] = OrderLine::getErrores();
                                    $this->page->render('Cart/Cart');
                                    return;
                                }
                            }
                            $userData = $this->userService->getUserById((INT)$_SESSION['user_id']);
                            if ($userData) 
                            {
                                $email = $userData['email'];
                                $nombre = $userData['nombre'];
                                $PDF = $this->pdf->generatePDF($nombre,$Order,$Orderlines);
                                $this->phpmailer->enviarCorreoConfirmacion($email,$nombre,$Order,$PDF);
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

    /**
     * Ver mis pedidos.
     *
     * Este método permite mostrar los pedidos de un usuario.
     *
     * @param int $id El id del usuario.
     * @return void
     */
    public function SeeMyOrders($id): void
    {
        try
        {
            $orders = $this->orderService->getOrderByIdUser($id);
            $this->page->render('Order/MyOrders', ['orders' => $orders]);
        }
        catch(\Exception $e)
        {
            error_log("Error al mostrar los productos: " . $e->getMessage());
            $_SESSION['error'] = 'No se pudieron cargar los productos.';
        }   
    }

    /**
     * Gestionar pedidos.
     *
     * Este método permite gestionar los pedidos de un usuario.
     *
     * @return void
     */
    public function HandleOrder(): void
    {
        $this->ListOrder();
        $this->page->render('Order/HandleOrder');
    }

    public function ListOrder(): void
    {
        try{
            $orders = $this->orderService->getAllOrders();
            unset($_SESSION['error']);
            $this->page->render('Order/HandleOrder',['orders' => $orders]);
        }
        catch(\Exception $e)
        {
            error_log("Error al mostrar las categorías: " . $e->getMessage());
            $_SESSION['error'] = 'No se pudieron cargar los pedidos.';
            $this->page->render('Order/HandleOrder');
        }   
    }

    /**
     * Actualizar el estado de un pedido.
     *
     * Este método permite actualizar el estado de un pedido.
     *
     * @return void
     */
    public function UpdateStatusOrder(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if ($id && $estado) 
            {
                try 
                {
                    $id = (int)$id;
                    $this->orderService->updateOrderStatus($id, $estado);
                    $Datos = $this->orderService->getDataOrder($id);
                    $Estado = $Datos[0]['estado'];
                    $userId = (int)$Datos[0]['usuario_id'];
                    $userData = $this->userService->getUserById($userId);
                    if ($userData) 
                    {
                        $email = $userData['email'];
                        $NombreCliente = $userData['nombre'];
                        $this->phpmailer->sendStatus($email,$Estado,$NombreCliente);
                    } 
                    else 
                    {
                        $_SESSION['error'] = 'No se ha podido enviar el correo de confirmación.';
                    }
                    $_SESSION['order_status'] = 'Estado actualizado correctamente';
                } 
                catch (\Exception $e) 
                {
                    $_SESSION['order_status'] = 'Error al actualizar el estado';
                    $_SESSION['errores'] = $e->getMessage();
                }
            } 
            else 
            {
                $_SESSION['order_status'] = 'Error: Datos incompletos';
                header('Location: ' . BASE_URL ) ;
                exit();
            }
        } 
        else 
        {
            $_SESSION['order_status'] = 'Método no permitido';
        }
        header('Location: ' . BASE_URL . 'HandleOrder');
        exit;
    }


}