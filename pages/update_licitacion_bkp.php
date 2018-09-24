<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php
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

//echo $_GET['ndesde']."<------";


$x1= explode("/",  $_GET['ndesde']);
$fecha1 = $x1[2]."-".$x1[1]."-".$x1[0];
$x1= explode("/",  $_GET['nhasta']);
$fecha2 = $x1[2]."-".$x1[1]."-".$x1[0];
$x1= explode("/",  $_GET['c_hasta']);
$c2= explode(" ",  $x1[2]);
$fecha3 = $c2[0]."-".$x1[1]."-".$x1[0]." ".$c2[1];
//$fecha1 = date("m/d/Y", mktime(0, 0, 0, $x[1], $x[0], $x[2]));


$insertSQL = sprintf("CALL `update_licitacion`(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, @result, %s);",
                       GetSQLValueString($_GET['tipo'], "int"),
                       GetSQLValueString($_GET['estado'], "int"),
                       GetSQLValueString($_GET['nombre'], "text"),
                       GetSQLValueString($_GET['codigo'], "text"),
                       GetSQLValueString($fecha1, "text"),
                       GetSQLValueString($fecha2, "text"),
					   GetSQLValueString($fecha3, "text"),
					   GetSQLValueString($_GET['glosa'], "text"),
					   GetSQLValueString($_GET['hora1'], "text"),
                       GetSQLValueString($_GET['hora2'], "text"),
					   GetSQLValueString($_GET['idlicitacion'], "int"));

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);
    echo $row_rsRubros["estado"];
	//echo $insertSQL;

?>