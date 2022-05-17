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
                $municipality->name = $this->eliminarAcentos(html_entity_decode($d_mnpio));
               
                $settlements = collect();
    
              
                $settlement_type = new \stdClass();
                $settlement_type->name = $this->eliminarAcentos($data->d_tipo_asenta);

                $d_asenta = mb_strtoupper($this->eliminarAcentos(html_entity_decode($data->d_asenta)));
    
                $settle = new \stdClass();
                $settle->key                = intval(html_entity_decode($data->id_asenta_cpcons));
                $settle->name               = $this->eliminarAcentos($d_asenta);
                $settle->zone_type          = mb_strtoupper($this->eliminarAcentos($data->d_zona));
                $settle->settlement_type    = $settlement_type;
                $settlements->push($settle);
                
                $result = new \stdClass();
                if (strlen($data->d_codigo)===4){
                    $data->d_codigo = "0".$data->d_codigo;
                }         
                $result->zip_code = strval($data->d_codigo);
                
    
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

    public function eliminarAcentos($cadena){
       //Reemplazamos la A y a
		$cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
            );
    
            //Reemplazamos la E y e
            $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena );
    
            //Reemplazamos la I y i
            $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena );
    
            //Reemplazamos la O y o
            $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena );
    
            //Reemplazamos la U y u
            $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena );
    
            //Reemplazamos la N, n, C y c
            $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
            );
            
            return $cadena;

    }

    public function show(){
        $data = ZipCode::paginate(50);
        return view('ziplist',compact('data'));    
    }
}
