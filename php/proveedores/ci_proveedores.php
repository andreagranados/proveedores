<?php
class ci_proveedores extends  abm_ci 
{
        protected $nombre_tabla='proveedor';
        //-----------------------------------------------------------------------------------
	//---- form_encabezado --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_encabezado(toba_ei_formulario $form)
	{
             if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()) {
                $prov=$this->controlador()->dep('datos')->tabla('proveedor')->get();
                $texto='Proveedor: '.$prov['razon_social'];
                $form->set_titulo($texto);
            }
	}
}?>