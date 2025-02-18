<?php
namespace Controllers;

use Lib\Pages;
use Models\User;
use Services\UserService;

class UserController{
    //PROPIEDADES
    private Pages $page;
    private UserService $userservice;

    //CONSTRUCTOR
    function __construct(){
        $this->page = new Pages();
        $this->userservice = new UserService();
    }

    //METODOS
    /**
     * Registra al usuario en la base de datos.
     *
     * Este método maneja la solicitud de registro de usuario. Si la solicitud es de tipo POST y contiene datos,
     * intenta registrar al usuario en la base de datos después de validar y encriptar la contraseña. 
     * Si el registro es exitoso, redirige al usuario a la página principal. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de registro nuevamente con los errores.
     *
     * @return void
     */
    public function Register() : void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($_POST['data'])
            {
                $User = User::fromArray($_POST['data']);
                $User->sanitate();
                if($User->ValidateRegister())
                {
                    $password = password_hash($User->getpassword(), PASSWORD_BCRYPT,['cost'=>5]);
                    $User->setpassword($password);
                    try{
                        $registerSuccess = $this->userservice->RegisterUser($User);
                        if (!$registerSuccess)
                        {
                            $_SESSION['register'] = 'Complete';
                            unset($_SESSION['errores']);
                            $this->page->render('User/RegisterSuccessful');
                        } 
                        else 
                        {
                            $_SESSION['register'] = 'Fail';
                            $error = 'El email ya está registrado.';
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                            $this->page->render('User/registerForm');
                        }
                    }
                    catch(\Exception $e)
                    {
                        $_SESSION['register'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                }
                else
                {
                    $_SESSION['register'] = 'Fail';
                    $_SESSION['errores'] = User::getErrores();
                    $_SESSION['old_data'] = $_POST['data'];
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error){
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->page->render('User/RegisterForm');
                }
            }
            else
            {
                $_SESSION['register'] = 'Fail';
            }
        }
        else
        {
            $this->page->render('User/RegisterForm');
        }
    }

    /**
     * Logear al usuario en la base de datos.
     *
     * Este método maneja la solicitud de login de usuario. Si la solicitud es de tipo POST y contiene datos,
     * intenta logear al usuario en la base de datos después de comprobar que los datos sean correctos y coincidan en la base de datos. 
     * Si el login es exitoso, redirige al usuario a la página principal donde se muestran sus mensajes. En caso de error, almacena los 
     * mensajes de error en la sesión y renderiza la página de login nuevamente con los errores.
     *
     * @return void
     */
    public function Login() : void
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (isset($_POST['data']) && !empty($_POST['data']['email']) && !empty($_POST['data']['password'])) 
            {
                $User = User::fromArray($_POST['data']);
                $User->sanitate();

                if ($User->ValidateLogin()) 
                {
                    try{
                        $loginSuccess = $this->userservice->LoginUser($User);
                        if ($loginSuccess) 
                        {
                            $_SESSION['login'] = 'Complete';
                            $this->page->render('User/LoginSuccessful'); 
                        } 
                        else 
                        {
                            $_SESSION['login'] = 'Fail';
                            $error = 'El email o la contraseña son incorrectos.';
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                            $this->page->render('User/LoginForm');
                        }
                    }
                    catch(\Exception $e)
                    {
                        $_SESSION['login'] = 'Fail';
                        $_SESSION['errores'] = $e->getMessage();
                    }
                } 
                else 
                {
                    $_SESSION['login'] = 'Fail';
                    $_SESSION['errores'] = User::getErrores();
                    $_SESSION['old_data'] = $_POST['data'];
                    if (isset($_SESSION['errores']) && is_array($_SESSION['errores']))
                    {
                        foreach ($_SESSION['errores'] as $error){
                            echo '<p style="color:red">*'.htmlspecialchars($error).'</p>';
                        }
                    }
                    $this->page->render('User/LoginForm');
                }
            } 
            else 
            {
                $_SESSION['login'] = 'Fail';
                $this->page->render('User/LoginForm');
            }
        }
        else
        {
            $this->page->render('User/LoginForm');
        }
    }

    /**
     * Cerrar sesión al usuario.
     *
     * Este método maneja la solicitud de cerrar sesión de usuario.Tras hacerlo se redirigirá a la páginia
     * principal(login).
     *
     * @return void
     */
    public function Logout() : void
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL); 
        exit;
    }
}