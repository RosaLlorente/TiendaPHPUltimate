<?php
namespace Lib;

class Utils {
    /**
     * Obtiene los totales del carrito.
     *
     * Este método se encarga de obtener los totales del carrito, incluyendo el total de productos, precio y oferta.
     * 
     * @return array Devuelve un array con los totales del carrito.
     */
    public static function getCartTotals(): array 
    {
        $totalItems = 0;
        $totalPrice = 0;
        $totalOffer = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) 
        {
            foreach ($_SESSION['cart'] as $item) 
            {
                $subtotal = $item['price'] * $item['quantity'];

                // Verificar si el producto tiene un descuento
                if (isset($item['offer']) && $item['offer'] > 0) 
                {
                    $descuento = ($subtotal * ($item['offer'] / 100)); 
                    $totalOffer += $descuento;
                    $subtotal -= $descuento;
                }

                $totalItems += $item['quantity'];
                $totalPrice += $subtotal;
            }
        }

        return [
            'totalItems' => $totalItems,
            'totalPrice' => $totalPrice,
            'totalOffer' => $totalOffer 
        ];
    }
}
