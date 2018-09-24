<?php 
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

$colname_rsListadoArchivos = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsListadoArchivos = $_GET['ID_LICITACION'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoArchivos = sprintf("SELECT * FROM listar_maestro_archivos_proveedores WHERE ID_LICITACION = %s ORDER BY PROVEEDOR ASC", GetSQLValueString($colname_rsListadoArchivos, "int"));
//echo $query_rsListadoArchivos;
$rsListadoArchivos = mysql_query($query_rsListadoArchivos, $cn_licitaciones) or die(mysql_error());
$row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos);
$totalRows_rsListadoArchivos = mysql_num_rows($rsListadoArchivos);

$colname_rsBasesLicitacion = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsBasesLicitacion = $_GET['ID_LICITACION'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = sprintf("SELECT *, LENGTH(ARCHIVO) AS LARGO FROM archivos_licitacion WHERE ID_LICITACION = %s ORDER BY ARCHIVO ASC", GetSQLValueString($colname_rsBasesLicitacion, "int"));
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsTotalDivisiones = sprintf("SELECT * FROM requisitos_licitacion WHERE id_licitacion = %s", GetSQLValueString($colname_rsBasesLicitacion, "int"));
$rsTotalDivisiones = mysql_query($query_rsTotalDivisiones, $cn_licitaciones) or die(mysql_error());
$row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones);
$totalRows_rsTotalDivisiones = mysql_num_rows($rsTotalDivisiones);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rs_inserta = "0";
//echo $_POST["idinserta"];

if($_POST["idinserta"]>0 and isset($_POST["idlicitacion"])){

$file_size=$HTTP_POST_FILES['ARCHIVO']['tmp_name'];

//echo "<<<<<< tamaño: ".filesize($file_size);
//$HTTP_POST_FILES[
$archivo = $_FILES["ARCHIVO"]["tmp_name"]; 
$tamanio = $_FILES["ARCHIVO"]["size"];
$tipo    = $_FILES["ARCHIVO"]["type"];
$nombre  = limpiar($_FILES["ARCHIVO"]["name"]);
$idpostulacion = $_POST["idlicitacion"];
$xnombre = limpiar($_POST["nombre"]);
$xtipo = $_POST["tipo"];

$fp = fopen($archivo, "rb");

$totaltam = filesize($archivo);

/*
echo "NOMBRE -------->".$nombre."<BR>";
echo "ARCHIVO TEMP -->".$archivo."<BR>";
echo "TAMAÑO -------->".$tamanio."<BR>";
echo "DATA LARGO ----------->".$totaltam;
*/


$contenido = fread($fp, $tamanio);
$contenido = addslashes($contenido);
fclose($fp); 

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsInsertaArchivos = "INSERT INTO `archivos_licitacion`
    (ID_LICITACION, ARCHIVO, EXTENSION, LICI_NOMBRE,LICI_ARCHNOMBRE,lici_glosa, LICI_FECHA)
VALUES
    (".$idpostulacion.", '".$contenido."', '".$tipo."','".$xnombre."','".strtoupper($nombre)."','".$xtipo."',NOW())";

//echo $query_rsInsertaArchivos;

mysql_query($query_rsInsertaArchivos, $cn_licitaciones) or die(mysql_error());





if(mysql_affected_rows($cn_licitaciones) > 0)
       $colname_rs_inserta = 1;
    else
       $colname_rs_inserta = 2;

}

//echo "<br> ----> insertado: ".$colname_rs_inserta;

//$colname_rs_inserta = -1;

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

function abandonar(){
document.location.href = 'salir.php';
}

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

function createArchivo(){
var data1 = $("#rut").val();
var data2 = $("#dv").val(); 		
var data3 = $("#nombre").val();

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

var dataString = $("#frm_upempresa").serialize();
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_archivo.php",
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

}

function updateEmpresa(){

var data1 = $("#rut").val();
var data2 = $("#dv").val(); 		
var data3 = $("#nombre").val();

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

function dataArchivo(numero){
	var dataString = "ID_EMPRESA="+numero;
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/datos_empresa.php",
	  data: dataString, 
	  success: function(data){ 
	  		var datos = data.split(",");
			if(datos.length > 0){
				$("#rut").val(datos[4]);
				$("#dv").val(datos[5]);
				$("#dire").val(datos[6]);
				$("#fono").val(datos[7]);
				$("#nombre").val(datos[8]);
				$("#rubros").val(datos[2]); 
				$("#region").val(datos[1]); 
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

function deleteArchivo(numero){
if(confirm("¿Está seguro de borrar el Archivo?")) {
	var dataString = "idarchivo="+numero;
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_archivo.php",
	  data: dataString, 
	  success: function(data){ 
	  		if(data==1){
			$(this).msjAccion('ARCHIVO ELIMINADO',1);
			}
	   },
	  error: function(){alert("Error");}
	});
}
}

function deleteArchivoBase(numero){
if(confirm("¿Está seguro de borrar el Archivo Base?")) {
	var dataString = "idarchivo="+numero;
	//alert(dataString);
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_archivosbase.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
	  		if(data==1){
			$(this).msjAccion('ARCHIVO ELIMINADO',1);
			}
	   },
	  error: function(){alert("Error");}
	});
}
}

