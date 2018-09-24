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

$colname_rsDivisiones = "-1";
if (isset($_GET['EMPRESA'])) {
  $colname_rsDivisiones = $_GET['EMPRESA'];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsDivisiones = sprintf("SELECT ID_DIVISION, NOMDIVISION FROM divisiones WHERE ID_EMPRESA = %s ORDER BY NOMDIVISION ASC", GetSQLValueString($colname_rsDivisiones, "int"));
$rsDivisiones = mysql_query($query_rsDivisiones, $cn_licitaciones) or die(mysql_error());
$row_rsDivisiones = mysql_fetch_assoc($rsDivisiones);
$totalRows_rsDivisiones = mysql_num_rows($rsDivisiones);
?>
<?php
echo '<option value="0">-- SELECCIONE --</option>';
if($totalRows_rsDivisiones > 0 and $colname_rsDivisiones != "0"){
do{
echo '<option value="'.$row_rsDivisiones["ID_DIVISION"].'"';
echo '>'.$row_rsDivisiones["NOMDIVISION"].'</option>';
} while ($row_rsDivisiones = mysql_fetch_assoc($rsDivisiones));
}
?>
<?php
mysql_free_result($rsDivisiones);
?>
