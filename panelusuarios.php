<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$colname_rsListadoUsuarios = "-1";
if (isset($_GET['ID_EMPRESA'])) {
  $colname_rsListadoUsuarios = $_GET['ID_EMPRESA'];
}

if($_SESSION['MM_UserGroup']!="1"){
if($_SESSION['MM_Empresa']!=$colname_rsListadoUsuarios){
die;
}
if($_SESSION['MM_UserGroup']=="3"){
die;
}
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsUsuario = sprintf("SELECT * FROM listar_datos_usuario WHERE ID_USUARIO = %s ORDER BY NOMBRE ASC", GetSQLValueString($colname_rsUsuario, "int"));
$rsUsuario = mysql_query($query_rsUsuario, $cn_licitaciones) or die(mysql_error());
$row_rsUsuario = mysql_fetch_assoc($rsUsuario);
$totalRows_rsUsuario = mysql_num_rows($rsUsuario);
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
if($_SESSION['MM_UserGroup']=="1"){
$query_rsListadoUsuarios = sprintf("SELECT * FROM listar_maestro_usuarios WHERE ID_EMPRESA = %s AND PERFIL = 'ADM. EMPRESA' ORDER BY NOMBRE ASC", GetSQLValueString($colname_rsListadoUsuarios, "int"));
}elseif($_SESSION['MM_UserGroup']=="2"){
$query_rsListadoUsuarios = sprintf("SELECT * FROM listar_maestro_usuarios WHERE ID_EMPRESA = %s AND PERFIL = 'PROVEEDOR' ORDER BY NOMBRE ASC", GetSQLValueString($colname_rsListadoUsuarios, "int"));
}
$rsListadoUsuarios = mysql_query($query_rsListadoUsuarios, $cn_licitaciones) or die(mysql_error());
$row_rsListadoUsuarios = mysql_fetch_assoc($rsListadoUsuarios);
$totalRows_rsListadoUsuarios = mysql_num_rows($rsListadoUsuarios);

$colname_rsDivisiones = "-1";
if (isset($_GET['ID_EMPRESA'])) {
  $colname_rsDivisiones = $_GET['ID_EMPRESA'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsDivisiones = sprintf("SELECT * FROM divisiones WHERE ID_EMPRESA = %s ORDER BY NOMDIVISION ASC", GetSQLValueString($colname_rsDivisiones, "int"));
$rsDivisiones = mysql_query($query_rsDivisiones, $cn_licitaciones) or die(mysql_error());
$row_rsDivisiones = mysql_fetch_assoc($rsDivisiones);
$totalRows_rsDivisiones = mysql_num_rows($rsDivisiones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEstados = "SELECT * FROM estado_usuario ORDER BY CR_USRESTAD ASC";
$rsEstados = mysql_query($query_rsEstados, $cn_licitaciones) or die(mysql_error());
$row_rsEstados = mysql_fetch_assoc($rsEstados);
$totalRows_rsEstados = mysql_num_rows($rsEstados);

$sqlnext = "";
if($_SESSION['MM_UserGroup']=="2"){
$sqlnext = "WHERE ID_PERFIL NOT IN(1,2) ";
}

if($_SESSION['MM_UserGroup']=="1"){
$sqlnext = "WHERE ID_PERFIL NOT IN(1,3) ";
}

//echo "---------------->".$_SESSION['MM_UserGroup'];

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsPerfil = "SELECT * FROM perfiles ".$sqlnext." ORDER BY PERFIL ASC";
$rsPerfil = mysql_query($query_rsPerfil, $cn_licitaciones) or die(mysql_error());
$row_rsPerfil = mysql_fetch_assoc($rsPerfil);
$totalRows_rsPerfil = mysql_num_rows($rsPerfil);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEmpresas = "SELECT * FROM empresas ORDER BY PROVEEDOR ASC";
$rsEmpresas = mysql_query($query_rsEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
$totalRows_rsEmpresas = mysql_num_rows($rsEmpresas);

$colname_rsTotalDivisiones = "-1";
if (isset($_GET['ID_EMPRESA'])) {
  $colname_rsTotalDivisiones = $_GET['ID_EMPRESA'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsTotalDivisiones = sprintf("SELECT * FROM divisiones WHERE ID_EMPRESA = %s", GetSQLValueString($colname_rsTotalDivisiones, "int"));
$rsTotalDivisiones = mysql_query($query_rsTotalDivisiones, $cn_licitaciones) or die(mysql_error());
$row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones);
$totalRows_rsTotalDivisiones = mysql_num_rows($rsTotalDivisiones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsRegiones = "SELECT * FROM regiones ORDER BY CR_REGION ASC";
$rsRegiones = mysql_query($query_rsRegiones, $cn_licitaciones) or die(mysql_error());
$row_rsRegiones = mysql_fetch_assoc($rsRegiones);
$totalRows_rsRegiones = mysql_num_rows($rsRegiones);


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

function dataEmpresa(numero){
	var dataString = "ID_USUARIO="+numero;
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/datos_usuario.php",
	  data: dataString, 
	  success: function(data){ 
	        //alert(data);
	  		var datos = data.split(",");
			if(datos.length > 0){
				$("#nombre").val(datos[0]);
				$("#paterno").val(datos[1]);
				$("#materno").val(datos[2]);
				$("#usuario").val(datos[3]);
				$("#clave").val(datos[4]);
				$("#correo").val(datos[5]); 
				$("#division").val(datos[6]);
				$("#estado").val(datos[7]);
				$("#rut").val(datos[9]);
				$("#dv").val(datos[10]);
				$("#rut").attr('disabled','disabled');
				$("#dv").attr('disabled','disabled');
				$("#region").val(datos[11]); 
				$("#dire").val(datos[14]); 
				$("#empresa").val(datos[13]); 
				$("#comunas").load('pages/datos_comuna.php?REGION='+datos[11]+'&ACTIVO='+datos[12]);
				xdatas = datos[8];
				xdatas= xdatas + "";
				$("#perfil").val(xdatas);
				if($("#idnivel").val()=="2"){
					if(datos[8]=="2"){
						$("#usuario").attr('disabled','disabled');
					}else{
						$("#usuario").attr('disabled','');
					}
				}				
				$("#idusuario").val(numero);
				$("#btn_guardar").show();
				$("#btn_crear").hide();
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function EditarDivision(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   $("#iddivision").val(parseHTML(data));
		   $("#txt_division").val(parse2HTML(data));
		   $("#btn_mod_divi").show();
		   $("#btn_crear_divi").hide();
		   //dataEmpresa(parseHTML(data));
   }); 
}; 

function borrarDivision(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   var numero = parseHTML(data);
		   deleteDivision(numero);
   }); 
}; 

function Editar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   //alert(data);
		   dataEmpresa(parseHTML(data));
   }); 
}; 

function Borrar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		  // alert(data);
		   deleteUsuario(parseHTML(data));
   }); 
}; 

