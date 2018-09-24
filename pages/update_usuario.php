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

$insertSQL = sprintf("CALL `update_usuario`(@result,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);",
                       GetSQLValueString($_GET['division'], "int"),
                       GetSQLValueString($_GET['perfil'], "int"),
                       GetSQLValueString($_GET['estado'], "int"),
                       GetSQLValueString($_GET['idempresa'], "int"),
                       GetSQLValueString($_GET['nombre'], "text"),
                       GetSQLValueString($_GET['paterno'], "text"),
                       GetSQLValueString($_GET['materno'], "text"),
			  GetSQLValueString($_GET['usuario'], "text"),
                       GetSQLValueString($_GET['clave'], "text"),
                       GetSQLValueString($_GET['correo'], "text"),
			  GetSQLValueString($_GET['idusuario'], "int"),
			  GetSQLValueString($_GET['region'], "int"),
			  GetSQLValueString($_GET['comunas'], "int"),
			  GetSQLValueString($_GET['empresa'], "text"),
			  GetSQLValueString($_GET['dire'], "text"),
			  $_SESSION['MM_UserID']);

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  echo $row_rsRubros["estado"];
	

?>