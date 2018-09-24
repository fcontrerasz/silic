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

$colname_rsRutEmpresa = "-1";
if (isset($_GET['RUT'])) {
  $colname_rsRutEmpresa = $_GET['RUT'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRutEmpresa = sprintf("SELECT * FROM listar_maestro_rut WHERE RUT = %s", GetSQLValueString($colname_rsRutEmpresa, "int"));
$rsRutEmpresa = mysql_query($query_rsRutEmpresa, $cn_licitaciones) or die(mysql_error());
$row_rsRutEmpresa = mysql_fetch_assoc($rsRutEmpresa);
$totalRows_rsRutEmpresa = mysql_num_rows($rsRutEmpresa);

echo $totalRows_rsRutEmpresa;

mysql_free_result($rsRutEmpresa);
?>