function parseHTML(html) {
	var root = document.createElement("div");
	root.innerHTML = html;
    var allChilds = root.childNodes;
	return allChilds[0].id;
}

function parse2HTML(html) {
	var root = document.createElement("div");
	root.innerHTML = html;
    var allChilds = root.childNodes;
	return allChilds[0].title;
}


function habilitarPanel(){

<?php if($totalRows_rsTotalDivisiones>0){ ?>
$('.flexme5').flexigrid({
colModel : [
                        {display: 'N', name : 'iidiv', width : 20, sortable : true, align: 'left'},
						{display: 'Division', name : 'nomdiv', width : 150, sortable : true, align: 'left'},
						{display: ' ', name : 'editar', width : 20, sortable : true, align: 'left', process: EditarDivision},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: borrarDivision}
            ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 80,
singleSelect: true
});
<?php } ?>

<?php if($totalRows_rsListadoUsuarios>0){ ?>
$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 15, sortable : true, align: 'left'},
						{display: 'Rut', name : 'rut', width : 60, sortable : true, align: 'left'},
                        {display: 'Empresa', name : 'empresa', width : 120, sortable : true, align: 'left'},
						{display: 'Division', name : 'division', width : 80, sortable : true, align: 'left'},
                        {display: 'Responsable', name : 'nombre', width : 180, sortable : true, align: 'left'},
						{display: 'Creacion', name : 'fecha', width : 60, sortable : true, align: 'left'},
						{display: 'Perfil', name : 'perfil', width : 85, sortable : true, align: 'left'},
						{display: ' ', name : 'estado', width : 20, sortable : true, align: 'left'},
						{display: ' ', name : 'editar', width : 20, sortable : true, align: 'left', process: Editar},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar}
            ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
