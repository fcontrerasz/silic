<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
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
$query_rsEmpresas = "SELECT ID_EMPRESA, PROVEEDOR FROM empresas WHERE ID_ESTADOEMP = 1 ORDER BY PROVEEDOR ASC";
$rsEmpresas = mysql_query($query_rsEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
$totalRows_rsEmpresas = mysql_num_rows($rsEmpresas);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRegiones = "SELECT * FROM regiones ORDER BY CR_REGION ASC";
$rsRegiones = mysql_query($query_rsRegiones, $cn_licitaciones) or die(mysql_error());
$row_rsRegiones = mysql_fetch_assoc($rsRegiones);
$totalRows_rsRegiones = mysql_num_rows($rsRegiones);


if (!isset($_SESSION)) {
  session_start();
}
//echo "{{{{{{{{{{{{{{{{{{{".$_POST['rut'];

$editFormAction = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_habilitar") && ($_POST["activo"]=="1")) {

$nombreuser = generatePassword(10);
					   
$insertSQL = sprintf("CALL `insert_usuario`(@result, %s, 3, 2, %s, %s,%s, %s, %s,'', %s, 0, 1, %s, %s, %s, %s, %s, %s);",
                       GetSQLValueString($_POST['divisiones'], "int"),
					   GetSQLValueString($_POST['postula'], "int"),
                       GetSQLValueString($_POST['nombre'], "text"),
					   GetSQLValueString($_POST['paterno'], "text"),
					   GetSQLValueString($_POST['materno'], "text"),
					   GetSQLValueString($nombreuser, "text"),
                       GetSQLValueString($_POST['correo'], "text"),
					   GetSQLValueString($_POST['rut'], "int"),
					   GetSQLValueString($_POST['dv'], "text"),
					   GetSQLValueString($_POST['region'], "int"),
					   GetSQLValueString($_POST['comunas'], "int"),
					   GetSQLValueString($_POST['empresa'], "text"),
					   GetSQLValueString($_POST['dire'], "text"));

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());
  //echo $insertSQL;
  $insertGoTo = "habilitacion_proveedor.php?success";
  header(sprintf("Location: %s", $insertGoTo));
}

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

function dv(T) {
	var M = 0, S = 1; for (; T; T = Math.floor(T / 10))
		S = (S + T % 10 * (9 - M++ % 6)) % 11; return S ? S - 1 : 'K';
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}

function creado(){
$("#mensajes").jGrowl("LA SOLICITUD FUE ENVIADA, EN BREVE UN MAIL CONFIRMARA TU INGRESO AL SISTEMA.", { life: 5000 }, { sticky: true }, { header: 'Important' });
}

function existeRutUsuario(rut, callback) { 
var dataString = "RUT="+rut;
var respuesta = 0;
$.ajax({
cache: false,
async:   true,	
type: "GET",
dataType: "text",
url: "pages/existerutusuario.php",
data: dataString, 
success: callback
});
}

