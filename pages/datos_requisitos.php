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

$colname_rsDatosEmpresa = "-1";
if (isset($_GET['IDLICITACION'])) {
  $colname_rsDatosEmpresa = $_GET['IDLICITACION'];
}

$colname_rsDatosEmpresaNombre = "-1";
if (isset($_GET['NOMBRE'])) {
  $colname_rsDatosEmpresaNombre = $_GET['NOMBRE'];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsDatosEmpresa = sprintf("SELECT * FROM listado_requisitos WHERE id_licitacion = %s and docu_nomarchivo = %s", GetSQLValueString($colname_rsDatosEmpresa, "int"), GetSQLValueString($colname_rsDatosEmpresaNombre, "text"));
$rsDatosEmpresa = mysql_query($query_rsDatosEmpresa, $cn_licitaciones) or die(mysql_error());
$row_rsDatosEmpresa = mysql_fetch_assoc($rsDatosEmpresa);
$totalRows_rsDatosEmpresa = mysql_num_rows($rsDatosEmpresa);

echo $totalRows_rsDatosEmpresa;

mysql_free_result($rsDatosEmpresa);
?>