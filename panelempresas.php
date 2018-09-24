<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/acceso_admin.php'); ?>
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

$colname_rs_usuario = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rs_usuario = $_SESSION['MM_UserID'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rs_usuario = sprintf("SELECT * FROM listar_datos_usuario WHERE ID_USUARIO = %s", GetSQLValueString($colname_rs_usuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $cn_licitaciones) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEmpresas = "SELECT * FROM empresas ORDER BY PROVEEDOR ASC";
$rsEmpresas = mysql_query($query_rsEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
$totalRows_rsEmpresas = mysql_num_rows($rsEmpresas);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoEmpresas = "SELECT * FROM listar_maestro_empresas ORDER BY id_empresa ASC";
$rsListadoEmpresas = mysql_query($query_rsListadoEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsListadoEmpresas = mysql_fetch_assoc($rsListadoEmpresas);
$totalRows_rsListadoEmpresas = mysql_num_rows($rsListadoEmpresas);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRubros = "SELECT * FROM rubro ORDER BY RUBRO ASC";
$rsRubros = mysql_query($query_rsRubros, $cn_licitaciones) or die(mysql_error());
$row_rsRubros = mysql_fetch_assoc($rsRubros);
$totalRows_rsRubros = mysql_num_rows($rsRubros);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRegiones = "SELECT * FROM regiones ORDER BY CR_REGION ASC";
$rsRegiones = mysql_query($query_rsRegiones, $cn_licitaciones) or die(mysql_error());
$row_rsRegiones = mysql_fetch_assoc($rsRegiones);
$totalRows_rsRegiones = mysql_num_rows($rsRegiones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsComunas = "SELECT * FROM comunas ORDER BY CR_COMUNA ASC";
$rsComunas = mysql_query($query_rsComunas, $cn_licitaciones) or die(mysql_error());
$row_rsComunas = mysql_fetch_assoc($rsComunas);
$totalRows_rsComunas = mysql_num_rows($rsComunas);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsProvincias = "SELECT * FROM provincias ORDER BY CR_PROVINCIA ASC";
$rsProvincias = mysql_query($query_rsProvincias, $cn_licitaciones) or die(mysql_error());
$row_rsProvincias = mysql_fetch_assoc($rsProvincias);
$totalRows_rsProvincias = mysql_num_rows($rsProvincias);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEstadosEmpresa = "SELECT * FROM estado_empresa ORDER BY ESTADO_EMP ASC";
$rsEstadosEmpresa = mysql_query($query_rsEstadosEmpresa, $cn_licitaciones) or die(mysql_error());
$row_rsEstadosEmpresa = mysql_fetch_assoc($rsEstadosEmpresa);
$totalRows_rsEstadosEmpresa = mysql_num_rows($rsEstadosEmpresa);


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
<!--<script src="js/flexigrid.pack.js" type="text/javascript"></script>-->
<script src="js/flexigrid.js" type="text/javascript"></script>
<script src="js/jquery.event.drag-2.0.min.js"></script>
<script src="js/jquery.tipsy.js" type="text/javascript"></script>
<script src="js/jquery.bestupper.min.js" type="text/javascript" ></script>
<script src="js/jquery.numeric.js" type="text/javascript" ></script>
<script src="js/jquery.jgrowl.js" type="text/javascript"></script>


<script>

function dv(T) {
	var M = 0, S = 1; for (; T; T = Math.floor(T / 10))
		S = (S + T % 10 * (9 - M++ % 6)) % 11; return S ? S - 1 : 'K';
}

function parseXML(text) {
    var doc;

    if(window.DOMParser) {
        var parser = new DOMParser();
        doc = parser.parseFromString(text, "text/xml");
    }
    else if(window.ActiveXObject) {
        doc = new ActiveXObject("Microsoft.XMLDOM");
        doc.async = "false";
        doc.loadXML(text);
    }
    else {
        throw new Error("Cannot parse XML");
    }

    return doc;
}

function parseHTML(html) {
	var root = document.createElement("div");
	root.innerHTML = html;
    var allChilds = root.childNodes;
	return allChilds[0].id;
}

function abandonar(){
document.location.href = 'salir.php';
}

function createEmpresa(){
var data1 = $("#rut").val();
var data2 = $("#dv").val(); 		
var data3 = $("#nombre").val();
var data4 = $("#dire").val(); 		
var data5 = $("#fono").val();
var data6 = $("#region option:selected").val(); 		
var data7 = $("#provincias option:selected").val();
var data8 = $("#comunas option:selected").val(); 		
var data9 = $("#rubros option:selected").val();

if (data1 == "") {
	$('#dv').attr('alert','RUT INVALIDO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#rut").focus();
	return false;
}

if (data2 == "") {
	$('#dv').attr('alert','FALTA DIGITO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#dv").focus();
	return false;
}

var resultado = dv(data1);
if (resultado != data2) {
	$('#dv').attr('alert','RUT INVALIDO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#rut").focus();
	return false;
}

existeRutEmpresa(data1, function(data) {
if(data==0){

$('#dv').attr('alert','OK');
$('#dv').tipsy({title: 'alert',gravity: 'w'});
$('#dv').tipsy("show");

if (data3 == "" || (data3.length < 3) ) {
	$('#nombre').attr('alert','NOMBRE DEMASIADO CORTO');
	$('#nombre').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#nombre').tipsy("show");
	$("#nombre").focus();
	return false;
}

if (data4 == "") {
	$('#dire').attr('alert','INGRESA UNA DIRECCION');
	$('#dire').tipsy({title: 'alert',gravity: 'w'});
	$('#dire').tipsy("show");
	$("#dire").focus();
	return false;
}

if (data5 == "") {
	$('#fono').attr('alert','INGRESA UNA TELEFONO');
	$('#fono').tipsy({title: 'alert',gravity: 'w'});
	$('#fono').tipsy("show");
	$("#fono").focus();
	return false;
}

if (data6 == "0") {
	$('#region').attr('alert','SELECCIONA UNA REGION');
	$('#region').tipsy({title: 'alert',gravity: 'w'});
	$('#region').tipsy("show");
	$("#region").focus();
	return false;
}

/*
if (data7 == "0") {
	$('#provincias').attr('alert','SELECCIONA UNA PROVINCIA');
	$('#provincias').tipsy({title: 'alert',gravity: 'w'});
	$('#provincias').tipsy("show");
	$("#provincias").focus();
	return false;
}*/

if (data8 == "0") {
	$('#comunas').attr('alert','SELECCIONA UNA COMUNA');
	$('#comunas').tipsy({title: 'alert',gravity: 'w'});
	$('#comunas').tipsy("show");
	$("#comunas").focus();
	return false;
}

if (data9 == "0") {
	$('#rubros').attr('alert','SELECCIONA UN RUBRO');
	$('#rubros').tipsy({title: 'alert',gravity: 'w'});
	$('#rubros').tipsy("show");
	$("#rubros").focus();
	return false;
}

$("#btn_crear").hide();
$("#enviardata").html("<img src='img/loader.gif' width='30px' height='9px' />");
var dataString = $("#frm_upempresa").serialize();
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_empresa.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('EMPRESA CREADA');
			}else if(data == 0){
				$(this).msjAccion('YA EXISTE LA EMPRESA');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});

}else{
		$('#dv').attr('alert','YA EXISTE RUT');
		$('#dv').tipsy({title: 'alert',gravity: 'w'});
		$('#dv').tipsy("show");
		return false;
}
});	

}

function existeRutEmpresa(rut, callback) { 
var dataString = "RUT="+rut;
var respuesta = 0;
$.ajax({
cache: false,
async:   true,	
type: "GET",
dataType: "text",
url: "pages/existerut.php",
data: dataString, 
success: callback
});
}

function updateEmpresa(){

var data1 = $("#rut").val();
var data2 = $("#dv").val(); 		
var data3 = $("#nombre").val();
var data4 = $("#dire").val(); 		
var data5 = $("#fono").val();
var data6 = $("#region option:selected").val(); 		
var data7 = $("#provincias option:selected").val();
var data8 = $("#comunas option:selected").val(); 		
var data9 = $("#rubros option:selected").val();

if (data1 == "") {
	$('#dv').attr('alert','RUT INVALIDO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#rut").focus();
	return false;
}

if (data2 == "") {
	$('#dv').attr('alert','FALTA DIGITO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#dv").focus();
	return false;
}

var resultado = dv(data1);
if (resultado != data2) {
	$('#dv').attr('alert','RUT INVALIDO');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#rut").focus();
	return false;
}else{
	$('#dv').attr('alert','OK');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
}

if (data3 == "" || (data3.length < 3) ) {
	$('#nombre').attr('alert','NOMBRE DEMASIADO CORTO');
	$('#nombre').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#nombre').tipsy("show");
	$("#nombre").focus();
	return false;
}

if (data4 == "") {
	$('#dire').attr('alert','INGRESA UNA DIRECCION');
	$('#dire').tipsy({title: 'alert',gravity: 'w'});
	$('#dire').tipsy("show");
	$("#dire").focus();
	return false;
}

if (data5 == "") {
	$('#fono').attr('alert','INGRESA UNA TELEFONO');
	$('#fono').tipsy({title: 'alert',gravity: 'w'});
	$('#fono').tipsy("show");
	$("#fono").focus();
	return false;
}

if (data6 == "0") {
	$('#region').attr('alert','SELECCIONA UNA REGION');
	$('#region').tipsy({title: 'alert',gravity: 'w'});
	$('#region').tipsy("show");
	$("#region").focus();
	return false;
}
/*
if (data7 == "0") {
	$('#provincias').attr('alert','SELECCIONA UNA PROVINCIA');
	$('#provincias').tipsy({title: 'alert',gravity: 'w'});
	$('#provincias').tipsy("show");
	$("#provincias").focus();
	return false;
}*/

if (data8 == "0") {
	$('#comunas').attr('alert','SELECCIONA UNA COMUNA');
	$('#comunas').tipsy({title: 'alert',gravity: 'w'});
	$('#comunas').tipsy("show");
	$("#comunas").focus();
	return false;
}

if (data9 == "0") {
	$('#rubros').attr('alert','SELECCIONA UN RUBRO');
	$('#rubros').tipsy({title: 'alert',gravity: 'w'});
	$('#rubros').tipsy("show");
	$("#rubros").focus();
	return false;
}

$("#btn_guardar").hide();
$("#enviardata").html("<img src='img/loader.gif' width='30px' height='9px' />");

var dataString = $("#frm_upempresa").serialize();
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_empresa.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('EMPRESA ACTUALIZADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ACTUALIZAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});

}

function dataEmpresa(numero){
	var dataString = "ID_EMPRESA="+numero;
	//alert(dataString);
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/datos_empresa.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
	  		var datos = data.split(",");
			if(datos.length > 0){
				$("#rut").val(datos[4]);
				$("#dv").val(datos[5]);
				$("#dire").val(datos[6]);
				$("#fono").val(datos[7]);
				$("#nombre").val(datos[8]);
				$("#rubros").val(datos[2]); 
				$("#region").val(datos[1]); 
				$("#comunas").load('pages/datos_comuna.php?REGION='+datos[1]+'&ACTIVO='+datos[0]);
				$("#comunas").val(datos[0]); 
				$("#estado").val(datos[3]); 
				$("#provincias").val(datos[9]);
				$("#idempresa").val(numero);
				$("#btn_guardar").show();
				$("#btn_crear").hide();
				
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function deleteEmpresa(numero){
if(confirm("¿Está seguro de borrar la Empresa?")) {
var dataString = "idempresa="+numero;
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_empresa.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('EMPRESA ELIMINADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ELIMINAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}
}

function Editar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   //alert(parseHTML(data));
		   dataEmpresa(parseHTML(data));
   }); 
}; 

function Listar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelusuarios.php?ID_EMPRESA='+parseHTML(data);
   }); 
}; 


function Borrar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   deleteEmpresa(parseHTML(data));
   }); 
}; 

function test(obj, data){

}

function postFlexigrid()
{

$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 20, sortable : true, align: 'left'},
						{display: 'Rut', name : 'rut', width : 70, sortable : true, align: 'left'},
                        {display: 'Empresa', name : 'empresa', width : 110, sortable : true, align: 'left'},
                        {display: 'Telefono', name : 'telefono', width : 50, sortable : true, align: 'left'},
                        {display: 'Region', name : 'region', width : 180, sortable : true, align: 'left'},
                        {display: 'Comuna', name : 'comuna', width : 80, sortable : true, align: 'left'},
						{display: 'Creado', name : 'crado', width : 65, sortable : true, align: 'left'},
						{display: 'Estado', name : 'admin', width : 80, sortable : true, align: 'left'},
                        {display: ' ', name : 'estado', width : 20, sortable : true, align: 'left', hide: true},
						{display: ' ', name : 'modificar', width : 20, sortable : true, align: 'left', process: Editar},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar},
						{display: ' ', name : 'listar', width : 20, sortable : true, align: 'left', process: Listar}
                ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 380,
singleSelect: true
});

}


jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
  $("#comunas").load('pages/datos_comuna.php?REGION=0');
}

$(document).ready(function() {

$("#comunas").load('pages/datos_comuna.php?REGION=0');

$(".panel1").hide().fadeIn('slow');
$(".panel2").hide().fadeIn('slow');
//$("#enviardata").hide();


$("#btn_guardar").hide();

jQuery.fn.msjAccion = function(msg) {
				$("#mensajes").jGrowl(msg, { life: 300 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
                    location.reload();
                }});
};

$("#rut").focus();
$("#rut").numeric();
$("#dv").numeric("K");
$("#fono").numeric("-");
$('.upper').bestupper();

$("#btn_guardar").click(function(){
	updateEmpresa();
});

$("#btn_crear").click(function(){
	createEmpresa();
});

$("#btn_cancela").click(function(){
	$("#frm_upempresa").reset();
	$("#btn_guardar").hide();
	$("#btn_crear").show();
});

 $("#region").change(function(event){
            var id = $("#region").find(':selected').val();
			//alert(id);
            $("#comunas").load('pages/datos_comuna.php?REGION='+id);
});

postFlexigrid();

});

</script>
</head>
<body>
<div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo-largo">

<div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150"></div>
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
<p>IDENTIFICACION</p>
<br /><br />
<p>NOMBRE: <strong><?php echo $row_rs_usuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO: <strong><?php echo $row_rs_usuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rs_usuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rs_usuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto-largo">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo">
<div id="ver-usuarios" class="panel1">

