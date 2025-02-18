<?php
namespace Controllers;

use Lib\Pages;
use Lib\Utils;
use Services\CategoryService;
use Services\ProductService;


class CartController {

    //PROPIEDADES
    private Pages $page;
    private Utils $utils;
    private ProductService $productService;
    private CategoryService $categoryService;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->page = new Pages();
        $this->utils = new Utils();
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    //METODOS
    /**
     * Mostrar el carrito de compras.
     *
     * Este método maneja la solicitud de mostrar el carrito de compras. Si la solicitud es de tipo GET, intenta mostrar el carrito de compras
     * en la base de datos. Si la lista es exitosa, renderiza la página de carrito de compras con los productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de carrito de compras nuevamente con los errores.
     *
     * @return void
     */
    public function showCart(): void
    {
        $cartTotals = $this->utils->getCartTotals();
        $_SESSION['cart_totals'] = $cartTotals;
        $this->page->render("Cart/Cart", ['cartTotals' => $cartTotals]);
    }

    /**
     * Añade un producto al carrito de compras. 
     * 
     * Este método maneja la solicitud de añadir un producto al carrito de compras. Si la solicitud es de tipo POST y contiene datos,
     * intenta añadir el producto al carrito de compras en la base de datos después de validar que el id del producto sea válido y que el stock no sea 0.
     * Si la añadición es exitosa, redirige al usuario a la página de carrito de compras. En caso de error, almacena los mensajes de error en la sesión y renderiza la página de añadir un producto al carrito de compras nuevamente con los errores.
     * 
     * @return void
     */
    public function addProductCart($id): void
    {
        $product = $this->productService->GetProduct($id);
        if(!$product)
        {
            $_SESSION['errores'] = 'El producto no existe';
            $products = $this->productService->SeeCatalog(); 
            $categories = $this->categoryService->ListCategories(); 
            $this->page->render('Product/CatalogProduct', [
                'categories' => $categories,
                'products' => $products
            ]);
            return;
        }
        else
        {
            $stockDisponible = $this->productService->GetStockDisponible($id);
            if($stockDisponible <= 0)
            {
                $_SESSION['errores'] = "No hay stock disponible para este producto.";
                header('Location: ' . BASE_URL .'Cart');
                exit;
            }
            else
            {
                if (!isset($_SESSION['cart'])) 
                {
                    $_SESSION['cart'] = [];
                }
                if (isset($_SESSION['cart'][$id])) 
                {
                    $nuevaCantidad = $_SESSION['cart'][$id]['quantity'] + 1;

                    if ($stockDisponible > 0) 
                    {
                        $_SESSION['cart'][$id]['quantity'] = $nuevaCantidad;
                    } 
                    else 
                    {
                        $_SESSION['error'] = "No puedes agregar más de " . $stockDisponible . " unidades de este producto.";
                    }
                } 
                else
                {
                    $_SESSION['cart'][$id] = [
                        'id' => $product->getId(),
                        'name' => $product->getNombre(),
                        'price' => $product->getPrecio(),
                        'quantity' => 1,
                        'offer' => $product->getOferta(),
                        'image' => $product->getImagen(),
                    ];
                }
                $this->productService->updateProductStock($id, 1);
                header('Location: ' . BASE_URL .'Cart');
                exit;
            }
        }
    }

