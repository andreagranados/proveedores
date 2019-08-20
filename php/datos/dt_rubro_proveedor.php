<?php
class dt_rubro_proveedor extends toba_datos_tabla
{
    function get_rubro_proveedor($id_prov){
        $where = " and id_prov=".$id_prov;
        $sql="select ru.id_rubro,ru.nombre,rp.detalle from rubro_proveedor rp, rubro ru "
                . " WHERE  rp.id_rubro=ru.id_rubro"
                . " $where";        
        return toba::db('proveedores')->consultar($sql);
    }

}?>