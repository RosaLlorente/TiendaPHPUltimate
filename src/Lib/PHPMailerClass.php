<?php
namespace Lib;

use Dotenv\Util\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Stringable;

class PHPMailerClass
{
    /**
     * Envía un correo de confirmación al usuario.
     *
     * Este método se encarga de enviar un correo de confirmación al usuario, utilizando la información proporcionada.
     * 
     * @param string $correo La dirección de correo electrónico del usuario.
     * @return void
     */
    public function enviarCorreoConfirmacion(string $correo)
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

            // Destinatario
            $mail->setFrom('TiendaPhp@mailtrap.io', 'TiendaPHP'); 
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de tu pedido';
            
            $mensaje = "<h2>Gracias por su compra!</h2>";
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
}