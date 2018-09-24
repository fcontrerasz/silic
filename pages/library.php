<?php 

function random_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return "#".$c;
}

function normaliza($cadena){
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","ý");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","y");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

function limpiar($s)
{
$s = preg_replace("/[áàâãª]/","a",$s);
$s = preg_replace("/[ÁÀÂÃ]/","A",$s);
$s = preg_replace("/[ÍÌÎ]/","I",$s);
$s = preg_replace("/[íìî]/","i",$s);
$s = preg_replace("/[éèê]/","e",$s);
$s = preg_replace("/[ÉÈÊ]/","E",$s);
$s = preg_replace("/[óòôõº]/","o",$s);
$s = preg_replace("/[ÓÒÔÕ]/","O",$s);
$s = preg_replace("/[úùû]/","u",$s);
$s = preg_replace("/[ÚÙÛ]/","U",$s);
$s = str_replace("ç","c",$s);
$s = ereg_replace("Ç","C",$s);
$s = ereg_replace("ñ","n",$s);
$s = ereg_replace("Ñ","N",$s);
$s = ereg_replace ("ö","o", $s );
//$s = preg_replace("([^\w\s\d\-_~,;:\[\]\(\]]{2,})", '', $s);
return $s;
}

function generatePassword($length = 8)
{
$password = "";
$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
$maxlength = strlen($possible);
if ($length > $maxlength) {
  $length = $maxlength;
}
$i = 0; 
while ($i < $length) { 
  $char = substr($possible, mt_rand(0, $maxlength-1), 1);
  if (!strstr($password, $char)) { 
	$password .= $char;
	$i++;
  }
}
return $password;
}

function acortarGlosa($string, $length=NULL)
{
    if ($length == NULL)
            $length = 50;
   
    $stringDisplay = substr(strip_tags($string), 0, $length);
    if (strlen(strip_tags($string)) > $length)
        $stringDisplay .= ' ...';
    return $stringDisplay;
}

