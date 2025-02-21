<?php
namespace Controllers;

use Dotenv\Util\Str;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayController
{
    private $client;

    public function __construct() {
        $clientId = $_ENV['CLIENT_ID']; 
        $clientSecret = $_ENV['SECRET_KEY'];  
        $environment = $_ENV['PAYPAL_MODE'];

        // Configurar el entorno de pruebas (Sandbox)
        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }
    
}