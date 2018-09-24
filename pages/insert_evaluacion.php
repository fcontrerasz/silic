<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}


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

$insertSQL = sprintf("CALL `insert_evaluaciones`(%s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,@result);",
                       GetSQLValueString($_GET['idlicitacion'], "int"),
                       GetSQLValueString($_GET['categoria1'], "text"),
                       GetSQLValueString($_GET['categoria2'], "text"),
                       GetSQLValueString($_GET['categoria3'], "text"),
                       GetSQLValueString($_GET['item11'], "text"),
                       GetSQLValueString($_GET['item12'], "text"),
                       GetSQLValueString($_GET['item13'], "text"),
                       GetSQLValueString($_GET['item14'], "text"),
                       GetSQLValueString($_GET['item15'], "text"),
                       GetSQLValueString($_GET['item21'], "text"),
                       GetSQLValueString($_GET['item22'], "text"),
                       GetSQLValueString($_GET['item23'], "text"),
                       GetSQLValueString($_GET['item24'], "text"),
                       GetSQLValueString($_GET['item25'], "text"),
                       GetSQLValueString($_GET['item31'], "text"),
                       GetSQLValueString($_GET['item32'], "text"),
                       GetSQLValueString($_GET['item33'], "text"),
                       GetSQLValueString($_GET['item34'], "text"),
                       GetSQLValueString($_GET['item35'], "text"),
                       GetSQLValueString($_GET['vcategoria1'], "int"),
                       GetSQLValueString($_GET['vcategoria2'], "int"),
                       GetSQLValueString($_GET['vcategoria3'], "int"),
                       GetSQLValueString($_GET['vitem11'], "int"),
                       GetSQLValueString($_GET['vitem12'], "int"),
                       GetSQLValueString($_GET['vitem13'], "int"),
                       GetSQLValueString($_GET['vitem14'], "int"),
                       GetSQLValueString($_GET['vitem15'], "int"),
                       GetSQLValueString($_GET['vitem21'], "int"),
                       GetSQLValueString($_GET['vitem22'], "int"),
                       GetSQLValueString($_GET['vitem23'], "int"),
                       GetSQLValueString($_GET['vitem24'], "int"),
                       GetSQLValueString($_GET['vitem25'], "int"),
                       GetSQLValueString($_GET['vitem31'], "int"),
                       GetSQLValueString($_GET['vitem32'], "int"),
                       GetSQLValueString($_GET['vitem33'], "int"),
                       GetSQLValueString($_GET['vitem34'], "int"),
                       GetSQLValueString($_GET['vitem35'], "int")
					   ,$_SESSION['MM_UserID']);

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  echo $row_rsRubros["estado"];
	

?>