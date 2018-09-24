<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}
//echo "{{{{{{{{{{{{{{{{{{{".$_POST['rut'];

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  //$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_habilitar") && ($_POST["activo"]=="1")) {
					   
$insertSQL = sprintf("CALL `insert_habilitacion`(%s, %s, %s, %s, %s, %s, %s, %s, %s, @result);",
                       GetSQLValueString($_POST['comuna'], "int"),
                       GetSQLValueString($_POST['rubro'], "int"),
                       GetSQLValueString($_POST['region'], "int"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['rut'], "int"),
                       GetSQLValueString($_POST['dv'], "text"),
					   GetSQLValueString($_POST['fono'], "int"),
                       GetSQLValueString($_POST['dire'], "text"),$_SESSION['MM_UserID']);

  mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
  $Result1 = mysql_query($insertSQL, $cn_licitaciones) or die(mysql_error());

  $insertGoTo = "habilitacion.php?success";
  if (isset($_SERVER['QUERY_STRING'])) {
 //   $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
  //  $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsProvincias = "-1";
if (isset($_POST['region'])) {
  $colname_rsProvincias = $_POST['region'];
}

$colname_rsProvincias = "-1";
if (isset($_POST['region'])) {
  $colname_rsProvincias = $_POST['region'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsProvincias = sprintf("SELECT * FROM provincias WHERE ID_REGION = %s ORDER BY CR_PROVINCIA ASC", GetSQLValueString($colname_rsProvincias, "int"));
$rsProvincias = mysql_query($query_rsProvincias, $cn_licitaciones) or die(mysql_error());
$row_rsProvincias = mysql_fetch_assoc($rsProvincias);
$totalRows_rsProvincias = mysql_num_rows($rsProvincias);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRegiones = "SELECT * FROM regiones ORDER BY CR_REGION ASC";
$rsRegiones = mysql_query($query_rsRegiones, $cn_licitaciones) or die(mysql_error());
$row_rsRegiones = mysql_fetch_assoc($rsRegiones);
$totalRows_rsRegiones = mysql_num_rows($rsRegiones);

$colname_rsComunas = "-1";
if (isset($_POST['provincia'])) {
  $colname_rsComunas = $_POST['provincia'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsComunas = sprintf("SELECT * FROM comunas WHERE ID_PROVINCIA = %s ORDER BY CR_COMUNA ASC", GetSQLValueString($colname_rsComunas, "int"));
$rsComunas = mysql_query($query_rsComunas, $cn_licitaciones) or die(mysql_error());
$row_rsComunas = mysql_fetch_assoc($rsComunas);
$totalRows_rsComunas = mysql_num_rows($rsComunas);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRubros = "SELECT * FROM rubro ORDER BY RUBRO ASC";
$rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
$row_rsRubros = mysql_fetch_assoc($rsRubros);
$totalRows_rsRubros = mysql_num_rows($rsRubros);
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

function permitir(){
	$('[disabled]').each( 
	  function(){
		$(this).attr('disabled','');
	  }
	);
	$("#rut").attr('readonly','readonly');
	$("#dv").attr('readonly','readonly');
	$("#consulta_rut").attr('disabled','disabled');
	$("#nombre").focus();
//	readonly="readonly"
	//$("#dv").attr('disabled','disabled');
}

function creado(){
$("#mensajes").jGrowl("FORMULARIO ENVIADO SIN PROBLEMAS", { life: 5000 }, { sticky: true }, { header: 'Important' });
}


$(document).ready(function() {

$.jGrowl.defaults.position = 'bottom-right';
//$.jGrowl("TIENES 10 OPORTUNIDADES NUEVAS", { life: 5000 } , { glue: 'after'}, { sticky: true }, { header: 'Important' }, { position: 'bottom-right' });

<?php
if ($_POST['llave']=="1" ) {
  echo "permitir();";
}
if(isset($_GET["success"])){
echo "creado();";
}
?>

$("#rut").focus();
$("#rut").numeric();
$("#dv").numeric("K");
$("#fono").numeric("-");
$('.upper').bestupper();

$('#dv').blur(function() {
  $("#consulta_rut").trigger('click');
});


$("#consulta_rut").click(function(){ 
	
	$("#consulta_rut").attr('disabled','disabled');
	//$('#dv').tipsy("hide");
 	var data1 = $("#rut").val();
	var data2 = $("#dv").val();
	
	if((data1.length != 8) && (data1.length != 7)){
		 $("#rut").val("");
		 $("#rut").focus();
		 $("#consulta_rut").attr('disabled','');
		 return false;
	}
	
	var resultado = dv(data1);
	
	if (resultado != data2) {
		$('#dv').attr('alert','RUT INVALIDO');
		$('#dv').tipsy({title: 'alert',gravity: 'w'});
		$('#dv').tipsy("show");
		$("#consulta_rut").attr('disabled','');
		$("#rut").focus();
		return false;
	}
	

	var dataString = "RUT="+data1;

	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/existerut.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 0){
				$("#llave").val("1");
				$('#dv').attr('alert','OK');
				$('#dv').tipsy({title: 'alert',gravity: 'w'});
				$('#dv').tipsy("show");
				permitir();
			}
			if(data > 0){
				$('#dv').attr('alert','RUT YA EXISTE');
				$('#dv').tipsy({title: 'alert',gravity: 'w'});
				$('#dv').tipsy("show");
				$("#consulta_rut").attr('disabled','');
				$("#rut").focus();
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
	
});
  
  $("#habilitar").click(function(){ 
		var data1 = $("#rut").val();
		var data2 = $("#dv").val(); 		
		var data3 = $("#nombre").val();
		var data4 = $("#dire").val(); 		
		var data5 = $("#fono").val();
		var data6 = $("#region option:selected").val(); 		
		var data7 = $("#provincia option:selected").val();
		var data8 = $("#comuna option:selected").val(); 		
		var data9 = $("#rubro option:selected").val();
		
		if (data1 == "") {
			$("#rut").focus();
			return false;
		}

		if (data2 == "") {
			$("#dv").focus();
			return false;
		}
		
		if (data3 == "" || (data3.length < 3) ) {
			$('#nombre').tipsy({title: 'title',gravity: 'w'});
			$('#nombre').tipsy("show");
			$("#nombre").focus();
			return false;
		}
		
		if (data4 == "") {
			$('#dire').tipsy({title: 'title',gravity: 'w'});
			$('#dire').tipsy("show");
			$("#dire").focus();
			return false;
		}
		
		if (data5 == "") {
			$('#fono').tipsy({title: 'title',gravity: 'w'});
			$('#fono').tipsy("show");
			$("#fono").focus();
			return false;
		}
		
		if (data6 == "0") {
			$('#region').tipsy({title: 'title',gravity: 'w'});
			$('#region').tipsy("show");
			$("#region").focus();
			return false;
		}
		
		if (data7 == "0") {
			$('#provincia').tipsy({title: 'title',gravity: 'w'});
			$('#provincia').tipsy("show");
			$("#provincia").focus();
			return false;
		}
		
		if (data8 == "0") {
			$('#comuna').tipsy({title: 'title',gravity: 'w'});
			$('#comuna').tipsy("show");
			$("#comuna").focus();
			return false;
		}
		
		if (data9 == "0") {
			$('#rubro').tipsy({title: 'title',gravity: 'w'});
			$('#rubro').tipsy("show");
			$("#rubro").focus();
			return false;
		}
		
		$("#activo").val("1");
		//alert("Listo");
		$("#frm_habilitar").submit();
  });
  
  $("#region").change(function(){ 
		var data1 = $("#region option:selected").val();
		//alert(data1);
		$("#frm_habilitar").submit();
   });
   
   $("#provincia").change(function(){ 
		var data1 = $("#provincia option:selected").val();
		//alert(data1);
		$("#frm_habilitar").submit();
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
<p class="titulo-texto">HABILITACION NUEVA EMPRESA</p>
<form action="<?php echo $editFormAction; ?>" id="frm_habilitar" name="frm_habilitar" method="POST">
<input type="hidden" name="llave" id="llave" value="<?php echo $_POST['llave']; ?>" />
<input type="hidden" name="estado" value="3" />
<input type="hidden" name="activo" id="activo" value="0" />
<p class="titulo-form"><label for="usuario">RUT:</label><input type="text" class="upper" maxlength="8" size="10" name="rut" id="rut" value="<?php echo $_POST['rut']; ?>" />-<input class="upper" maxlength="1" size="2" value="<?php echo $_POST['dv']; ?>" type="text" name="dv" id="dv" alert="RUT ERRONEO O YA EXISTE" /><input type="button" class="btn-ir" id="consulta_rut" value="IR" /></p>
<p class="titulo-form"><label for="nombre">EMPRESA:</label><input type="text" class="upper" size="50" title="INGRESA UN NOMBRE" disabled="disabled" name="nombre" id="nombre" value="<?php echo $_POST['nombre']; ?>" /></p>

<p class="titulo-form"><label for="dire">DIRECCION:</label><input class="upper" disabled="disabled" title="INGRESA UNA DIRECCION" size="50"  value="<?php echo $_POST['dire']; ?>" type="text" name="dire" id="dire" />
</p>
<p class="titulo-form"><label for="fono">FONO:</label><input type="text" maxlength="10" disabled="disabled" title="INGRESA UN TELEFONO" class="upper" name="fono" id="fono" value="<?php echo $_POST['fono']; ?>" /></p>
<p class="titulo-form"><label for="region">REGION </label>
<select name="region" id="region" disabled="disabled" class="sl" title="SELECCIONA REGION">
  <option value="0" <?php if (!(strcmp(0, $_POST['region']))) {echo "selected=\"selected\"";} ?>>---SELECCIONE--</option>
  <?php
do {  
?><option value="<?php echo $row_rsRegiones['ID_REGION']?>"<?php if (!(strcmp($row_rsRegiones['ID_REGION'], $_POST['region']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsRegiones['CR_REGION'])?></option>
  <?php
} while ($row_rsRegiones = mysql_fetch_assoc($rsRegiones));
  $rows = mysql_num_rows($rsRegiones);
  if($rows > 0) {
      mysql_data_seek($rsRegiones, 0);
	  $row_rsRegiones = mysql_fetch_assoc($rsRegiones);
  }
?>
</select></p>
<p class="titulo-form"><label for="provincia">PROVINCIA </label><select name="provincia" id="provincia" disabled="disabled" class="sl" title="SELECCIONA UNA PROVINCIA">
<option value="0" <?php if (!(strcmp(0, $_POST['provincia']))) {echo "selected=\"selected\"";} ?>>---SELECCIONE--</option>
  <?php
  if($totalRows_rsProvincias>0){
do {  
?>
  <option value="<?php echo $row_rsProvincias['ID_PROVINCIA']?>" <?php if (!(strcmp($row_rsProvincias['ID_PROVINCIA'], $_POST['provincia']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsProvincias['CR_PROVINCIA'])?></option>
  <?php
} while ($row_rsProvincias = mysql_fetch_assoc($rsProvincias));
  $rows = mysql_num_rows($rsProvincias);
  if($rows > 0) {
      mysql_data_seek($rsProvincias, 0);
	  $row_rsProvincias = mysql_fetch_assoc($rsProvincias);
  }
 }
?>
</select></p>
<p class="titulo-form"><label for="comuna">COMUNA</label><select name="comuna" id="comuna" disabled="disabled" class="sl" title="SELECCIONA UNA COMUINA">
 <option value="0">---SELECCIONE--</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rsComunas['ID_COMUNA']?>"><?php echo strtoupper($row_rsComunas['CR_COMUNA'])?></option>
  <?php
} while ($row_rsComunas = mysql_fetch_assoc($rsComunas));
  $rows = mysql_num_rows($rsComunas);
  if($rows > 0) {
      mysql_data_seek($rsComunas, 0);
	  $row_rsComunas = mysql_fetch_assoc($rsComunas);
  }
?>
</select></p>
<p class="titulo-form"><label for="rubro">RUBRO</label><select name="rubro" id="rubro" disabled="disabled" class="sl" title="SELECCIONA UN RUBRO">
  <option value="0" <?php if (!(strcmp(0, $_POST['rubro']))) {echo "selected=\"selected\"";} ?>>-- SELECCIONE --</option>
  <?php
do {  
?>
  <option value="<?php echo $row_rsRubros['ID_RUBRO']?>"<?php if (!(strcmp($row_rsRubros['ID_RUBRO'], $_POST['rubro']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsRubros['RUBRO'])?></option>
  <?php
} while ($row_rsRubros = mysql_fetch_assoc($rsRubros));
  $rows = mysql_num_rows($rsRubros);
  if($rows > 0) {
      mysql_data_seek($rsRubros, 0);
	  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  }
?>
</select></p>
<br /><br />
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
mysql_free_result($rsProvincias);

mysql_free_result($rsRegiones);

mysql_free_result($rsComunas);

mysql_free_result($rsRubros);
?>