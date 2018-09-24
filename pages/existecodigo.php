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

$colname_rsCodigoLicitacion = "-1";
if (isset($_GET['CODIGOLICI'])) {
  $colname_rsCodigoLicitacion = $_GET['CODIGOLICI'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsCodigoLicitacion = sprintf("SELECT * FROM licitaciones WHERE CODIGOLICI = %s", GetSQLValueString($colname_rsCodigoLicitacion, "text"));
$rsCodigoLicitacion = mysql_query($query_rsCodigoLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsCodigoLicitacion = mysql_fetch_assoc($rsCodigoLicitacion);
$totalRows_rsCodigoLicitacion = mysql_num_rows($rsCodigoLicitacion);

echo $totalRows_rsCodigoLicitacion;

mysql_free_result($rsCodigoLicitacion);
?>