function msj_nuevasEmpresas(){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric = "SELECT count(*) as total FROM empresas WHERE ID_ESTADOEMP = 3";
$rsGeneric = mysql_query($query_rsGeneric, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric = mysql_fetch_assoc($rsGeneric);
$totalRows_rsGeneric = mysql_num_rows($rsGeneric);
return $row_rsGeneric["total"];
}

/*
function existeBaseRequisito(){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric = "SELECT count(*) as total FROM empresas WHERE ID_ESTADOEMP = 3";
$rsGeneric = mysql_query($query_rsGeneric, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric = mysql_fetch_assoc($rsGeneric);
$totalRows_rsGeneric = mysql_num_rows($rsGeneric);
return $row_rsGeneric["total"];
}*/



function msj_nuevosProveedores($idempresa){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric = "SELECT count(*) as total FROM usuarios WHERE ID_PERFIL = 3 AND CLAVE = '' AND ID_EMPRESA = ".$idempresa;
$rsGeneric = mysql_query($query_rsGeneric, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric = mysql_fetch_assoc($rsGeneric);
$totalRows_rsGeneric = mysql_num_rows($rsGeneric);
return $row_rsGeneric["total"];
}

function nuevasPreguntas($idlicitacion){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric = "SELECT ID_PREGUNTA FROM listar_nuevas_respuestas WHERE TOTAL = 0 AND ID_LICITACION = ".$idlicitacion;
$rsGeneric = mysql_query($query_rsGeneric, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric = mysql_fetch_assoc($rsGeneric);
$totalRows_rsGeneric = mysql_num_rows($rsGeneric);
return $totalRows_rsGeneric;
}

function nombreLicitacion($idlicitacion){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric5 = "SELECT NOMBRELICI FROM licitaciones WHERE ID_LICITACION = ".$idlicitacion;
$rsGeneric5 = mysql_query($query_rsGeneric5, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric5 = mysql_fetch_assoc($rsGeneric5);
$totalRows_rsGeneric5 = mysql_num_rows($rsGeneric5);
return $row_rsGeneric5["NOMBRELICI"];
}


function tipoLicitacion($idlicitacion){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric5 = "SELECT ID_TIPOLICITACION FROM licitaciones WHERE ID_LICITACION = ".$idlicitacion;
$rsGeneric5 = mysql_query($query_rsGeneric5, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric5 = mysql_fetch_assoc($rsGeneric5);
$totalRows_rsGeneric5 = mysql_num_rows($rsGeneric5);
return $row_rsGeneric5["ID_TIPOLICITACION"];
}

function estadoEmpresa($idempresa){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric2 = "SELECT ID_ESTADOEMP FROM empresas WHERE ID_EMPRESA = ".$idempresa;
$rsGeneric2 = mysql_query($query_rsGeneric2, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric2 = mysql_fetch_assoc($rsGeneric2);
$totalRows_rsGeneric2 = mysql_num_rows($rsGeneric2);
return $row_rsGeneric2["ID_ESTADOEMP"];
}

function EmpresaPostula($idempresa,$idlicita){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric5 = "SELECT ID_POSTULACIONES FROM postulaciones WHERE ID_EMPRESA = ".$idempresa." AND ID_LICITACION = ".$idlicita;
$rsGeneric5 = mysql_query($query_rsGeneric5, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric5 = mysql_fetch_assoc($rsGeneric5);
$totalRows_rsGeneric5 = mysql_num_rows($rsGeneric5);
return $totalRows_rsGeneric5;
}

function licitacionesEmpresas($idempresa){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  postulaciones.ID_LICITACION
FROM
  postulaciones
WHERE
  postulaciones.ID_EMPRESA = '".$idempresa."'
UNION DISTINCT
SELECT 
licitaciones.ID_LICITACION
FROM `licitaciones` 
WHERE
  (ID_TIPOLICITACION = 1)";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$postulaciones = "";
$conta = 1;
do {  
$postulaciones .= $row_rsGeneric3['ID_LICITACION'];
if($conta<$totalRows_rsGeneric3){
$postulaciones .= ",";
}
$conta++;
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
return $postulaciones;
}

function miEstadoLicita($idprovee,$idlicita){
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  postulaciones.ID_POSTULACIONES
FROM
  postulaciones
WHERE
  postulaciones.ID_EMPRESA = '".$idprovee."' and postulaciones.ID_LICITACION = '".$idlicita."'";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
return $totalRows_rsGeneric3;
}

function misLicitacionesProveedores($idprovee){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  postulaciones.ID_LICITACION
FROM
  postulaciones
WHERE
  postulaciones.ID_EMPRESA = '".$idprovee."'";
 // echo $query_rsGeneric3;
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
do {  
$postulaciones .= $row_rsGeneric3['ID_LICITACION'];
if($conta<$totalRows_rsGeneric3){
$postulaciones .= ",";
}
$conta++;
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
//echo $query_rsGeneric3;
return $postulaciones;
}

function licitacionesProveedores($idprovee,$idempresa){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  postulaciones.ID_LICITACION
FROM
  postulaciones
WHERE
  postulaciones.ID_EMPRESA = '".$idprovee."'
  UNION DISTINCT
SELECT 
licitaciones.ID_LICITACION
FROM `licitaciones` 
WHERE
  (ID_TIPOLICITACION = 1 AND ID_ESTADOLICITA = 1 AND ID_EMPRESACREADORA='".$idempresa."')";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
do {  
$postulaciones .= $row_rsGeneric3['ID_LICITACION'];
if($conta<$totalRows_rsGeneric3){
$postulaciones .= ",";
}
$conta++;
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
//echo $query_rsGeneric3;
return $postulaciones;
}


function haceDias($fecha_unix){
        $ahora=time();
		$segundos=$fecha_unix-$ahora;
        $dias=floor($segundos/86400);
	    return $dias;
}

function haceHoras($fecha_unix){
        $ahora=time();
		$segundos=$fecha_unix-$ahora;
        $dias=floor($segundos/86400);
        $mod_hora=$segundos%86400;
        $horas=floor($mod_hora/3600);
	    return $horas;
}

function haceMinu($fecha_unix){
        $ahora=time();
		$segundos=$fecha_unix-$ahora;
        $dias=floor($segundos/86400);
        $mod_hora=$segundos%86400;
        $horas=floor($mod_hora/3600);
        $mod_minuto=$mod_hora%3600;
        $minutos=floor($mod_minuto/60);
	    return $minutos+"";
}

function hace($fecha_unix){
        $ahora=time();
		$segundos=$fecha_unix-$ahora;
        $dias=floor($segundos/86400);
	    echo "DIAS: ".$dias."<----<br>";
        $mod_hora=$segundos%86400;
        $horas=floor($mod_hora/3600);
	    echo "HORAS: ".$horas."<----<br>";
        $mod_minuto=$mod_hora%3600;
        $minutos=floor($mod_minuto/60);
		echo "MINUTOS: ".$minutos."<----<br>";
        if($horas<=0){
                echo $minutos.' minutos';
        }elseif($dias<=0){
                echo $horas.' horas '.$minutos.' minutos';
        }else{
                echo $dias.' dias '.$horas.' horas '.$minutos.' minutos';
        }
}


function counterProveedoresPostula($idprovee){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  *
FROM
  listar_restantes_consultas
WHERE
  ID_POSTULACIONES = '".$idprovee."'";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
$postulaciones = "";
do {  
if($row_rsGeneric3['RESTANTES']>0){

$minutos = haceMinu($row_rsGeneric3['RESTANTES']);
$dias = haceDias($row_rsGeneric3['RESTANTES']);
$horas = haceHoras($row_rsGeneric3['RESTANTES']);
$postulaciones .= "zxcCountDown('counter".$row_rsGeneric3['ID_POSTULACIONES']."','Vencido',59,$minutos,$horas,$dias);\n";
$conta++;
}
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
//echo $query_rsGeneric3;
return $postulaciones;
}

function counterPostulaadmin($filtro){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  *
FROM
  listar_restantes_adminempresa WHERE ID_EMPRESACREADORA = ".$filtro;
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
$postulaciones = "";
do {  
if($row_rsGeneric3['RESTANTES']>0){
$minutos = haceMinu($row_rsGeneric3['RESTANTES']);
$dias = haceDias($row_rsGeneric3['RESTANTES']);
$horas = haceHoras($row_rsGeneric3['RESTANTES']);
$postulaciones .= "zxcCountDown('counter".$row_rsGeneric3['ID_LICITACION']."','Vencido',59,$minutos,$horas,$dias);\n";
$conta++;
}
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
return $postulaciones;
}

function counterPostula(){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  *
FROM
  listar_maestro_restantes";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
$postulaciones = "";
do {  
if($row_rsGeneric3['RESTANTES']>0){
$minutos = haceMinu($row_rsGeneric3['RESTANTES']);
$dias = haceDias($row_rsGeneric3['RESTANTES']);
$horas = haceHoras($row_rsGeneric3['RESTANTES']);
$postulaciones .= "zxcCountDown('counter".$row_rsGeneric3['ID_LICITACION']."','Vencido',59,$minutos,$horas,$dias);\n";
$conta++;
}
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
return $postulaciones;
}

function counterPostulaLici($idlicita){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  *
FROM
  listar_maestro_restantes
WHERE
  ID_LICITACION = '".$idlicita."'";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
$postulaciones = "";
do {  
if($row_rsGeneric3['RESTANTES']>0){

$minutos = haceMinu($row_rsGeneric3['RESTANTES']);
$dias = haceDias($row_rsGeneric3['RESTANTES']);
$horas = haceHoras($row_rsGeneric3['RESTANTES']);
$postulaciones .= "zxcCountDown('counter".$row_rsGeneric3['ID_LICITACION']."','Vencido',59,$minutos,$horas,$dias);\n";
$conta++;
}
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
return $postulaciones;
}


function counterProveedores($idprovee){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric3 = "SELECT 
  *
FROM
  listar_restantes_consultas
WHERE
  ID_EMPRESA = '".$idprovee."'";
$rsGeneric3 = mysql_query($query_rsGeneric3, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3);
$totalRows_rsGeneric3 = mysql_num_rows($rsGeneric3);
if($totalRows_rsGeneric3>0){
$conta = 1;
$postulaciones = "";
do {  
if($row_rsGeneric3['RESTANTES']>0){

$minutos = haceMinu($row_rsGeneric3['RESTANTES']);
$dias = haceDias($row_rsGeneric3['RESTANTES']);
$horas = haceHoras($row_rsGeneric3['RESTANTES']);
$postulaciones .= "zxcCountDown('counter".$row_rsGeneric3['ID_LICITACION']."','Vencido',59,$minutos,$horas,$dias);\n";
$conta++;
}
} while ($row_rsGeneric3 = mysql_fetch_assoc($rsGeneric3));
}
//echo $query_rsGeneric3;
return $postulaciones;
}

function licitacionesPostulacion($idpostulacion){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric4 = "SELECT 
  ID_LICITACION
FROM
  postulaciones
WHERE
  ID_POSTULACIONES = ".$idpostulacion;
$rsGeneric4 = mysql_query($query_rsGeneric4, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric4 = mysql_fetch_assoc($rsGeneric4);
$totalRows_rsGeneric4 = mysql_num_rows($rsGeneric4);
if($totalRows_rsGeneric4>0){
$postulaciones = $row_rsGeneric4['ID_LICITACION'];
}
return $postulaciones;
}


function postulacionLicitaciones($idlicitacion, $idempresa){
$postulaciones = "0";
global $database_cn_licitaciones, $cn_licitaciones;
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsGeneric4 = "SELECT 
  ID_POSTULACIONES
FROM
  postulaciones
WHERE
  ID_LICITACION = ".$idlicitacion." AND ID_EMPRESA = ".$idempresa;
 // echo " ------>".$_SESSION['MM_UserID']."   --->".$query_rsGeneric4;
$rsGeneric4 = mysql_query($query_rsGeneric4, $cn_licitaciones) or die(mysql_error());
$row_rsGeneric4 = mysql_fetch_assoc($rsGeneric4);
$totalRows_rsGeneric4 = mysql_num_rows($rsGeneric4);
if($totalRows_rsGeneric4>0){
$postulaciones = $row_rsGeneric4['ID_POSTULACIONES'];
}
return $postulaciones;
}


?>