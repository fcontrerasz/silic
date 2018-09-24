<?php require_once('Connections/cn_licitaciones.php'); ?>
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

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListarEmpresas = "SELECT * FROM listar_empresas_portada";
$rsListarEmpresas = mysql_query($query_rsListarEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsListarEmpresas = mysql_fetch_assoc($rsListarEmpresas);
$totalRows_rsListarEmpresas = mysql_num_rows($rsListarEmpresas);
?><div id="info-container">
	<ul id="infoticker">
    <?php do { ?>
	<li>
        
          <span><a href="#" id="<?php echo $row_rsListarEmpresas['ID_EMPRESA']; ?>"><?php echo $row_rsListarEmpresas['PROVEEDOR']; ?></a></span>
          
	</li>
    <?php } while ($row_rsListarEmpresas = mysql_fetch_assoc($rsListarEmpresas)); ?>
	
</ul>
</div>
<?php
mysql_free_result($rsListarEmpresas);
?>
