<?php
namespace Controllers;

use Lib\Pages;

class ErrorController{

    /**
     * Método que renderiza la página de error 404.
     *
     * @return void
     */
    public static function error404(){
        $pages = new Pages();
        $pages->render('error/error404',['titulo' => 'Pagina no encontrada']);
    }
}