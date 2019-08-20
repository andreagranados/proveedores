<?php
class proveedores_abm_ci extends abm_ci
{
    protected $s__pantalla;
    /*agregar al atributo nombre_tabla la tabla sobre la que trabaja el ci */
    //private $nombre_tabla='';
        function conf__pant_inicial(toba_ei_pantalla $pantalla){
                $this->s__pantalla='pant_inicial';
            }
        function conf__pant_rubro(toba_ei_pantalla $pantalla){
                $this->s__pantalla='pant_rubro';
            }
    
        function conf__formulario(toba_ei_formulario $form) {
            if ($this->controlador()->dep('datos')->tabla($this->nombre_tabla)->esta_cargada()) {
                $datos=$this->controlador()->dep('datos')->tabla($this->nombre_tabla)->get();
            //autocompleto el documento con ceros adelante hasta 8
                $datos['nro_cuit']=$datos['cuit1'].str_pad($datos['cuit'], 8, '0', STR_PAD_LEFT).$datos['cuit2'];
            //------adjuntos
                if ($this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->esta_cargada()) {
                    $pa=$this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->get();
                    if(isset($pa['const_insc_afip'])){
                        $nomb_ft='/proveedores/1.0/adjuntos/'.$pa['const_insc_afip'];//en windows
                        $datos['const_insc_afip']=$pa['const_insc_afip'];
                        $datos['imagen_vista_previa_ciafip'] = "<a target='_blank' href='{$nomb_ft}' >Constancia AFIP</a>";
                    }
                    if(isset($pa['const_insc_sipro'])){
                        $nomb_sipro='/proveedores/1.0/adjuntos/'.$pa['const_insc_sipro'];//en windows
                        $datos['const_insc_sipro']=$pa['const_insc_sipro'];
                        $datos['imagen_vista_previa_cis'] = "<a target='_blank' href='{$nomb_sipro}' >Constancia SIPRO</a>";
                    }
                }
                //fin adjuntos
                               
                $form->set_datos($datos);
                //genero la imagen codigo de barras
                //$filepath='C:\proyectos\toba_2.6.3\proyectos\proveedores\www\codigo_barras\imagen'.$datos['id_prov'].'.jpg';
                //$filepath='C:\proyectos\toba_2.6.3\proyectos\proveedores\www\img\imagen'.$datos['id_prov'].'.jpg';
                $filepath="/home/andrea/toba_2.7.13/proyectos/proveedores/www/codigo_barras/imagen".$datos['id_prov'].'.jpg';
                $text=$datos['codigo_barra'];//'01234567898888888888';
            //barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
                barcode( $filepath, $text,'30','horizontal','code128',true,1);       
    
            }else{
                $this->pantalla()->tab("pant_rubro")->desactivar();
                $this->pantalla()->tab("pant_domicilio")->desactivar();
            }
        }
        function evt__formulario__cancelar() {
            $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->resetear();
            $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->resetear();//ver si aqui hay que resetear las demas tablas!!!
            $this->controlador()->set_pantalla('pant_inicial');
        }
        function evt__formulario__alta($datos) {
            $datos['cuit1']=substr($datos['nro_cuit'], 0, 2);
            $datos['cuit']=substr($datos['nro_cuit'], 2, 8);
            $datos['cuit2']=substr($datos['nro_cuit'], 10, 1);
            if(!isset($datos['fecha_inscripcion'])){//sino se completo la fecha entonces toma la actual
             $datos['fecha_inscripcion']=date('Y-m-d');   
            }
            //genera un codigo de barras 
            $fecha_hora=getdate();
            $datos['codigo_barra']=date('Y').date('m').date('d').str_pad($fecha_hora['hours'], 2, "0", STR_PAD_LEFT).str_pad($fecha_hora['minutes'], 2, "0", STR_PAD_LEFT).str_pad($fecha_hora['seconds'], 2, "0", STR_PAD_LEFT);
            $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->set($datos);
            $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->sincronizar();
            toba::notificacion()->agregar('El registro ha sido ingresado correctamente', 'info');
           // if (!$this->controlador()->dep('datos')->tabla($this->nombre_tabla)->esta_cargada()) {
                 $prov=$this->controlador()->dep('datos')->tabla($this->nombre_tabla)->get();
                 $carga=array('id_prov'=>$prov['id_prov']);
                 $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->cargar($carga);
            // }
              //adjuntos
            $datos2['id_prov']=$prov['id_prov'];
            if (isset($datos['const_insc_afip'])) {
                            $nombre_ca="const_insc_afip".$prov['id_prov'].".pdf";
                           // $destino_ca="C:/proyectos/toba_2.6.3/proyectos/proveedores/www/adjuntos/".$nombre_ca;
                            $destino_ca="/home/andrea/toba_2.7.13/proyectos/proveedores/www/adjuntos/".$nombre_ca;
                            if(move_uploaded_file($datos['const_insc_afip']['tmp_name'], $destino_ca)){//mueve un archivo a una nueva direccion, retorna true cuando lo hace y falso en caso de que no
                                $datos2['const_insc_afip']=strval($nombre_ca);}
            }
            if (isset($datos['const_insc_sipro'])) {
                            $nombre_sip="const_insc_sipro".$prov['id_prov'].".pdf";
                            //$destino_sip="C:/proyectos/toba_2.6.3/proyectos/proveedores/www/adjuntos/".$nombre_sip;
                            $destino_sip="/home/andrea/toba_2.7.13/proyectos/proveedores/www/adjuntos/".$nombre_sip;
                            if(move_uploaded_file($datos['const_insc_sipro']['tmp_name'], $destino_sip)){//mueve un archivo a una nueva direccion, retorna true cuando lo hace y falso en caso de que no
                                $datos2['const_insc_sipro']=strval($nombre_sip);}
            }
            $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->set($datos2);
            $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->sincronizar();           
            //sino esta cargada la carga
            if(($this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->esta_cargada())!=true){
               $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->cargar($carga); 
            }
        }