function createRequisito(){
var data2 = $("#txt_division").val();
var data1 = $("#idlicitacion").val(); 	

if (data2 == "" || (data2.length < 3) ) {
	$('#txt_division').attr('alert','VACIO O DEMASIADO CORTO');
	$('#txt_division').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#txt_division').tipsy("show");
	$("#txt_division").focus();
	return false;
}

var dataString = "idlicitacion="+data1+"&nombre="+data2;
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_requisito.php",
	  data: dataString, 
	  success: function(data){ 
			 // alert(data);
			if(data == 1){
				$(this).msjAccion('REQUISITO CREADO',1);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}


function deleteRequisito(numero){
if(confirm(" �Esta seguro de borrar el Requisito?")) {
	var dataString = "idrequisito="+numero;
	//alert(dataString);
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_requisito.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
	  		if(data==1){
			$(this).msjAccion('ARCHIVO ELIMINADO',1);
			}
	   },
	  error: function(){alert("Error");}
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

function Borrar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   deleteArchivo(parseHTML(data));
   }); 
}; 

function Borrar2(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   deleteArchivoBase(parseHTML(data));
   }); 
}; 

function BorrarRequisito(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   deleteRequisito(parseHTML(data));
   }); 
}; 

function habilitarPanel()
{

$('.flexme15').flexigrid({
colModel : [
                        {display: 'N', name : 'iidiv', width : 20, sortable : true, align: 'left'},
						{display: 'NOMBRE ARCHIVO', name : 'nomdiv', width : 150, sortable : true, align: 'left'},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: BorrarRequisito}
            ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 100,
singleSelect: true
});


$('.flexme3').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 20, sortable : true, align: 'left'},
						{display: 'Nombre', name : 'nombre', width : 200, sortable : true, align: 'left'},
                        {display: 'Archivo', name : 'archivo', width : 130, sortable : true, align: 'left'},
                        {display: 'Tamaño', name : 'tamano', width : 70, sortable : true, align: 'left'},
						{display: 'Fecha Creacion', name : 'tipo', width : 130, sortable : true, align: 'left'},
						{display: 'Tipo', name : 'tipo', width : 130, sortable : true, align: 'left'},
						{display: ' ', name : 'modificar', width : 20, sortable : true, align: 'left', process: Editar},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar2 <?php if($_SESSION['MM_UserGroup']=="1"){ ?>, hide: true <?php } ?>}
                ],
sortname: "",
sortorder: "asc",
resizable: false,
width: 850,
height: 100,
singleSelect: true
});

$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 20, sortable : true, align: 'left'},
						{display: 'Empresa', name : 'empresa', width : 100, sortable : true, align: 'left'},
						{display: 'Nombre', name : 'nombre', width : 150, sortable : true, align: 'left'},
                        {display: 'Archivo', name : 'archivo', width : 130, sortable : true, align: 'left'},
     					{display: 'Tamaño', name : 'tamano', width : 70, sortable : true, align: 'left'},
						{display: 'Fecha Creacion', name : 'tipo', width : 80, sortable : true, align: 'left'},
						{display: 'Tipo', name : 'tipo', width : 130, sortable : true, align: 'left'},
						{display: ' ', name : 'modificar', width : 20, sortable : true, align: 'left', process: Editar},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar<?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?>, hide: true <?php } ?>}
                ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 170,
singleSelect: true
});

}


jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

