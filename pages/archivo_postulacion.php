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

$colname_rsArchivo = "-1";
if (isset($_GET['ID_DOCUMENTACION'])) {
  $colname_rsArchivo = $_GET['ID_DOCUMENTACION'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsArchivo = sprintf("SELECT * FROM documentacion WHERE ID_DOCUMENTACION = %s", GetSQLValueString($colname_rsArchivo, "int"));
$rsArchivo = mysql_query($query_rsArchivo, $cn_licitaciones) or die(mysql_error());
$row_rsArchivo = mysql_fetch_assoc($rsArchivo);
$totalRows_rsArchivo = mysql_num_rows($rsArchivo);

$file = $row_rsArchivo['docu_nomarchivo'];
header ("Content-Disposition: attachment; filename=".$file." "); 
header('Content-type: '.$row_rsArchivo['DOCU_TIPO']); // possible but still strange...
//header ("Content-Length: ".filesize($row_rsArchivo['DOCU_ARCHIVO']));//readfile($url); 
echo $row_rsArchivo['DOCU_ARCHIVO'];
exit;

mysql_free_result($rsArchivo);
?>