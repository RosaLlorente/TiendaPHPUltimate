<?php
namespace Lib;

class Pages{
    /*
    * Este método se encarga de renderizar una vista, cargando el encabezado, el contenido de la página
     * y el pie de página. Los parámetros opcionales permiten pasar variables que estarán disponibles
     * dentro de la vista que se renderiza.
     *
     * @param string $pageName El nombre de la vista que se desea renderizar.
     * @param array|null $params Un arreglo opcional con variables a pasar a la vista.
    */
    public function render(string $pageName,array $params = null): void{
        if($params != null)
        {
            foreach($params as $name => $value)
            {
                $$name = $value;
            }
        }
        $SubirNivel = dirname(__DIR__,1);
        require_once $SubirNivel."/Views/layout/header.php";
        require_once $SubirNivel."/Views/$pageName.php";
        require_once $SubirNivel."/Views/layout/footer.php";
    }
}