<?php

namespace ZipCodeApp\Http\Controllers\API;

use ZipCodeApp\Http\Controllers\Controller;
use ZipCodeApp\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ZipCodeController extends Controller{

    /**
     * Recive zipcode from route
     */

    public function obtenerZipCode($zip_code){

        try{
            $data = ZipCode::where("d_codigo",$zip_code)->firstOrFail();
            
            if($data){
                $d_estado = mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_estado)));
                $federal_entity = new \stdClass();
                $federal_entity->key  = intval(html_entity_decode($data->c_estado));
                $federal_entity->name =  mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_estado)));
                if ($data->c_cp===""){
                    $data->c_cp=null;
                }
                $federal_entity->code = $data->c_cp;
    
                $municipality = new \stdClass();
                $municipality->key  = intval($this->eliminarAcentos(html_entity_decode($data->c_mnpio)));
    
                $d_mnpio = mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_mnpio)));
                $municipality->name = ($this->eliminarAcentos(html_entity_decode($d_mnpio)));
               
                $settlements = collect();
    
              
                $settlement_type = new \stdClass();
                $settlement_type->name = $this->eliminarAcentos($data->d_tipo_asenta);

                $d_asenta = mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_asenta)));
    
                $settle = new \stdClass();
                $settle->key                = intval(html_entity_decode($data->id_asenta_cpcons));
                $settle->name               = $d_asenta;
                $settle->zone_type          = mb_strtoupper($this->eliminarAcentos($data->d_zona));
                $settle->settlement_type    = $settlement_type;
                $settlements->push($settle);
                
                $result = new \stdClass();

               
  
                $result->zip_code = ($data->d_codigo);
          
    
                $locality = mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_ciudad)));
    
                $result->locality       = $locality;
                $result->federal_entity = $federal_entity;
    
                $result->settlements  = $settlements;
                $result->municipality = $municipality;
    
               return response()->json($result);
            }
        return response()->json($data);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(['message' => 'Not Found!'], 404);
        }

    }

    public function eliminarAcentos($texto){
        $replace = array('/é/','/í/','/ó/','/á/','/ñ/', '/ú/', '/ü/','/Á/','/É/','/Í/','/Ó/','/Ú/');
        $remplazo = array('e','i','o','a', 'n', 'u', 'u','A','E','I','O','U');
        $nuevaCadena = preg_replace($replace, $remplazo, $texto);
        return $nuevaCadena;
    }

    public function show(){
        $data = ZipCode::paginate(50);
        return view('ziplist',compact('data'));    
    }
}