<form name="frm_upempresa" id="frm_upempresa">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">ADMINISTRAR EMPRESA</td>
  </tr>
  <tr>
    <td>RUT</td>
    <td><input type="text" name="rut" id="rut" maxlength="8" class="upper" />
      <input type="text" name="dv" id="dv" size="3" maxlength="1" class="upper" alert="" /></td>
    <td>NOMBRE</td>
    <td><input type="text" name="nombre" id="nombre" class="upper" alert="" /></td>
    <td>TELEFONO</td>
    <td><input type="text" name="fono" id="fono" class="upper" /></td>
  </tr>
  <tr>
    <td>DIRECCION</td>
    <td><input type="text" name="dire" id="dire" size="30" class="upper" /></td>
    <td>REGION</td>
    <td><select name="region" id="region" style="width:200px;" alert="" class="s2">
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
    </select></td>
    <td>COMUNA</td>
    <td><select name="comunas" id="comunas" class="s2" alert=""></select></td>
  </tr>
  <tr>
    <td>RUBRO</td>
    <td><label>
    <select name="rubros" id="rubros" class="s2" alert="">
      <option value="0">--- SELECCIONE --</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsRubros['ID_RUBRO']?>"><?php echo strtoupper($row_rsRubros['RUBRO'])?></option>
      <?php
} while ($row_rsRubros = mysql_fetch_assoc($rsRubros));
  $rows = mysql_num_rows($rsRubros);
  if($rows > 0) {
      mysql_data_seek($rsRubros, 0);
	  $row_rsRubros = mysql_fetch_assoc($rsRubros);
  }
