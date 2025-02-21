<?php
namespace Lib;

use Dotenv\Util\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Stringable;
use Models\Order;

class PHPMailerClass
{
    /**
     * Enviar un correo de confirmación del pedido.
     * 
     * Este método se encarga de enviar un correo de confirmación al usuario, utilizando la información proporcionada.
     * 
     * @param string $correo La dirección de correo electrónico del usuario a quien se le enviará el correo.
     * @param string $nombre El nombre del usuario.
     * @param Order $pedido El objeto Order que contiene los datos del pedido.
     * @param string $PDF El nombre del archivo PDF del pedido.
     * @return void
     */
    public function enviarCorreoConfirmacion(string $correo,string $nombre,Order $pedido,string $PDF): void
    {
        $mail = new PHPMailer(true);

        try 
        {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['MAIL_PORT'];;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Configuración UTF-8
            $mail->CharSet = 'UTF-8';

            // Destinatario
            $mail->setFrom('TiendaPhp@mailtrap.io', 'TiendaPHP'); 
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de tu pedido';
            
            $mensaje = "<h2>¡Gracias por su compra en TiendaPHP!</h2>";
            $mensaje .= "<p><b>Nombre de usuario:</b> " . htmlspecialchars($nombre) . "</p>";
            $mensaje .= "<p><b>ID del Pedido:</b> " . htmlspecialchars($pedido->getId()) . "</p>";
            $mensaje .= "<p><b>Provincia:</b> " . htmlspecialchars($pedido->getProvincia()) . "</p>";
            $mensaje .= "<p><b>Localidad:</b> " . htmlspecialchars($pedido->getLocalidad()) . "</p>";
            $mensaje .= "<p><b>Dirección:</b> " . htmlspecialchars($pedido->getDireccion()) . "</p>";
            $mensaje .= "<p><b>Coste Total:</b> " . number_format($pedido->getCoste(), 2, ',', '.') . " €</p>";
            $mensaje .= "<p><b>Estado:</b> " . htmlspecialchars($pedido->getEstado()) . "</p>";
            $mensaje .= "<p><b>Fecha:</b> " . htmlspecialchars($pedido->getFecha()->format('Y-m-d')) . "</p>";
            $mensaje .= "<p><b>Hora:</b> " . htmlspecialchars($pedido->getHora()) . "</p>";
        
            $mail->Body = $mensaje;

            // Agregar un archivo PDF al correo
            $mail->addAttachment($PDF, 'Pedido' . $pedido->getId() . '.pdf');

            // Enviar el correo
            $mail->send();
            $_SESSION['email'] = 'Succesful';
        } 
        catch (Exception $e) 
        {
            $_SESSION['email'] = 'Fail';
            $_SESSION['error'] = 'No se ha podido enviar el correo de confirmación.';
        }
    }

    /**
     * Enviar un correo de actualización del estado del pedido.
     *
     * Este método se encarga de enviar un correo informativo al usuario, para avisarle del nuevo estado del pedido.
     * 
     * @param string $correo La dirección de correo electrónico del usuario.
     * @param string $estado El estado del pedido.
     * @param string $nombre El nombre del usuario.
     * @return void
     */
    public function sendStatus(string $correo,string $estado,string $nombre): void
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['MAIL_PORT'];;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Configuración UTF-8
            $mail->CharSet = 'UTF-8';

            // Destinatario
            $mail->setFrom('TiendaPhp@mailtrap.io', 'TiendaPHP'); 
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Actualización de tu pedido';
            
            $mensaje = "<h2>¡Su pedido ha sido actualizado!</h2>";
            $mensaje .= "<p><b>Nombre de usuario:</b> " . htmlspecialchars($nombre) . "</p>";
            $mensaje .= "<p><b>Estado:</b> " . htmlspecialchars($estado) . "</p>";
            $mensaje .= "<p>Le recomendamos que revises el estado de su pedido en la sección de pedidos,para
            comprobar si los cambios se han aplicado correctamente.</p>";
            $mensaje .= "<p>Si tienes alguna duda, no dudes en contactarnos a través de nuestro correo.</p>";
            $mail->Body = $mensaje;

            // Enviar el correo
            $mail->send();
            $_SESSION['email'] = 'Succesful';
        } 
        catch (Exception $e) 
        {
            $_SESSION['email'] = 'Fail';
            $_SESSION['error'] = 'No se ha podido enviar el correo de confirmación.';
        }
    }

    public function sendVerificationEmail(string $correo, string $token): void
    {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['MAIL_PORT'];;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];   
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Configuración UTF-8
            $mail->CharSet = 'UTF-8';

            // Destinatario
            $mail->setFrom('TiendaPhp@mailtrap.io', 'TiendaPHP'); 
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta';
            
            $mensaje = "<h2>¡Usted debe verificar su correo electrónico!</h2>";
            $mensaje .= "<p><b>Le solicitamos a </b> " . htmlspecialchars($correo) . " que por favor confirme su cuenta.</p>";
            $mensaje .= "<p>Presiona aquí: <a href=" . BASE_URL ."User/verifyEmail?token=" . $token . "'>Confirmar cuenta</a></p>";
            echo BASE_URL . "User/verifyEmail?token=" . $token;
            $mensaje .= "<P>Si usted no ha solicitado esta acción, puede ignorar este correo.</p>";
            $mail->Body = $mensaje;
            // Enviar el correo
            $mail->send();
            $_SESSION['email'] = 'Succesful';
        } 
        catch (Exception $e) 
        {
            $_SESSION['email'] = 'Fail';
            $_SESSION['error'] = 'No se ha podido enviar el correo de verificación.';
        }
    }
}