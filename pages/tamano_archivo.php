<?php
function tamano_archivo($peso , $decimales = 2 ) {
$clase = array(" Bytes", " KB", " MB", " GB", " TB"); 
return round($peso/pow(1024,($i = floor(log($peso, 1024)))),$decimales ).$clase[$i];
}
$nombre_archivo = $_GET["archivo"]; // nombre archivo
$peso_archivo = filesize($nombre_archivo); // obtenemos su peso en bytes
echo tamano_archivo($peso_archivo);  // mostramos su peso ya modificado
?>