?>
    </select>
    </label>
      <input type="hidden" name="provincias" id="provincias" value="0">
      <input name="idempresa" type="hidden" id="idempresa" value="0" /></td>
    <td>ESTADO</td>
    <td><label>
      <select name="estado" id="estado" class="s2">
      <option value="0">-- SELECCIONE --</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsEstadosEmpresa['ID_ESTADOEMP']?>"><?php echo $row_rsEstadosEmpresa['ESTADO_EMP']?></option>
        <?php
} while ($row_rsEstadosEmpresa = mysql_fetch_assoc($rsEstadosEmpresa));
  $rows = mysql_num_rows($rsEstadosEmpresa);
  if($rows > 0) {
      mysql_data_seek($rsEstadosEmpresa, 0);
	  $row_rsEstadosEmpresa = mysql_fetch_assoc($rsEstadosEmpresa);
  }
?>
      </select>
    </label></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="6" align="center"><div style="margin-left:300px;">
    <div id="enviardata"></div>
    <input type="button" class="ingreso-btn" id="btn_crear" value="Crear" alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" /><input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div></td>
  </tr>
  </tbody>
</table>
</form>


</div>

<div id='listar-usuarios' class="panel2">

<div id="mensajes" title="CREADA"></div>

    <table class="flexme2">
          <tbody>
        <?php do { ?>
            
            <tr>
                  <td><?php echo $row_rsListadoEmpresas['id_empresa']; ?></td>
			      <td><?php echo $row_rsListadoEmpresas['RUT']; ?>-<?php echo $row_rsListadoEmpresas['DV']; ?></td>
                  
			      <td><?php echo $row_rsListadoEmpresas['PROVEEDOR']; ?></td>
                <td><?php echo $row_rsListadoEmpresas['FONO']; ?></td>
                  <td><?php echo strtoupper($row_rsListadoEmpresas['CR_REGION']); ?></td>
                <td><?php echo strtoupper($row_rsListadoEmpresas['CR_COMUNA']); ?></td>
                  <td><?php echo date("d/m/Y",strtotime($row_rsListadoEmpresas['EMPRE_CREACION'])); ?>
                  </td>
                  <td <?php if($row_rsListadoEmpresas['ID_ESTADOEMP']=="1"){
					echo "style='background-color:#6E9489;'";
					}else if($row_rsListadoEmpresas['ID_ESTADOEMP']=="2"){
					echo "style='background-color:#FF765E;'";
					}else{
					echo "style='background-color:#CFD96C;'";
					} ?>><?php echo $row_rsListadoEmpresas['ESTADO_EMP']; ?>
                  </td>	
                  <td>
                  <?php 
				  	if($row_rsListadoEmpresas['ID_ESTADOEMP']=="1"){
					echo "<img src='img/accept_16.png' class='typeme' title='Empresa Activa' />";
					}else if($row_rsListadoEmpresas['ID_ESTADOEMP']=="2"){
					echo "<img src='img/dropbox_16.png' class='typeme' title='Empresa Inactiva' />";
					}else{
					echo "<img src='img/questionmark_16.png' class='typeme' title='Pendiente Empresa' />";
					} ?>                </td>			      
                  <td><a href="#" id="<?php echo $row_rsListadoEmpresas['id_empresa']; ?>"><img src="img/edit.png"  class="editme" title="Editar Empresa" /></a></td>
                  <td>
                  <?php if($row_rsListadoEmpresas['id_empresa']!=1){ ?>
                  <a href="#" id="<?php echo $row_rsListadoEmpresas['id_empresa']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" />
                  <?php } ?>
                  </a>
                  </td>
                  <td><a href="#" id="<?php echo $row_rsListadoEmpresas['id_empresa']; ?>"><img src="img/group_half_16.png"  class="editme" title="Usuarios Empresa" /></a></td>
                  
		      </tr>
              <?php } while ($row_rsListadoEmpresas = mysql_fetch_assoc($rsListadoEmpresas)); ?>
        </tbody>
      </table>
      
   </div>


</div>



</div>
</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>

<script type='text/javascript'>
  $(function() {
	$('#btn_guardar').tipsy({title: 'alert',gravity: 'w',opacity: 0.98});
    $('.editme').tipsy({gravity: 'w',opacity: 0.98});
	$('.typeme').tipsy({gravity: 'w',opacity: 0.98});
	$('.deleteme').tipsy({gravity: 'w',opacity: 0.98});
  });
</script>
</div>
</body>
</html>
<?php
mysql_free_result($rs_usuario);

mysql_free_result($rsEmpresas);

mysql_free_result($rsListadoEmpresas);

mysql_free_result($rsRubros);

mysql_free_result($rsRegiones);

mysql_free_result($rsComunas);

mysql_free_result($rsProvincias);

mysql_free_result($rsEstadosEmpresa);
?>