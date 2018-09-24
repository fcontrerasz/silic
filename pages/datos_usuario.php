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

$colname_rsUsuario = "-1";
if (isset($_GET['ID_USUARIO'])) {
  $colname_rsUsuario = $_GET['ID_USUARIO'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUsuario = sprintf("SELECT * FROM usuarios WHERE ID_USUARIO = %s ORDER BY NOMBRE ASC", GetSQLValueString($colname_rsUsuario, "int"));
$rsUsuario = mysql_query($query_rsUsuario, $cn_licitaciones) or die(mysql_error());
$row_rsUsuario = mysql_fetch_assoc($rsUsuario);
$totalRows_rsUsuario = mysql_num_rows($rsUsuario);
?>
<?php
if($totalRows_rsUsuario > 0){

echo $row_rsUsuario['NOMBRE'].",".$row_rsUsuario['APELLIDOPAT'].",".$row_rsUsuario['APELLIDOMAT'].",".$row_rsUsuario['USUARIO'].",".$row_rsUsuario['CLAVE'].",".$row_rsUsuario['CORREO'].",".$row_rsUsuario['ID_DIVISION'].",".$row_rsUsuario['ID_ESTUSU'].",".$row_rsUsuario['ID_PERFIL'].",".$row_rsUsuario['RUT'].",".$row_rsUsuario['DV'].",".$row_rsUsuario['ID_REGION'].",".$row_rsUsuario['ID_COMUNA'].",".$row_rsUsuario['USUPROVEEDOR'].",".$row_rsUsuario['USUDIRECCION'];

}
?>
<?php
mysql_free_result($rsUsuario);
?>
