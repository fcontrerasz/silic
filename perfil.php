<?php require_once('Connections/cn_licitaciones.php'); ?>
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

$colname_rsUsuario = "-1";
if (isset($_GET['ID_USUARIO'])) {
  $colname_rsUsuario = $_GET['ID_USUARIO'];
}

if($_SESSION['MM_UserID']!=$colname_rsUsuario){
die;
//header("Location: index.php");
}


mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUsuario = sprintf("SELECT * FROM usuarios WHERE ID_USUARIO = %s", GetSQLValueString($colname_rsUsuario, "int"));
$rsUsuario = mysql_query($query_rsUsuario, $cn_licitaciones) or die(mysql_error());
$row_rsUsuario = mysql_fetch_assoc($rsUsuario);
$totalRows_rsUsuario = mysql_num_rows($rsUsuario);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rs_usuario = sprintf("SELECT * FROM listar_datos_usuario WHERE ID_USUARIO = %s", GetSQLValueString($colname_rsUsuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $cn_licitaciones) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Perfil</title>
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

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}

function mensajes(msg){
$("#mensajes").jGrowl(msg, { life: 5000 }, { sticky: true }, { header: 'Important' });
}

function alertme(who,msg){
		$(who).attr('alert',msg);
		$(who).tipsy({title: 'alert',gravity: 'w', fade: true});
   	    $(who).tipsy("show");
}

jQuery.fn.msjAccion = function(msg) {
				$("#mensajes").jGrowl(msg, { life: 300 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
                    location.reload();
                }});
};


$(document).ready(function() {

$.jGrowl.defaults.position = 'bottom-right';


$("#nombre").focus();
$("#clave").numeric();
$('.upper').bestupper();

$("#btn_generar").click(function(){
		$.ajax({
		  type: "GET",
		  dataType: "text",
		  url: "pages/generar_pwd.php",
		  success: function(data){ 
				$("#nclave").val(data);
		   },
		  error: function(uno, dos, tres){alert("Error");}
		});
});


$("#btn_guardar").click(function(){ 
		var data1 = $("#nombre").val();
		var data2 = $("#paterno").val(); 		
		var data3 = $("#materno").val();
		var data4 = $("#correo").val(); 		
		var data5 = $("#usuario").val();
		var data6 = $("#clave").val();
		var data7 = $("#nclave").val();
		var data8 = $("#rclave").val();

		if (data1 == "") {
			alertme("#nombre","INGRESA UN NOMBRE");
			$("#nombre").focus();
			return false;
		}

		if (data2 == "") {
			alertme("#paterno","INGRESA UN APELLIDO");
			$("#paterno").focus();
			return false;
		}
		
		if (data3 == "") {
			alertme("#materno","INGRESA UN APELLIDO");
			$("#materno").focus();
			return false;
		}
		//alert(isValidEmailAddress(data4));
		if (!isValidEmailAddress(data4)) {
			alertme("#correo","INGRESA UN EMAIL CORRECTO");
			$("#correo").focus();
			return false;
		}
		
		if (data5 == "") {
			alertme("#usuario","INGRESA UN USUARIO");
			$("#usuario").focus();
			return false;
		}
		
		if (data6 == "" || (data6.length < 8)) {
			alertme("#clave","CLAVE MUY CORTA");
			$("#clave").focus();
			return false;
		}
		
		if (data7 == "" || (data7.length < 8)  || (data7.length > 8)) {
			alertme("#nclave","NUEVA CLAVE SIN LARGO REQUERIDO");
			$("#nclave").focus();
			return false;
		}

		if (data8 == "" || (data8.length < 8)  || (data8.length > 8)) {
			alertme("#rclave","NUEVA CLAVE SIN LARGO REQUERIDO");
			$("#rclave").focus();
			return false;
		}
		
		if(data7 != "" || data8 != ""){
			if((data7 != data8)){
				alertme("#rclave","NUEVAS CLAVES DISTINTAS");
				$("#nclave").focus();
				return false;				
			}else{
				$("#clave").val(data7);
			}
		}
		
		var dataString = $("#frm_perfil").serialize();	
			
		//alert(dataString);
		//return false;

		$.ajax({
		  type: "GET",
		  dataType: "text",
		  url: "pages/update_clave.php",
		  data: dataString, 
		  success: function(data){ 
				//alert(data);
				if(data == 1){
					$(this).msjAccion('USUARIO ACTUALIZADO');
				}else if(data == 0){
					$(this).msjAccion('NO SE PUDO ACTUALIZAR');
				}
		   },
		  error: function(uno, dos, tres){alert("Error");}
		});

});
  

});

