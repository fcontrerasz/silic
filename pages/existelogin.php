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

$colname_rsUserName = "-1";
if (isset($_GET['LOGIN'])) {
  $colname_rsUserName = $_GET['LOGIN'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUserName = sprintf("SELECT * FROM usuarios WHERE USUARIO = %s", GetSQLValueString($colname_rsUserName, "text"));
$rsUserName = mysql_query($query_rsUserName, $cn_licitaciones) or die(mysql_error());
$row_rsUserName = mysql_fetch_assoc($rsUserName);
$totalRows_rsUserName = mysql_num_rows($rsUserName);

echo $totalRows_rsUserName;

mysql_free_result($rsUserName);
?>