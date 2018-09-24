<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Recuperación</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.alerts.css" type="text/css" media="screen" /> 
<link type="text/css" href="css/menu.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link type="text/css" href="css/tipsy.css" rel="stylesheet" />

<script src="js/jquery-1.4.2.min.js" type="text/javascript" ></script>
<script src="js/jquery.numeric.js" type="text/javascript" ></script>
<script src="js/jquery.bestupper.min.js" type="text/javascript" ></script>
<script src="js/jquery.jgrowl.js" type="text/javascript"></script>
<script src="js/jquery.alerts.js" type="text/javascript" ></script>
<script src="js/jquery.menu.js" type="text/javascript" ></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script>

<script>

function mensaje(msg){
$("#mensajes").jGrowl(msg, { life: 5000 }, { sticky: true }, { header: 'Important' });
}

$(document).ready(function() {

$.jGrowl.defaults.position = 'bottom-right';

$("#email").focus();
$('.upper').bestupper();
  
$("#recuperar").click(function(){ 
	var data1 = $("#email").val();
	
	if (data1 == "") {
		$('#email').tipsy({title: 'title',gravity: 'w'});
		$('#email').tipsy("show");
		$("#email").focus();
		return false;
	}
	
	var dataString = "CORREO="+data1;
	
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/existeusuario.php",
	  data: dataString, 
	  success: function(data){ 
			if(data > 0){
				mensaje("SE HA ENVIADO TU CONTRASEÑA POR CORREO");
			}else{
				mensaje("NO EXISTE");
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
		
});
  
  
   
});



</script>
</head>
<body>
<div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">
<div id="cabecera-normal"><a href="inicio.php"><img src="img/banner_chico.jpg" width="900" height="150"></a></div>
<div id="contexto">
<div id="barra">&nbsp;</div>
<div class="limpiar"></div>
<div id="falso-salto"></div>
<div id="contenido-normal">
<div id="campoformulario">
<p class="titulo-texto">RECUPERAR CONTRASEÑA</p>
<form action="#" id="frm_recuperar" name="frm_recuperar" method="POST">
<p class="titulo-form"><label for="email">INGRESA TU EMAIL:</label>
  <input type="text" title="INGRESA UN EMAIL" class="upper" name="email" style="width:200px;" id="email" value="" />
</p>
<br />
<center>
  <input name="recuperar" type="button" class="ingreso" id="recuperar" value="Recuperar" />
</center>
</form>
</div>
<div id="mensajes"></div>
<div id="espacio"></div>
</div>

</div>

</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
</html>