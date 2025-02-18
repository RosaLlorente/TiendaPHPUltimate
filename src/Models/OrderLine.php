<?php
namespace Models;

class OrderLine{
    //PROPIEDADES
    protected static array $errores = [];

    //CONSTRUCTOR
    public function __construct(
        private int|null $id,
        private int $pedido_id,
        private int $producto_id,
        private int $unidades,
    )
    {
        $this->id = $id;
        $this->pedido_id = $pedido_id;
        $this->producto_id = $producto_id;
        $this->unidades = $unidades;
    }
    
    //SETTERS
    public function setId(int|null $id): void
    {
        $this->id = $id;
    }
    public function setPedidoId(int $pedido_id): void
    {
        $this->pedido_id = $pedido_id;
    }
    public function setProductoId(int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }
    public function setUnidades(int $unidades): void
    {
        $this->unidades = $unidades;
    }

    //GETTERS
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getPedidoId(): int
    {
        return $this->pedido_id;
    }
    public function getProductoId(): int
    {
        return $this->producto_id;
    }
    public function getUnidades(): int
    {
        return $this->unidades;
    }

    //METODOS
    /**
     * Obtiene una lista de errores.
     *
     * @return array Lista de errores.
     */
    public static function getErrores(): array
    {
        return self::$errores;
    }

    /**
     * Obtiene una lista de campos con errores.
     *
     * @return array Lista de campos con errores.
     */
    public static function getErroresCampos(): array 
    {
        return array_keys(self::$errores);
    }

    /**
     * Establece los errores en el sistema.
     *
     * @param array $errores Lista de errores a establecer.
     * @return void
     */
    public static function SetErrores(array $errores): void
    {
        self::$errores = $errores;
    }

    /**
     * Valida la información del  la linea del pedido.
     *
     * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
     */
    public function ValidateOrderLine(): bool
    {
        self::$errores = [];

        //Validacion pedido_id
        if(empty($this->pedido_id))
        {
            self::$errores[] = 'El usuario es obligatorio';
        }
        else
        {
            $patron = '/^[0-9]+$/';
            if(!preg_match($patron,$this->pedido_id))
            {
                self::$errores[] = 'El usuario no es valido'; 
            }
        }

        //Validacion producto_id
        if(empty($this->producto_id))
        {
            self::$errores[] = 'El producto es obligatorio';
        }
        else
        {
            $patron = '/^[0-9]+$/';
            if(!preg_match($patron,$this->producto_id))
            {
                self::$errores[] = 'El producto no es valido'; 
            }
        }

        //Validacion unidades
        if(empty($this->unidades))
        {
            self::$errores[] = 'La unidades es obligatoria';
        }
        else
        {
            $patron = '/^[0-9]+$/';
            if(!preg_match($patron,$this->unidades))
            {
                self::$errores[] = 'La unidades no es valido'; 
            }
        }
        return self::$errores ? false : true;
    }

    /**
     * Sanea los datos del la linea del pedido.
    *
    * Este método se encarga de limpiar y validar los datos del  la linea del pedido
    * para asegurar que no contengan caracteres o valores no deseados.
    *
    * @return void
    */
    public function sanitateOrderLines(): void
    {
        $this->pedido_id = filter_var($this->pedido_id, FILTER_SANITIZE_NUMBER_INT);
        $this->producto_id = filter_var($this->producto_id, FILTER_SANITIZE_NUMBER_INT);
        $this->unidades = filter_var($this->unidades, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Crea una instancia de OrderLines a partir de un array de datos.
     *
     * @param array $data Los datos para crear la instancia de OrderLines.
     * @return OrderLine La instancia de OrderLines creada.
     */
    public static function fromArray(array $data): OrderLine
    {
        return new OrderLine(
            id: $data['id'] ?? null,
            pedido_id: $data['pedido_id'] ?? 0,
            producto_id: $data['producto_id'] ?? 0,
            unidades: $data['unidades'] ?? 0
        );
    }
}