function abandonar(){
document.location.href = 'salir.php';
}

</script>
</head>
<body>
<div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">
<div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150"></div>
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
<p>IDENTIFICACION</p>
<br /><br />
<p>NOMBRE:<strong><?php echo $row_rs_usuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO:<strong><?php echo $row_rs_usuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rs_usuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rs_usuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar"></div>
<div id="falso-salto"></div>
<div id="contenido-normal">
<div id="campoformulario">
<p class="titulo-texto">MI PERFIL</p>
<form action="<?php echo $editFormAction; ?>" id="frm_perfil" name="frm_perfil" method="POST">
<input type="hidden" name="idusuario" id="idusuario" value="<?php echo $row_rsUsuario['ID_USUARIO']; ?>" />
<input type="hidden" name="idempresa" id="idempresa" value="<?php echo $row_rsUsuario['ID_EMPRESA']; ?>" />
<input type="hidden" name="division" id="division" value="<?php echo $row_rsUsuario['ID_DIVISION']; ?>" />
<input type="hidden" name="estado" id="estado" value="<?php echo $row_rsUsuario['ID_ESTUSU']; ?>" />
<input type="hidden" name="perfil" id="perfil" value="<?php echo $row_rsUsuario['ID_PERFIL']; ?>" />

<p class="titulo-form"><label for="paterno">NOMBRE:</label>
<input type="text" class="upper" size="50" alert="" name="nombre" id="nombre" value="<?php echo $row_rsUsuario['NOMBRE']; ?>" />
</p>
<p class="titulo-form"><label for="paterno">PATERNO:</label>
<input type="text" class="upper" size="50" alert="" name="paterno" id="paterno" value="<?php echo $row_rsUsuario['APELLIDOPAT']; ?>" />
</p>
<p class="titulo-form"><label for="paterno">MATERNO:</label>
<input type="text" class="upper" size="50" alert="" name="materno" id="materno" value="<?php echo $row_rsUsuario['APELLIDOMAT']; ?>" />
</p>
<p class="titulo-form">
  <label for="paterno">EMAIL:</label>
<input name="correo" type="text" class="upper" id="correo" value="<?php echo $row_rsUsuario['CORREO']; ?>" size="50" alert="" />
</p>
<p class="titulo-form"><label for="paterno">USUARIO:</label>
<input type="text" class="upper" size="20" alert="" name="usuariox" id="usuariox" disabled="disabled" value="<?php echo $row_rsUsuario['USUARIO']; ?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $row_rsUsuario['USUARIO']; ?>" />
</p>
<p class="titulo-form"><label for="paterno">CLAVE:</label>
<input type="password" class="upper" size="20" alert="" name="clave" maxlength="8" id="clave" value="<?php echo $row_rsUsuario['CLAVE']; ?>" />
</p>

<p class="titulo-form"><label for="paterno">NUEVA CLAVE: <i>Largo 8</i></label>
<input type="text" class="upper" size="20" alert="" name="nclave" maxlength="8" id="nclave" value="" />
<a id="btn_generar" href="#" />Generar Clave</a>
</p>
<p class="titulo-form"><label for="paterno">REPETIR CLAVE: <i>Largo 8</i></label>
<input type="text" class="upper" size="20" alert="" name="rclave" maxlength="8" id="rclave" value="" />
</p>

<br /><br />
<center><input name="btn_guardar" type="button" class="ingreso" id="btn_guardar" value="Guardar" />
</center>

</form>
</div>
<div id="mensajes" title="CREADA"></div>
<div id="espacio"></div>
</div>

</div>

</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
  <script type='text/javascript'>
    $(function() {
      //$('#campoformulario [title]').tipsy({trigger: 'focus', gravity: 'w'});
    });
  </script>
</html>
<?php
mysql_free_result($rsUsuario);
?>
