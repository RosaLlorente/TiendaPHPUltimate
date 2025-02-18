<?php
namespace Lib;
use Controllers\ErrorController;

class Router{
    private static $routes = [];

    /*
    *Este método permite registrar rutas asociadas a controladores en el sistema de enrutamiento. 
    Se especifica el método HTTP (como GET, POST), la acción (la ruta que se desea manejar) 
    * y el controlador (una función o método que se ejecutará cuando se acceda a esa ruta).
    *  
    * @param string $method El método HTTP.
    * @param string $action La ruta que se desea manejar
    * @param Callable $controller El controlador (una función o método) que se ejecutará para esa ruta.
    */
    public static function add(string $method,string $action,Callable $controller): void{
        $action = trim($action,'/');
        self::$routes[$method][$action] = $controller;
    }

    /*
    * Este método se encarga de manejar la solicitud HTTP, identificar la ruta y el método,
    * y ejecutar el controlador correspondiente. Si la ruta contiene un parámetro numérico
    * al final, este parámetro se extrae y se pasa al controlador. Si no se encuentra una
    * ruta válida, se redirige al usuario a una página de "No encontrado".
    */
    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = preg_replace('/TiendaPHP/','', $_SERVER['REQUEST_URI'] );
        $action = trim($action,'/');
        $param = null;
        preg_match('/[0-9]+$/',$action,$match);
        if(!empty($match))
        {
            $param = $match[0];
            $action=preg_replace('/'.$match[0].'/',':id',$action);
        }
        $fn = self::$routes[$method][$action] ?? null;
        if($fn)
        {
            $callback = self::$routes[$method][$action];
            echo call_user_func($callback,$param);
        }
        else
        {
            header('Location: /TiendaPHP/not-found');
            //ErrorController::error404();
        }
    }
}