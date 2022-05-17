<?php

namespace ZipCodeApp\Http\Controllers\API;

use ZipCodeApp\Http\Controllers\Controller;
use ZipCodeApp\Models\ZipCode;
use Illuminate\Http\Request;

class ZipCodeController extends Controller{

    public function obtenerZipCode($zip_code){
        $data = ZipCode::where("d_codigo",$zip_code)->firstOrFail();
            if($data){
                $d_estado = mb_strtoupper((html_entity_decode($data->d_estado)));
                $federal_entity = new \stdClass();
                $federal_entity->key  = intval(html_entity_decode($data->c_estado));
                $federal_entity->name =  mb_strtoupper(html_entity_decode($data->d_estado));
                if ($data->c_cp===""){
                    $data->c_cp=null;
                }
                $federal_entity->code = $data->c_cp;
    
                $municipality = new \stdClass();
                $municipality->key  = intval(html_entity_decode($data->c_mnpio));
    
                $d_mnpio = mb_strtoupper( html_entity_decode($data->d_mnpio));
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

                if (strlen($data->d_codigo)<5){
                  $data->d_codigo = "0".$data->d_codigo;
                }
  
                $result->zip_code = strval($data->d_codigo);
          
    
                $locality = mb_strtoupper(html_entity_decode($data->d_ciudad));
    
                $result->locality       = $locality;
                $result->federal_entity = $federal_entity;
    
                $result->settlements  = $settlements;
                $result->municipality = $municipality;
    
               return response()->json($result);
            }
        return response()->json($data);
    }
}