<?php echo $_SESSION['MM_UserGroup']==1?"height: 230,":"height: 310,"; ?>
singleSelect: true
});
<?php } ?>


}

function updateDivision(numero){
var data1 = $("#txt_division").val();
var dataString = "iddivision="+numero+"&division="+data1;
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_division.php",
	  data: dataString, 
	  success: function(data){ 
			 // alert(data);
			if(data == 1){
				$(this).msjAccion('DIVISION CREADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ACTUALIZAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function existeLogin(login, callback) { 
var dataString = "LOGIN="+login;
var respuesta = 0;
$.ajax({
cache: false,
async:   true,	
type: "GET",
dataType: "text",
url: "pages/existelogin.php",
data: dataString, 
success: callback
});

}

function createDivision(){
var data2 = $("#txt_division").val();
var data1 = $("#idempresa").val(); 	

if (data2 == "" || (data2.length < 3) ) {
	$('#txt_division').attr('alert','VACIO O DEMASIADO CORTO');
	$('#txt_division').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#txt_division').tipsy("show");
	$("#txt_division").focus();
	return false;
}

var dataString = "idempresa="+data1+"&division="+data2;
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_division.php",
	  data: dataString, 
	  success: function(data){ 
			 //alert(data);
			if(data == 1){
				$(this).msjAccion('DIVISION CREADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ACTUALIZAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}



function createUsuario(){
var data1 = $("#nombre").val();
var data2 = $("#paterno").val(); 		
var data3 = $("#materno").val();
var data4 = $("#usuario").val(); 		
var data5 = $("#clave").val();
var data6 = $("#correo").val(); 		
var data7 = $("#division option:selected").val();
var data8 = $("#estado option:selected").val(); 		
var data9 = $("#perfil option:selected").val();
var data10 = $("#region option:selected").val();
var data11 = $("#comunas option:selected").val();
var data12 = $("#empresa").val();
var data13 = $("#dire").val();
var data15 = $("#rut").val();
var data16 = $("#dv").val();


if (data15 == "" || (data15.length < 7) || data16 == "" ) {
	$('#dv').attr('alert','INGRESA UN RUT');
	$('#dv').tipsy({title: 'alert',gravity: 'w'});
	$('#dv').tipsy("show");
	$("#rut").focus();
	return false;
}

if (data12 == "" || (data12.length < 3) ) {
	$('#empresa').attr('alert','INGRESA UNA EMPRESA');
	$('#empresa').tipsy({title: 'alert',gravity: 'w'});
	$('#empresa').tipsy("show");
	$("#empresa").focus();
	return false;
}

if (data1 == "" || (data1.length < 3) ) {
	$('#nombre').attr('alert','VACIO O DEMASIADO CORTO');
	$('#nombre').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#nombre').tipsy("show");
	$("#nombre").focus();
	return false;
}

if (data2 == "") {
	$('#paterno').attr('alert','INGRESA EL APELLIDO');
	$('#paterno').tipsy({title: 'alert',gravity: 'w'});
	$('#paterno').tipsy("show");
	$("#paterno").focus();
	return false;
}

if (data3 == "") {
	$('#materno').attr('alert','INGRESA EL APELLIDO');
	$('#materno').tipsy({title: 'alert',gravity: 'w'});
	$('#materno').tipsy("show");
	$("#materno").focus();
	return false;
}

if (data4 == "") {
	$('#usuario').attr('alert','INGRESA UN NOMBRE');
	$('#usuario').tipsy({title: 'alert',gravity: 'w'});
	$('#usuario').tipsy("show");
	$("#usuario").focus();
	return false;
}

if (data5 == "" || (data5.length != 8)) {
	$('#clave').attr('alert','CLAVE NO VALIDA (8 CARACTERES)');
	$('#clave').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#clave').tipsy("show");
	$("#clave").focus();
	return false;
}

if (data10 == "0") {
	$('#region').attr('alert','SELECCIONA UNA REGION');
	$('#region').tipsy({title: 'alert',gravity: 'w'});
	$('#region').tipsy("show");
	$("#region").focus();
	return false;
}

if (data11 == "0") {
	$('#comunas').attr('alert','SELECCIONA UNA COMUNA');
	$('#comunas').tipsy({title: 'alert',gravity: 'w'});
	$('#comunas').tipsy("show");
	$("#comunas").focus();
	return false;
}

if (data7 == "0") {
	$('#division').attr('alert','SELECCIONA UNA DIVISION');
	$('#division').tipsy({title: 'alert',gravity: 'w'});
	$('#division').tipsy("show");
	$("#division").focus();
	return false;
}

if (data13 == "" || (data13.length < 5)) {
	$('#dire').attr('alert','INGRESA UNA DIRECCION');
	$('#dire').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#dire').tipsy("show");
	$("#dire").focus();
	return false;
}

if (data8 == "0") {
	$('#estado').attr('alert','SELECCIONA UN ESTADO');
	$('#estado').tipsy({title: 'alert',gravity: 'w'});
	$('#estado').tipsy("show");
	$("#estado").focus();
	return false;
}
//alert(data9);
if (data9 == "0") {
	$('#perfil').attr('alert','SELECCIONA UN PERFIL');
	$('#perfil').tipsy({title: 'alert',gravity: 'w'});
	$('#perfil').tipsy("show");
	$("#perfil").focus();
	return false;
}

existeRutEmpresa(data15, function(data) {
if(data==0){

$("#btn_crear").hide();
$("#enviardata").html("<img src='img/loader.gif' width='30px' height='9px' />");

var dataString = $("#frm_upusuario").serialize();
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_usuario.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('USUARIO CREADO');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ACTUALIZAR');
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

function deleteUsuario(numero){
if(confirm("¿Está seguro de borrar el Usuario?")) {
var dataString = "idusuario="+numero;
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_usuario.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('USUARIO ELIMINADO');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ELIMINAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}
}

function deleteDivision(numero){
if(confirm("¿Está seguro de borrar esta Division?")) {
var dataString = "iddivision="+numero;
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_division.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('DIVISION ELIMINADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ELIMINAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}
}


function updateUsuario(){

var data1 = $("#nombre").val();
var data2 = $("#paterno").val(); 		
var data3 = $("#materno").val();
var data4 = $("#usuario").val(); 		
var data5 = $("#clave").val();
var data6 = $("#correo").val(); 		
var data7 = $("#division option:selected").val();
var data8 = $("#estado option:selected").val(); 		
var data9 = $("#perfil option:selected").val();
var data10 = $("#region option:selected").val();
var data11 = $("#comunas option:selected").val();
var data12 = $("#empresa").val();
var data13 = $("#dire").val();
var data15 = $("#rut").val();


if (data12 == "" || (data12.length < 3) ) {
	$('#empresa').attr('alert','INGRESA UNA EMPRESA');
	$('#empresa').tipsy({title: 'alert',gravity: 'w'});
	$('#empresa').tipsy("show");
	$("#empresa").focus();
	return false;
}

if (data1 == "" || (data1.length < 3) ) {
	$('#nombre').attr('alert','VACIO O DEMASIADO CORTO');
	$('#nombre').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#nombre').tipsy("show");
	$("#nombre").focus();
	return false;
}

if (data2 == "") {
	$('#paterno').attr('alert','INGRESA EL APELLIDO');
	$('#paterno').tipsy({title: 'alert',gravity: 'w'});
	$('#paterno').tipsy("show");
	$("#paterno").focus();
	return false;
}

if (data3 == "") {
	$('#materno').attr('alert','INGRESA EL APELLIDO');
	$('#materno').tipsy({title: 'alert',gravity: 'w'});
	$('#materno').tipsy("show");
	$("#materno").focus();
	return false;
}

if (data4 == "") {
	$('#usuario').attr('alert','INGRESA UN NOMBRE');
	$('#usuario').tipsy({title: 'alert',gravity: 'w'});
	$('#usuario').tipsy("show");
	$("#usuario").focus();
	return false;
}

if (data5 == "" || (data5.length != 8)) {
	$('#clave').attr('alert','CLAVE NO VALIDA (8 CARACTERES)');
	$('#clave').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#clave').tipsy("show");
	$("#clave").focus();
	return false;
}

if (data10 == "0") {
	$('#region').attr('alert','SELECCIONA UNA REGION');
	$('#region').tipsy({title: 'alert',gravity: 'w'});
	$('#region').tipsy("show");
	$("#region").focus();
	return false;
}

if (data11 == "0") {
	$('#comunas').attr('alert','SELECCIONA UNA COMUNA');
	$('#comunas').tipsy({title: 'alert',gravity: 'w'});
	$('#comunas').tipsy("show");
	$("#comunas").focus();
	return false;
}



if (data13 == "" || (data13.length < 5)) {
	$('#dire').attr('alert','INGRESA UNA DIRECCION');
	$('#dire').tipsy({title: 'alert',gravity: 'w', delayOut: 1000});
	$('#dire').tipsy("show");
	$("#dire").focus();
	return false;
}

if (data7 == "0") {
	$('#division').attr('alert','SELECCIONA UNA DIVISION');
	$('#division').tipsy({title: 'alert',gravity: 'w'});
	$('#division').tipsy("show");
	$("#division").focus();
	return false;
}

if (data8 == "0") {
	$('#estado').attr('alert','SELECCIONA UN ESTADO');
	$('#estado').tipsy({title: 'alert',gravity: 'w'});
	$('#estado').tipsy("show");
	$("#estado").focus();
	return false;
}
//alert(data9);
if (data9 == "0") {
	$('#perfil').attr('alert','SELECCIONA UN PERFIL');
	$('#perfil').tipsy({title: 'alert',gravity: 'w'});
	$('#perfil').tipsy("show");
	$("#perfil").focus();
	return false;
}

$("#btn_guardar").hide();
$("#enviardata").html("<img src='img/loader.gif' width='30px' height='9px' />");

var dataString = $("#frm_upusuario").serialize();
//alert(dataString);

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_usuario.php",
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



}

jQuery.fn.reset = function () {
  $("#rut").attr('disabled','');
  $("#dv").attr('disabled','');
  $(this).each (function() { this.reset(); });
}

jQuery.fn.block = function () {
  $(':input', this).each(function() { 
  if(this.id!="division"){
  	$(this).attr('disabled', 'disabled');
  } });
  $("#mensajes").jGrowl("NO EXISTEN DIVISIONES PARA ESTA EMPRESA.", { life: 5000 , sticky: false , easing: 'linear'});
}

function existeRutEmpresa(rut, callback) { 
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

$('#usuario').blur(function() {
var data1 = $('#usuario').val();
existeLogin(data1, function(data) {
if(data>0){
	$('#usuario').attr('alert','YA EXISTE EL NOMBRE DE USUARIO');
	$('#usuario').tipsy({title: 'alert',gravity: 'w'});
	$('#usuario').tipsy("show");
	$('#usuario').val("");
	$("#usuario").focus();}
});
});

var tDivisiones = <?php echo $totalRows_rsDivisiones; ?>;

if(tDivisiones==0){
$("#frm_upusuario").block();
}

$(".panel3").hide();
$(".panel1").hide();

var empresa = $("#idempresa").val();
if(empresa>0){
$(".panel3").fadeIn('slow');
$(".panel1").fadeIn('slow');
}

$("#nombre").focus();
$("#clave").numeric();
$("#rut").numeric();
$('.upper').bestupper();
$("#btn_guardar").hide();
$("#btn_mod_divi").hide();
$(".panel2").hide();

habilitarPanel();

jQuery.fn.msjAccion = function(msg) {
				$("#mensajes").jGrowl(msg, { life: 300 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
                    location.reload();
                }});
};

$("#ID_EMPRESA").change(function(){ 
	$("#frm_salto").submit();
});

$("#btn_divi_c").click(function(){
	$(".panel2").hide();
	$(".panel1").show();
});

$("#newdivision").click(function(){
	$(".panel1").hide();
	$(".panel2").show();
});

$("#btn_guardar").click(function(){
	updateUsuario();
});

$("#btn_crear").click(function(){
	createUsuario();
});

$("#btn_crear_divi").click(function(){
	createDivision();
});

$("#btn_mod_divi").click(function(){
	var data1 = $("#iddivision").val();
	updateDivision(data1);
});


$("#btn_cancela").click(function(){
	$("#frm_upusuario").reset();
	$("#btn_guardar").hide();
	$("#btn_crear").show();
});

 $("#region").change(function(event){
            var id = $("#region").find(':selected').val();
			//alert(id);
            $("#comunas").load('pages/datos_comuna.php?REGION='+id);
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
<div id="cuerpo-largo">
<div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150"></div>
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
<p>IDENTIFICACION</p>
<br /><br />
<p>NOMBRE: <strong><?php echo $row_rsUsuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO: <strong><?php echo $row_rsUsuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rsUsuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rsUsuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto-largo">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo">
<?php if(in_array($_SESSION['MM_UserGroup'], array("1"))){ ?>
<div id="ver-empresas">
<form id="frm_salto" action="panelusuarios.php" method="get">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">EMPRESAS</td>
  </tr>
  <tr>
    <td><select name="ID_EMPRESA" id="ID_EMPRESA" class="s2" alert="">
      <option value="0" <?php if (!(strcmp(0, $_GET['ID_EMPRESA']))) {echo "selected=\"selected\"";} ?>>-- SELECCIONE --</option>
      <?php
do {  
?><option value="<?php echo $row_rsEmpresas['ID_EMPRESA']?>"<?php if (!(strcmp($row_rsEmpresas['ID_EMPRESA'], $_GET['ID_EMPRESA']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsEmpresas['PROVEEDOR']?></option>
      <?php
} while ($row_rsEmpresas = mysql_fetch_assoc($rsEmpresas));
  $rows = mysql_num_rows($rsEmpresas);
  if($rows > 0) {
      mysql_data_seek($rsEmpresas, 0);
	  $row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
  }
?>
        </select></td>
    </tr>
</table>
</form>

</div>
<?php } ?>
<div id="ver-usuarios" class="panel2">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td id="titulo_admin" align="center">ADMINISTRAR DIVISIONES</td>
  </tr>
  <tr>
    <td>
    <DIV style="width:500PX; float:left;">
    DIVISION: 
      <input type="text" name="txt_division" id="txt_division" maxlength="20" size="35" class="upper" />
      <input name="iddivision" type="hidden" id="iddivision" value="0" />
      <span style="margin-left:10px;">
      <input type="button" class="ingreso-btn" id="btn_crear_divi" value="Crear" alert="" />
	  <input type="button" class="ingreso-btn" id="btn_mod_divi" value="Guardar" alert="" />
      <input type="button" class="ingreso-btn" id="btn_divi_c" value="Volver" alert="" />
      </span>
      </DIV>
    <DIV style="width:330PX; float:left;">
     
        
          <table class="flexme5" >
            <tbody>
            <?php 
			if($totalRows_rsTotalDivisiones>0){
			do { ?>
              <tr>
                <td><?php echo $row_rsTotalDivisiones['ID_DIVISION']; ?></td>
                <td><?php echo $row_rsTotalDivisiones['NOMDIVISION']; ?></td>
                <td><a href="#" id="<?php echo $row_rsTotalDivisiones['ID_DIVISION']; ?>" title="<?php echo $row_rsTotalDivisiones['NOMDIVISION']; ?>"><img src="img/edit.png"  class="editme" title="Editar Division" /></a></td>
                <td><a href="#" id="<?php echo $row_rsTotalDivisiones['ID_DIVISION']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a></td>
              </tr>
             <?php } while ($row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones)); } ?>
            </tbody>
          </table>
          </DIV>
    </td>
  </tr>
</table>
</div>

<div id="ver-usuarios-admempresa" class="panel1">

<form name="frm_upusuario" id="frm_upusuario">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">ADMINISTRAR USUARIOS</td>
  </tr>
  <tr>
    <td colspan="6" align="center" style="color:#990000;text-align:center;">
    <div style="margin:0 auto; width:500px; text-align:left;">
    RUT EMPRESA:&nbsp;
      <input type="text" name="rut" id="rut" maxlength="8" size="8" class="upper" />
      -
      <input type="text" name="dv" id="dv" maxlength="1" size="1" class="upper" />
      </div>
      </td>
    </tr>
  <tr>
    <td colspan="6" align="center" style="color:#990000;text-align:center;"><div style="margin:0 auto; width:500px; text-align:left;">
      NOMBRE DE LA EMPRESA:&nbsp;<input type="text" name="empresa" id="empresa" size="50" class="upper" />
      </div>
      </td>
  </tr>
    <tr>
    <td>NOMBRE</td>
    <td align="left"><input type="text" name="nombre" id="nombre" maxlength="8" class="upper" /></td>
    <td>APELLIDO</td>
    <td align="left"><input type="text" name="paterno" id="paterno" class="upper" /></td>
    <td>MATERNO</td>
    <td><input type="text" name="materno" id="materno" class="upper" alert="" /></td>
  </tr>
  <tr>
    <td>USUARIO</td>
    <td align="left"><input type="text" name="usuario" id="usuario" size="12" maxlength="10" class="upper" /></td>
    <td>CLAVE</td>
    <td align="left"><input type="text" name="clave" id="clave" size="12" maxlength="8" class="upper" alert="" /></td>
    <td>CORREO</td>
    <td><input type="text" name="correo" id="correo" class="upper" alert="" /></td>
  </tr>
  <tr>
    <td>REGION</td>
    <td align="left"><select name="region" id="region" style="width:200px;" alert="" class="s2">
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
    <td align="left"><select name="comunas" id="comunas" class="s2" alert=""></select></td>
    <td>DIRECCION</td>
    <td><input type="text" name="dire" id="dire" class="upper" alert="" /></td>
  </tr>
  <tr>
    <td id="mensajeaviso" alert="">DIVISION</td>
    <td align="left"><label></label>
      <select name="division" id="division" class="s2" alert="">
        <option value="0">-- SELECCIONE --</option>
        <?php
		if($totalRows_rsDivisiones>0){
do {  
?>
        <option value="<?php echo $row_rsDivisiones['ID_DIVISION']?>"><?php echo $row_rsDivisiones['NOMDIVISION']?></option>
        <?php
} while ($row_rsDivisiones = mysql_fetch_assoc($rsDivisiones));
  $rows = mysql_num_rows($rsDivisiones);
  if($rows > 0) {
      mysql_data_seek($rsDivisiones, 0);
	  $row_rsDivisiones = mysql_fetch_assoc($rsDivisiones);
  }}
?>
      </select>
      <a href="#" id="newdivision"><IMG src="img/plus_16.png" /></a>
      <input name="idempresa" type="hidden" id="idempresa" value="<?php echo $colname_rsListadoUsuarios; ?>" />
      <input name="idnivel" type="hidden" id="idnivel" value="<?php echo $_SESSION['MM_UserGroup']; ?>" />
      <input name="idusuario" type="hidden" id="idusuario" value="0" /></td>
    <td>ESTADO</td>
    <td align="left"><select name="estado" id="estado" class="s2" alert="">
      <option value="0">-- SELECCIONE --</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsEstados['ID_ESTUSU']?>"><?php echo $row_rsEstados['CR_USRESTAD']?></option>
      <?php
} while ($row_rsEstados = mysql_fetch_assoc($rsEstados));
  $rows = mysql_num_rows($rsEstados);
  if($rows > 0) {
      mysql_data_seek($rsEstados, 0);
	  $row_rsEstados = mysql_fetch_assoc($rsEstados);
  }
?>
        </select></td>
    <td>PERFIL</td>
    <td>
    <?php if($_SESSION['MM_UserGroup']=="2"){ ?>
    <input type="hidden" name="perfil" id="perfil" value="3" />
    <input type="text" name="x_perfil" id="x_perfil" size="12" maxlength="10" value="PROVEEDOR" disabled="disabled" class="upper" />
    
    
        <?php }else{ ?>
    <select name="perfil" id="perfil" class="s2" alert="">
      <option value="0">-- SELECCIONE --</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsPerfil['ID_PERFIL']?>"><?php echo $row_rsPerfil['PERFIL']?></option>
      <?php
} while ($row_rsPerfil = mysql_fetch_assoc($rsPerfil));
  $rows = mysql_num_rows($rsPerfil);
  if($rows > 0) {
      mysql_data_seek($rsPerfil, 0);
	  $row_rsPerfil = mysql_fetch_assoc($rsPerfil);
  }
?>
        </select>
                <?php } ?>        </td>
  </tr>
  
  <tr>
    <td colspan="6" align="center"><div style="margin-left:300px;"><div id="enviardata"></div><input type="button" class="ingreso-btn" id="btn_crear" value="Crear" alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" /><input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div></td>
  </tr>
  </tbody>
</table>
</form>


</div>

<div id='listar-usuarios' class="panel3">
<div id="mensajes"></div>
      <table class="flexme2">
        <tbody>
        <?php 
		
		if($totalRows_rsListadoUsuarios>0){
		
		do { ?>
          <tr >
                <td><?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['RUT']; ?>-<?php echo $row_rsListadoUsuarios['DV']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['USUPROVEEDOR']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['NOMDIVISION']; ?></td>
                <td><?php echo $row_rsListadoUsuarios['NOMBRE']." ".$row_rsListadoUsuarios['APELLIDOPAT']." ".$row_rsListadoUsuarios['APELLIDOMAT']; ?></td>
                <td><?php echo date("d/m/Y",strtotime($row_rsListadoUsuarios['FECHA_CREACION'])); ?></td>
                <td <?php
		    if($row_rsListadoUsuarios['CLAVE']==""){
			 echo "style='background-color:#CFD96C;'";
			}?>><?php echo $row_rsListadoUsuarios['PERFIL']; ?></td>
                <td>       	<?php 
				  	if($row_rsListadoUsuarios['ID_ESTUSU']=="1"){
					echo "<img src='img/accept_16.png' class='typeme' title='Usuario Activa' />";
					}else if($row_rsListadoUsuarios['ID_ESTUSU']=="2"){
					echo "<img src='img/dropbox_16.png' class='typeme' title='Usuario Inactivo' />";
					}
					?> 
                </td>
                <td><?php if($_SESSION['MM_UserGroup'] == "1" && $row_rsListadoUsuarios['PERFIL'] != "PROVEEDOR"  ){ ?><a href="#" id="<?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?>"><img src="img/edit.png"  class="editme" title="Editar Usuario" /></a><?php }elseif($_SESSION['MM_UserGroup'] == "2"){ ?><a href="#" id="<?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?>"><img src="img/edit.png"  class="editme" title="Editar Usuario" /></a><?php } ?>
                </td>
                <td><?php if($_SESSION['MM_UserGroup'] == "1" and $row_rsListadoUsuarios['PERFIL'] != "PROVEEDOR"  ){ ?><a href="#" id="<?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a><?php }elseif($_SESSION['MM_UserGroup'] == "2"){ ?><a href="#" id="<?php echo $row_rsListadoUsuarios['ID_USUARIO']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a><?php } ?>
                </td>
              </tr>
              <?php } while ($row_rsListadoUsuarios = mysql_fetch_assoc($rsListadoUsuarios)); } ?>
          </tbody>
        </table>
      </div>


</div>


</div>
</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div>
</body>
</html>
<?php
mysql_free_result($rsUsuario);

mysql_free_result($rsListadoUsuarios);

mysql_free_result($rsDivisiones);

mysql_free_result($rsEstados);

mysql_free_result($rsPerfil);

mysql_free_result($rsEmpresas);

mysql_free_result($rsTotalDivisiones);
?>