<?php 
header("Content-Type: text/html;charset=utf-8");
require_once('Connections/cn_licitaciones.php'); ?>
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

$colname_rs_usuario = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rs_usuario = $_SESSION['MM_UserID'];
}

$altogrilla = 480;
$ocultar = "false";
$filtroempre = "";
if($_SESSION['MM_UserGroup']==2){
$altogrilla = 300;
$ocultar = "true";
//$filtroempre = "AND ID_LICITACION IN (".licitacionesEmpresas($_SESSION['MM_Empresa']).")";
$filtroempre = "AND ID_EMPRESACREADORA=".$_SESSION['MM_Empresa'];
}




mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rs_usuario = sprintf("SELECT * FROM listar_datos_usuario WHERE ID_USUARIO = %s", GetSQLValueString($colname_rs_usuario, "int"));
$rs_usuario = mysql_query($query_rs_usuario, $cn_licitaciones) or die(mysql_error());
$row_rs_usuario = mysql_fetch_assoc($rs_usuario);
$totalRows_rs_usuario = mysql_num_rows($rs_usuario);

$colname_rsListadoLicitaciones = "1970-1-1";
if (isset($_GET['campodesde'])) {
  $x1= explode("/",  $_GET['campodesde']);
  $colname_rsListadoLicitaciones = $x1[2]."-".$x1[1]."-".$x1[0]." 00:00";
}

