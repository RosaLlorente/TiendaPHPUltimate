<?php
namespace Controllers;

use DateTime;
use Lib\Pages;
use Models\Product;
use Services\CategoryService;
use Services\ProductService;

class ProductController{

    //PROPIEDADES
    private Pages $page;
    private ProductService $productService;
    private CategoryService $categoryService;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->page = new Pages();
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    //METODOS

    /**
     * Mostrar productos aleatorios.
     *
     * Este método maneja la solicitud de mostrar productos aleatorios. Si la solicitud es de tipo GET, intenta mostrar productos aleatorios
     * en la base de datos. Si la lista es exitosa, renderiza la página de productos aleatorios con los productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de productos aleatorios nuevamente con los errores.
     *
     * @return void
     */
    public function index(): void
    {
        try 
        {
            $products = $this->productService->ShowRandomProducts();
            $this->page->render('Product/RandomProduct', ['products' => $products]);
        } catch (\Exception $e) 
        {
            error_log("Error al mostrar los productos: " . $e->getMessage());
            $_SESSION['error'] = "No se pudieron cargar los productos.";
            $this->page->render('Error/ErrorPage');
        }
    }

    /**
     * Gestionar productos.
     *
     * Este método maneja la solicitud de gestionar productos. Si la solicitud es de tipo GET, intenta mostrar la página de gestión de productos
     * en la base de datos. Si la lista es exitosa, renderiza la página de gestión de productos con los productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de gestión de productos nuevamente con los errores.
     *
     * @return void 
     */
    public function HandleProducts(): void
    {
        $this->ListProducts();
        $this->page->render("Product/HandleProduct");
    }

