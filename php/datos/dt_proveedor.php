<?php
class dt_proveedor extends toba_datos_tabla
{
    function get_listado($filtro=null){
//       if(!is_null($filtro)){
//            $where=' WHERE '.$filtro;
//        }else{
//            $where='';
//            }
        $where=' where 1=1 ';
        
        if (isset($filtro['razon_social']['valor'])) {
                switch ($filtro['razon_social']['condicion']) {
                    case 'es_distinto_de':$where.=" and razon_social  !='".$filtro['razon_social']['valor']."'";break;
                    case 'es_igual_a':$where.=" and razon_social like '".$filtro['razon_social']['valor']."'";break;
                    case 'termina_con':$where.=" and razon_social ILIKE '%".$filtro['razon_social']['valor']."'";break;
                    case 'comienza_con':$where.=" and razon_social ILIKE '".$filtro['razon_social']['valor']."%'";break;
                    case 'no_contiene':$where.=" and razon_social NOT ILIKE '%".$filtro['razon_social']['valor']."%'";break;
                    case 'contiene':$where.=" and razon_social ILIKE '%".$filtro['razon_social']['valor']."%'";break;
                }
             }
        if (isset($filtro['cuit']['valor'])) {
                switch ($filtro['cuit']['condicion']) {
                    case 'es_distinto_de':$where.=" and cuit  !='".$filtro['cuit']['valor']."'";break;
                    case 'es_igual_a':$where.=" and cuit like '".$filtro['cuit']['valor']."'";break;
                    case 'termina_con':$where.=" and cuit ILIKE '%".$filtro['cuit']['valor']."'";break;
                    case 'comienza_con':$where.=" and cuit ILIKE '".$filtro['cuit']['valor']."%'";break;
                    case 'no_contiene':$where.=" and cuit NOT ILIKE '%".$filtro['cuit']['valor']."%'";break;
                    case 'contiene':$where.=" and cuit ILIKE '%".$filtro['cuit']['valor']."%'";break;
                }
             }  
        if (isset($filtro['correo_principal']['valor'])) {
            switch ($filtro['correo_principal']['condicion']) {
                case 'es_distinto_de':$where.=" and correo_principal  !='".$filtro['correo_principal']['valor']."'";break;
                case 'es_igual_a':$where.=" and correo_principal like '".$filtro['correo_principal']['valor']."'";break;
                case 'termina_con':$where.=" and correo_principal ILIKE '%".$filtro['correo_principal']['valor']."'";break;
                case 'comienza_con':$where.=" and correo_principal ILIKE '".$filtro['correo_principal']['valor']."%'";break;
                case 'no_contiene':$where.=" and correo_principal NOT ILIKE '%".$filtro['correo_principal']['valor']."%'";break;
                case 'contiene':$where.=" and correo_principal ILIKE '%".$filtro['correo_principal']['valor']."%'";break;
            }
         }    
        if (isset($filtro['nombre_fantasia']['valor'])) {
            switch ($filtro['nombre_fantasia']['condicion']) {
                case 'es_distinto_de':$where.=" and nombre_fantasia  !='".$filtro['nombre_fantasia']['valor']."'";break;
                case 'es_igual_a':$where.=" and nombre_fantasia like '".$filtro['nombre_fantasia']['valor']."'";break;
                case 'termina_con':$where.=" and nombre_fantasia ILIKE '%".$filtro['nombre_fantasia']['valor']."'";break;
                case 'comienza_con':$where.=" and nombre_fantasia ILIKE '".$filtro['nombre_fantasia']['valor']."%'";break;
                case 'no_contiene':$where.=" and nombre_fantasia NOT ILIKE '%".$filtro['nombre_fantasia']['valor']."%'";break;
                case 'contiene':$where.=" and nombre_fantasia ILIKE '%".$filtro['nombre_fantasia']['valor']."%'";break;
            }
         }     
        if (isset($filtro['id_prov']['valor'])) {
            switch ($filtro['id_prov']['condicion']) {
                case 'es_distinto_de':$where.=" and id_prov !=".$filtro['id_prov']['valor'];break;
                case 'es_igual_a':$where.=" and id_prov = ".$filtro['id_prov']['valor'];break;
                case 'es_mayor_que':$where.=" and id_prov > ".$filtro['id_prov']['valor'];break;
                case 'es_mayor_igual_que':$where.=" and id_prov >= ".$filtro['id_prov']['valor'];break;
                case 'es_menor_que':$where.=" and id_prov <".$filtro['id_prov']['valor'];break;
                 case 'es_menor_igual_que':$where.=" and id_prov <=".$filtro['id_prov']['valor'];break;
                 case 'entre':$where.=" and id_prov >=".$filtro['id_prov']['valor']['desde']." and id_prov<=".$filtro['id_prov']['valor']['hasta'];break;
            }
         }    
        if (isset($filtro['inscripto_sipro']['valor'])) {
            switch ($filtro['inscripto_sipro']['condicion']) {
                case 'es_distinto_de':$where.=" and inscripto_sipro  !='".$filtro['inscripto_sipro']['valor']."'";break;
                case 'es_igual_a':$where.=" and inscripto_sipro like '".$filtro['inscripto_sipro']['valor']."'";break;
               
            }
         }    
         //si buscar por los dos rubros al mismo tiempo
        if (isset($filtro['rubros2']['valor'])&& isset($filtro['rubros3']['valor'])) {
             switch ($filtro['rubros2']['condicion']) {
                    case 'es_distinto_de':$where.=" and ".$filtro['rubros2']['valor']. " not in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)" ;break;
                    case 'es_igual_a':$where.=" and ".$filtro['rubros2']['valor']." in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)";break;
                 }
            switch ($filtro['rubros3']['condicion']) {
                    case 'es_distinto_de':$where.=" and ".$filtro['rubros3']['valor']. " not in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)" ;break;
                    case 'es_igual_a':$where.=" and ".$filtro['rubros3']['valor']." in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)";break;
                 }
        } 
        if (isset($filtro['rubros2']['valor'])) {
                 switch ($filtro['rubros2']['condicion']) {
                    case 'es_distinto_de':$where.=" and ".$filtro['rubros2']['valor']. " not in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)" ;break;
                    case 'es_igual_a':$where.=" and ".$filtro['rubros2']['valor']." in( select a.id_rubro from rubro_proveedor a where a.id_prov=sub.id_prov)";break;
                 }
             }

        $sql="select * from (select p.id_prov,razon_social,nombre_fantasia,cuit1||'-'||cuit||'-'||cuit2 as cuit, correo_principal,correo_secundario,fecha_inscripcion,coalesce(telefono,'')||' / '||coalesce(celular,'')  telefono,case when inscripto_sipro then 'SI' else 'NO' end as inscripto_sipro,string_agg(r.nombre,' /') as rubros"
                . " from proveedor p"
                . " left outer join rubro_proveedor rp on (rp.id_prov=p.id_prov)"
                . " left outer join rubro r on (r.id_rubro=rp.id_rubro)"
                . " left outer join domicilio d on (d.id_prov=p.id_prov)"
                . " group by p.id_prov,razon_social,nombre_fantasia,cuit1,cuit,cuit2,correo_principal,correo_secundario,fecha_inscripcion,telefono,celular,inscripto_sipro"
                . ")sub"
                . $where
                ." order by id_prov";
        
        return toba::db('proveedores')->consultar($sql);
    }

}?>