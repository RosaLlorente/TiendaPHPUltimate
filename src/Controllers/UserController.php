<?php
namespace Controllers;

use Lib\Pages;
use Lib\Security;
use Lib\PHPMailerClass;
use Models\User;
use Services\UserService;

class UserController{
    //PROPIEDADES
    private Pages $page;
    private Security $security;
    private PHPMailerClass $mailer;
    private UserService $userservice;


    //CONSTRUCTOR
    function __construct(){
        $this->page = new Pages();
        $this->security = new Security();
        $this->mailer = new PHPMailerClass();
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if (!isset($_POST['data'])) {
                $_SESSION['register'] = 'Fail';
                $this->page->render('User/RegisterForm');
                return;
            }

            $User = User::fromArray($_POST['data']);
            $User->sanitate(); // Corregido el nombre del método
            
            // Validar datos del usuario
            if (!$User->ValidateRegister()) {
                $_SESSION['register'] = 'Fail';
                $_SESSION['errores'] = User::getErrores();
                $_SESSION['old_data'] = $_POST['data'];
                $this->page->render('User/RegisterForm');
                return;
            }

            // Verificar si el correo ya está registrado
            if ($this->userservice->checkUserByEmail($User->getEmail())) {
                $_SESSION['register'] = 'Fail';
                $_SESSION['errores'] = ['El email ya está registrado.'];
                $this->page->render('User/RegisterForm');
                return;
            }

            // Cifrar contraseña
            $password = $this->security->encryptPassw($User->getPassword());
            $User->setPassword($password);

            try {
                // Crear el token de activación
                $secretKey = $this->security->getSecretKey();
                $token = $this->security->createToken($secretKey, ['email' => $User->getEmail()]);
                
                // Añadir el token y la fecha de expiración al usuario
                $User->setToken($token);
                $User->setTokenExpiration(time() + 3600); // Expira en una hora

                // Guardar el usuario en la base de datos
                if ($this->userservice->RegisterUser($User))
                {
                    // Enviar correo de verificación
                    $this->mailer->sendVerificationEmail($User->getEmail(), $token);
                    
                    $_SESSION['register'] = 'Complete';
                    unset($_SESSION['errores']);
                    $this->page->render('User/RegisterSuccessful');
                    return;
                }

                // Si hay un fallo en la base de datos
                throw new \Exception('Hubo un error al registrar el usuario.');

            } catch (\Exception $e) {
                $_SESSION['register'] = 'Fail';
                $_SESSION['errores'] = [$e->getMessage()];
                $this->page->render('User/RegisterForm');
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

    public function VerifyEmail()
    {die('llega');
        if (isset($_GET['token'])) 
        {die('llega');  
            try 
            {
                $token = $_GET['token'];
                $isActivated = $this->security->activateUserFromToken($token, $this->userservice);
                
                if ($isActivated) 
                {
                    $_SESSION['verification'] = 'complete';
                    header('Location: ' . BASE_URL . '/login');  
                    exit;
                } 
                else 
                {
                    $_SESSION['verification'] = 'fail';
                    $_SESSION['errores'] = ['Usuario no encontrado o token inválido.'];
                    header('Location: ' . BASE_URL . '/register');  
                    exit;
                }
            } 
            catch (\Exception $e) 
            {
                $_SESSION['verification'] = 'fail';
                $_SESSION['errores'] = ['Error al verificar el token: ' . $e->getMessage()];
                header('Location: ' . BASE_URL . '/register');
                exit;
            }
        } 
        else 
        {
            $_SESSION['verification'] = 'fail';
            $_SESSION['errores'] = ['No se ha proporcionado un token.'];
            header('Location: ' . BASE_URL . '/register');
            exit;
        }
    }


}