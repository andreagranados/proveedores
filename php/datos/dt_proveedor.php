<?php
class dt_proveedor extends toba_datos_tabla
{
    function get_listado($filtro=null){
       if(!is_null($filtro)){
            $where=' WHERE '.$filtro;
        }else{
            $where='';
            }
     
        $sql="select * from (select p.id_prov,razon_social,cuit1||'-'||cuit||'-'||cuit2 as cuit, correo_principal,correo_secundario,fecha_inscripcion,telefono,case when inscripto_sipro then 'SI' else 'NO' end as inscripto_sipro,string_agg(r.nombre,' /') as rubros"
                . " from proveedor p"
                . " left outer join rubro_proveedor rp on (rp.id_prov=p.id_prov)"
                . " left outer join rubro r on (r.id_rubro=rp.id_rubro)"
                . " group by p.id_prov,razon_social,cuit1,cuit,cuit2,correo_principal,correo_secundario,fecha_inscripcion,telefono,inscripto_sipro"
                . ")sub"
                . $where
                ." order by id_prov";
        return toba::db('proveedores')->consultar($sql);
    }

}?>