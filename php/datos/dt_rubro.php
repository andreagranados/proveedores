<?php
class dt_rubro extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_rubro, nombre FROM rubro ORDER BY nombre";
		return toba::db('proveedores')->consultar($sql);
	}

}
?>