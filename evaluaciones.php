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

$colname_rsListadoArchivos = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsListadoArchivos = $_GET['ID_LICITACION'];
}else{
die;
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoArchivos = sprintf("SELECT * FROM evaluaciones WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoArchivos, "int"));
$rsListadoArchivos = mysql_query($query_rsListadoArchivos, $cn_licitaciones) or die(mysql_error());
$row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos);
$totalRows_rsListadoArchivos = mysql_num_rows($rsListadoArchivos);


$editFormAction= $_SERVER['PHP_SELF']."?ID_LICITACION=".$colname_rsListadoArchivos;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Habilitacion</title>
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


jQuery.fn.msjAccion = function(msg,doit) {
				$("#mensajes").jGrowl(msg, { life: 2000 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(doit==1){
                    	location.href = "<?php echo $editFormAction; ?>";
					}
                }});
};

function guardarEvaluacion(){
var data1 = $("#idlicitacion").val();
var dataString = $("#frm_evaluaciones").serialize();

for(k=1;k<4;k++){
	var data1 = $("#categoria"+k).val();
	var data2 = $("#vcategoria"+k).val();
	if (data1.length > 2) {
		if(data2 == "" || data2 <= 0 || data2 > 100){
		 $(this).msjAccion('NO ES UN PORCENTAJE CORRECTO',2);
		 $("#vcategoria"+k).focus();
		 return false;
		}
	}
}

var totalsum = 0;
var totalitems = 0;
var data3 = "";
var data4 = 0;
for(j=1;j<4;j++){
data3 = $("#categoria"+j).val();
data4 = $("#vcategoria"+j).val();
if(data3.length > 0 || data4.length > 0){
for(k=1;k<6;k++){
	var data1 = $("#item"+j+k).val();
	var data2 = $("#vitem"+j+k).val();
	
	if (data1.length > 0) {
		if(data2.length == 0 || data2 <= 0 || data2 > 100){
			$(this).msjAccion('NO ES UN PORCENTAJE CORRECTO',2);
			$("#vitem"+j+k).focus();
			return false;
		}else{
			if(data2.length > 0){
			totalsum += parseInt(data2); 
			}else{
			totalsum += 0; 
			}
		}
		totalitems += 1;
	}else{
		if (data2.length > 0) {
			$(this).msjAccion('DEBES INDICAR UN NOMBRE PARA EL ITEM',2);
			$("#item"+j+k).focus();
			return false;
		}
	}
	//alert(j+" ITEM"+data1.length+" VALOR ACUMULADO:"+totalsum);
}
if(totalsum>100 || totalsum<100){
		 $(this).msjAccion('TOTAL DE SUBITEMS DEBE SUMAR 100%',2);
		 $("#vitem"+j+"1").focus();
		 return false;
}else{
totalsum = 0;
totalitems = 0;
}

}else{
for(k=1;k<6;k++){
	var data1 = $("#item"+j+k).val();
	var data2 = $("#vitem"+j+k).val();
	if(data1.length >0 || data2.length >0 ){
		$(this).msjAccion('DEBES INGRESAR UNA CATEGORIA PRINCIPAL',2);
		$("#categoria"+j).focus();
		return false;
	}
}

}
}

//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_evaluacion.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('EVALUACION GUARDADA',1);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR',2);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function crearEvaluacion(){
var data1 = $("#idlicitacion").val();
var dataString = $("#frm_evaluaciones").serialize();

for(k=1;k<4;k++){
	var data1 = $("#categoria"+k).val();
	var data2 = $("#vcategoria"+k).val();
	if (data1.length > 2) {
		if(data2 == "" || data2 <= 0 || data2 > 100){
		 $(this).msjAccion('NO ES UN PORCENTAJE CORRECTO',2);
		 $("#vcategoria"+k).focus();
		 return false;
		}
	}
}

var totalsum = 0;
var totalitems = 0;
var data3 = "";
var data4 = 0;
for(j=1;j<4;j++){
data3 = $("#categoria"+j).val();
data4 = $("#vcategoria"+j).val();
if(data3.length > 0 || data4.length > 0){
for(k=1;k<6;k++){
	var data1 = $("#item"+j+k).val();
	var data2 = $("#vitem"+j+k).val();
	
	if (data1.length > 0) {
		if(data2.length == 0 || data2 <= 0 || data2 > 100){
			$(this).msjAccion('NO ES UN PORCENTAJE CORRECTO',2);
			$("#vitem"+j+k).focus();
			return false;
		}else{
			if(data2.length > 0){
			totalsum += parseInt(data2); 
			}else{
			totalsum += 0; 
			}
		}
		totalitems += 1;
	}else{
		if (data2.length > 0) {
			$(this).msjAccion('DEBES INDICAR UN NOMBRE PARA EL ITEM',2);
			$("#item"+j+k).focus();
			return false;
		}
	}
	//alert(j+" ITEM"+data1.length+" VALOR ACUMULADO:"+totalsum);
}
if(totalsum>100 || totalsum<100){
		 $(this).msjAccion('TOTAL DE SUBITEMS DEBE SUMAR 100%',2);
		 $("#vitem"+j+"1").focus();
		 return false;
}else{
totalsum = 0;
totalitems = 0;
}

}else{
for(k=1;k<6;k++){
	var data1 = $("#item"+j+k).val();
	var data2 = $("#vitem"+j+k).val();
	if(data1.length >0 || data2.length >0 ){
		$(this).msjAccion('DEBES INGRESAR UNA CATEGORIA PRINCIPAL',2);
		$("#categoria"+j).focus();
		return false;
	}
}

}
}


$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_evaluacion.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('EVALUACION CREADA',1);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR',2);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

$(document).ready(function() {

for(k=1;k<5;k++){
	$("#vitem1"+k).numeric();
	$("#vitem2"+k).numeric();
	$("#vitem3"+k).numeric();	
}

$('.upper').bestupper();

$("#btn_crear").click(function(){
	crearEvaluacion();
});

$("#btn_guardar").click(function(){
	guardarEvaluacion();
});

$("#btn_cancela").click(function(){
	$("#frm_evaluaciones").reset();
});


var totaldata = <?php echo $totalRows_rsListadoArchivos; ?>;

if(totaldata>0){
$("#btn_crear").hide();
$("#btn_guardar").hide().fadeIn('slow');
}else{
$("#btn_crear").hide().fadeIn('slow');
$("#btn_guardar").hide();
}

$("#campoformulario").hide().fadeIn('slow');

});


</script>
</head>
<body>
<div id="fondositio-largo">
<div id="espacio"></div>
<div id="cuerpo-largo-2">
<div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150"></div>
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
<p>IDENTIFICACION</p>
<span><a href="#" onclick="abandonar();">CERRAR SESION</a></span>
<p>NOMBRE:<strong><?php echo $row_rsUsuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO:<strong><?php echo $row_rsUsuario['PERFIL']; ?></strong></p>
<p>EMPRESA:<strong><?php echo $row_rsUsuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rsUsuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto-largo-2">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo-2">

<div id="ver-migadepan">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<div style="width:300px; font-size:11px; float:left; text-align:left;height:20px;">
PANEL &gt; LICITACIONES &gt;<a style="text-decoration:underline;" href="panellicitaciones.php">VOLVER A <?php echo nombreLicitacion($colname_rsListadoArchivos);?></a>
</div>
    </td>
    </tr>
</table>
</div>

<div id="campoformulario">
<form id="frm_evaluaciones" name="frm_evaluaciones">
<br />
<p class="titulo-texto">EVALUACIONES PARA PROVEEDOR</p>
<br /><br />

<input type="hidden" name="idlicitacion" id="idlicitacion" value="<?php echo $colname_rsListadoArchivos; ?>" />
<p class="titulo-form"><label for="nombre">CATEGORIA 1:</label><input type="text" class="upper" size="54" title="INGRESA UNA CATEGORIA" name="categoria1" id="categoria1" value="<?php echo $row_rsListadoArchivos['CATEGORIA1']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vcategoria1" id="vcategoria1" value="<?php echo $row_rsListadoArchivos['VCATEGORIA1']; ?>" maxlength="3" />%</p>
<UL>
<LI>ITEM 1.1 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item11" id="item11" value="<?php echo $row_rsListadoArchivos['ITEM11']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem11" id="vitem11" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM11']; ?>" />%</LI>
<LI>ITEM 1.2 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item12" id="item12" value="<?php echo $row_rsListadoArchivos['ITEM12']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem12" id="vitem12" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM12']; ?>" />%
</LI>
<LI>ITEM 1.3 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item13" id="item13" value="<?php echo $row_rsListadoArchivos['ITEM13']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem13" id="vitem13" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM13']; ?>" />%
</LI>
<LI>ITEM 1.4 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item14" id="item14" value="<?php echo $row_rsListadoArchivos['ITEM14']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem14" id="vitem14" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM14']; ?>" />%
</LI>
<LI>ITEM 1.5 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item15" id="item15" value="<?php echo $row_rsListadoArchivos['ITEM15']; ?>" /><input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem15" id="vitem15" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM15']; ?>" />%
</LI>

</UL>
<BR /><br />
<p class="titulo-form"><label for="nombre">CATEGORIA 2:</label><input type="text" class="upper" size="54" title="INGRESA UNA CATEGORIA" name="categoria2" id="categoria2" value="<?php echo $row_rsListadoArchivos['CATEGORIA2']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vcategoria2" id="vcategoria2" value="<?php echo $row_rsListadoArchivos['VCATEGORIA2']; ?>" />%
</p>
<UL>
<LI>ITEM 2.1 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item21" id="item21" value="<?php echo $row_rsListadoArchivos['ITEM21']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem21" id="vitem21" value="<?php echo $row_rsListadoArchivos['VITEM21']; ?>" />%
</LI>
<LI>ITEM 2.2 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item22" id="item22" value="<?php echo $row_rsListadoArchivos['ITEM22']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem22" id="vitem22" value="<?php echo $row_rsListadoArchivos['VITEM22']; ?>" />%
</LI>
<LI>ITEM 2.3 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item23" id="item23" value="<?php echo $row_rsListadoArchivos['ITEM23']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem23" id="vitem23" value="<?php echo $row_rsListadoArchivos['VITEM23']; ?>" />%
</LI>
<LI>ITEM 2.4 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item24" id="item24" value="<?php echo $row_rsListadoArchivos['ITEM24']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem24" id="vitem24" value="<?php echo $row_rsListadoArchivos['VITEM24']; ?>" />%
</LI>
<LI>ITEM 2.5 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item25" id="item25" value="<?php echo $row_rsListadoArchivos['ITEM25']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem25" id="vitem25" value="<?php echo $row_rsListadoArchivos['VITEM25']; ?>" />%
</LI>

</UL>
<BR /><br />
<p class="titulo-form"><label for="nombre">CATEGORIA 3:</label><input type="text" class="upper" size="54" title="INGRESA UNA CATEGORIA" name="categoria3" id="categoria3" value="<?php echo $row_rsListadoArchivos['CATEGORIA3']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vcategoria3" id="vcategoria3" value="<?php echo $row_rsListadoArchivos['VCATEGORIA3']; ?>" />%
</p>
<UL>
<LI>ITEM 3.1 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item31" id="item31" value="<?php echo $row_rsListadoArchivos['ITEM31']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem31" id="vitem31" value="<?php echo $row_rsListadoArchivos['VITEM31']; ?>" />%
</LI>
<LI>ITEM 3.2 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item32" id="item32" value="<?php echo $row_rsListadoArchivos['ITEM32']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem32" id="vitem32" value="<?php echo $row_rsListadoArchivos['VITEM32']; ?>" />%
</LI>
<LI>ITEM 3.3 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item33" id="item33" value="<?php echo $row_rsListadoArchivos['ITEM33']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem33" id="vitem33" value="<?php echo $row_rsListadoArchivos['VITEM33']; ?>" />%
</LI>
<LI>ITEM 3.4 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item34" id="item34" value="<?php echo $row_rsListadoArchivos['ITEM34']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem34" id="vitem34" value="<?php echo $row_rsListadoArchivos['VITEM34']; ?>" />%
</LI>
<LI>ITEM 3.5 <input type="text" class="upper" size="30" title="INGRESA UN VALOR" name="item35" id="item35" value="<?php echo $row_rsListadoArchivos['ITEM35']; ?>" />
<input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem35" id="vitem35" value="<?php echo $row_rsListadoArchivos['VITEM35']; ?>" />%
</LI>

</UL>
<br /><br />

<div style="margin-left:100px;"><div id="enviardata"></div><input type="button" class="ingreso-btn" id="btn_crear" value="Crear" alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" /><input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div>

</div>
</form>
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
mysql_free_result($rsListadoArchivos);
mysql_free_result($rsUsuario);
?>
