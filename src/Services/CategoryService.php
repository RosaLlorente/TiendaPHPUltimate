<?php
namespace Services;

use Models\Category;
use Repositories\CategoryRepository;

class CategoryService{
     //PROPIEDADES
    private CategoryRepository $categoryRepository;

     //CONSTRUCTOR
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }
     //METODOS
    
    /**
     * Crear una nueva categoría.
     *
     * Este método se encarga de conectar el controlador con el repositorio para registrar una nueva categoría
     * en la base de datos.
     * 
     * @param Category $category Los datos de la categoría introducidos por el usuario.
     * 
     * @return void
     */
    public function CreateCategory(Category $category) : void
    {
        $this->categoryRepository->CreateCategory($category);
    }

    /**
     * Listar todas las categorías.
     *
     * Este método se encarga de conectar el controlador con el repositorio para obtener todas las categorías
     * en la base de datos.
     * 
     * @return array Devuelve una lista de categorías.
     */
    public function ListCategories(): array
    {
        return $this->categoryRepository->ListCategories();
    }

    /**
     * Eliminar una categoría.
     *
     * Este método se encarga de conectar el controlador con el repositorio para eliminar una categoría
     * en la base de datos.
     * 
     * @param int $id El id de la categoría a eliminar.
     * 
     * @return void
     */
    public function DeleteCategory(int $id): void
    {
        $this->categoryRepository->DeleteCategory($id);
    }

    /**
     * Editar una categoría.
     *
     * Este método se encarga de conectar el controlador con el repositorio para editar una categoría
     * en la base de datos.
     * 
     * @param string $nombre El nombre de la categoría a editar.
     * @param int $id El id de la categoría a editar.
     * 
     * @return void
     */
    public function EditCategory($nombre,$id): void
    {
        $this->categoryRepository->EditCategory($nombre,$id);
    }

    /**
     * Obtiene el nombre de una categoría.
     *
     * Este método se encarga de obtener el nombre de una categoría, incluyendo su nombre, descripción, precio, stock, oferta, fecha de creación y imagen.
     * 
     * @param int $id El id de la categoría.
     * 
     * @return string Devuelve el nombre de la categoría especificada.
     */
    public function GetCategoryName($id): string
    {
        return $this->categoryRepository->GetCategoryName($id);
        return $nombreCategoria;
    }
}