<?php
require_once 'barcode.php';

class abm_ci extends toba_ci {
    /* agregar al atributo nombre_tabla la tabla sobre la que trabaja el ci */

    //private $nombre_tabla='';
    protected $s__where=null;
    protected $s__datos_filtro=null;

    function conf__cuadro(toba_ei_cuadro $cuadro) {
        if (!is_null($this->s__where)) {
            $datos = $this->dep('datos')->tabla($this->nombre_tabla)->get_listado($this->s__where);
        } else {
            $datos = $this->dep('datos')->tabla($this->nombre_tabla)->get_listado();
        }
        $cuadro->set_datos($datos);
       
//        $filepath='C:\proyectos\toba_2.6.3\proyectos\proveedores\www\img\imagencb.jpg';
//        $text='01234567898888888888';
//        //barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
//        barcode( $filepath, $text,'70','horizontal','code128',true,1);       
        //echo "<img src='barcode.php?text=0123456789&size=50&orientation=horizontal' />";
        //echo "<a href='barcode.php?text=0123456789&size=50&orientation=vertical'>Generar</a>";
    }

    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    /**
     * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
     * El formato del carga debe ser array(<campo> => <valor>, ...)
     */
    function conf__filtros(toba_ei_filtro $filtro) {
        if (isset($this->s__datos_filtro))
            $filtro->set_datos($this->s__datos_filtro);
    }

    /**
     * Atrapa la interacci�n del usuario con el bot�n asociado
     * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
     */
    function evt__filtros__filtrar($datos) {
        $this->s__where = $this->dep('filtros')->get_sql_where();
        $this->s__datos_filtro = $datos;
    }

    /**
     * Atrapa la interacci�n del usuario con el bot�n asociado
     */
    function evt__filtros__cancelar() {
        
    }

    function evt__agregar($datos) {
        $this->set_pantalla('pant_edicion');
    }

    function evt__cuadro__seleccion($datos) {
           $this->set_pantalla('pant_edicion');
           $this->dep('datos')->tabla($this->nombre_tabla)->cargar($datos); 
           if($this->nombre_tabla=='proveedor'){
                $this->dep('datos')->tabla('domicilio')->cargar($datos); 
                $this->dep('datos')->tabla('rubro_proveedor')->cargar($datos);
                $this->dep('datos')->tabla('proveedor_adjuntos')->cargar($datos);
           }
    }

    //---- Formulario -------------------------------------------------------------------

    function conf__formulario(toba_ei_formulario $form) {
        if ($this->controlador()->dep('datos')->esta_cargada()) { 
            $form->set_datos($this->dep('datos')->tabla($this->nombre_tabla)->get());
        }
    }

    function evt__formulario__alta($datos) {
        /*
         * todo: el periodo por defecto
         */
        $this->dep('datos')->tabla($this->nombre_tabla)->set($datos);
        $this->dep('datos')->sincronizar();
        $this->resetear();
    }

    function evt__formulario__modificacion($datos) {
        $this->dep('datos')->tabla($this->nombre_tabla)->set($datos);
        $this->dep('datos')->sincronizar();
        $this->resetear();
    }

    function evt__formulario__baja() {
        $this->dep('datos')->eliminar_todo();
        toba::notificacion()->agregar('El registro se ha eliminado correctamente', 'info');
        $this->resetear();
    }

    function evt__formulario__cancelar() {
        $this->resetear();
        
    }

    function resetear() {
        $this->dep('datos')->resetear();
       // $this->set_pantalla('pant_cuadro');
    }

}
?>
