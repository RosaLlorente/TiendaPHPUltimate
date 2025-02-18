<?php
namespace Services;

use Models\Product;
use Repositories\ProductRepository;

class ProductService{
    //PROPIEDADES
    private ProductRepository $productRepository;
    
    //CONSTRUCTOR
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    //METODOS

    /** 
     * Crear un producto.
     *
     * Este método se encarga de conectar el controlador con el repositorio para registrar un producto
     * en la base de datos.
     * 
     * @param Product $product Los datos del producto a crear.
     * 
     * @return void
     */
    public function CreateProduct(Product $product): void
    {
        $this->productRepository->CreateProduct($product);
    }

    /**
     * Listar todos los productos.
     *
     * Este método se encarga de conectar el controlador con el repositorio para obtener todos los productos
     * en la base de datos.
     * 
     * @return array Devuelve una lista de productos.
     */
    public function ListProducts(): array
    {
        return $this->productRepository->ListProducts();
    }

    /** 
     * Eliminar un producto.
     *
     * Este método se encarga de conectar el controlador con el repositorio para eliminar un producto
     * en la base de datos.
     * 
     * @param int $id El id del producto a eliminar.
     * 
     * @return void
     */
    public function DeleteProduct(int $id): void
    {
        $this->productRepository->DeleteProduct($id);
    }

    /**
     * Editar un producto.
     *
     * Este método se encarga de conectar el controlador con el repositorio para editar un producto
     * en la base de datos.
     * 
     * @param int $id El id del producto a editar.
     * @param int $categoria_id El id de la categoría del producto a editar.
     * @param string $nombre El nombre del producto a editar.
     * @param string $descripcion La descripción del producto a editar.
     * @param float $precio El precio del producto a editar.
     * @param int $stock El stock del producto a editar.
     * @param int $oferta El oferta del producto a editar.
     * @param DateTime $fecha La fecha del producto a editar.
     * 
     * @return void
     */
    public function EditProduct($id, $categoria_id, $nombre, $descripcion, $precio, $stock, $oferta, $fecha): void
    {
        $this->productRepository->EditProduct($id, $categoria_id, $nombre, $descripcion, $precio, $stock, $oferta, $fecha);
    }

    /**
     * Editar la imagen de un producto.
     *
     * Este método se encarga de conectar el controlador con el repositorio para editar la imagen de un producto
     * en la base de datos.
     * 
     * @param int $id El id del producto a editar.
     * @param string $imagen La imagen del producto a editar.
     * 
     * @return void
     */
    public function EditImage($id, $imagen): void
    {
        $this->productRepository->EditImage($id, $imagen);
    }

    /**
     * Mostrar productos aleatorios.
     *
     * Este método se encarga de conectar el controlador con el repositorio para mostrar productos aleatorios
     * en la base de datos.
     * 
     * @return array Devuelve una lista de productos aleatorios.
     */
    public function ShowRandomProducts(): array
    {
        return $this->productRepository->ShowRandomProducts();
    }
    
    /**
     * Mostrar el catalogo de productos.
     *
     * Este método se encarga de conectar el controlador con el repositorio para mostrar el catalogo de productos
     * en la base de datos.
     * 
     * @param int $limit El número máximo de productos a mostrar.
     * @param int $offset El número de productos a omitir.
     * 
     * @return array Devuelve una lista de productos del catalogo.
     */
    public function SeeCatalog()
    {
        return $this->productRepository->SeeCatalog();
    }

    /**
     * Obtener un producto por su id.
     *
     * Este método se encarga de conectar el controlador con el repositorio para obtener un producto por su id
     * en la base de datos.
     * 
     * @param int $id El id del producto a obtener.
     * 
     * @return Product|null Devuelve el producto con el id especificado, o null si no se encuentra ningún producto con ese id.
     */
    public function GetProduct(int $id): ?Product
    {
        return $this->productRepository->GetProduct($id);
    }

    /**
     * Obtiene todos los productos de una categoría.
     *
     * Este método se encarga de obtener todos los productos de una categoría, incluyendo su nombre, descripción, precio, stock, oferta, fecha de creación y imagen.
     * 
     * @param int $id El id de la categoría.
     * 
     * @return array Devuelve una lista de productos de la categoría especificada.
     */
    public function GetProductsByCategory(int $id): array
    {
        return $this->productRepository->GetProductsByCategory($id);
    }

    /**
     * Obtiene el stock disponible de un producto.
     *
     * Este método se encarga de obtener el stock disponible de un producto, incluyendo su nombre, descripción, precio, stock, oferta, fecha de creación y imagen.
     * 
     * @param int $id El id del producto.
     * 
     * @return int Devuelve el stock disponible del producto especificado.
     */
    public function GetStockDisponible(int $id): int
    {
        return $this->productRepository->GetStockDisponible($id);
    }

    /**
     * Actualiza el stock de un producto.
     *
     * Este método se encarga de actualizar el stock de un producto, incluyendo su nombre, descripción, precio, stock, oferta, fecha de creación y imagen.
     * 
     * @param int $id El id del producto.
     * @param int $quantity El nuevo stock del producto.
     * 
     * @return void
     */
    public function updateProductStock(int $id, int $quantity): void
    {
        $this->productRepository->updateProductStock($id, $quantity);
    }
}