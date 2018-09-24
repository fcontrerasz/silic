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

$colname_rsDatosLicitacion = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsDatosLicitacion = $_GET['ID_LICITACION'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsDatosLicitacion = sprintf("SELECT * FROM licitaciones WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsDatosLicitacion, "int"));
$rsDatosLicitacion = mysql_query($query_rsDatosLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsDatosLicitacion = mysql_fetch_assoc($rsDatosLicitacion);
$totalRows_rsDatosLicitacion = mysql_num_rows($rsDatosLicitacion);

$deadtime = date("Y-m-d",strtotime($row_rsDatosLicitacion['FECHA_TERMINO']));
$today = date("Y-m-d");  

$estadobloq = 0;

//echo "-->".$today." mayor a ".$deadtime."<----";

if($today>$deadtime){ 
	$estadobloq = 1;
}


if($totalRows_rsDatosLicitacion > 0){
//.",".
echo $row_rsDatosLicitacion['ID_TIPOLICITACION'].",".$row_rsDatosLicitacion['ID_ESTADOLICITA'].",".$row_rsDatosLicitacion['NOMBRELICI'].",".$row_rsDatosLicitacion['CODIGOLICI'].",".$row_rsDatosLicitacion['FECHA_CREACION'].",".date("d/m/Y",strtotime($row_rsDatosLicitacion['FECHA_INICIO'])).",".date("d/m/Y",strtotime($row_rsDatosLicitacion['FECHA_TERMINO'])).",".$row_rsDatosLicitacion['DESCLICI'].",".$row_rsDatosLicitacion['HORA_PST'].",".$row_rsDatosLicitacion['HORA_CST'].",".$row_rsDatosLicitacion['ID_LICITACION'].",".$estadobloq.",".date("d/m/Y H:i",strtotime($row_rsDatosLicitacion['FECHA_CONSULTAS']));
}

mysql_free_result($rsDatosLicitacion);
?>