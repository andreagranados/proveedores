<?php
class dt_localidad extends toba_datos_tabla
{
	function get_descripciones($codigo_pcia =null)
	{
            $where="";
            if(isset($codigo_pcia)){
                $where=" where codigo_pcia=".$codigo_pcia;
            }
            $sql = "SELECT * FROM localidad $where ORDER BY nombre";
            return toba::db('proveedores')->consultar($sql);
	}
        function get_codigo_postal($id_localidad){
            $sql="SELECT cp from localidad where cod_localidad=".$id_localidad;
            $resul= toba::db('proveedores')->consultar($sql);
            return $resul[0]['cp'];
        }
}
?>