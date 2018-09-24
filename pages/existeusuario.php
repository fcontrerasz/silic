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

$colname_rsUsuarios = "-1";
if (isset($_GET['CORREO'])) {
  $colname_rsUsuarios = $_GET['CORREO'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUsuarios = sprintf("SELECT * FROM usuarios WHERE CORREO = %s", GetSQLValueString($colname_rsUsuarios, "text"));
$rsUsuarios = mysql_query($query_rsUsuarios, $cn_licitaciones) or die(mysql_error());
$row_rsUsuarios = mysql_fetch_assoc($rsUsuarios);
$totalRows_rsUsuarios = mysql_num_rows($rsUsuarios);

//echo $query_rsUsuarios;

echo $totalRows_rsUsuarios;

mysql_free_result($rsUsuarios);
?>