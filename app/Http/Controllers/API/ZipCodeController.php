<?php

namespace ZipCodeApp\Http\Controllers\API;

use ZipCodeApp\Http\Controllers\Controller;
use ZipCodeApp\Models\ZipCode;
use Illuminate\Http\Request;

class ZipCodeController extends Controller{

    public function obtenerZipCode($zip_code){
        $data = ZipCode::where("d_codigo",$zip_code)->firstOrFail();
            if($data){

                $info = $data->first();
    
                $d_estado = mb_strtoupper((html_entity_decode($info->d_estado)));
                $federal_entity = new \stdClass();
                $federal_entity->key  = intval(html_entity_decode($info->c_estado));
                $federal_entity->name =  mb_strtoupper(html_entity_decode($info->d_estado));
                if ($info->c_cp===""){
                    $info->c_cp=null;
                }
                $federal_entity->code = $info->c_cp;
    
                $municipality = new \stdClass();
                $municipality->key  = intval(html_entity_decode($info->c_mnpio));
    
                $d_mnpio = mb_strtoupper( html_entity_decode($info->d_mnpio));
                $municipality->name = html_entity_decode($d_mnpio);
    
                $settlements = collect();
    
              
                $settlement_type = new \stdClass();
                $settlement_type->name = $data->d_tipo_asenta;

                $d_asenta = mb_strtoupper(html_entity_decode($data->d_asenta));
    
                $settle = new \stdClass();
                $settle->key                = intval(html_entity_decode($data->id_asenta_cpcons));
                $settle->name               = $d_asenta;
                $settle->zone_type          = mb_strtoupper($data->d_zona);
                $settle->settlement_type    = $settlement_type;
                $settlements->push($settle);
                
                $result = new \stdClass();
                $result->zip_code = strval($info->d_codigo);
    
                $locality = mb_strtoupper(html_entity_decode($info->d_ciudad));
    
                $result->locality       = $locality;
                $result->federal_entity = $federal_entity;
    
                $result->settlements  = $settlements;
                $result->municipality = $municipality;
    
               return response()->json($result);
            }
        return response()->json($data);
    }
}