function openFile(file) {
    var extension = file.substr( (file.lastIndexOf('.') +1) );
    switch(extension) {
        case 'jpg': return 'IMAGEN JPG';break;    
        case 'png': return 'IMAGEN PNG';break;
		case 'txt': return 'TEXTO SIMPLE';break;
        case 'gif': return 'IMAGEN ANIMADA GIF';break;
        case 'zip': return 'ARCHIVO COMPRIMIDO ZIP';break; 
        case 'rar': return 'ARCHIVO COMPRIMIDO RAR';break; 
        case 'pdf': return 'ARCHIVO LECTURA PDF';break; 
		case 'xls': return 'ARCHIVO EXCEL';break; 
		case 'xlsx': return 'ARCHIVO EXCEL 2007';break; 
		case 'doc': return 'ARCHIVO WORD';break; 
		case 'docx': return 'ARCHIVO WORD 2007';break; 
		case 'ppt': return 'ARCHIVO POWERPOINT';break; 
		case 'pptx': return 'ARCHIVO POWERPOINT 2007';break; 
		case 'JPG': return 'IMAGEN JPG';break;    
        case 'PNG': return 'IMAGEN PNG';break;
		case 'TXT': return 'TEXTO SIMPLE';break;
        case 'GIF': return 'IMAGEN ANIMADA GIF';break;
        case 'ZIP': return 'ARCHIVO COMPRIMIDO ZIP';break; 
        case 'RAR': return 'ARCHIVO COMPRIMIDO RAR';break; 
        case 'PDF': return 'ARCHIVO LECTURA PDF';break; 
		case 'XLS': return 'ARCHIVO EXCEL';break; 
		case 'XLSX': return 'ARCHIVO EXCEL 2007';break; 
		case 'DOC': return 'ARCHIVO WORD';break; 
		case 'DOCX': return 'ARCHIVO WORD 2007';break; 
		case 'PPT': return 'ARCHIVO POWERPOINT';break; 
		case 'PPTX': return 'ARCHIVO POWERPOINT 2007';break; 
        default: return 'DESCONOCIDO';break; 
    }
}

jQuery.fn.msjAccion = function(msg,doit) {
				$("#mensajes").jGrowl(msg, { life: 300 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(doit==1){
                    	location.href = "<?php echo $editFormAction; ?>";
					}
                }});
};

function alertme(who,msg){
		$(who).attr('alert',msg);
		$(who).tipsy({title: 'alert',gravity: 'w', fade: true});
   	    $(who).tipsy("show");
}

