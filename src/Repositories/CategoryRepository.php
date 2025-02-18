<?php
namespace Repositories;

use Lib\DataBase;
use Models\Category;
use PDOException;
use PDO;

class CategoryRepository{
     //PROPIEDADES
    private DataBase $db;

     //CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DataBase;
    }

     //METODOS
    /**
     * Crea una nueva categoría en la base de datos.
     *
     * @param Category $category El objeto categoría que se va a crear.
     * @return bool Devuelve true si la categoría se creó correctamente, false en caso contrario.
     * @throws PDOException Si ocurre un error al interactuar con la base de datos.
     */
    public function CreateCategory(Category $category) : bool
    {
        try{
            $sql = $this->db->prepare('INSERT INTO categorias (nombre) VALUES (:nombre)');
            $sql->bindValue(':nombre', $category->getNombre(),PDO::PARAM_STR);
            $sql->execute();
            return true;
        }
        catch(PDOException $err)
        {
            error_log("Error al crear la categoria:" . $err->getMessage());
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
     * Listar todas las categorías.
     *
     * Este método se encarga de conectar el controlador con el repositorio para obtener todas las categorías
     * en la base de datos.
     * 
     * @return array Devuelve una lista de categorías.
     */
    public function ListCategories(): array
    {
        try{
            $sql = $this->db->prepare('SELECT * FROM categorias');
            $sql->execute();
            $categories = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $categories;
        }
        catch(PDOException $err)
        {
            error_log("Error al obtener categorías: " . $err->getMessage());
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
     * Eliminar una categoría.
     *
     * Este método se encarga de conectar el controlador con el repositorio para eliminar una categoría
     * en la base de datos.
     * 
     * @param int $id El id de la categoría a eliminar.
     * 
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function DeleteCategory(int $id): bool
    {
        try {
            // Paso 1: Obtener el nombre de la categoría antes de eliminarla
            $selectQuery = $this->db->prepare('SELECT nombre FROM categorias WHERE id = :id');
            $selectQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $selectQuery->execute();
            $category = $selectQuery->fetch(PDO::FETCH_ASSOC);

            // Paso 2: Mover todos los productos de la categoría a la nueva categoría
            $updateQuery = $this->db->prepare('UPDATE productos SET categoria_id = :newCategoryId WHERE categoria_id = :id');
            $updateQuery->bindValue(':newCategoryId',1, PDO::PARAM_INT);
            $updateQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $updateQuery->execute();

            // Paso 3: Eliminar la categoría original
            $deleteQuery = $this->db->prepare('DELETE FROM categorias WHERE id = :id');
            $deleteQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteQuery->execute();

            return true;
        } 
        catch (PDOException $err) 
        {
            error_log("Error al eliminar la categoría: " . $err->getMessage());
            return false;
        } 
        finally 
        {
            if (isset($selectQuery)) 
            {
                $selectQuery->closeCursor();
            }
            if (isset($updateQuery)) 
            {
                $updateQuery->closeCursor();
            }
            if (isset($deleteQuery)) 
            {
                $deleteQuery->closeCursor();
            }
        }
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
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function EditCategory($nombre,$id): bool
    {
        try{
            $sql = $this->db->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
            $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            return true;
        }
        catch(PDOException $err)
        {
            error_log("Error al actualizar la categoría: " . $err->getMessage());
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
        try
        {
            $sql = $this->db->prepare("SELECT nombre FROM categorias WHERE id = :id");
            $sql->bindParam(':id', $id);
            $sql->execute();
            $result = $sql->fetch();
            return $result['nombre'];
        }
        catch(\Exception $e)
        {
            error_log("Error al obtener la categoría: " . $e->getMessage());
            return 'Error al obtener la categoría';
        }
        finally 
        {
            if(isset($sql)) 
            {
                $sql->closeCursor();
            }
        }
    }
}