$colname_rsListadoLicitaciones2 = "2900-1-1";
if (isset($_GET['campohasta'])) {
  $x1= explode("/",  $_GET['campohasta']);
  $colname_rsListadoLicitaciones2 = $x1[2]."-".$x1[1]."-".$x1[0]." 23:59";
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoLicitaciones = sprintf("SELECT * FROM listar_maestro_licitaciones WHERE (FECHA_INICIO >= %s AND FECHA_TERMINO <= %s) ".$filtroempre."  ORDER BY CODIGOLICI ASC", GetSQLValueString($colname_rsListadoLicitaciones, "date"),GetSQLValueString($colname_rsListadoLicitaciones2, "date"));
$rsListadoLicitaciones = mysql_query($query_rsListadoLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones);
$totalRows_rsListadoLicitaciones = mysql_num_rows($rsListadoLicitaciones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEstadosEmpresa = "SELECT * FROM estado_empresa ORDER BY ESTADO_EMP ASC";
$rsEstadosEmpresa = mysql_query($query_rsEstadosEmpresa, $cn_licitaciones) or die(mysql_error());
$row_rsEstadosEmpresa = mysql_fetch_assoc($rsEstadosEmpresa);
$totalRows_rsEstadosEmpresa = mysql_num_rows($rsEstadosEmpresa);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEstadoLicitaciones = "SELECT * FROM estado_licitacion ORDER BY ESTADO_LICITA ASC";
$rsEstadoLicitaciones = mysql_query($query_rsEstadoLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsEstadoLicitaciones = mysql_fetch_assoc($rsEstadoLicitaciones);
$totalRows_rsEstadoLicitaciones = mysql_num_rows($rsEstadoLicitaciones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsTipoLicitaciones = "SELECT * FROM tipolicitacion ORDER BY TIPOLICITACION ASC";
$rsTipoLicitaciones = mysql_query($query_rsTipoLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsTipoLicitaciones = mysql_fetch_assoc($rsTipoLicitaciones);
$totalRows_rsTipoLicitaciones = mysql_num_rows($rsTipoLicitaciones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsArchivosLicitacion = "SELECT * FROM archivos_licitacion ORDER BY ARCHIVO ASC";
$rsArchivosLicitacion = mysql_query($query_rsArchivosLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsArchivosLicitacion = mysql_fetch_assoc($rsArchivosLicitacion);
$totalRows_rsArchivosLicitacion = mysql_num_rows($rsArchivosLicitacion);


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

	<link rel="stylesheet" href="css/datepicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
   <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
   
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
  <script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
  <script src="js/jquery.limit-1.2.source.js" type="text/javascript"></script>
  <script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function zxcDHMS(zxcsecs,zxcd,zxcp,zxc0){
 zxcsecs=((Math.floor(zxcsecs/zxcd))%zxcp);
 return zxcsecs>9?zxcsecs:'0'+zxcsecs;
}

function zxcCountDown(zxcid,zxcmess,zxcsecs,zxcmins,zxchrs,zxcdays){
//alert("-->"+zxcid+"<--");
 var zxcobj=document.getElementById(zxcid);
 var zxcoop=zxcobj.oop
 if (!zxcoop) zxcobj.oop=new zxcCountDownOOP(zxcobj,zxcmess,zxcsecs,zxcmins,zxchrs,zxcdays);
 else {
  clearTimeout(zxcoop.to);
  zxcoop.mess=zxcmess;
  zxcoop.mhd=[zxcmins,zxchrs,zxcdays];
  zxcoop.srt=new Date().getTime();
  zxcoop.fin=new Date().getTime()+((zxcdays||0)*86400)+((zxchrs||0)*3600)+((zxcmins||0)*60)+((zxcsecs||0));
  zxcoop.end=((zxcoop.fin-zxcoop.srt));
  zxcoop.to=null;
  zxcoop.cng();
 }
}

function zxcCountDownOOP(zxcobj,zxcmess,zxcsecs,zxcmins,zxchrs,zxcdays){
 this.obj=zxcobj;
 this.mess=zxcmess;
 this.mhd=[zxcmins,zxchrs,zxcdays];
 this.srt=new Date().getTime();
 this.fin=new Date().getTime()+((zxcdays||0)*86400)+((zxchrs||0)*3600)+((zxcmins||0)*60)+((zxcsecs||0));
 this.end=((this.fin-this.srt));
 this.to=null;
 this.cng();
}

zxcCountDownOOP.prototype.cng=function(){
 var zxcnow=new Date().getTime();
 if (this.end-Math.floor((zxcnow-this.fin)/1000)>0){
  var zxcsecs=this.end-Math.floor((zxcnow-this.fin)/1000)-1;
  this.obj.innerHTML=(this.mhd[2]?zxcDHMS(zxcsecs,86400,100000)+' dias ':'')+(this.mhd[1]||this.mhd[2]?zxcDHMS(zxcsecs,3600,24)+' horas ':'')+(this.mhd[0]||this.mhd[1]||this.mhd[2]?zxcDHMS(zxcsecs,60,24)+':':'')+zxcDHMS(zxcsecs,1,60)+'.';
  this.to=setTimeout(function(zxcoop){return function(){zxcoop.cng();}}(this),1000);
 }
 else this.obj.innerHTML=this.mess||'';
}
/*]]>*/
</script>

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

function alertme(who,msg){
		$(who).attr('alert',msg);
		$(who).tipsy({title: 'alert',gravity: 'w', fade: true});
   	    $(who).tipsy("show");
}

function createLicitacion(){
var data1 = $("#codigo").val();

existeCodigo(data1, function(data) {
if(data==0){

var data2 = $("#nombre").val(); 		
var data3 = $("#hora1").val();
var data4 = $("#hora2").val(); 		
var data5 = $("#ndesde").val();
var data6 = $("#nhasta").val();
var data7 = $("#estado option:selected").val(); 		
var data8 = $("#tipo option:selected").val();
var data9 = $("#glosa").val();
var data10 = $("#estadokey").val(); 	

//alert(data10); 		

if (data1 == "") {
	alertme("#codigo","INGRESA UN CODIGO");
	$("#codigo").focus();
	return false;
}

if (data2 == "") {
	alertme("#nombre","INGRESA UN NOMBRE");
	$("#nombre").focus();
	return false;
}

if (data3 == "") {
	alertme("#hora1","INGRESA UNA HORA");
	$("#hora1").focus();
	return false;
}

if (data4 == "") {
	alertme("#hora2","INGRESA UNA HORA");
	$("#hora2").focus();
	return false;
}

if (data5 == "") {
	alertme("#ndesde","INGRESA UNA FECHA DE INICIO");
	$("#ndesde").focus();
	return false;
}

if (data6 == "") {
	alertme("#nhasta","INGRESA UNA FECHA DE TERMINO");
	$("#nhasta").focus();
	return false;
}

if(data10!="0"){
if (data7 == "0") {
	alertme("#estado","INGRESA UN ESTADO");
	$("#estado").focus();
	return false;
}
}

if (data8 == "0") {
	alertme("#tipo","INGRESA UN TIPO");
	$("#tipo").focus();
	return false;
}

var dataString = $("#frm_upempresa").serialize();
//alert(dataString);
//return false;

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_licitacion.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('LICITACION CREADA',1);
			}else if(data == 0){
				$(this).msjAccion('YA EXISTE CODIGO DE LA LICITACION',2);
				$("#codigo").focus();
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});

}else{

	alertme("#codigo","YA EXISTE CODIGO");
	$("#codigo").focus();
	return false;

}
});

}

function existeCodigo(codigo, callback) { 
var dataString = "CODIGOLICI="+codigo;
var respuesta = 0;
$.ajax({
cache: false,
async:   true,	
type: "GET",
dataType: "text",
url: "pages/existecodigo.php",
data: dataString, 
success: callback
});

}

function updateLicitacion(){

var data1 = $("#codigo").val();
var data2 = $("#nombre").val(); 		
var data3 = $("#hora1").val();
var data4 = $("#hora2").val(); 		
var data5 = $("#ndesde").val();
var data6 = $("#nhasta").val();
var data7 = $("#estado option:selected").val(); 		
var data8 = $("#tipo option:selected").val();
var data9 = $("#glosa").val(); 	
	

if (data1 == "") {
	alertme("#codigo","INGRESA UN CODIGO");
	$("#codigo").focus();
	return false;
}

if (data2 == "") {
	alertme("#nombre","INGRESA UN NOMBRE");
	$("#nombre").focus();
	return false;
}

if (data3 == "") {
	alertme("#hora1","INGRESA UNA HORA");
	$("#hora1").focus();
	return false;
}

if (data4 == "") {
	alertme("#hora2","INGRESA UNA HORA");
	$("#hora2").focus();
	return false;
}

if (data5 == "") {
	alertme("#ndesde","INGRESA UNA FECHA DE INICIO");
	$("#ndesde").focus();
	return false;
}

if (data6 == "") {
	alertme("#nhasta","INGRESA UNA FECHA DE TERMINO");
	$("#nhasta").focus();
	return false;
}

if (data7 == "0") {
	alertme("#estado","INGRESA UN ESTADO");
	$("#estado").focus();
	return false;
}

if (data8 == "0") {
	alertme("#tipo","INGRESA UN TIPO");
	$("#tipo").focus();
	return false;
}

var dataString = $("#frm_upempresa").serialize();
//alert(dataString);
//return false;

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_licitacion.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('LICITACION ACTUALIZADA',1);
			}else if(data == 0){
				$(this).msjAccion('NO EXISTE LA LICITACION',2);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});


}

function dataLicitacion(numero){
	var dataString = "ID_LICITACION="+numero;
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/datos_licitacion.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
	  		var datos = data.split(",");
			if(datos.length > 0){
				$("#codigo").val(datos[3]);
				$("#nombre").val(datos[2]);
				$("#hora1").val(datos[8]);
				$("#hora2").val(datos[9]);
				$("#ndesde").val(datos[5]);
				$("#nhasta").val(datos[6]);
				$("#c_hasta").val(datos[12]);
				$("#estado").val(datos[1]);
				$("#tipo").val(datos[0]); 
				$("#glosa").val(datos[7]);
				$("#idlicitacion").val(datos[10]); 
				$("#btn_guardar").show();
				$("#btn_crear").hide();
				$("#xndata").hide();
				$("#estado option[value=1]").attr('disabled', '');
				$("#estado option[value=2]").attr('disabled', '');
				$("#estado option[value=3]").attr('disabled', '');
				$("#estado").show();
				$("#estadokey").val(1);
				//alert(datos[1]);
				if(datos[1]==1){
				$("#estado option[value=3]").attr('disabled', 'disabled');
				
				if(datos[11]==0){
					$("#estado option[value=2]").attr('disabled', 'disabled');
				}
				
				}
				if(datos[1]==2){
				$("#estado option[value=1]").attr('disabled', 'disabled');
				$("#estado option[value=3]").attr('disabled', 'disabled');			
				}
				if(datos[1]==3){
				$("#estado option[value=2]").attr('disabled', 'disabled');
				}
			}else{
			$("#estadokey").val(0);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function Editar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   dataLicitacion(parseHTML(data));
   }); 
}; 

function Borrar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   alert(parseHTML(data));
   }); 
}; 

