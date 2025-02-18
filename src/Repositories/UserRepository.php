<?php

namespace Repositories;

use Lib\DataBase;
use Models\User;
use PDOException;
use PDO;

class UserRepository{
    //PROPIEDADES
    private DataBase $db;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->db = new DataBase;
    }

    //METODOS
    /**
     * Insertar nuevo usuario en la base de datos.
     *
     * Este método se encarga de conectar con la base de datos e interactua con ella para realizar la insercción de un nuevo usuario.
     * 
     * @param User $user El objeto User que contiene los datos del usuario a crear.
     * 
     * @return bool Devuelve true si la ejecución ha sido exitosa,devuelve false si no ha sido exitosa.
     */
    public function create(User $user) : bool
    {
        try{
            //comprobar si el email ya está registrado
            $sql = $this->db->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
            $sql->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $sql->execute();

            if ($sql->fetchColumn() > 0) {
                $_SESSION['errores'] = ['El email ya está registrado.'];
                return false;
            }

            //registro del usuario
            $sql = $this->db->prepare('INSERT INTO usuarios (nombre,apellidos,email,password,rol) VALUES (:nombre, :apellidos, :email, :password, :rol)');
            $sql->bindValue(':nombre', $user->getNombre(),PDO::PARAM_STR);
            $sql->bindValue(':apellidos', $user->getApellidos(),PDO::PARAM_STR);
            $sql->bindValue(':email', $user->getEmail(),PDO::PARAM_STR);
            $sql->bindValue(':password', $user->getPassword(),PDO::PARAM_STR);
            $sql->bindValue(':rol', $user->getRole(),PDO::PARAM_STR);

            $sql->execute();
            return true;
        }
        catch(PDOException $err)
        {
            error_log("Error al crear el usuario:" . $err->getMessage());
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
     * Buscar usuario en la base de datos.
     *
     * Este método se encarga de conectar con la base de datos y realizar una consulta
     * para extraer los datos de un usuario,luego verificara si los datos coinciden con los anteriores
     * que introdujo el usuario en el formulario.
     * 
     * @param string $email El email del usuario.
     * @param string $password La contraseña del usuario.
     * @return array|null Devuelve el email y nombre del usuario especificado, o null si no se encuentra ningún usuario con ese id.
     */
    public function login(string $email, string $password): ?array
    {
        try {
            $sql = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email');
            $sql->bindValue(':email', $email, PDO::PARAM_STR);
            $sql->execute();
    
            $user = $sql->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            } 
            else 
            {
                return null;
            }
        } 
        catch(PDOException $err) 
        {
            error_log("Error al buscar el email:" . $err->getMessage());
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
     * Obtiene el email y nombre de un usuario.
     * 
     * @param int $id El id del usuario.
     * @return array|null Devuelve el email y nombre del usuario especificado, o null si no se encuentra ningún usuario con ese id.
     * 
     */
    public function getUserById(int $id): ?array
    {
        try 
        {
            $sql = $this->db->prepare('SELECT email,nombre FROM usuarios WHERE id = :id');
            $sql->bindValue(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
    
            return $result ? $result : null;
        } 
        catch(PDOException $err) 
        {
            error_log("Error al buscar el email:" . $err->getMessage());
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
}    