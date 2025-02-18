<?php

namespace Lib;

use Exception;
use Models\Order;
use Models\OrderLine;
use Services\OrderLineService;
use TCPDF;
use TCPDF_FONTS;

class PDF
{
    //PROPIEDADES
    private $tcpdf;
    private OrderLineService $orderLineService;

    //CONSTRUCTOR
    public function __construct()
    {

        $this->tcpdf = new TCPDF();
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(15, 15, 15);
        $this->tcpdf->SetAutoPageBreak(true, 20);
        $this->orderLineService = new OrderLineService();
    }

    //MÉTODOS
    /**
     * Generar un PDF del pedido.
     *
     * Este método se encarga de generar un PDF del pedido, incluyendo su nombre, apellidos, email, password, rol, fecha de creación y imagen.
     * 
     * @param string $nombre El nombre del usuario.
     * @param Order $order El objeto Order que contiene los datos del pedido.
     * @param array $Orderlines El array que contiene los datos de las líneas del pedido.
     * @return string Devuelve el nombre del archivo PDF generado.
     */
    public function generatePDF(string $nombre, Order $order, array $Orderlines): string
    {
        // Agregar una página al PDF
        $this->tcpdf->AddPage();
    
        $this->tcpdf->SetFont('helvetica', 'B', 16); 
        $this->tcpdf->SetTextColor(0, 0, 255); 
        $this->tcpdf->Cell(0, 10, 'Factura del pedido del usuario: ' . $nombre, 0, 1, 'C'); 
        $this->tcpdf->SetTextColor(0, 0, 0); 
        $this->tcpdf->SetFont('helvetica', '', 12); 
        $this->tcpdf->MultiCell(0, 10, "Este pedido se realizó el día: " . $order->getFecha()->format('d-m-Y'), 0, 'L'); 

        // Extraer los datos de las líneas del pedido
        $Orderlines = $this->orderLineService->getOrderLineById($order->getId());
    
        // Agregar una tabla para las líneas del pedido
        $this->tcpdf->SetFont('helvetica', 'B', 12);
        $this->tcpdf->Cell(80, 10, 'Producto', 1, 0, 'C');
        $this->tcpdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
        $this->tcpdf->Cell(30, 10, 'Precio', 1, 1, 'C');
    
        // Establecer fuente normal para los datos de la tabla
        $this->tcpdf->SetFont('helvetica', '', 12); 
        foreach ($Orderlines as $orderLine) 
        {
            $this->tcpdf->Cell(80, 10, $orderLine['producto_nombre'], 1, 0, 'L');
            $this->tcpdf->Cell(30, 10, $orderLine['unidades'], 1, 0, 'C');
            $this->tcpdf->Cell(30, 10, $orderLine['producto_precio'], 1, 1, 'C');
        }
    
        $this->tcpdf->Ln();
        $this->tcpdf->SetTextColor(0, 0, 255); 
        $this->tcpdf->Cell(0, 10, "El precio total del pedido es: " . $order->getCoste(), 0, 'L','I'); 
    
        $this->tcpdf->SetTextColor(0, 255, 0); 
        $this->tcpdf->Cell(0, 10, "El estado del pedido es: " . $order->getEstado(), 0, 'L','B');
    
        // Guardar el PDF en el servidor
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/TiendaPHP/public/PDF/';
        $outputPath = $carpetaDestino . $order->getId() . '.pdf';
        $this->tcpdf->Output($outputPath, 'F');
        return $outputPath;
    }
    

}