$(document).ready(function() {

<?php if($_SESSION['MM_UserGroup']=="1"){ ?>
$("#ver-archivos-largo").hide();
<?php } ?>


var llave = <?php echo $colname_rs_inserta; ?>;
if(llave==1){
$(this).msjAccion('ARCHIVO SUBIDO',2);
}
if(llave==2){
$(this).msjAccion('NO SE PUDO CARGAR EL ARCHIVO',2);
}

$("#btn_guardar").hide();



$("#nombre").focus();
$('.upper').bestupper();

$("#btn_guardar").click(function(){
	updateEmpresa();
});

$("#btn_crear_divi").click(function(){
	createRequisito();
});



$("#btn_crear").click(function(){
	var data1 = $("#tipo").val();
	var data2 = $("#ARCHIVO").val();
	var data3 = $("#nombre").val();
	
	if(data3==""){
	alertme("#nombre","ELIGE UN NOMBRE");
	return false;
	}
	
	if(data3.length < 5){
	alertme("#nombre","DEMASIADO CORTO EL NOMBRE");
	return false;
	}
	
	if(data2==""){
	alertme("#ARCHIVO","ELIGE UN ARCHIVO");
	return false;
	}
	
	if(data1=="" || data1=="DESCONOCIDO"){
	alertme("#tipo","ARCHIVO INVALIDO");
	return false;
	}
	$("#idinserta").val(1);
	$("#frm_uparchivo").submit();
});

$("#btn_cancela").click(function(){
	$("#frm_uparchivo").reset();
	$("#btn_guardar").hide();
	$("#btn_crear").show();
});

$('#ARCHIVO').change(function(e){
  $("#tipo").val(openFile($(this).val()));
});

habilitarPanel();

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
<span><a href="#" onclick="abandonar();">CERRAR SESION</a></span>
<p>NOMBRE:<strong><?php echo $row_rs_usuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO:<strong><?php echo $row_rs_usuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rs_usuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rs_usuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto-largo">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo">

<div id="ver-migadepan">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
 <div style="width:500px; font-size:11px; float:left; text-align:left;">
PANEL &gt; <a style="text-decoration:underline;" href="panellicitaciones.php">LICITACIONES </a> &gt; BASES POSTULACIONES &gt; ARCHIVOS :: <?php echo nombreLicitacion($colname_rsListadoArchivos);?>
</div>
    </td>
    </tr>
</table>
</div>


<div id="ver-archivos-largo">

<form enctype="multipart/form-data" name="frm_uparchivo" id="frm_uparchivo" action="<?php echo $editFormAction; ?>" method="post">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">SUBIR BASE TECNICA</td>
  </tr>
  
  <tr>
    <td>NOMBRE</td>
    <td><input type="text" name="nombre" id="nombre" size="20" class="upper" />
      <input name="idlicitacion" type="hidden" id="idlicitacion" value="<?php echo $_GET['ID_LICITACION'];?>" />
       <input name="idinserta" type="hidden" id="idinserta" value="0" />
      </td>
    <td>ARCHIVO</td>
    <td><label>
      <input type="file" name="ARCHIVO" id="ARCHIVO" class="upper" />
    </label></td>
    <td>TIPO</td>
    <td><input type="text" name="tipo" id="tipo" size="15" class="upper" /><input type="button" class="ingreso-btn-postular" id="btn_crear" value="Subir Base"  alert="" /></td>
  </tr>
  <TR>
  <TD colspan="6">
      <DIV style="width:500PX; float:left; margin-top:10PX;">
    NUEVO REQUISITO: 
      <input type="text" name="txt_division" id="txt_division" class="upper" />
      <span style="margin-left:10px;">
      <input type="button" class="ingreso-btn" id="btn_crear_divi" value="Crear" alert="" />
      </span>
      </DIV>
  
      <DIV style="width:300PX; float:left; margin-left:40px; margin-top:10PX;">
      <!--<TABLE width="100%">
 <tr>
    <td id="titulo_admin" align="center">BASES TECNICAS</td>
  </tr>
 </TABLE>-->
          <table class="flexme15" >
            <tbody>
            <?php
			if($totalRows_rsTotalDivisiones>0){ 
			do { ?>
              <tr>
                <td><?php echo $row_rsTotalDivisiones['id_requisito']; ?></td>
                <td><?php echo $row_rsTotalDivisiones['nombre_requisito']; ?></td>
                <td><a href="#" id="<?php echo $row_rsTotalDivisiones['id_requisito']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a></td>
              </tr>
             <?php } while ($row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones)); } ?>
            </tbody>
          </table>
          </DIV>
  
  
  </TD>
  </TR>
  
  <tr>
    <td colspan="6" align="center"><div style="margin-left:300px;"></div></td>
  </tr>
  </tbody>
</table>
</form>


</div>


<div id="adm-archivoslicitacion">
<table class="flexme3">
          <tbody>
                        
            <?php 
			if($totalRows_rsBasesLicitacion>0){
			do { ?>
<tr>
                <td><?php echo $row_rsBasesLicitacion['ID_ARCHIVO']; ?></td>
                <td><?php echo $row_rsBasesLicitacion['lici_nombre']; ?></td>
                <td><?php echo $row_rsBasesLicitacion['lici_archnombre']; ?></td>
                <td><?php $totaltm = round($row_rsBasesLicitacion['LARGO']/1024);
			echo ($totaltm >= 1024)?round($totaltm/1024)." MB":$totaltm." KB";  ?></td>
                <td><?php echo date("d/m/Y",strtotime($row_rsBasesLicitacion['lici_fecha'])); ?></td>
                <td><?php echo $row_rsBasesLicitacion['lici_glosa']; ?></td>
                <td><a href="pages/archivo_licitacion.php?ID_ARCHIVO=<?php echo $row_rsBasesLicitacion['ID_ARCHIVO']; ?>"><img src="img/download_16.png" /></a></td>
                <td><a href="#" id="<?php echo $row_rsBasesLicitacion['ID_ARCHIVO']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a></td>
      </tr>        
              <?php } while ($row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion)); }?>

        </tbody>
      </table>

<div id="limpiarespacio"></div>
</div>
<div id='listar-usuarios'>

<div id="mensajes" title="CREADA"></div>
<?php if(in_array($_SESSION['MM_UserGroup'], array("2"))){ ?>
    <table class="flexme2">
          <tbody>
        <?php 
		if($totalRows_rsListadoArchivos>0){
		
		do { ?>
            
            <tr>
            <td><?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?></td>
            <td><?php echo $row_rsListadoArchivos['PROVEEDOR']; ?></td>
             <td><?php echo $row_rsListadoArchivos['docu_nombre']; ?></td>
             <td><?php echo $row_rsListadoArchivos['docu_nomarchivo']; ?></td>
            <td><?php 
			$totaltm = round($row_rsListadoArchivos['LARGO']/1024);
			echo ($totaltm >= 1024)?round($totaltm/1024)."MB":$totaltm."KB"; 
			?></td>
            <td><?php echo date("d/m/Y",strtotime($row_rsListadoArchivos['DOCU_FECHA'])); ?></td>
            <td><?php echo $row_rsListadoArchivos['docu_glosa']; ?></td>
            <td><a href="pages/archivo_postulacion.php?ID_DOCUMENTACION=<?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?>"><img src="img/download_16.png" /></a></td>
            <td><a href="#" id="<?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a></td>
                  
		      </tr>
              <?php } while ($row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos)); } ?>
        </tbody>
      </table>
      <?php } ?>
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

mysql_free_result($rsListadoArchivos);

mysql_free_result($rsBasesLicitacion);
?>