    /**
     * Quitar un producto del carrito.
     *
     * Este método maneja la solicitud de quitar un producto del carrito. Si la solicitud es de tipo POST y contiene datos,
     * intenta quitar el producto del carrito en la base de datos después de validar que el id del producto sea válido. 
     * Si la eliminación es exitosa, redirige al usuario a la página de carrito de compras. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de quitar un producto del carrito nuevamente con los errores.
     *
     * @return void
     */
    public function quitProductCart($id): void
    {
        if (isset($_SESSION['cart'])) 
        {
            if (isset($_SESSION['cart'][$id])) 
            {
                if ($_SESSION['cart'][$id]['quantity'] > 0) 
                {
                    $_SESSION['cart'][$id]['quantity'] -= 1;
                    $this->productService->updateProductStock($id, -1);
                    if ($_SESSION['cart'][$id]['quantity'] <= 0) 
                    {
                        unset($_SESSION['cart'][$id]);
                        $_SESSION['success'] = "El producto ha sido eliminado del carrito.";
                    } 
                    else 
                    {
                        $_SESSION['success'] = "La cantidad del producto ha sido actualizada.";
                    }
                }
            } 
            else 
            {
                $_SESSION['error'] = "El producto no se encuentra en el carrito.";
            }
        } 
        else 
        {
            $_SESSION['error'] = "El carrito está vacío.";
        }
        header('Location: ' . BASE_URL . 'Cart');
        exit;
    }
    
    /**
     * Elimina un producto del carrito.
     *
     * Este método maneja la solicitud de eliminar un producto del carrito. Si la solicitud es de tipo POST y contiene datos,
     * intenta eliminar el producto del carrito en la base de datos después de validar que el id del producto sea válido. 
     * Si la eliminación es exitosa, redirige al usuario a la página de carrito de compras. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de eliminar un producto del carrito nuevamente con los errores.
     *
     * @return void
     */
    public function DeleteProductCart($id): void
    {
        if (isset($_SESSION['cart'])) 
        {
            if (isset($_SESSION['cart'][$id])) 
            {
                $this->productService->updateProductStock($id, -$_SESSION['cart'][$id]['quantity']);
                unset($_SESSION['cart'][$id]);
                $_SESSION['success'] = "El producto ha sido eliminado del carrito.";
            } 
            else 
            {
                $_SESSION['error'] = "El producto no se encuentra en el carrito.";
            }
        } 
        else 
        {
            $_SESSION['error'] = "El carrito está vacío.";
        }

        $this->page->render("Cart/Cart");
    }

    /**
     * Vacia el carrito.
     *
     * Este método maneja la solicitud de vaciar el carrito. Si la solicitud es de tipo POST, intenta vaciar el carrito en la base de datos. 
     * Si la vaciación es exitosa, redirige al usuario a la página de carrito de compras. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de vaciar el carrito nuevamente con los errores.
     *
     * @return void
     */
    public function ClearCart(): void
    {
        if (isset($_SESSION['cart'])) 
        {
            foreach ($_SESSION['cart'] as $id => $product) 
            {
                $this->productService->updateProductStock($id, -$product['quantity']);
            }
            unset($_SESSION['cart']);
            $_SESSION['success'] = "El carrito ha sido vaciado del carrito.";
            $this->page->render("Cart/Cart");
        } 
        else 
        {
            $_SESSION['error'] = "No se ha podido vaciar carrito.";
            $this->page->render("Cart/Cart");
        }
    }  
    
    /**
     * Vacia el carrito con éxito.
     *
     * Este método maneja la solicitud de vaciar el carrito con éxito. Si la solicitud es de tipo GET, intenta vaciar el carrito en la base de datos. 
     * Si la vaciación es exitosa, redirige al usuario a la página de carrito de compras. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de vaciar el carrito con éxito nuevamente con los errores.
     *
     * @return void
     */
    public function ClearCartProductSuccessful(): void
    {
        if (isset($_SESSION['cart'])) 
        {
            unset($_SESSION['cart']);
            $_SESSION['success'] = "El carrito ha sido vaciado del carrito.";
            $products = $this->productService->SeeCatalog(); 
            $categories = $this->categoryService->ListCategories(); 
            $this->page->render('Product/CatalogProduct', [
                'categories' => $categories,
                'products' => $products
            ]);
            return;
        } 
        else 
        {
            $_SESSION['error'] = "No se ha podido vaciar carrito.";
            $this->page->render("Cart/Cart");
        }
    }
}