function Listar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelpostulaciones.php?ID_LICITACION='+parseHTML(data);
   }); 
}; 

function Revisar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panellecturapostulaciones.php?ID_LICITACION='+parseHTML(data);
   }); 
}; 

function Bases(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelarchivoslicitaciones.php?ID_LICITACION='+parseHTML(data);
   }); 
};

function Archivos(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelarchivos.php?ID_LICITACION='+parseHTML(data);
   }); 
};


function Preguntas(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelpreguntaspostulaciones.php?ID_LICITACION='+parseHTML(data);
   }); 
}; 

function Evaluacion(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'evaluaciones.php?ID_LICITACION='+parseHTML(data);
   }); 
}; 

function EvaluacionFinal(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'evaluacionproveedores.php?ID_LICITACION='+parseHTML(data);
   }); 
}; 



function test(obj, data){

}

function postFlexigrid()
{


$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : <?php if($_SESSION['MM_UserGroup']==2){ ?>52<?php }else{ ?>60<?php } ?>, sortable : true, align: 'left'},
//						{display: 'Codigo', name : 'code', width : 50, sortable : true, align: 'left'},
                        {display: 'Nombre', name : 'nombre', width : 110, sortable : true, align: 'left'},
                        {display: 'Inicio', name : 'desde', width : 55, sortable : true, align: 'left'},
                        {display: 'Termino', name : 'hasta', width : 55, sortable : true, align: 'left'},
						{display: 'Proyecto', name : 'avance', width : 75, sortable : true, align: 'left'},
						{display: 'Avance', name : 'porciento', width : 35, sortable : true, align: 'left'},
						{display: 'Estado', name : 'estado', width : 65, sortable : true, align: 'left'},
						{display: 'Restante Consultas', name : 'consultas', width : 115, sortable : true, align: 'left'},
                        {display: ' ', name : 'tipo', width : <?php if($_SESSION['MM_UserGroup']==2){ ?>15<?php }elseif($_SESSION['MM_UserGroup']==1){ ?>52<?php } ?>, sortable : true, align: 'left'},
						{display: ' ', name : 'modificar', width : 15, sortable : true, align: 'left', process: Editar<?php if($_SESSION['MM_UserGroup']==1){ ?>, hide: true <?php } ?>},
						{display: ' ', name : 'eliminar', width : 18, sortable : true, align: 'left', <?php if($_SESSION['MM_UserGroup']==2){ ?>process: Listar<?php }else{ ?> process: Revisar<?php } ?> },
						{display: ' ', name : 'archivos', width : 18, sortable : true, align: 'left',   <?php if($_SESSION['MM_UserGroup']==2){ ?>process: Bases<?php }else{ ?> process: Archivos<?php } ?>},
						{display: ' ', name : 'evaluacion', width : 18, sortable : true, align: 'left', process: Evaluacion<?php if($_SESSION['MM_UserGroup']==1){ ?>, hide: true <?php } ?>},
						{display: ' ', name : 'preguntas', width : 18, sortable : true, align: 'left', process: Preguntas},
						{display: ' ', name : 'finaleval', width : 18, sortable : true, align: 'left', process: EvaluacionFinal<?php if($_SESSION['MM_UserGroup']==1){ ?>, hide: true <?php } ?>}
                ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: <?php echo $altogrilla; ?>,
