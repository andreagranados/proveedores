<?php

class ci_detalle_proveedor extends  proveedores_abm_ci
{
    protected $s__pantalla;
    protected $nombre_tabla='proveedor';
    //protected $nombre_tabla;
   
    
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

        function conf__form_rubros(toba_ei_formulario_ml $form)
	{ 
             if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()) {
                $pro=$this->controlador()->dep('datos')->tabla('proveedor')->get();
                //$datos=$this->dep('datos')->tabla('rubro_proveedor')->get_rubro_proveedor($pro['id_prov']);
                $ar=array('id_prov' => $pro['id_prov']);
                $datos= $this->controlador()->dep('datos')->tabla('rubro_proveedor')->get_filas($ar);//tiene que estar el cargar previamente
                $form->set_datos($datos); 
             }
           
//            $tu=$this->controlador()->dep('datos')->tabla('proveedor')->get();
//            $ar=array('id_prov' => $tu['id_prov']);
//            $res = $this->dep('datos')->tabla('rubro_proveedor')->get_filas($ar);
//            $form->set_datos($res);
           
         }
        function evt__form_rubros__guardar($datos)
	{
            $pro=$this->controlador()->dep('datos')->tabla('proveedor')->get();
            foreach ($datos as $clave => $elem){
                 $datos[$clave]['id_prov']=$pro['id_prov'];    
            }  
            $this->controlador()->dep('datos')->tabla('rubro_proveedor')->procesar_filas($datos);
            $this->controlador()->dep('datos')->tabla('rubro_proveedor')->sincronizar();
	}
        
        function evt__form_rubros__cancelar($datos) {
              $this->controlador()->dep('datos')->tabla('rubro_proveedor')->resetear();  
              $this->controlador()->set_pantalla('pant_inicial');
        }
        
        function conf__form_domicilio(toba_ei_formulario $form)
        { 
             if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()) {
                 if ($this->controlador()->dep('datos')->tabla('domicilio')->esta_cargada()) {
                    $datos=$this->controlador()->dep('datos')->tabla('domicilio')->get();
                    //autocompleto el documento con ceros adelante hasta 8
                        $form->set_datos($datos);
                    }
             }else{
                $this->pantalla()->tab("pant_rubro")->desactivar();
                $this->pantalla()->tab("pant_domicilio")->desactivar(); 
             }
            
        }
        function evt__form_domicilio__modificacion($datos) {
            if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()) {//modifica
                $prov=$this->controlador()->dep('datos')->tabla('proveedor')->get();
                $datos['id_prov']=$prov['id_prov'];
                $this->controlador()->dep('datos')->tabla('domicilio')->set($datos);
                $this->controlador()->dep('datos')->tabla('domicilio')->sincronizar();
                toba::notificacion()->agregar('Los datos se han guardado correctamente', 'info');
            }
            if (!$this->controlador()->dep('datos')->tabla('domicilio')->esta_cargada()) {//no esta cargada entonces se da de alta la primera vez
                 $domi=$this->controlador()->dep('datos')->tabla('domicilio')->get();
                 $domi_cargar=array('nro_domicilio'=>$domi['nro_domicilio']);
                $this->controlador()->dep('datos')->tabla('domicilio')->cargar($domi_cargar);
            }
        }
        function evt__form_domicilio__cancelar($datos) {
              $this->controlador()->dep('datos')->tabla('domicilio')->resetear();  
              $this->controlador()->set_pantalla('pant_inicial');
        }
        
        function vista_pdf(toba_vista_pdf $salida){ 
            if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()) {//modifica
                $prov=$this->controlador()->dep('datos')->tabla('proveedor')->get();
                $salida->set_nombre_archivo("Constancia.pdf");
                $salida->set_papel_orientacion('portrait');
                $salida->inicializar();
                $pdf = $salida->get_pdf();
                    //modificamos los márgenes de la hoja top, bottom, left, right
                $pdf->ezSetMargins(60, 30, 50, 50);
                    //Configuramos el pie de página. El mismo, tendra el número de página centrado en la página y la fecha ubicada a la derecha. 
                    //Primero definimos la plantilla para el número de página.
                $formato = utf8_decode('xx'.date('d/m/Y h:i:s a').'     Página {PAGENUM} de {TOTALPAGENUM} ');
                //Determinamos la ubicación del número página en el pié de pagina definiendo las coordenadas x y, tamaño de letra, posición, texto, pagina inicio 
                //$pdf->ezStartPageNumbers(500, 25, 8, 'left', $formato, 1); 
                $opciones = array(
                    'showLines'=>1,
                    'splitRows'=>0,
                    'rowGap' => 1,
                    'showHeadings' => true,
                    'titleFontSize' => 9,
                    'fontSize' => 10,
                    'shadeCol' => array(0.9,0.9,0.9),
                    'outerLineThickness' => 0,
                    'innerLineThickness' => 0,
                    'xOrientation' => 'center',
                    'width' => 500
                );
                $pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
                //encabezado
                $tabla_texto=array();
                $tabla_texto[0]=array('dato'=>utf8_d_seguro('<b>CONSTANCIA DE INSCRIPCIÓN</b>'.chr(10).'<b>PADRÓN DE PROVEEDORES</b>'.chr(10).'<b>UNIVERSIDAD NACIONAL DEL COMAHUE</b>').chr(10));
                $cols=array('dato'=>'');
                $pdf->ezTable($tabla_texto,$cols,'',array('showLines'=>0,'showHeadings'=>0,'shaded'=>0,'width'=>450,'fontSize' => 14,'cols'=>array('dato'=>array('justification'=>'center'))));
                
                //$salida->titulo(utf8_d_seguro('CONSTANCIA DE INSCRIPCIÓN'.chr(10).'PADRÓN DE PROVEEDORES'.chr(10).' UNIVERSIDAD NACIONAL DEL COMAHUE ').chr(10));    
                $pdf->ezText("\n\n\n", 7);
                
                $tabla_texto=array();
                $tabla_texto[0]=array('dato'=>utf8_decode("         Se deja constancia que <b>".trim($prov['razon_social'])."</b> CUIT ".$prov['cuit1']."-".$prov['cuit'].'-'.$prov['cuit2']." se encuentra inscripto en el Padrón de Proveedores de la Universidad Nacional del Comahue, según registro N°: ".$prov['id_prov']));
                $pdf->ezTable($tabla_texto,$cols,'',array('showLines'=>0,'showHeadings'=>0,'shaded'=>0,'width'=>450,'fontSize' => 12,'cols'=>array('dato'=>array('justification'=>'left'))));
                
                //$texto=utf8_decode("Se deja constancia que <b>".trim($prov['razon_social'])."</b> CUIT ".$prov['cuit1']."-".$prov['cuit'].'-'.$prov['cuit2']." se encuentra inscripto en el Padrón de Proveedores de la Universidad Nacional del Comahue, según N° de registro: ".$prov['id_prov']);    
                //$pdf->ezText($texto,12);
                $pdf->ezText("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n", 12);
                
                $tabla_texto=array();
                $tabla_texto[0]=array('dato'=>utf8_decode('Departamento de Compras y Contrataciones'));
                $tabla_texto[1]=array('dato'=>utf8_decode('Secretaría de Hacienda'));
                $tabla_texto[2]=array('dato'=>utf8_decode('Universidad Nacional del Comahue'));
                $tabla_texto[3]=array('dato'=>utf8_decode('TE: 0299 4490300 Interno 470'));
                $tabla_texto[4]=array('dato'=>utf8_decode('Mail: compras@central.uncoma.edu.ar'));
                $tabla_texto[5]=array('dato'=>utf8_decode('Buenos Aires 1400 - Neuquén Capital'));
                //aqui un correo general de finanzas?
                $pdf->ezTable($tabla_texto,$cols,'',array('showLines'=>0,'showHeadings'=>0,'shaded'=>0,'width'=>450,'fontSize' => 12,'cols'=>array('dato'=>array('justification'=>'left'))));
                //$pdf->ezImage('test.gif',1,2,3,1);
 
                foreach ($pdf->ezPages as $pageNum=>$id){ 
                    $pdf->reopenObject($id); //definimos el path a la imagen de logo de la organizacion 
                    //agregamos al documento la imagen y definimos su posición a través de las coordenadas (x,y) y el ancho y el alto.
                    $imagen = toba::proyecto()->get_path().'/www/img/logo.jpg';//la imagen logo_unco.jpg ver xq no anda
                    //$imagen2 = toba::proyecto()->get_path().'/www/img/imagen'.$prov['id_prov'].'.jpg';
                    $imagen2 = toba::proyecto()->get_path().'/www/codigo_barras/imagen'.$prov['id_prov'].'.jpg';
                    $pdf->addJpegFromFile($imagen, 70, 730, 70, 66); 
                    $pdf->addJpegFromFile($imagen2, 225, 70, 150, 43);//x, y 209,60
                    $pdf->closeObject(); 
                } 
                
            }
            
         }
         
        function conf__pant_inicial(toba_ei_pantalla $pantalla){
            if ($this->controlador()->dep('datos')->tabla('proveedor')->esta_cargada()){       
                    $this->evento('constancia')->mostrar();
            }else{
                    $this->evento('constancia')->ocultar();
                }
        }
}
?>