$(document).ready(function() {

$.jGrowl.defaults.position = 'bottom-right';

$("#postula").change(function(event){
            var id = $("#postula").find(':selected').val();
            $("#divisiones").load('pages/datos_division.php?EMPRESA='+id);
});

$("#region").change(function(event){
            var id = $("#region").find(':selected').val();
            $("#comunas").load('pages/datos_comuna.php?REGION='+id);
});


<?php if(isset($_GET["success"])){ ?>
$("#campoformulario").hide();
<?php echo "creado();"; } ?>

$("#nombre").focus();
$("#fono").numeric("-");
$('.upper').bestupper();

$("#habilitar").click(function(){ 
		var data1 = $("#nombre").val();
		var data2 = $("#correo").val(); 		
		var data3 = $("#postula option:selected").val();
		var data4 = $("#divisiones option:selected").val();
		var data5 = $("#paterno").val();
		var data6 = $("#materno").val();
		var data7 = $("#rut").val();
		var data8 = $("#dv").val();
		var data9 = $("#region option:selected").val();
		var data10 = $("#comunas option:selected").val();
		var data11 = $("#empresa").val();
		var data12 = $("#dire").val();

		if (data7 == "" || (data7.length < 8) ) {
			$('#rut').tipsy({title: 'title',gravity: 'w'});
			$('#rut').tipsy("show");
			$("#rut").focus();
			return false;
		}
		
		if (data8 == "" || (data8.length > 1) ) {
			$('#dv').tipsy({title: 'title',gravity: 'w'});
			$('#dv').tipsy("show");
			$("#dv").focus();
			return false;
		}
		
		var resultado = dv(data7);
	
		if (resultado != data8) {
			$('#dv').attr('alert','RUT INVALIDO');
			$('#dv').tipsy({title: 'alert',gravity: 'w'});
			$('#dv').tipsy("show");
			$("#rut").focus();
			return false;
		}
		
		
		if (data11 == "" || (data11.length < 2) ) {
			$('#empresa').tipsy({title: 'title',gravity: 'w'});
			$('#empresa').tipsy("show");
			$("#empresa").focus();
			return false;
		}
		
		if (data1 == "" || (data1.length < 3) ) {
			$('#nombre').tipsy({title: 'title',gravity: 'w'});
			$('#nombre').tipsy("show");
			$("#nombre").focus();
			return false;
		}
		
				
		if (data5 == "" || (data5.length < 3) ) {
			$('#paterno').tipsy({title: 'title',gravity: 'w'});
			$('#paterno').tipsy("show");
			$("#paterno").focus();
			return false;
		}
		
		if (data6 == "" || (data6.length < 3) ) {
			$('#materno').tipsy({title: 'title',gravity: 'w'});
			$('#materno').tipsy("show");
			$("#materno").focus();
			return false;
		}

		if (data2 == "") {
			$('#correo').tipsy({title: 'title',gravity: 'w'});
			$('#correo').tipsy("show");
			$('#correo').focus();
			return false;
		}
		
		if(!isValidEmailAddress(data2)){
			$('#correo').tipsy({title: 'title',gravity: 'w'});
			$('#correo').tipsy("show");
			$('#correo').focus();
			return false;
		}
		
		if (data3 == "0") {
			$('#postula').tipsy({title: 'title',gravity: 'w'});
			$('#postula').tipsy("show");
			$('#postula').focus();
			return false;
		}
		
		if (data4 == "0") {
			$('#divisiones').tipsy({title: 'title',gravity: 'w'});
			$('#divisiones').tipsy("show");
			$('#divisiones').focus();
			return false;
		}

		if (data9 == "0") {
			$('#region').tipsy({title: 'title',gravity: 'w'});
			$('#region').tipsy("show");
			$('#region').focus();
			return false;
		}
		
		if (data10 == "0") {
			$('#comunas').tipsy({title: 'title',gravity: 'w'});
			$('#comunas').tipsy("show");
			$('#comunas').focus();
			return false;
		}
		
		if (data12 == "" || (data12.length < 5) ) {
			$('#dire').tipsy({title: 'title',gravity: 'w'});
			$('#dire').tipsy("show");
			$("#dire").focus();
			return false;
		}
		
		existeRutUsuario(data7, function(data) {
		if(data==0){
			$("#activo").val("1");
			$("#frm_habilitar").submit();
		}else{
		$('#dv').attr('alert','YA EXISTE RUT');
		$('#dv').tipsy({title: 'alert',gravity: 'w'});
		$('#dv').tipsy("show");
		return false;
		}
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
<div id="barra" style="text-align:left;"><div style="padding-left:10px; padding-top:5px;"><a href="inicio.php">VOLVER A LA PORTADA</a></div></div>
<div class="limpiar"></div>
<div id="falso-salto"></div>
<div id="contenido-normal">
<div id="campoformulario">
<p class="titulo-texto">HABILITACION NUEVO PROVEEDOR</p>
<form action="<?php echo $editFormAction; ?>" id="frm_habilitar" name="frm_habilitar" method="POST">
<input type="hidden" name="llave" id="llave" value="<?php echo $_POST['llave']; ?>" />
<input type="hidden" name="estado" value="3" />
<input type="hidden" name="activo" id="activo" value="0" />

<p class="titulo-form"><label for="region">EMPRESA A POSTULAR </label>
<select name="postula" id="postula" class="sl" title="SELECCIONA EMPRESA">
  <option value="0">--SELECCIONE--</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rsEmpresas['ID_EMPRESA']?>"><?php echo $row_rsEmpresas['PROVEEDOR']?></option>
  <?php
} while ($row_rsEmpresas = mysql_fetch_assoc($rsEmpresas));
  $rows = mysql_num_rows($rsEmpresas);
  if($rows > 0) {
      mysql_data_seek($rsEmpresas, 0);
	  $row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
  }
?>
</select></p>

<p class="titulo-form"><label for="region">DIVISION A POSTULAR </label>
<select name="divisiones" id="divisiones" class="sl" title="SELECCIONA DIVISION">
<option value="0">--SELECCIONE--</option>
</select></p>

<p class="titulo-form"><label for="nombre">RUT:</label><input type="text" class="upper" title="INGRESA UN RUT" name="rut" maxlength="8" size="12" id="rut" value="" />-<input type="text" class="upper" title="INGRESA UN DV" name="dv" maxlength="1" size="2" id="dv" value="" /></p>
<p class="titulo-form"><label for="nombre">EMPRESA:</label><input type="text" class="upper" size="50" title="INGRESA UNA EMPRESA" name="empresa" id="empresa" value="" /></p>
<p class="titulo-form"><label for="nombre">NOMBRE:</label><input type="text" class="upper" size="50" title="INGRESA UN NOMBRE" name="nombre" id="nombre" value="" /></p>
<p class="titulo-form"><label for="nombre">PATERNO:</label><input type="text" class="upper" size="50" title="INGRESA UN APELLIDO" name="paterno" id="paterno" value="" /></p>
<p class="titulo-form"><label for="nombre">MATERNO:</label><input type="text" class="upper" size="50" title="INGRESA UN APELLIDO" name="materno" id="materno" value="" /></p>
<p class="titulo-form"><label for="region">REGION</label>
<select name="region" id="region" class="sl"  alert="">
    <option value="0">-- SELECCIONE --</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsRegiones['ID_REGION']?>"><?php echo strtoupper($row_rsRegiones['CR_REGION'])?></option>
      <?php
} while ($row_rsRegiones = mysql_fetch_assoc($rsRegiones));
  $rows = mysql_num_rows($rsRegiones);
  if($rows > 0) {
      mysql_data_seek($rsRegiones, 0);
	  $row_rsRegiones = mysql_fetch_assoc($rsRegiones);
  }
?>
</select></p>
<p class="titulo-form"><label for="region">COMUNA</label>
<select name="comunas" id="comunas" style="width:200px;" alert="" class="s2">
</select></p>


<p class="titulo-form"><label for="dire">CORREO:</label><input class="upper" title="INGRESA UN EMAIL" size="50"  value="" type="text" name="correo" id="correo" />
</p>

<p class="titulo-form"><label for="nombre">DIRECCION:</label><input type="text" class="upper" size="50" title="INGRESA UNA DIRECCION" name="dire" id="dire" value="" /></p>


<br />
<center><input name="habilitar" type="button" class="ingreso" id="habilitar" value="Solicitar" /><input name="cancelar" id="cancelar" type="button" class="ingreso" value="Limpiar" /></center>
<input type="hidden" name="MM_insert" value="frm_habilitar" />

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
mysql_free_result($rsEmpresas);
?>
