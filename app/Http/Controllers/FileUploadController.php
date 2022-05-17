<?php
   
namespace ZipCodeApp\Http\Controllers;
use ZipCodeApp\Models\ZipCode;
use Illuminate\Http\Request;
use DB;

class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return view('fileUpload');
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:txt|max:20481',
        ]);
  
        $fileName = time().'.'.$request->file->extension();  
   
        $request->file->move(public_path('uploads'), $fileName);
   
        return back()
            ->with('success','You have successfully upload file.')
            ->with('file',$fileName);
   
    }

    public function fileUploadDatabase(Request $request)
    {
        $fileName = $request->all();
        $file =$fileName['nombreArchivo'];

        
        $handle = fopen(public_path()."/uploads/$file", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $arrZip = explode('|',$line);
                if (is_numeric($arrZip['0'])){
                    foreach ($arrZip as $key =>$arrZ){
                            $arrZipNew[$key] =  htmlentities(mb_convert_encoding($arrZ,'UTF-8','ISO-8859-15'));
                            $arrZipNew[$key] = trim(preg_replace("[\n|\r|\n\r]", "",$arrZipNew[$key]));
                    }
                    
                    $d_codigo = $arrZipNew[0];
                    $d_asenta = $arrZipNew[1];
                    $d_tipo_asenta = $arrZipNew[2];
                    $D_mnpio =$arrZipNew[3];
                    $d_estado =$arrZipNew[4];
                    $d_ciudad=$arrZipNew[5];
                    $d_CP=$arrZipNew[6];
                    $c_estado=$arrZipNew[7];
                    $c_oficina=$arrZipNew[8];
                    $c_CP=$arrZipNew[9];
                    $c_tipo_asenta=$arrZipNew[10];
                    $c_mnpio=$arrZipNew[11];
                    $id_asenta_cpcons=$arrZipNew[12];
                    $d_zona=$arrZipNew[13];
                    $c_cve_ciudad=$arrZipNew[14];
                    
                    $data=array(
                        'd_codigo'=>$d_codigo,
                        "d_asenta"=>$d_asenta,
                        'd_tipo_asenta'=>$d_tipo_asenta,
                        "D_mnpio"=>$D_mnpio,
                        "d_estado"=>$d_estado,
                        'd_ciudad'=>$d_ciudad,
                        "d_CP"=>$d_CP,
                        "c_estado"=>$c_estado,
                        "c_oficina"=>$c_oficina,
                        'c_CP'=>$c_CP,
                        "c_tipo_asenta"=>$c_tipo_asenta,
                        "c_mnpio"=>$c_mnpio,
                        "id_asenta_cpcons"=>$id_asenta_cpcons,
                        "d_zona"=>$d_zona,
                        "c_cve_ciudad"=>$c_cve_ciudad
                    );
                    DB::table('zip_codes')->insert($data);


            
                }             
            }

            fclose($handle);
        }
        return redirect()->back()->with('message', 'Importados!');

    }
}