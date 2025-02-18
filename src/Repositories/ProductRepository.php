<?php
namespace Repositories;

use Lib\DataBase;
use Models\Product;
use PDOException;
use PDO;
use Exception;

use Zebra_Pagination;

class ProductRepository{
    //PROPIEDADES
    private DataBase $db;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DataBase;
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
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function CreateProduct(Product $product): bool
    {
        try{
            $sql = $this->db->prepare('SELECT COUNT(*) FROM categorias WHERE id = :categoria_id');
            $sql->bindValue(':categoria_id', $product->getCategoriaId(), PDO::PARAM_INT);
            $sql->execute();
            $count = $sql->fetchColumn();

            if ($count == 0) 
            {
                throw new Exception("La categoría no existe.");
            }

            $sql = $this->db->prepare('INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) VALUES (:categoria_id, :nombre, :descripcion, :precio, :stock, :oferta, :fecha, :imagen)');
            $sql->bindValue(':categoria_id', $product->getCategoriaId(),PDO::PARAM_INT);
            $sql->bindValue(':nombre', $product->getNombre(),PDO::PARAM_STR);
            $sql->bindValue(':descripcion', $product->getDescripcion(),PDO::PARAM_STR);
            $sql->bindValue(':precio', $product->getPrecio(),PDO::PARAM_STR);
            $sql->bindValue(':stock', $product->getStock(),PDO::PARAM_INT);
            $sql->bindValue(':oferta', $product->getOferta(),PDO::PARAM_INT);
            $sql->bindValue(':fecha', $product->getFecha()->format('Y-m-d'),PDO::PARAM_STR);
            $sql->bindValue(':imagen', $product->getImagen(),PDO::PARAM_STR);
            $sql->execute();
            return true;
        }
        catch(PDOException $err)
        {
            error_log("Error al crear el producto: " . $err->getMessage());
            return false;
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
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
        try{
            $sql = $this->db->prepare('SELECT * FROM productos');
            $sql->execute();
            $products = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
        catch(PDOException $err)
        {
            error_log("Error al obtener productos: " . $err->getMessage());
            return [];
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
    }

    /**
     * Eliminar un producto.
     *
     * Este método se encarga de conectar el controlador con el repositorio para eliminar un producto
     * en la base de datos.
     * 
     * @param int $id El id del producto a eliminar.
     * 
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function DeleteProduct(int $id): bool
    {
        try {
            $deleteQuery = $this->db->prepare('DELETE FROM productos WHERE id = :id');
            $deleteQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteQuery->execute();

            return true;
        } 
        catch(PDOException $err) 
        {
            error_log("Error al eliminar el producto: " . $err->getMessage());
            return false;
        } 
        finally {
            if (isset($deleteQuery)) {
                $deleteQuery->closeCursor();
            }
        }
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
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function EditProduct($id, $categoria_id, $nombre, $descripcion, $precio, $stock, $oferta, $fecha)
    {
        try {
            // Construir la consulta UPDATE
            $sql = $this->db->prepare("UPDATE productos SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, oferta = :oferta, fecha = :fecha WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
            $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $sql->bindParam(':precio', $precio, PDO::PARAM_STR);
            $sql->bindParam(':stock', $stock, PDO::PARAM_INT);
            $sql->bindParam(':oferta', $oferta, PDO::PARAM_INT);
            $sql->bindValue(':fecha', $fecha->format('Y-m-d'), PDO::PARAM_STR);
            $sql->execute();
            return true;
        } 
        catch (PDOException $err) {
            error_log("Error al actualizar el producto: " . $err->getMessage());
            return false;
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
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
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function EditImage($id, $imagen): bool
    {
        try {
            $sql = $this->db->prepare("UPDATE productos SET imagen = :imagen WHERE id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            $sql->execute();
            return true;
        } 
        catch (PDOException $err) {
            error_log("Error al actualizar la imagen del producto: " . $err->getMessage());
            return false;
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
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
        try {
            // Seleccionar 3 productos aleatorios
            $sql = $this->db->prepare('SELECT * FROM productos ORDER BY RAND() LIMIT 3');
            $sql->execute();
            $products = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        } 
        catch (PDOException $err) 
        {
            error_log("Error al obtener productos aleatorios: " . $err->getMessage());
            return [];
        } 
        finally 
        {
            if (isset($sql)) {
                $sql->closeCursor();
            }
        }
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
    public function SeeCatalog(): array
    {
        try {
            $sql = $this->db->prepare('SELECT * FROM productos WHERE categoria_id');
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } 
        catch (PDOException $err) 
        {
            error_log("Error al obtener productos: " . $err->getMessage());
            return [];
        } 
        finally 
        {
            if (isset($sql)) 
            {
                $sql->closeCursor();
            }
        }
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
    public function GetProduct(int $id): ? Product
    {
        try{
            $sql = $this->db->prepare('SELECT * FROM productos WHERE id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $productData = $sql->fetch(PDO::FETCH_ASSOC);
                if (!$productData) 
                {
                    return null;
                }
                $product = Product::fromArray($productData);
                return $product;
        }
        catch(PDOException $err)
        {
            error_log("Error al obtener el producto: " . $err->getMessage());
            return null;
        }
        finally
        {
            if(isset($sql))
            {
                $sql->closeCursor();
            }
        }
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
    public function GetProductsByCategory(int $id) : array
    {
        try 
        {
            $sql = $this->db->prepare('SELECT * FROM productos WHERE categoria_id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute([':id' => $id]);
            return $sql->fetchAll(PDO::FETCH_ASSOC); 
        } 
        catch(PDOException $err) 
        {
            error_log("Error al obtener los productos: " . $err->getMessage());
            return null;
        } 
        finally 
        {
            if(isset($sql)) 
            {
                $sql->closeCursor();
            }
        }
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
    public function GetStockDisponible($id): int
    {
        try 
        {
            $sql = $this->db->prepare('SELECT stock FROM productos WHERE id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            
            $stok = $sql->fetchColumn();

            if($stok === false)
            {
                return 0;
            }
            else
            {
                return (int) $stok;
            }
        }
        catch(PDOException $err) 
        {
            error_log("Error al obtener el stock: " . $err->getMessage());
            return 0;
        }
        finally 
        {
            if (isset($sql)) 
            {
                $sql->closeCursor();
            }
        }
    }

    /**
     * Actualiza el stock de un producto.
     *
     * Este método se encarga de actualizar el stock de un producto, incluyendo su nombre, descripción, precio, stock, oferta, fecha de creación y imagen.
     * 
     * @param int $id El id del producto.
     * @param int $quantity El nuevo stock del producto.
     * 
     * @return bool Devuelve true si la actualización fue exitosa, de lo contrario false.
     */
    public function updateProductStock($id, $quantity)
    {
        try {
            // Verificar el stock actual antes de actualizar
            $sql = $this->db->prepare('SELECT stock FROM productos WHERE id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $stockActual = (int)$sql->fetchColumn();
            
            // Verificar si hay suficiente stock antes de descontar
            if ($quantity > 0 && $stockActual <= 0) 
            {
                error_log("Error: Stock insuficiente para el producto (ID: $id)");
                return false;
            }

            // Calcular nuevo stock asegurando que no sea negativo
            $nuevoStock = max(0, $stockActual - $quantity);
            // Actualizar el stock
            $sql = $this->db->prepare('UPDATE productos SET stock = :nuevoStock WHERE id = :id');
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':nuevoStock', $nuevoStock, PDO::PARAM_INT);
            $sql->execute();
            return true; 
        } 
        catch (PDOException $err) 
        {
            error_log("Error al actualizar el stock: " . $err->getMessage());
            return false;
        } 
        finally 
        {
            if (isset($sqlCheck)) 
            {
                $sqlCheck->closeCursor();
            }
            if (isset($sqlUpdate)) 
            {
                $sqlUpdate->closeCursor();
            }
        }
    }

}