    /**
     * Crear un producto.
     *
     * Este método maneja la solicitud de crear un producto. Si la solicitud es de tipo POST y contiene datos,
     * intenta crear el producto en la base de datos después de validar y encriptar los datos del producto. 
     * Si el registro es exitoso, redirige al usuario a la página de gestión de productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de crear un producto nuevamente con los errores.
     *
     * @return void
     */
    public function CreateProduct(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $_POST['data']['descripcion'] = htmlspecialchars(strip_tags(trim($_POST['data']['descripcion'])));
                $_POST['data']['categoria_id'] = (int)($_POST['data']['categoria_id']);
                $_POST['data']['precio'] = (float)($_POST['data']['precio']);
                $_POST['data']['stock'] = (int)($_POST['data']['stock']);
                $_POST['data']['oferta'] = (int)($_POST['data']['oferta']);
    
                if (isset($_FILES['data']['name']['imagen']) && $_FILES['data']['error']['imagen'] == 0) 
                {
                    // Ruta de la carpeta de destino
                    $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/TiendaPHP/public/Img/';
                    $nombreImagen = basename($_FILES['data']['name']['imagen']);
                    $rutaDestino = $carpetaDestino . $nombreImagen;
    
                    // Mover el archivo a la carpeta de destino
                    if (move_uploaded_file($_FILES['data']['tmp_name']['imagen'], $rutaDestino)) 
                    {
                        $_POST['data']['imagen'] = $nombreImagen;
                    } 
                    else 
                    {
                        $_SESSION['product'] = 'Fail';
                        $_SESSION['errores'][] = 'Hubo un problema al cargar la imagen.';
                        $this->page->render('Product/CreateProduct');
                        return;
                    }
                } 
                else 
                {
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'][] = 'La imagen es obligatoria.';
                    $this->page->render('Product/CreateProduct');
                    return;
                }
    
                $product = Product::fromArray($_POST['data'] ?? []);
                $product->sanitate();
    
                if($product->ValidateCreateProduct())
                {
                    try{
                        $this->productService->CreateProduct($product);
                        $_SESSION['product'] = 'Complete';
                        $this->page->render('Product/ProductSuccessful');
                    }
                    catch(\Exception $e){
                        $_SESSION['product'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else
                {
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'] = Product::getErrores();
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error)
                        {
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $_SESSION['old_data'] = $_POST['data'] ?? [];
                    $this->page->render('Product/CreateProduct');
                }
            }
            else
            {
                $_SESSION['product'] = 'Fail';
            }
        }
        else
        {
            $this->page->render('Product/CreateProduct');
            $_SESSION['product'] = 'Fail';
        }
    }
    

    /**
     * Eliminar un producto.
     *
     * Este método maneja la solicitud de eliminar un producto. Si la solicitud es de tipo POST y contiene datos,
     * intenta eliminar el producto en la base de datos después de validar que el id del producto sea válido. 
     * Si la eliminación es exitosa, redirige al usuario a la página de gestión de productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de eliminar un producto nuevamente con los errores.
     *
     * @return void
     */
    public function DeleteProduct($id): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            if($id)
            {
                try{
                    $this->productService->DeleteProduct($id);
                    $_SESSION['product'] = 'Complete';
                    $this->page->render('Product/DeleteProductSuccesful');
                }
                catch(\Exception $e){
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'] = $e->getMessage();
                }
            }
            else
            {
                $_SESSION['errores'] = "ID no válido";
            }
        }
        else
        {
            $this->page->render('Product/HandleProduct');
            $_SESSION['product'] = 'Fail';
        }
    }

    /**
     * Editar un producto.
     *
     * Este método maneja la solicitud de editar un producto. Si la solicitud es de tipo POST y contiene datos,
     * intenta editar el producto en la base de datos después de validar que el id del producto sea válido y que los datos sean correctos. 
     * Si la edición es exitosa, redirige al usuario a la página de gestión de productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de editar un producto nuevamente con los errores.
     *
     * @return void
     */
    public function EditProduct(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $id=$_POST['data']['id'] ?? null;
                $categoria_id =(int)($_POST['data']['categoria_id']);
                $nombre = htmlspecialchars(strip_tags(trim($_POST['data']['nombre'])));
                $descripcion = htmlspecialchars(strip_tags(trim($_POST['data']['descripcion'])));
                $precio = (float)($_POST['data']['precio']);
                $stock  = (int)($_POST['data']['stock']);
                $oferta = (int)($_POST['data']['oferta']);
                $fecha = new DateTime($_POST['data']['fecha']);

                $product = Product::fromArray($_POST['data'] ?? []);
                $product->sanitate();
                
                if($product->ValidateEditProduct())
                {
                    try{
                        $this->productService->EditProduct($id, $categoria_id, $nombre, $descripcion, $precio, $stock, $oferta, $fecha);
                        $_SESSION['product'] = 'Complete';
                        $this->page->render('Product/editProductSuccesful');
                    }
                    catch(\Exception $e){
                        $_SESSION['product'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else{
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'] = Product::getErrores();
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error)
                        {
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->ListProducts();
                    $this->page->render('Product/HandleProduct');
                }
            }
            else
            {
                $_SESSION['product'] = 'Fail';
                $_SESSION['errores'] = "Datos inválidos";
            }
        }
        else
        {
            $this->page->render('Product/HandleProduct');
            $_SESSION['product'] = 'Fail';
        }
    }

    /**
     * Editar la imagen de un producto.
     *
     * Este método maneja la solicitud de editar la imagen de un producto. Si la solicitud es de tipo POST y contiene datos,
     * intenta editar la imagen del producto en la base de datos después de validar que el id del producto sea válido y que la imagen sea válida. 
     * Si la edición es exitosa, redirige al usuario a la página de gestión de productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de editar la imagen de un producto nuevamente con los errores.
     *
     * @return void 
     */
    public function EditImage(): void
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if ($_POST['data']) 
        {
            $id = $_POST['data']['id'] ?? null;
            
            // Verificar si se sube una imagen
            if (isset($_FILES['data']['name']['imagen']) && $_FILES['data']['error']['imagen'] == 0) 
            {
                // Ruta de la carpeta de destino
                $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/TiendaPHP/public/Img/';
                $nombreImagen = basename($_FILES['data']['name']['imagen']);
                $rutaDestino = $carpetaDestino . $nombreImagen;
    
                // Mover el archivo a la carpeta de destino
                if (move_uploaded_file($_FILES['data']['tmp_name']['imagen'], $rutaDestino)) 
                {
                    $_POST['data']['imagen'] = $nombreImagen; // Asignar la ruta completa de la imagen
                } 
                else 
                {
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'][] = 'Hubo un problema al cargar la imagen.';
                    $this->page->render('Product/CreateProduct');
                    return;
                }
            } 
            else 
            {
                $_SESSION['product'] = 'Fail';
                $_SESSION['errores'][] = 'La imagen es obligatoria.';
                $this->page->render('Product/CreateProduct');
                return;
            }

            // Crear un objeto Product a partir de los datos recibidos
            $product = Product::fromArray($_POST['data'] ?? []);
            $product->sanitate();

            // Validar la imagen
            if ($product->ValidateImagen()) 
            {
                try 
                {
                    $this->productService->EditImage($id, $_POST['data']['imagen']); // Pasar la imagen cargada
                    $_SESSION['product'] = 'Complete';
                    $this->page->render('Product/EditProductSuccesful');
                } 
                catch (\Exception $e) 
                {
                    $_SESSION['product'] = 'Fail';
                    $_SESSION['errores'] = $e->getMessage();
                }
            } 
            else 
            {
                $_SESSION['product'] = 'Fail';
                $_SESSION['errores'] = Product::getErrores();
                if (isset($_SESSION['errores']) && is_array($_SESSION['errores'])) 
                {
                    foreach ($_SESSION['errores'] as $error) 
                    {
                        echo '<p style="color:red">*' . htmlspecialchars($error) . '</p>';
                    }
                }
                $this->ListProducts();
                $this->page->render('Product/HandleProduct');
            }
        } 
        else 
        {
            $_SESSION['product'] = 'Fail';
            $_SESSION['errores'] = "Datos inválidos";
        }
    } 
    else 
    {
        $this->page->render('Product/HandleProduct');
        $_SESSION['product'] = 'Fail';
    }
}



    /**
     * Listar todos los productos.
     *
     * Este método maneja la solicitud de listar todos los productos. Si la solicitud es de tipo GET, intenta listar todos los productos
     * en la base de datos. Si la lista es exitosa, renderiza la página de gestión de productos con los productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de gestión de productos nuevamente con los errores.
     *
     * @return void
     */
    public function ListProducts(): void
    {
        try{
            $products = $this->productService->ListProducts();
            foreach ($products as &$product) 
            {
                $categoryName = $this->categoryService->GetCategoryName($product['categoria_id']);
                $product['categoria_nombre'] = $categoryName;  
            }
            $this->page->render('Product/HandleProduct', ['products' => $products]);
        }
        catch(\Exception $e){
            error_log("Error al mostrar los productos: " . $e->getMessage());
            $_SESSION['error'] = 'No se pudieron cargar los productos.';
            $this->page->render('Product/HandleProduct');
        }   
    }

    /**
     * Mostrar el catalogo de productos.
     *
     * Este método maneja la solicitud de mostrar el catalogo de productos. Si la solicitud es de tipo GET, intenta mostrar el catalogo de productos
     * en la base de datos. Si la lista es exitosa, renderiza la página de productos del catalogo con los productos. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de productos del catalogo nuevamente con los errores.
     *
     * @return void
     */
    public function SeeCatalog(): void
    {
        try{
            $categories = $this->categoryService->ListCategories();
            $selectedCategory = $_POST['category'] ?? null;
            if ($selectedCategory) 
            {
                $products = $this->productService->GetProductsByCategory((int)$selectedCategory);
            } 
            else 
            {
                $products = $this->productService->SeeCatalog();
            }
            $this->page->render('Product/CatalogProduct', [
                'categories' => $categories,
                'products' => $products
            ]);
        }
        catch(\Exception $e){
            error_log("Error al mostrar el catalogo: " . $e->getMessage());
            $_SESSION['error'] = 'No se pudieron cargar los productos.';
            $this->page->render('Product/CatalogProduct', ['products'=> $products]);
        }
    }
}