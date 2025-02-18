<?php
namespace Controllers;

use Lib\Pages;
use Models\Category;
use Services\CategoryService;

class CategoryController{

    //PROPIEDADES
    private Pages $page;
    private CategoryService $categoryService;

    //CONSTRUCTOR
    function __construct()
    {
        $this->page = new Pages();
        $this->categoryService = new CategoryService();
    }

    //METODOS
    /**
     * Panel de gestión de categorias
     *
     * Este método permite mostrar el panel de gestión de categorias.
     *
     * @return void
     */
    public function HandleCategory(): void
    {
        $this->ListCategories();
        $this->page->render('Category/HandleCategory');
    }
    
    /**
     * Crea una nueva categoría.
     *
     * Este método permite crear una nueva categoria en el sistema.
     * 
     * @param array $data Los datos del producto a crear.
     * @return void
     */
    public function CreateCategory(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $category = Category::fromArray($_POST['data'] ?? []);
                $category->sanitate();
                if($category->ValidateCategory())
                {
                    try{
                        $this->categoryService->CreateCategory($category);
                        $_SESSION['category'] = 'Complete';
                        $this->page->render('Category/CategorySuccessful');
                    }
                    catch(\Exception $e){
                        $_SESSION['category'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else{
                    $_SESSION['category'] = 'Fail';
                    $_SESSION['errores'] = Category::getErrores();
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error){
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->page->render('Category/CreateCategory');
                }
            }
            else{
                $_SESSION['category'] = 'Fail';
            }
        }
        else
        {
            $this->page->render('Category/CreateCategory');
            $_SESSION['category'] = 'Fail';
        }
    }

    /**
     * Eliminar una categoría.
     *
     * Este método maneja la solicitud de eliminar una categoría. Si la solicitud es de tipo POST y contiene datos,
     * intenta eliminar la categoría en la base de datos después de validar que el id de la categoría sea válido. 
     * Si la eliminación es exitosa, redirige al usuario a la página de gestión de categorías. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de eliminar una categoría nuevamente con los errores.
     *
     * @return void
     */
    public function DeleteCategory($id): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            if($id)
            {
                try{
                    $this->categoryService->DeleteCategory($id);
                    $_SESSION['category'] = 'Complete';
                    $this->page->render('Category/DeleteCategorySuccesful');
                }
                catch(\Exception $e){
                    $_SESSION['category'] = 'Fail';
                    $_SESSION['errores'] = $e->getMessage();
                }
            }
            else
            {die('llega');
                $_SESSION['errores'] = "ID no válido";
            }
        }
        else
        {
            $_SESSION['category'] = 'Fail';
            $this->page->render('Category/HandleCategory');
        }
    }

    /**
     * Editar una categoría.
     *
     * Este método maneja la solicitud de editar una categoría. Si la solicitud es de tipo POST y contiene datos,
     * intenta editar la categoría en la base de datos después de validar que el id de la categoría sea válido y que los datos sean correctos. 
     * Si la edición es exitosa, redirige al usuario a la página de gestión de categorías. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de editar una categoría nuevamente con los errores.
     *
     * @return void
     */
    public function EditCategory(): void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $nombre = $_POST['data']['nombre'] ?? null;
                $id = $_POST['data']['id'] ?? null;
                $id = (int)$id;
                $category = Category::fromArray($_POST['data'] ?? []);
                $category->sanitate();
                if($category->ValidateCategory())
                {
                    try{
                        $this->categoryService->EditCategory($nombre, $id);
                        $_SESSION['category'] = 'Complete';
                        $this->ListCategories();
                        $this->page->render('Category/HandleCategory');
                    }
                    catch(\Exception $e){
                        $_SESSION['category'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else{
                    $_SESSION['category'] = 'Fail';
                    $_SESSION['errores'] = Category::getErrores();
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error){
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->ListCategories();
                    $this->page->render('Category/HandleCategory');
                }
            }
            else
            {
                $_SESSION['category'] = 'Fail';
                $_SESSION['errores'] = "Datos inválidos";
            }
        }
        else
        {
            $this->page->render('Category/HandleCategory');
            $_SESSION['category'] = 'Fail';
        }
    }
    
    /**
     * Listar todas las categorías.
     *
     * Este método maneja la solicitud de listar todas las categorías. Si la solicitud es de tipo GET, intenta listar todas las categorías
     * en la base de datos. Si la lista es exitosa, renderiza la página de gestión de categorías con las categorías. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de gestión de categorías nuevamente con los errores.
     *
     * @return void
     */
    public function ListCategories(): void
    {
        try{
            $categories = $this->categoryService->ListCategories();
            unset($_SESSION['error']);
            $this->page->render('Category/HandleCategory',['categories' => $categories]);
        }
        catch(\Exception $e){
            error_log("Error al mostrar las categorías: " . $e->getMessage());
            $_SESSION['error'] = 'No se pudieron cargar las categorías.';
            $this->page->render('Category/HandleCategory');
        }   
    }
}