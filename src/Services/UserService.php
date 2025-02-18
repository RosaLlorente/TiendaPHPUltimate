<?php
namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService{
    //PROPIEDADES
    private UserRepository $userRepository;

    //CONSTRUCTOR
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    //METODOS
    /**
     * Conexión con repositorio para registrar usuario.
     *
     * Este método se encarga de conectar el controlador con el repositorio,para acceder a la base de datos.
     * 
     * @param user los datos anteriormente introducidos por el usuario
     * 
     * @return User Devuelve los datos anteriormente introducidos por el usuario
     */
    public function RegisterUser(User $user): void
    {
        $this->userRepository->create($user);
    }

    /**
     * Conexión con repositorio para loguear usuario.
     *
     * Este método se encarga de conectar el controlador con el repositorio,para acceder a la base de datos.
     * 
     * @return User Devuelve los datos anteriormente introducidos por el usuario
     */
    public function LoginUser($User): bool
    {
        $email = $User->getEmail();
        $password = $User->getPassword();
        $userData = $this->userRepository->login($email, $password);
        if ($userData) 
        {
            if (password_verify($password, $userData['password'])) 
            {
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['role'] = $userData['rol']; 
                $_SESSION['user_name'] = $userData['nombre'];
                return true;
            } 
            else 
            {
                return false;  
            }
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Obtiene el email de un usuario.
     *
     * Este método se encarga de obtener el email de un usuario, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param int $id El id del usuario.
     * 
     * @return string|null Devuelve el email del usuario especificado, o null si no se encuentra ningún usuario con ese id.
     */
    public function getUserById(int $id): string
    {
        return $this->userRepository->getUserById($id);
    }
}