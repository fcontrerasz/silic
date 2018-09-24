<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php require_once('pages/acceso_empresa.php'); ?>
<?PHP
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Panel Principal</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.alerts.css" type="text/css" media="screen" /> 
<link type="text/css" href="css/menu.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link type="text/css" href="css/tipsy.css" rel="stylesheet" />

<script src="js/jquery-1.4.2.min.js" type="text/javascript" ></script>
<script src="js/jquery.jgrowl.js" type="text/javascript"></script>
<script src="js/jquery.alerts.js" type="text/javascript" ></script>
<script src="js/jquery.menu.js" type="text/javascript" ></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script>
<style>
div.jGrowl div.jGrowl-notification, div.jGrowl div.jGrowl-closer {
	background-color: #336666;
	opacity: 				.95;
    -ms-filter: 			"progid:DXImageTransform.Microsoft.Alpha(Opacity=65)"; 
    filter: 				progid:DXImageTransform.Microsoft.Alpha(Opacity=65); 
}
</style>
<script>
$(document).ready(function() {

//$.jGrowl.defaults.position = 'center';
$.jGrowl.defaults.position = 'center';
<?php if(msj_nuevosProveedores($_SESSION['MM_Empresa'])>0){ ?>
$("#mensajes_panel").jGrowl("<?php echo msj_nuevosProveedores($_SESSION['MM_Empresa']); ?>  NUEVOS PROVEEDORES HAN SOLICITADO SER HABILITADOS EN EL SISTEMA.", { life: 100000 } , { glue: 'after'}, { sticky: true }, { header: 'Important' });
<?php } ?>

 
});



</script>

</head>
<body>
<div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">
<div id="cabecera-normal"><img src="img/banner_chico.jpg" width="900" height="150"></div>
<div id="contexto">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar"></div>
<div id="falso-salto"></div>
<div id="contenido-normal">
<div id="campoformulario">
<p class="titulo-texto">BIENVENIDO</p>
</div>
<div id="chart_div" style="width: 900px; height: 400px;"></div>
<div id="mensajes_panel" title="CREADA"></div>
<div id="espacio"></div>
</div>

</div>

</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
</html>
