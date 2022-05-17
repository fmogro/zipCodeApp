<?php

namespace ZipCodeApp\Http\Controllers\API;

use ZipCodeApp\Http\Controllers\Controller;
use ZipCodeApp\Models\ZipCode;
use Illuminate\Http\Request;



class ZipCodeController extends Controller{

    /**
     * Recive zipcode from route
     */

    public function obtenerZipCode($zip_code){


           $data = ZipCode::where("d_codigo",$zip_code)->get();


            if(isset($data)){
      
                $dato = $data->first();

                $federal_entity=$this->federalEntity($dato);

                $municipality = $this->getMunicipality($dato);

                $settlements=$this->getSettlements($data);
               
                $resultados = new \stdClass();

                $dato->d_codigo = strlen($dato->d_codigo)===4 ? "0".$dato->d_codigo : $dato->d_codigo;

       
                $resultados->zip_code = strval($dato->d_codigo);
                
    
                $locality = mb_strtoupper($this->eliminarAcentos(html_entity_decode($dato->d_ciudad)));
    
                $resultados->locality       = $locality;
                $resultados->federal_entity = $federal_entity;
    
                $resultados->settlements  = $settlements;
                $resultados->municipality = $municipality;
    
               return response()->json($resultados);
            }
            return response()->json($dato);
        

    }

    public function eliminarAcentos($cadena){
      
		$cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
            );
    
            $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena );
    

            $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena );

            $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena );
    

            $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena );
    

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

    /**
     * Create function for more elegant code
     */
    public function  getSettlements($data){
        $settlements = collect();
    
        foreach ($data as $line){

            $settlement_type = new \stdClass();
            $settlement_type->name = $line->d_tipo_asenta;

            $d_asenta = mb_strtoupper($this->eliminarAcentos(html_entity_decode($line->d_asenta))   );

            $settle = new \stdClass();
            $settle->key                = intval(html_entity_decode($line->id_asenta_cpcons));
            $settle->name               = $d_asenta;
            $settle->zone_type          = mb_strtoupper($this->eliminarAcentos($line->d_zona));
            $settle->settlement_type    = $settlement_type;

            $settlements->push($settle);
        }
        return $settlements;
    }


    /**
     * Funcion Federal Entity
     */
    public function federalEntity($dato){
        $d_estado = $dato->d_estado;
        $federal_entity = new \stdClass();
        $federal_entity->key  = intval($dato->c_estado);
        $federal_entity->name =  $d_estado;

        if ($dato->c_cp===""){
            $dato->c_cp=null;
        }

        $federal_entity->code = $dato->c_cp;
        return $federal_entity;
    }

    public function getMunicipality($dato){
        $municipality = new \stdClass();
        $municipality->key  = intval($dato->c_mnpio);

        $d_mnpio = mb_strtoupper($this->eliminarAcentos(html_entity_decode(($dato->d_mnpio))));
        $municipality->name = $d_mnpio;
        return $municipality;
    }
}
