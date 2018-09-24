<?php 
//header('Content-type: text/html;charset=UTF-8');
//mb_internal_encoding('utf-8');
//header("Cache-Control: no-store, no-cache, must-revalidate"); 
?>
<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function limpiarCaracteresEspeciales($string){
$order   = array("\r\n", "\n", "\r");
$string = str_replace($order, "", $string);
$vowels = array("'", ",","´");
$string = str_replace($vowels, "", $string);
return $string;
}

$charset = mb_detect_encoding($_GET['texto'], 'UTF-8, ISO-8859-1');
if($charset=="UTF-8"){
	$textodata = limpiarCaracteresEspeciales($_GET['texto']);
}else{
	$textodata = limpiarCaracteresEspeciales(utf8_encode($_GET['texto']));
}

$insertSQL = sprintf("CALL `insert_pregunta`(%s, %s, %s, %s, @result);",
                       GetSQLValueString($_GET['idpostulacion'], "int"),
                       GetSQLValueString($textodata, "text"),
					   GetSQLValueString($_GET['tipo'], "int"),$_SESSION['MM_UserID']);

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());
  
  //$encoding = htmlentities($_GET['texto']);
  //$escName = mb_convert_encoding($_GET['texto'], $encoding, "UTF-8");
  //echo "".$encoding." _ ".utf8_decode($encoding);
  


  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  echo $row_rsRubros["estado"];
	

?>