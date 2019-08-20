<?php
class dt_provincia extends toba_datos_tabla
{
	function get_descripciones($codigo_pais =null)
	{
            $where="";
            if(isset($codigo_pais)){
                $where=" where cod_pais='".$codigo_pais."'";
            }
            $sql = "SELECT codigo_pcia,descripcion_pcia FROM provincia $where"
                    . " ORDER BY descripcion_pcia";
            return toba::db('proveedores')->consultar($sql);
	}

}

?>