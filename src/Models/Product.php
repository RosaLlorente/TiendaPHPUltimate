<?php
namespace Models;

use DateTime;

class Product{
     //PROPIEDADES
    protected static array $errores = [];

    //CONSTRUCTOR
    public function __construct(
        private int|null $id,
        private int $categoria_id,
        private string $nombre,
        private string $descripcion,
        private float $precio,
        private int $stock,
        private int $oferta,
        private DateTime $fecha,
        private string $imagen 
    ) {
        $this->id = $id;
        $this->categoria_id = $categoria_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->oferta = $oferta;
        $this->fecha = $fecha;
        $this->imagen = $imagen;
    }

    //SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCategoriaId(int  $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function setOferta(int $oferta): void
    {
        $this->oferta = $oferta;
    }

    public function setFecha(DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function setImagen(string $imagen): void
    {
        $this->imagen = $imagen;
    }

    //GETTERS
    public function getId(): int|null
    {
        return $this->id;
    }

    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getOferta(): int
    {
        return $this->oferta;
    }

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function getImagen(): string
    {
        return $this->imagen;
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
     * Valida la información del producto.
    *
    * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
    */
    public function ValidateCreateProduct(): bool
    {
        // Sanear los datos antes de validar
        self::$errores = [];

        //Validación campo categoria_id
        if(empty($this->categoria_id))
        {
            self::$errores[] = 'La categoria es obligatoria';
        }
        elseif (!filter_var($this->categoria_id, FILTER_VALIDATE_INT) || $this->categoria_id <= 0) 
        {
            self::$errores[] = 'La categoría debe ser un número entero positivo';
        }
        // Validación campo nombre
        if (empty($this->nombre)) 
        {
            self::$errores[] = 'El nombre es obligatorio';
        } 
        elseif (!preg_match('/^[\p{L} ,]+$/u', $this->nombre)) 
        {
            self::$errores[] = 'El nombre no es válido';
        }

        // Validación campo descripción
        if (empty($this->descripcion)) 
        {
            self::$errores[] = 'La descripción es obligatoria';
        } 
        elseif (strlen($this->descripcion) > 500) 
        {
            self::$errores[] = 'La descripción no puede tener más de 500 caracteres';
        }

        // Validación precio
        if (empty($this->precio)) 
        {
            self::$errores[] = 'El precio es obligatorio';
        } 
        elseif ($this->precio <= 0) 
        {
            self::$errores[] = 'El precio debe ser mayor a 0';
        }

        // Validación stock
        if (empty($this->stock)) 
        {
            self::$errores[] = 'El stock es obligatorio';
        } 
        if ($this->stock < 0) 
        {
            self::$errores[] = 'El stock no puede ser negativo';
        }

        // Validación oferta
        if ($this->oferta < -1 || $this->oferta > 100) 
        {
            self::$errores[] = 'La oferta debe estar entre 0 y 100';
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

        // Validación imagen
        if (empty($this->imagen)) 
        {
            self::$errores[] = 'La imagen es obligatoria';
        } 
        elseif (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $this->imagen)) 
        {
            self::$errores[] = 'La imagen debe ser un archivo válido (jpg, jpeg, png, gif)';
        }

        return empty(self::$errores);
    }

    /**
     * Valida la información modificada del producto.
    *
    * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
    */
    public function ValidateEditProduct(): bool
    {
        // Sanear los datos antes de validar
        self::$errores = [];

        //Validación campo categoria_id
        
        if (!filter_var($this->categoria_id, FILTER_VALIDATE_INT) || $this->categoria_id <= 0) 
        {
            self::$errores[] = 'La categoría debe ser un número entero positivo';
        }
        // Validación campo nombre
        if (!preg_match('/^[\p{L} ,]+$/u', $this->nombre)) 
        {
            self::$errores[] = 'El nombre no es válido';
        }

        // Validación campo descripción
        if (strlen($this->descripcion) > 500) 
        {
            self::$errores[] = 'La descripción no puede tener más de 500 caracteres';
        }

        // Validación precio
        if ($this->precio <= 0) 
        {
            self::$errores[] = 'El precio debe ser mayor a 0';
        }

        // Validación stock
        
        if ($this->stock < 0) 
        {
            self::$errores[] = 'El stock no puede ser negativo';
        }

        // Validación oferta
        if ($this->oferta < -1 || $this->oferta > 100) 
        {
            self::$errores[] = 'La oferta debe estar entre 0 y 100';
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

        return empty(self::$errores);
    }

    /**
     * Valida la imagen del producto.
    *
    * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
    */
    public function ValidateImagen(): bool
    {
        self::$errores = [];
        if (empty($this->imagen)) 
        {
            self::$errores[] = 'La imagen es obligatoria';
        } 
        elseif (!preg_match('/\.(jpg|jpeg|png|gif)$/i', $this->imagen)) 
        {
            self::$errores[] = 'La imagen debe ser un archivo válido (jpg, jpeg, png, gif)';
        }

        return empty(self::$errores);
    }

    /**
     * Sanea los datos del producto.
    *
    * Este método se encarga de limpiar y validar los datos del producto
    * para asegurar que no contengan caracteres o valores no deseados.
    *
    * @return void
    */
    public function sanitate(): void
    {
        $this->categoria_id = filter_var($this->categoria_id, FILTER_SANITIZE_NUMBER_INT);
        $this->nombre = htmlspecialchars(strip_tags(trim($this->nombre)));
        $this->descripcion = htmlspecialchars(strip_tags(trim($this->descripcion)));
        $this->precio = filter_var($this->precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $this->stock = filter_var($this->stock, FILTER_SANITIZE_NUMBER_INT);
        $this->oferta = filter_var($this->oferta, FILTER_SANITIZE_NUMBER_INT);
        $this->imagen = htmlspecialchars(strip_tags(trim($this->imagen)));
    }

    /**
     * Crea una instancia de Product a partir de un array de datos.
    *
    * @param array $data Los datos para crear la instancia de Product.
    * @return Product La instancia de Product creada.
    */
    public static function fromArray(array $data): Product
    {
        return new Product(
            id: $data['id'] ?? null,
            categoria_id: $data['categoria_id'] ?? 0,
            nombre: $data['nombre'] ?? '',
            descripcion: $data['descripcion'] ?? '',
            precio: $data['precio'] ?? 0.0,
            stock: $data['stock'] ?? 0,
            oferta: $data['oferta'] ?? 0,
            fecha: isset($data['fecha']) ? new DateTime($data['fecha']) : new DateTime(),
            imagen: $data['imagen'] ?? ''
        );
    }
}