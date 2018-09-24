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

function limpiarCaracteresEspeciales($string ){
//$string = htmlentities($string);
//$string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
//$string = utf8_encode($string);
$vowels = array("\"", "'", ",","´");
$string = str_replace($vowels, "", $string);
return $string;
}

$x1= explode("/",  $_GET['ndesde']);
$fecha1 = $x1[2]."-".$x1[1]."-".$x1[0];
$x1= explode("/",  $_GET['nhasta']);
$fecha2 = $x1[2]."-".$x1[1]."-".$x1[0];
$x1= explode("/",  $_GET['c_hasta']);
$c2= explode(" ",  $x1[2]);
$fecha3 = $c2[0]."-".$x1[1]."-".$x1[0]." ".$c2[1];
$myglosa = limpiarCaracteresEspeciales($_GET['glosa']);
$mynombre = limpiarCaracteresEspeciales($_GET['nombre']);
$mycode = limpiarCaracteresEspeciales($_GET['codigo']);

$insertSQL = sprintf("CALL `insert_licitacion`(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, @result);",
                       GetSQLValueString($_GET['tipo'], "int"),
                       GetSQLValueString($_GET['estado'], "int"),
                       GetSQLValueString($mynombre, "text"),
                       GetSQLValueString($mycode, "text"),
                       GetSQLValueString($fecha1, "text"),
                       GetSQLValueString($fecha2, "text"),
					   GetSQLValueString($fecha3, "text"),
					   GetSQLValueString($myglosa, "text"),
					   GetSQLValueString($_GET['hora1'], "date"),
                       GetSQLValueString($_GET['hora2'], "date"),
					   $_SESSION['MM_Empresa'],$_SESSION['MM_UserID']);

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

 //echo " [".$myglosa."] [".$_GET['glosa']."]";
	
  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  echo $row_rsRubros["estado"];
	

?>