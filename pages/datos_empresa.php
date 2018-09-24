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
if (isset($_GET['ID_EMPRESA'])) {
  $colname_rsDatosEmpresa = $_GET['ID_EMPRESA'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsDatosEmpresa = sprintf("SELECT * FROM listar_info_empresa WHERE id_empresa = %s ORDER BY PROVEEDOR ASC", GetSQLValueString($colname_rsDatosEmpresa, "int"));
$rsDatosEmpresa = mysql_query($query_rsDatosEmpresa, $cn_licitaciones) or die(mysql_error());
$row_rsDatosEmpresa = mysql_fetch_assoc($rsDatosEmpresa);
$totalRows_rsDatosEmpresa = mysql_num_rows($rsDatosEmpresa);

if($totalRows_rsDatosEmpresa > 0){

echo $row_rsDatosEmpresa['ID_COMUNA'].",".$row_rsDatosEmpresa['ID_REGION'].",".$row_rsDatosEmpresa['ID_RUBRO'].",".$row_rsDatosEmpresa['ID_ESTADOEMP'].",".$row_rsDatosEmpresa['RUT'].",".$row_rsDatosEmpresa['DV'].",".$row_rsDatosEmpresa['DIRECCION'].",".$row_rsDatosEmpresa['FONO'].",".$row_rsDatosEmpresa['PROVEEDOR'].",".$row_rsDatosEmpresa['ID_PROVINCIA'];

}

mysql_free_result($rsDatosEmpresa);
?>