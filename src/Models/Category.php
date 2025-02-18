<?php
namespace Models;

class Category{
    //PROPIEDADES
    protected static array $errores = [];

    //CONSTRUCTOR
    public function __construct(
    private int|null $id, 
    private string $nombre,)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

     //SETTERS
    public function setId(int|null $id): void
    {
        $this->id = $id;
    }
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    //GETTERS
    public function getId(): int|null 
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
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
     * Valida la información del la categoria.
     *
     * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
     */
    public function ValidateCategory() : bool
    {
        self::$errores = [];
        if(empty($this->nombre))
        {
            self::$errores[] = 'El nombre es obligatorio';
        }
        else
        {
            $patron = '/^[a-zA-Z, ]*$/';
            if(!preg_match($patron,$this->nombre))
            {
                self::$errores[] = 'El nombre no es valido'; 
            }
        }
        return self::$errores ? false : true;
    }

    /**
     * Sanea los datos del la categoria.
     *
     * Este método se encarga de limpiar y validar los datos del la categoria
     * para asegurar que no contengan caracteres o valores no deseados.
     *
     * @return void
     */
    public function sanitate(): void
    {
        $this->nombre = filter_var($this->nombre, FILTER_SANITIZE_STRING);
    }

    /**
     * Crea una instancia de Category a partir de un array de datos.
     *
     * @param array $data Los datos para crear la instancia de Category.
     * @return Category La instancia de Category creada.
     */
    public static function fromArray(array $data): Category
    {
        return new Category(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? ''
        );
    }
}