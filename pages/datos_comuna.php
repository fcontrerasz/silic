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

$colname_rsComunas = "-1";
if (isset($_GET['REGION'])) {
  $colname_rsComunas = $_GET['REGION'];
}

$colname_XrsComunas = "-1";
if (isset($_GET['ACTIVO'])) {
  $colname_XrsComunas = $_GET['ACTIVO'];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsComunas = sprintf("SELECT ID_COMUNA, UPPER(CR_COMUNA) AS CR_COMUNA  FROM listar_maestro_comunas WHERE ID_REGION = %s ORDER BY CR_COMUNA ASC", GetSQLValueString($colname_rsComunas, "int"));
$rsComunas = mysql_query($query_rsComunas, $cn_licitaciones) or die(mysql_error());
$row_rsComunas = mysql_fetch_assoc($rsComunas);
$totalRows_rsComunas = mysql_num_rows($rsComunas);
?>
<?php
echo '<option value="0">-- SELECCIONE --</option>';
if($totalRows_rsComunas > 0){
do{


echo '<option value="'.$row_rsComunas["ID_COMUNA"].'"';
if (!(strcmp($row_rsComunas["ID_COMUNA"], $colname_XrsComunas))) {echo "selected=\"selected\"";}
echo '>'.$row_rsComunas["CR_COMUNA"].'</option>';

} while ($row_rsComunas = mysql_fetch_assoc($rsComunas));

}
?>
<?php
mysql_free_result($rsUsuario);

mysql_free_result($rsComunas);
?>
