<?php
/**
 * Esta clase fue y ser� generada autom�ticamente. NO EDITAR A MANO.
 * @ignore
 */
class proveedores_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
                'abm_ci' => 'extension_toba/componentes/abm_ci.php',
                'proveedores_abm_ci' => 'extension_toba/componentes/proveedores_abm_ci.php',
		'proveedores_ci' => 'extension_toba/componentes/proveedores_ci.php',
		'proveedores_cn' => 'extension_toba/componentes/proveedores_cn.php',
		'proveedores_datos_relacion' => 'extension_toba/componentes/proveedores_datos_relacion.php',
		'proveedores_datos_tabla' => 'extension_toba/componentes/proveedores_datos_tabla.php',
		'proveedores_ei_arbol' => 'extension_toba/componentes/proveedores_ei_arbol.php',
		'proveedores_ei_archivos' => 'extension_toba/componentes/proveedores_ei_archivos.php',
		'proveedores_ei_calendario' => 'extension_toba/componentes/proveedores_ei_calendario.php',
		'proveedores_ei_codigo' => 'extension_toba/componentes/proveedores_ei_codigo.php',
		'proveedores_ei_cuadro' => 'extension_toba/componentes/proveedores_ei_cuadro.php',
		'proveedores_ei_esquema' => 'extension_toba/componentes/proveedores_ei_esquema.php',
		'proveedores_ei_filtro' => 'extension_toba/componentes/proveedores_ei_filtro.php',
		'proveedores_ei_firma' => 'extension_toba/componentes/proveedores_ei_firma.php',
		'proveedores_ei_formulario' => 'extension_toba/componentes/proveedores_ei_formulario.php',
		'proveedores_ei_formulario_ml' => 'extension_toba/componentes/proveedores_ei_formulario_ml.php',
		'proveedores_ei_grafico' => 'extension_toba/componentes/proveedores_ei_grafico.php',
		'proveedores_ei_mapa' => 'extension_toba/componentes/proveedores_ei_mapa.php',
		'proveedores_servicio_web' => 'extension_toba/componentes/proveedores_servicio_web.php',
		'proveedores_comando' => 'extension_toba/proveedores_comando.php',
		'proveedores_modelo' => 'extension_toba/proveedores_modelo.php',
	);
}
?>