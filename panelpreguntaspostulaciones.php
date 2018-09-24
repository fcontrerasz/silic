<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php require_once('pages/acceso_empresa.php'); ?>
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

$colname_rsUsuario = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rsUsuario = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUsuario = sprintf("SELECT * FROM listar_datos_usuario WHERE ID_USUARIO = %s ORDER BY NOMBRE ASC", GetSQLValueString($colname_rsUsuario, "int"));
$rsUsuario = mysql_query($query_rsUsuario, $cn_licitaciones) or die(mysql_error());
$row_rsUsuario = mysql_fetch_assoc($rsUsuario);
$totalRows_rsUsuario = mysql_num_rows($rsUsuario);

$colname_rsListadoPostulaciones = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsListadoPostulaciones = $_GET['ID_LICITACION'];
}

if($_SESSION['MM_UserGroup'] == "1"){
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_maestro_postulaciones WHERE ID_EMPRESA = %s AND ID_LICITACION = %s", GetSQLValueString($_SESSION['MM_Empresa'], "text"), GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}else{
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_maestro_postulaciones WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$rsListadoPostulaciones = mysql_query($query_rsListadoPostulaciones, $cn_licitaciones) or die(mysql_error());
$row_rsListadoPostulaciones = mysql_fetch_assoc($rsListadoPostulaciones);
$totalRows_rsListadoPostulaciones = mysql_num_rows($rsListadoPostulaciones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
//$query_rsPreguntas = sprintf("SELECT * FROM listar_maestro_respuestas WHERE ID_LICITACION = %s ORDER BY FECHA_PREGUNTA DESC", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
$query_rsPreguntas = sprintf("SELECT * FROM listar_proveedor_respuestas WHERE ID_LICITACION = %s ORDER BY FECHA_PREGUNTA DESC", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
$rsPreguntas = mysql_query($query_rsPreguntas, $cn_licitaciones) or die(mysql_error());
$row_rsPreguntas = mysql_fetch_assoc($rsPreguntas);
$totalRows_rsPreguntas = mysql_num_rows($rsPreguntas);

//echo "--------->".$totalRows_rsListadoPostulaciones." _ ".$row_rsListadoPostulaciones["ID_POSTULACIONES"]." [".$totalRows_rsPreguntas."] ";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panel Administrador</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link type="text/css" href="css/menu.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/flexigrid.pack.css" />
<link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" type="text/css"/>
<link type="text/css" href="css/tipsy.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<script src="js/jquery-1.4.2.min.js" type="text/javascript" ></script>
<script src="js/jquery.menu.js" type="text/javascript" ></script>
<script src="js/flexigrid.js" type="text/javascript"></script>
<script src="js/jquery.event.drag-2.0.min.js"></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script>
<script src="js/jquery.bestupper.min.js" type="text/javascript" ></script>
<script src="js/jquery.numeric.js" type="text/javascript" ></script>
<script src="js/jquery.jgrowl.js" type="text/javascript"></script>
<script>


function marcar(idpre){
$("#btn"+idpre).attr('disabled','disabled');
$("#idpregunta").val(idpre);
}

function createRespuesta(){
var data2 = $("#txt_pregunta").val();
var data1 = $("#idpregunta").val(); 	

if(data1=="0"){
	alert("Selecciona la pregunta a responder.");
	return false;
}

if (data2 == "" || (data2.length < 3) ) {
	alert("Pregunta Vacia o Demasiado Corta");
	return false;
}

var dataString = "idpregunta="+data1+"&texto="+data2;
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_respuesta.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('PREGUNTA CREADA',true);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

$(document).ready(function() {

<?php if($_SESSION['MM_UserGroup']=="1"){ ?>
$("#panel-ingreso-preguntas").hide();
<?php } ?>


$('.upper').bestupper();


jQuery.fn.msjAccion = function(msg,key) {
				$("#mensajes").jGrowl(msg, { life: 600 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(key){
                    	location.reload();
					}
                }});
};

$("#btn_preguntar").click(function(){
	createRespuesta();
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
<span><a href="#" onclick="abandonar();">CERRAR SESION</a></span>
<p>NOMBRE:<strong><?php echo $row_rsUsuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO:<strong><?php echo $row_rsUsuario['PERFIL']; ?></strong></p>
<p>EMPRESA:<strong><?php echo $row_rsUsuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rsUsuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel">

<div id="ver-migadepan">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<div style="width:300px; font-size:11px; float:left; text-align:left;height:20px;">
PANEL &gt; LICITACIONES &gt;<a style="text-decoration:underline;" href="panellicitaciones.php">&nbsp;<?php echo nombreLicitacion($colname_rsListadoPostulaciones);?></a>
</div>
    </td>
    </tr>
</table>
</div>

<div id="ver-postulaciones-lectura" class="panel1">
<div id="mensajes"></div>
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="3" id="titulo_admin" align="left">PREGUNTAS</td>
  </tr>
  <tr id="panel-ingreso-preguntas">
    <td width="10%">RESPUESTA</td>
      <td width="80%"><textarea name="txt_pregunta" id="txt_pregunta" maxlength="600" cols="100"  rows="3" class="upper"></textarea>
      <input name="idpregunta" id="idpregunta" type="hidden" value="0" />
      </td>
       <td width="10%"><input name="btn_preguntar" id="btn_preguntar" type="submit" value="ENVIAR" class="ingreso-btn" /></td>
    </tr>
     <tr>
    <td colspan="3">
    <div id="lectura-preguntas-full">
    <table width="90%" border="0" align="center" style="margin-top:10px;">
      <?php 
	  if($totalRows_rsPreguntas>0){
	  do { ?>
          <tr>
            <td valign="top"><?php echo date("d/m/Y",strtotime($row_rsPreguntas["FECHA_PREGUNTA"])); ?></td>
            <td valign="top"><img src="img/chat_16.png" />&nbsp;<?php echo $row_rsPreguntas["USUPROVEEDOR"]; ?> DICE:</td>
            <td class="item-preguntas"><div style="text-align:left"><?php echo $row_rsPreguntas["PREGUNTA"]; ?></div>
            <?php if($row_rsPreguntas["ID_RESPUESTA"]>0){ ?><div style="border:1 solid #666666; margin:3px; color:#CC6600;"><img src="img/user_close_16.png" /> <?php echo $row_rsPreguntas["DESC_RESPUESTA"]; ?></div><?php } ?>
            <div style="text-align:left; margin-bottom:15px;"></div>
            </td>
            <td><?php if($_SESSION['MM_UserGroup']=="2"){ if($row_rsPreguntas["ID_RESPUESTA"]==0){ ?><input type="button" value="RESPONDER" class="ingreso-btn-postular" id="btn<?php echo $row_rsPreguntas["ID_PREGUNTA"]; ?>" onclick="marcar(<?php echo $row_rsPreguntas["ID_PREGUNTA"]; ?>);" /><?php } } ?>
            </td>
          </tr>
        
        <?php } while ($row_rsPreguntas = mysql_fetch_assoc($rsPreguntas)); } ?>
        </table>
        </div>
        </td>
  </tr>
</table>
</div>



</div>
<script type='text/javascript'>
  $(function() {
	$('#btn_guardar').tipsy({title: 'alert',gravity: 'w',opacity: 0.98});
    $('.editme').tipsy({gravity: 'w',opacity: 0.98});
	$('.typeme').tipsy({gravity: 'w',opacity: 0.98});
	$('.deleteme').tipsy({gravity: 'w',opacity: 0.98});
  });
</script>


</div>
</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
</html>
<?php
mysql_free_result($rsUsuario);

mysql_free_result($rsListadoPostulaciones);

mysql_free_result($rsPreguntas);

?>