singleSelect: true
});

}


jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

$(window).load(function(){
<?php 

if($_SESSION['MM_UserGroup']==1){
echo counterPostula(); 
}

if($_SESSION['MM_UserGroup']==2){
echo counterPostulaadmin($_SESSION['MM_Empresa']);
}


 ?>

});

$(document).ready(function() {

<?php if($_SESSION['MM_UserGroup']==2){ ?>
$('#glosa').limit('300');
<?php } ?>

<?php if($_SESSION['MM_UserGroup']==1){ ?>
$(".panel1").hide();
<?php } ?>

<?php if($_SESSION['MM_UserGroup']==2){ ?>
$(".panel1").hide().fadeIn('slow');
<?php } ?>

$(".panel3").hide().fadeIn('slow');
$(".panel4").hide().fadeIn('slow');
$(".panel2").hide();

//var empresa = $("#idempresa").val();
//$(".panel2").hide().fadeIn('slow');

$.datepicker.setDefaults({ dateFormat: 'dd/mm/yy' });
$.datepicker.setDefaults($.datepicker.regional['es']);

$('#hora1').timepicker({});
$('#hora2').timepicker({});

$("#campodesde").datepicker({
   showOn: 'both',
   buttonImage: 'img/calendar_16.png',
   buttonImageOnly: true,
   changeYear: true,
   numberOfMonths: 2
});

$("#campohasta").datepicker({
   showOn: 'both',
   buttonImage: 'img/calendar_16.png',
   buttonImageOnly: true,
   changeYear: true,
   numberOfMonths: 2
});

$("#ndesde").datepicker({
   showOn: 'both',
   buttonImage: 'img/calendar_16.png',
   buttonImageOnly: true,
   changeYear: true,
   minDate: '0',
   numberOfMonths: 1
});

$("#nhasta").datepicker({
   showOn: 'both',
   buttonImage: 'img/calendar_16.png',
   buttonImageOnly: true,
   changeYear: true,
   minDate: '0',
   numberOfMonths: 1
});

$('#c_hasta').datetimepicker({minDate: '0'});


$("#btn_guardar").hide();

jQuery.fn.msjAccion = function(msg,doit) {
				$("#mensajes").jGrowl(msg, { life: 300 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(doit==1){
                    	location.reload();
					}
                }});
};


$("#rut").focus();
$("#rut").numeric();
$("#dv").numeric("K");
$("#fono").numeric("-");
$('.upper').bestupper();

$("#btn_guardar").click(function(){
	updateLicitacion();
});

$("#btn_crear").click(function(){
	createLicitacion();
});

$("#btn_filtro").click(function(){
	$("#frm_filtro").submit();
});

$("#btn_cancelafiltro").click(function(){
	location.href = "panellicitaciones.php";
});

$("#btn_cancela").click(function(){
	$("#frm_upempresa").reset();
	$("#btn_guardar").hide();
	$("#btn_crear").show();
	$("#xndata").show();
	$("#estado").hide();
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
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg); color:#000000;">
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

<?php if($_SESSION['MM_UserGroup']==2){ ?>
<div id="adm-licitacion" class="panel1">

<form name="frm_upempresa" id="frm_upempresa">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">ADMINISTRAR LICITACION</td>
  </tr>
  <tr>
    <td>CODIGO</td>
    <td><input type="text" name="codigo" id="codigo" maxlength="8" class="upper" />
      <input name="idlicitacion" type="hidden" id="idlicitacion" value="0" />
      <input name="estadokey" type="hidden" id="estadokey" value="0" />
      </td>
    <td>NOMBRE</td>
    <td><input type="text" name="nombre" id="nombre" class="upper" size="35" maxlength="50" alert="" /></td>
    <td>CONSULTAS HASTA</td>
    <td><input type="text" name="c_hasta" id="c_hasta" readonly="readonly" size="20" class="upper" /></td>
  </tr>
  <tr>
    <td>RANGO</td>
    <td><input type="text" name="ndesde" readonly="readonly" id="ndesde" size="8" class="upper" />&nbsp;-&nbsp;<input readonly="readonly" type="text" name="nhasta" id="nhasta" size="8" class="upper" /></td>
    <td>HORA</td>
    <td><input type="text" name="hora1" readonly="readonly" id="hora1" class="upper" size="5" />
      <input type="text" name="hora2" readonly="readonly" id="hora2" class="upper" size="3" />
    
    </td>
    <td>ESTADO</td>
    <td><select name="estado" id="estado" class="s2" alert="" style="display:none;">
      <option value="0">-- SELECCIONE --</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsEstadoLicitaciones['ID_ESTADOLICITA']?>"><?php echo $row_rsEstadoLicitaciones['ESTADO_LICITA']?></option>
      <?php
} while ($row_rsEstadoLicitaciones = mysql_fetch_assoc($rsEstadoLicitaciones));
  $rows = mysql_num_rows($rsEstadoLicitaciones);
  if($rows > 0) {
      mysql_data_seek($rsEstadoLicitaciones, 0);
	  $row_rsEstadoLicitaciones = mysql_fetch_assoc($rsEstadoLicitaciones);
  }
?>
        </select><div id="xndata">PENDIENTE</div></td>
  </tr>
  <tr>
    <td>GLOSA</td>
    <td colspan="3"><label>
      <textarea name="glosa" cols="80" rows="4" id="glosa" class="upper"></textarea>
    </label></td>
    <td>TIPO</td>
    <td><label>
      <select name="tipo" id="tipo" class="s2" alert="">
        <option value="0">-- SELECCIONE --</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsTipoLicitaciones['ID_TIPOLICITACION']?>"><?php echo $row_rsTipoLicitaciones['TIPOLICITACION']?></option>
        <?php
} while ($row_rsTipoLicitaciones = mysql_fetch_assoc($rsTipoLicitaciones));
  $rows = mysql_num_rows($rsTipoLicitaciones);
  if($rows > 0) {
      mysql_data_seek($rsTipoLicitaciones, 0);
	  $row_rsTipoLicitaciones = mysql_fetch_assoc($rsTipoLicitaciones);
  }
?>
      </select>
    </label></td>
  </tr>
  
  <tr>
    <td colspan="6" align="center"><div style="margin-left:300px;"><input type="button" class="ingreso-btn" id="btn_crear" value="Crear" alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" />
        <input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div></td>
  </tr>
  </tbody>
</table>
</form>
</div>
<?php } ?>

