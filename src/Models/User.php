<?php
namespace Models;

class User{
    //PROPIEDADES
    protected static array $errores = [];

    //CONSTRUCTOR
    public function __construct(
        private int|null $id,
        private string $nombre,
        private string $apellidos,
        private string $email,
        private string $password,
        private string $role,
    ){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
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
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
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
    public function getApellidos(): string
    {
        return $this->apellidos;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getRole(): string
    {
        return $this->role;
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
     * Valida la información del usuario.
     *
     * @return bool Devuelve true si la validación es exitosa, de lo contrario false.
     */
    public function ValidateRegister(): bool
    {
        self::$errores = [];
        //Validacion campo nombre
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

        //Validacion campo apellido
        if(empty($this->apellidos))
        {
            self::$errores[] = 'El apellido es obligatorio';
        }
        else
        {
            $patron = '/^[a-zA-Z, ]*$/';
            if(!preg_match($patron,$this->apellidos))
            {
                self::$errores[] = 'El apellido no es valido';
            }
        }

        //Validacion campo email
        if(empty($this->email))
        {
            self::$errores[] = 'El email es obligatorio';
        }
        else
        {
            $patron = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/';
            if(!preg_match($patron,$this->email))
            {
                self::$errores[] = 'El email no es valido';  
            }
        }

        //Validacion campo password(minimo 8 caracteres, una mayuscula, una minuscula y un numero)
        if(empty($this->password))
        {
            self::$errores[] = 'La contraseña es obligatoria';
        }
        else
        {
            $patron = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
            if(!preg_match($patron,$this->password))
            {
                self::$errores[] = 'La contraseña no es valida';     
            }
        }

        //Validacion campo rol
        if(empty($this->role))
        {
            self::$errores[] = 'El rol es obligatorio';
        }
        else
        {
            $patron = '/^(admin|user)$/';
            if(!preg_match($patron,$this->role))
            {
                self::$errores[] = 'El rol no es valido';     
            }
        }
        return self::$errores ? false : true;
    }
    
    /**
     * Valida los datos de inicio de sesión del usuario.
     *
     * @return bool Devuelve true si los datos de inicio de sesión son válidos, de lo contrario, devuelve false.
     *
     */
    public function ValidateLogin(): bool
    {
        self::$errores = [];

        //Validacion campo email
        if(empty($this->email))
        {
            self::$errores[] = 'El email es obligatorio';
        }
        else
        {
            $patron = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/';
            if(!preg_match($patron,$this->email))
            {
                self::$errores[] = 'El email no es valido';  
            }
        }

        //Validacion campo password(minimo 8 caracteres, una mayuscula, una minuscula y un numero)
        if(empty($this->password))
        {
            self::$errores[] = 'La contraseña es obligatoria';
        }
        else
        {
            $patron = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
            if(!preg_match($patron,$this->password))
            {
                self::$errores[] = 'La contraseña no es valida';     
            }
        }

        return self::$errores ? false : true;
    }

    /**
     * Sanea los datos del usuario.
     *
     * Este método se encarga de limpiar y validar los datos del usuario
     * para asegurar que no contengan caracteres o valores no deseados.
     *
     * @return void
     */
    public function sanitate(): void
    {
        $this->nombre = filter_var($this->nombre, FILTER_SANITIZE_STRING);
        $this->apellidos = filter_var($this->apellidos, FILTER_SANITIZE_STRING);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->password = filter_var($this->password, FILTER_SANITIZE_STRING);
        $this->role = filter_var($this->role, FILTER_SANITIZE_STRING);
    }
    
    /**
     * Crea una instancia de User a partir de un array de datos.
     *
     * @param array $data Los datos para crear la instancia de User.
     * @return User La instancia de User creada.
     */
    public static function fromArray(array $data): User
    {
        return new User(
            id: $data['id'] ?? null,
            nombre: $data['nombre'] ?? '',
            apellidos: $data['apellidos'] ?? '',
            email: $data['email'],
            password: $data['password'],
            role: $data['role'] ?? 'user'
        );
    }

}