<?php
namespace Models;

use DateTime;

class Order{
    //PROPIEDADES
    protected static array $errores = [];

    //CONSTRUCTOR
    public function __construct(
        private int|null $id,
        private int $usuario_id,
        private string $provincia,
        private string $localidad,
        private string $direccion,
        private float $coste,
        private string $estado,
        private DateTime $fecha,
        private string  $hora,
    ) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->provincia = $provincia;
        $this->localidad = $localidad;
        $this->direccion = $direccion;
        $this->coste = $coste;
        $this->estado = 'confirmado';
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    //SETTERS
    public function setId(int|null $id): void
    {
        $this->id = $id;
    }
    public function setUsuarioId(int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }
    public function setProvincia(string $provincia): void
    {
        $this->provincia = $provincia;
    }
    public function setLocalidad(string $localidad): void
    {
        $this->localidad = $localidad;
    }
    public function setDireccion(string $direccion): void
    {
        $this->direccion = $direccion;
    }
    public function setCoste(float $coste): void
    {
        $this->coste = $coste;
    }
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }
    public function setFecha(DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }
    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }

    //GETTERS
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }
    public function getProvincia(): string
    {
        return $this->provincia;
    }
    public function getLocalidad(): string
    {
        return $this->localidad;
    }
    public function getDireccion(): string
    {
        return $this->direccion;
    }
    public function getCoste(): float
    {
        return $this->coste;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getFecha(): DateTime
    {
        return $this->fecha;
    }
    public function getHora(): string
    {
        return $this->hora;
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
     * Valida la información del pedido.
     *
     * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
     */
    public function ValidateOrder(): bool
    {
        //Validacion usuario_id
        self::$errores = [];
        if(empty($this->usuario_id))
        {
            self::$errores[] = 'El usuario es obligatorio';
        }
        else
        {
            $patron = '/^[0-9]+$/';
            if(!preg_match($patron,$this->usuario_id))
            {
                self::$errores[] = 'El usuario no es valido'; 
            }
        }

        //Validacion provincia
        if(empty($this->provincia))
        {
            self::$errores[] = 'La provincia es obligatoria';
        }
        elseif (!preg_match('/^[a-zA-Z, ]*$/',$this->provincia))
        {
            self::$errores[] = 'La provincia no es valido';
        }

        //Validacion localidad
        if(empty($this->localidad))
        {
            self::$errores[] = 'La localidad es obligatoria';
        }
        elseif (!preg_match('/^[a-zA-Z, ]*$/',$this->localidad))
        {
            self::$errores[] = 'La localidad no es valido';
        }

        //Validacion direccion
        if(empty($this->direccion))
        {
            self::$errores[] = 'La direccion es obligatoria';
        }
        elseif (!preg_match('/^[a-zA-Z, ]*$/',$this->direccion))
        {
            self::$errores[] = 'La direccion no es valido';
        }

        //Validacion coste
        if(empty($this->coste))
        {
            self::$errores[] = 'El coste es obligatorio';
        }
        elseif (!is_numeric($this->coste) || $this->coste <= 0)
        {
            self::$errores[] = 'El coste no es válido'; 
        }

        //Validacion estado
        if(empty($this->estado))
        {
            self::$errores[] = 'El estado es obligatorio';
        }
        elseif (!preg_match('/^[a-zA-Z, ]*$/',$this->estado))
        {
            self::$errores[] = 'El estado no es valido';
        }

        // Validación fecha
        if (!$this->fecha instanceof DateTime) 
        {
            self::$errores[] = 'La fecha es inválida';
        } 
        else 
        {
            $hoy = new DateTime();
            if ($this->fecha > $hoy) 
            {
                self::$errores[] = 'La fecha no puede ser una fecha futura';
            }
        }

        //Validacion hora
        if(empty($this->hora))
        {
            self::$errores[] = 'La hora es obligatoria';
        }
        else
        {
            $patron = '/^[0-9]{2}:[0-9]{2}$/';
            if(!preg_match($patron,$this->hora))
            {
                self::$errores[] = 'La hora no es valido'; 
            }       
        }
        return self::$errores ? false : true;
    }

    /**
     * Sanea los datos del pedido.
    *
    * Este método se encarga de limpiar y validar los datos del pedido
    * para asegurar que no contengan caracteres o valores no deseados.
    *
    * @return void
    */
    public function sanitate(): void
    {
        $this->usuario_id = filter_var($this->usuario_id, FILTER_SANITIZE_NUMBER_INT);
        $this->provincia = filter_var($this->provincia, FILTER_SANITIZE_STRING);
        $this->localidad = filter_var($this->localidad, FILTER_SANITIZE_STRING);
        $this->direccion = filter_var($this->direccion, FILTER_SANITIZE_STRING);
        $this->coste = filter_var($this->coste, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->estado = filter_var($this->estado, FILTER_SANITIZE_STRING);
        $this->hora = filter_var($this->hora, FILTER_SANITIZE_STRING);
    }

    /**
     * Crea una instancia de Order a partir de un array de datos.
    *
    * @param array $data Los datos para crear la instancia de Order.
    * @return Order La instancia de Order creada.
    */
    public  static function fromArray(array $data): Order
    {
        return new Order(
            id: $data['id'] ?? null,
            usuario_id: $data['usuario_id'] ?? 0,
            provincia: $data['provincia'] ?? '',
            localidad: $data['localidad'] ?? '',
            direccion: $data['direccion'] ?? '',
            coste: $data['coste'] ?? 0.0,
            estado: $data['estado'] ?? '',
            fecha: isset($data['fecha']) ? new DateTime($data['fecha']) : new DateTime(),
            hora: !empty($data['hora']) ? $data['hora'] : date('H:i')
        );
    }
    

}