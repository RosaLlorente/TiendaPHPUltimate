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
     * @param User $User El objeto User que contiene los datos del usuario a crear.
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
     * Obtiene el email y el nombre de un usuario.
     *
     * Este método se encarga de obtener el email y el nombre de un usuario.
     * 
     * @param int $id El id del usuario.
     * 
     * @return array El email y el nombre del usuario.
     */
    public function getUserById(int $id): ?array
    {
        return $this->userRepository->getUserById($id);
    }
}