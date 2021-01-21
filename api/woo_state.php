<?php
header('Content-Type: application/json');

require_once "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
global $arrayEstado; 

    # Crear la ruta si no existe
    $rutaDeSubidas = __DIR__ . "\upload";
    if (!is_dir($rutaDeSubidas)) {
    mkdir($rutaDeSubidas, 0777, true);
    }

if(!empty($_FILES["archivo"])){     

    $informacionDelArchivo = $_FILES["archivo"];
    $ubicacionTemporal = $informacionDelArchivo["tmp_name"];
    $nombreArchivo = $informacionDelArchivo["name"];
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        if(file_exists($rutaDeSubidas)){
            if($extension=='xlsb'){
                convertFile($ubicacionTemporal,$rutaDeSubidas);
            } else if($extension=='xlsx'|| $extension =='xls'){           
                $nuevaUbicacion = $rutaDeSubidas."\\".$nombreArchivo;              
                $resultado = move_uploaded_file($ubicacionTemporal, $nuevaUbicacion);
                        if ($resultado === true) {
                        printExcel($nuevaUbicacion,$rutaDeSubidas);
                        } else { echo "<h1>Error al subir archivo</h1>"; }
                   }
                   else{
                    echo "<h1>La extension no es aceptable </h1>";
                   } 
        }else{ echo "<h1>la ubicaciones no existe </h1>";  }
        }else{ echo '<h1>No subio Ningun Archivo</h1>';  } 


    function convertFile($rutaArchivo,$rootRoute){
        // Creamos la instancia de Excel files
            $workbook = new COM("EasyXLS.ExcelDocument");
            // Cargar XLSB file
            $workbook->easy_LoadXLSBFile($rutaArchivo);
            // Escribir XLSX file
          $workbook->easy_WriteXLSXFile( $rootRoute."\Excel.xlsx");
        printExcel($rootRoute."\Excel.xlsx",$rootRoute);
    }   
    
    function printExcel($archivo,$carpeta){
        global $arrayEstado;
        $arrayEstado=array();
        $cont = 0;
        if (file_exists($archivo)){
           $documento = IOFactory::load($archivo);  
           # Los documentos pueden  tener múltiples hojas  por eso tenemos que iterarlos 
        //    $totalDeHojas = $documento->getSheetCount(); 
          // for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {          
           # Obtener hoja en el índice que vaya del ciclo
               $hojaActual = $documento->getSheet(0);
               #iteramos las filas con foreach
               foreach($hojaActual->getRowIterator() as $fila ){
                   
                foreach($fila->getCellIterator() as $celda){ 
               #pagina ayuda
               #https://parzibyte.me/blog/2019/02/14/leer-archivo-excel-php-phpspreadsheet/       
               # El valor, así como está en el documento
               $valorRaw = $celda->getValue();   
               # Formateado por ejemplo como dinero o con decimales
            //    $valorFormateado = $celda->getFormattedValue();    
            //    # Si es una fórmula y necesitamos su valor, llamamos a:
            //    $valorCalculado = $celda->getCalculatedValue();       
            //    # Fila, que comienza en 1, luego 2 y así...
            //    $fila = $celda->getRow();
               # Columna, que es la A, B, C y así...      
               $columna = $celda->getColumn();
               if($columna=='A' && $valorRaw!=''){
                  if($valorRaw!='Remesa'){
                        //$arrayEstado[$valorRaw]='POR CAMBIAR';
                        $arrayEstado[$cont]['id_guia']=$valorRaw;
                        $cont++;
                    }
            }}}} else{  
                //http_response_code(400);
                status_header(400);
                if (!isset($res))
                {   $res = (object)array();         }
                $res->data = "El archivo no existe";              
            }
            //printTable($arrayEstado); 
            deleteDir($carpeta);               
            output_json($arrayEstado);
           }

    function output_json($arrayEstado){
        if (!isset($res))
        {
          $res = (object)array();
        }
        $res->data = "transferencia correcta";
        echo json_encode($arrayEstado);
        //echo json_encode($res);
        exit;

    }
    
    function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }}
        rmdir($dirPath);
    }
    
    function printTable($valores){ 
      ?> 
        <div style="text-align:center"> 
            <table>
            <tbody> 
            <?php foreach($valores as $key=>$value) {?>
                <tr> <?php 
                    echo '<td>'.$key.'</td>';
                    echo '<td>'.$value.'</td>';
                    ?>
                </tr> 
                <?php }?>   
            </tbody>
            </table> 
        </div>
        <style>
        table {  position:center;    }
         table, th, td { border: 1px solid black;   border:none;   }     
         </style>  
           <?php 
      }
    
    
    
    
    