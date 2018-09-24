<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
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

if (isset($_POST['usuario'])) {
  $loginUsername=$_POST['usuario'];
  $password=$_POST['clave'];
  $MM_fldUserAuthorization = "ID_PERFIL";
  $MM_redirectLoginSuccess = "";
  $MM_redirectLoginFailed = "inicio.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  	
  $LoginRS__query=sprintf("SELECT USUARIO, CLAVE, ID_PERFIL, ID_EMPRESA, ID_ESTUSU, ID_USUARIO FROM usuarios WHERE USUARIO=%s AND CLAVE=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $cn_licitaciones) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'ID_PERFIL');
	$loginID  = mysql_result($LoginRS,0,'ID_USUARIO');
	$loginEmpresa  = mysql_result($LoginRS,0,'ID_EMPRESA');
	$statUser = mysql_result($LoginRS,0,'ID_ESTUSU');
    
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_UserID'] = $loginID;
	$_SESSION['MM_Empresa'] = $loginEmpresa;
	
	if(estadoEmpresa($_SESSION['MM_Empresa'])=="1"){
	
	if($statUser=="1"){
	
	switch($loginStrGroup){
	case "1":
		$MM_redirectLoginSuccess = "panel.php";
		break;
	case "2":
		$MM_redirectLoginSuccess = "panel_empresa.php";
		break;
	case "3":
		$MM_redirectLoginSuccess = "panel_proveedor.php";
		break;
	default: 
		$MM_redirectLoginSuccess = "index.php";
	}
	
	}else{
		$MM_redirectLoginSuccess = "informacion.php?mensaje=USUARIO INACTIVO CONTACTE A SU ADMINISTRADOR.";
	}
	
	}else{
	
	$MM_redirectLoginSuccess = "informacion.php?mensaje=EMPRESA INACTIVA O PENDIENTE DE HABILITARSE.";
	
	
	}
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OTIC</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<script src="js/jquery-1.4.2.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/jquery.vticker-min.js"></script>
<script src="js/jquery.numeric.js" type="text/javascript" ></script>
<script src="js/jquery.bestupper.min.js" type="text/javascript" ></script>
<script>
function avanzar(){
	$("#frm_acceso").submit();
}
function habilitar(){
	document.location.href = 'maqueta_gestion.php';
}

$(function(){
	$('#news-container').vTicker({ 
		speed: 1500,
		pause: 8000,
		animation: 'fade',
		mousePause: false,
		showItems: 6
	});
	$('#info-container').vTicker({ 
		speed: 1900,
		pause: 6000,
		animation: 'fade',
		mousePause: false,
		showItems: 8
	});
});

$(document).ready(function() {

$('#usuario').bestupper();
$('#buscar').bestupper();
$("#usuario").focus();

$("#clave").keypress(function(event) {
  if ( event.which == 13 ) {
     avanzar();
   }
});

$("#usuario").keypress(function(event) {
  if ( event.which == 13 ) {
     $("#clave").focus();
   }
});

});




</script>
</head>
<body><div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">

<div id="cabecera-portada"><img src="img/banner_9.jpg" /></div>
<div id="contexto-portada">
<div id="informacion-portada">
<div id="noticias-portada">
<p class="titulo-texto">NOTICIAS</p>
<?php include("pages/noticiasportada.php"); ?>
</div>

<!--
<div id="descargas-portada">
<p class="titulo-texto">DESCARGAR LICITACIONES</p>
<UL id="descargas-temas">
<LI>
ULTIMAS SEMANA
</LI>
<LI>
ULTIMOS QUINCE DIAS
</LI>
<LI>
ULTIMO MES
</LI>
<LI>
ULTIMO AÑO
</LI>
</UL>
</div>
-->

</div>

<div id="acceso-portada">
<img src="img/iniciar.jpg" width="260" height="40">
<form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" id="frm_acceso" name="frm_acceso">
<p><label for="usuario">USUARIO:</label><input type="text" name="usuario" id="usuario" class="acceso-input" /></p>
<p><label for="clave">CLAVE:</label><input type="password" name="clave" id="clave" class="acceso-input" /></p>
<p><span><a href="recuperacion.php">Recuperar Contraseña</a></span></p>
<p><input name="ingreso" type="button" class="ingreso" onclick="avanzar();" value="Ingresar" /></p>
</form>
</div>
<div id="pendientes">
<p><a href="habilitacion_proveedor.php"><img src="img/solicitar.jpg" width="260" height="40"></a></p>
<div id="noticias-scroll">
<?php include("pages/empresasportada.php"); ?>
</div>
<p>&nbsp;</p>
<div id="espacio"></div>
</div>

</div>

</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div></body>
</html>