        function evt__formulario__modificacion($datos) { 
            if ($this->controlador()->dep('datos')->tabla($this->nombre_tabla)->esta_cargada()) {
                $prov=$this->controlador()->dep('datos')->tabla($this->nombre_tabla)->get();
                $id=$prov['id_prov'];
                $datos['cuit1']=substr($datos['nro_cuit'], 0, 2);
                $datos['cuit']=substr($datos['nro_cuit'], 2, 8);
                $datos['cuit2']=substr($datos['nro_cuit'], 10, 1);
                $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->set($datos);
                $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->sincronizar();
                //modificacion de los adjuntos
                $datos2['id_prov']=$id;
                if (isset($datos['const_insc_afip'])) {
                    $nombre_ca="const_insc_afip".$id.".pdf";
                    //$destino_ca="C:/proyectos/toba_2.6.3/proyectos/proveedores/www/adjuntos/".$nombre_ca;
                    $destino_ca="/home/andrea/toba_2.7.13/proyectos/proveedores/www/adjuntos/".$nombre_ca;
                    if(move_uploaded_file($datos['const_insc_afip']['tmp_name'], $destino_ca)){//mueve un archivo a una nueva direccion, retorna true cuando lo hace y falso en caso de que no
                        $datos2['const_insc_afip']=strval($nombre_ca);
                    }
                }
                if (isset($datos['const_insc_sipro'])) {
                    $nombre_sip="const_insc_sipro".$id.".pdf";
                    //$destino_sip="C:/proyectos/toba_2.6.3/proyectos/proveedores/www/adjuntos/".$nombre_sip;
                    $destino_sip="/home/andrea/toba_2.7.13/proyectos/proveedores/www/adjuntos/".$nombre_sip;
                    if(move_uploaded_file($datos['const_insc_sipro']['tmp_name'], $destino_sip)){//mueve un archivo a una nueva direccion, retorna true cuando lo hace y falso en caso de que no
                        $datos2['const_insc_sipro']=strval($nombre_sip);}
                }
                //sino esta cargada la inserta y si esta cargada la modifica
                $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->set($datos2);
                $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->sincronizar();
                //sino esta cargada la carga
                if(($this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->esta_cargada())!=true){
                       $auxi['id_prov']=$prov['id_prov'];
                       $this->controlador()->dep('datos')->tabla('proveedor_adjuntos')->cargar($auxi); 
                }
                toba::notificacion()->agregar('Se ha modificado correctamente', 'info');
            }
        }

        function evt__formulario__baja() {
            $this->controlador()->dep('datos')->tabla($this->nombre_tabla)->eliminar_todo();
            toba::notificacion()->agregar('El registro se ha eliminado correctamente', 'info');
            $this->controlador()->dep('datos')->resetear();
            $this->controlador()->set_pantalla('pant_inicial');
        }
    
  
}