<DIV id="espacio-mini"></DIV>

<div id="ver-licitaciones" class="panel3">

<form id="frm_filtro" action="panellicitaciones.php" method="get">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">LICITACIONES</td>
  </tr>
  <tr>
    <td>
    <div style="margin-left:200px;">
     <p class="buscar-form"><label for="campodesde">DESDE:</label><input type="text" name="campodesde" id="campodesde" value="<?php echo $_GET['campodesde']; ?>"> </p>
    <p class="buscar-form"><label for="campohasta">HASTA:</label> <input type="text" name="campohasta" id="campohasta" value="<?php echo $_GET['campohasta']; ?>"></p>
<p class="buscar-form"><input name="btn_filtro" type="button" class="ingreso-btn" id="btn_filtro" value="Filtrar" alert="" />
</p>
<p class="buscar-form"><input name="btn_cancelafiltro" type="button" class="ingreso-btn" id="btn_cancelafiltro" value="Cancelar" alert="" /></p>
</div>

    </td>
  </tr>
</table>
</form>

</div>


<div id='listar-licitaciones' class="panel4">

<div id="mensajes" title="CREADA"></div>

    <table class="flexme2">
          <tbody>
        <?php 
		
		if($totalRows_rsListadoLicitaciones>0){
		do { ?>
            
            <tr>
                  <td><?php echo $row_rsListadoLicitaciones['CODIGOLICI']; //ID_LICITACION ?></td>
			      <!--<td><?php echo $row_rsListadoLicitaciones['CODIGOLICI']; ?></td>-->
			      <td><?php echo $row_rsListadoLicitaciones['NOMBRELICI']; ?></td>
               <!-- <td><?php echo date("d/m/Y",strtotime($row_rsListadoLicitaciones['FECHA_CREACION'])); ?></td>-->
                  <td><?php echo date("d/m/Y",strtotime($row_rsListadoLicitaciones['FECHA_INICIO'])); ?></td>
                <td><?php echo date("d/m/Y",strtotime($row_rsListadoLicitaciones['FECHA_TERMINO'])); ?></td>
                  <td <?php if(($row_rsListadoLicitaciones['DIASRESTANTES'])<=0){
							//echo "style='background-color:#611427; color: #fff;'";
							} ?> ><?php 
				  			
							
							
				  			$diash =  $row_rsListadoLicitaciones['DIASTOTALES']-$row_rsListadoLicitaciones['DIASRESTANTES'];
							$diast = $row_rsListadoLicitaciones['DIASTOTALES'];
							
							//echo "RESTANTES: ".$row_rsListadoLicitaciones['DIASRESTANTES']."   _";
							
				           	if(($diast-$diash)<=0){
							echo "Terminado."; 
							}else if($diash>0){
							echo $diash." de ".$diast." dias."; 
							}else{
							echo "Inicia en ".($diash*-1)." dias.";
							}
							
				  ?></td>
                  <td <?php if($diash>0){
					echo "style='background-color:#C4C49C;'";		
					} ?>><?php if($diash>0){ if($diash<=$diast){echo round(($diash*100)/$diast)."%";}else{echo "100%";} } ?></td>
                  <td <?php if($row_rsListadoLicitaciones['ESTADO_LICITA']=="PENDIENTE"){
					echo "style='background-color:#CFD96C;'";
					}else if($row_rsListadoLicitaciones['ESTADO_LICITA']=="VIGENTE"){
					echo "style='background-color:#D9964B;'";
					}else{
					echo "style='background-color:#AB3E2C;'";
					} ?>
                  
                  
                  ><?php echo $row_rsListadoLicitaciones['ESTADO_LICITA']; ?></td>	
                  <td><span <?php if($row_rsListadoLicitaciones['ESTADO_LICITA']!="VIGENTE"){ ?> style="display:none;" <?php } ?> id="counter<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"></span></td>
                  <td>
				  <?php if($_SESSION['MM_UserGroup']==1){ ?>
				  <?php echo $row_rsListadoLicitaciones['TIPOLICITACION']; ?>
                  <?php } ?>
				  
				  <?php if($_SESSION['MM_UserGroup']==2){ ?>
                  <img src="<?php echo ($row_rsListadoLicitaciones['TIPOLICITACION']=="ABIERTA")?"img/security_open_16.png":"img/security_closed_16.png"; ?>" class="editme"  title="Tipo <?php echo $row_rsListadoLicitaciones['TIPOLICITACION'];?>" />
                  <?php } ?>
                  </td>	
                  	      
                  <td><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><img src="img/edit.png"  class="editme" title="Editar Licitacion" /></a></td>
                  <td><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><img src="<?php 
				  if($_SESSION['MM_UserGroup']==1){
				  echo ($row_rsListadoLicitaciones['TIPOLICITACION']=="ABIERTA")?"img/security_open_16.png":"img/security_closed_16.png";
				  }else{
				  echo "img/view_16.png";
				  } ?>" class="editme" title="Postulantes <?php echo $row_rsListadoLicitaciones['TIPOLICITACION'];?>" /></a></td>
                  <td><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><img src="img/attachment_16.png"  class="editme" title="Bases Tecnicas" /></a></td>
                  <td><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><img src="img/document_text_accept_16.png"  class="editme" title="Evaluacion" /></a></td>
                  <td><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><?php $totalnews = nuevasPreguntas($row_rsListadoLicitaciones['ID_LICITACION']); if($totalnews>0){ ?><img src="img/3.png" class="editme" title="<?php echo $totalnews; ?> Nuevas Consultas" /><?php }else{ ?><img src="img/questionmark_16.png" class="editme" title="Preguntas" /><?php } ?></a></td>
                  <td><?php if($row_rsListadoLicitaciones['ESTADO_LICITA']=="FINALIZADA"){ ?><a href="#" id="<?php echo $row_rsListadoLicitaciones['ID_LICITACION']; ?>"><img src="img/statistics_16.png"  class="editme" title="Final" /></a><?php } ?></td>	
	        </tr>
              <?php } while ($row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones)); }?>
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

mysql_free_result($rsListadoLicitaciones);

mysql_free_result($rsEstadosEmpresa);

mysql_free_result($rsEstadoLicitaciones);

mysql_free_result($rsTipoLicitaciones);

mysql_free_result($rsArchivosLicitacion);
?>