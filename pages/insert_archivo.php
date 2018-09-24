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

$archivo = $_FILES["archivito"]["tmp_name"]; 
$tamanio = $_FILES["archivito"]["size"];
$tipo    = $_FILES["archivito"]["type"];
$nombre  = $_FILES["archivito"]["name"];
/*
$insertSQL = sprintf("CALL `insert_usuario`(@result, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s);",
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
					   GetSQLValueString($_GET['idusuario'], "int"));

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $query_rsRubros = "SELECT @result as estado";
  $rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
  $row_rsRubros = mysql_fetch_assoc($rsRubros);

  echo $nombre;
 */	

?>
<?php
	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES['ARCHIVO']['tmp_name']) || $_FILES['ARCHIVO']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
			$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES['ARCHIVO']['tmp_name']);
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES['ARCHIVO']);		
	}		
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "'\n";
	echo "}";
?>