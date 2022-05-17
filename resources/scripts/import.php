<?php
$handle = fopen("CPdescarga.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $arrZip = explode('|',$line);
        if (is_numeric($arrZip['0'])){
            
            $servername = "localhost";
            $database = "zipcodes";
            $username = "root";
            $password = "";
            $conn = mysqli_connect($servername, $username, $password, $database);


           $text=mb_convert_encoding($arrZip['4'],'UTF-8','ISO-8859-15');
         
            
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            echo "Connected successfully $line \n";
                foreach ($arrZip as $key =>$arrZ){
                    $arrZipNew[$key] =  htmlentities(mb_convert_encoding($arrZ,'UTF-8','ISO-8859-15'));
                    $arrZipNew[$key] = trim(preg_replace("[\n|\r|\n\r]", "",$arrZipNew[$key]));
            }

            $sql = "INSERT INTO zip_codes (d_codigo,d_asenta,d_tipo_asenta,D_mnpio,d_estado,d_ciudad,d_CP,c_estado,c_oficina,c_CP,c_tipo_asenta,c_mnpio,id_asenta_cpcons,d_zona,c_cve_ciudad) VALUES ('$arrZipNew[0]','$arrZipNew[1]', '$arrZipNew[2]','$arrZipNew[3]','$arrZipNew[4]','$arrZipNew[5]','$arrZipNew[6]','$arrZipNew[7]','$arrZipNew[8]','$arrZipNew[9]','$arrZipNew[10]','$arrZipNew[11]','$arrZipNew[12]','$arrZipNew[13]','$arrZipNew[14]')";
            
            if (mysqli_query($conn, $sql)) {
                echo "Nuevo Registro importado\n";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }             
    }

    fclose($handle);
}
