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
<title>Actividades</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.alerts.css" type="text/css" media="screen" /> 
<link type="text/css" href="css/menu.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link type="text/css" href="css/tipsy.css" rel="stylesheet" />
<link rel='stylesheet' type='text/css' href='js/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='js/fullcalendar.print.css' media='print' />

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.8.17.custom.min.js'></script>
<script type='text/javascript' src='js/fullcalendar.min.js'></script>
<script src="js/jquery.menu.js" type="text/javascript" ></script>

<script type='text/javascript'>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
		
			editable: false,
			
			left:   '',
    		center: '',
   			right:  '',
			
			events: "pages/eventos_licitaciones.php?idempresa=1",
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		
	});

</script>
<style type='text/css'>

	
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 850px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<div id="fondositio-largo">
<div id="espacio"></div>
<div id="cuerpo-largo-2">
<div id="cabecera-normal"><img src="img/banner_chico.jpg" width="900" height="150"></div>
<div id="contexto-largo-2">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar"></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo-2">
<div id='loading' style='display:none'>Cargando Eventos...</div>
<div id='calendar'></div>
<div id="espacio"></div>
</div>

</div>

</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
</html>
