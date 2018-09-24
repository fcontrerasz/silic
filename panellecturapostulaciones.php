<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php require_once('pages/acceso_proveedor.php'); ?>
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
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);

if($_SESSION['MM_UserGroup'] == "2"){
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_maestro_postulaciones WHERE ID_EMPRESA = %s AND ID_LICITACION = %s", GetSQLValueString($_SESSION['MM_Empresa'], "text"), GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}else{
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_postulaciones_proveedor WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}

/*
echo $query_rsListadoPostulaciones;

echo "<BR>USER: ".$_SESSION['MM_Username']."<br>";
echo " GRUPO: ".$_SESSION['MM_UserGroup']."<br>";
echo " ID: ".$_SESSION['MM_UserID']."<br>";
echo " EMPRESA: ".$_SESSION['MM_Empresa']."<br>";
*/

$rsListadoPostulaciones = mysql_query($query_rsListadoPostulaciones, $cn_licitaciones) or die(mysql_error());
$row_rsListadoPostulaciones = mysql_fetch_assoc($rsListadoPostulaciones);
$totalRows_rsListadoPostulaciones = mysql_num_rows($rsListadoPostulaciones);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsEstados = "SELECT * FROM estado_usuario ORDER BY CR_USRESTAD ASC";
$rsEstados = mysql_query($query_rsEstados, $cn_licitaciones) or die(mysql_error());
$row_rsEstados = mysql_fetch_assoc($rsEstados);
$totalRows_rsEstados = mysql_num_rows($rsEstados);

$colname_rsLicitaciones = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsLicitaciones = $_GET['ID_LICITACION'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsLicitaciones = sprintf("SELECT 
  *,
  (to_days(FECHA_TERMINO) - to_days(curdate())) AS FALTA,
  (to_days(FECHA_TERMINO) - to_days(FECHA_INICIO)) AS TOTALES,
  tipolicitacion.TIPOLICITACION
FROM
  tipolicitacion
  INNER JOIN licitaciones ON (tipolicitacion.ID_TIPOLICITACION = licitaciones.ID_TIPOLICITACION)
WHERE
  ID_LICITACION = %s
ORDER BY
  NOMBRELICI", GetSQLValueString($colname_rsLicitaciones, "int"));
$rsLicitaciones = mysql_query($query_rsLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsLicitaciones = mysql_fetch_assoc($rsLicitaciones);
$totalRows_rsLicitaciones = mysql_num_rows($rsLicitaciones);

$query_rsEmpresas = "SELECT * FROM empresas WHERE ID_ESTADOEMP = 1";
if($_SESSION['MM_UserGroup'] == "2"){
$query_rsEmpresas = "SELECT * FROM empresas WHERE ID_EMPRESA = ".$_SESSION['MM_Empresa']." AND ID_ESTADOEMP = 1";
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$rsEmpresas = mysql_query($query_rsEmpresas, $cn_licitaciones) or die(mysql_error());
$row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
$totalRows_rsEmpresas = mysql_num_rows($rsEmpresas);


$filtroempre = "WHERE ID_ESTADOLICITA=1";
if($_SESSION['MM_UserGroup'] == "2"){
$filtroempre = "WHERE ID_ESTADOLICITA = 1 AND ID_LICITACION IN (".licitacionesEmpresas($_SESSION['MM_Empresa']).")";
}
$query_rsTotalLicitaciones = "SELECT * FROM licitaciones ".$filtroempre;

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$rsTotalLicitaciones = mysql_query($query_rsTotalLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsTotalLicitaciones = mysql_fetch_assoc($rsTotalLicitaciones);
$totalRows_rsTotalLicitaciones = mysql_num_rows($rsTotalLicitaciones);


$diash = $row_rsLicitaciones["TOTALES"]-$row_rsLicitaciones["FALTA"];
$diast = $row_rsLicitaciones["TOTALES"];
$resta = $diast - $diash;
//echo "Dias Totales: ".$diast." Dias Trabajados: ".$diash;

if($diash<0){
$progress = 0;
}else if($diash==0){
$progress = 1;
}else{
$porcentaje = round(($diash*100)/$diast);
$progress = $porcentaje;
}

if($progress>100){
$progress = 100;
}



$colname_rsBasesLicitacion = "-1";
$idlicitacionx = $colname_rsLicitaciones;
if ($idlicitacionx!="0") {
  $colname_rsBasesLicitacion = $idlicitacionx;
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = sprintf("SELECT * FROM archivos_licitacion WHERE ID_LICITACION = %s ORDER BY ARCHIVO ASC", GetSQLValueString($colname_rsBasesLicitacion, "int"));
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

$colname_rsPreguntas = "-1";
if (isset($row_rsListadoPostulaciones["ID_POSTULACIONES"])) {
  $colname_rsPreguntas = $row_rsListadoPostulaciones["ID_POSTULACIONES"];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
//listar_proveedor_respuestas
//$query_rsPreguntas = sprintf("SELECT * FROM listar_maestro_respuestas WHERE ID_LICITACION = %s ORDER BY FECHA_PREGUNTA DESC", GetSQLValueString($colname_rsBasesLicitacion, "int"));
$query_rsPreguntas = sprintf("SELECT * FROM listar_proveedor_respuestas WHERE ID_LICITACION = %s ORDER BY FECHA_PREGUNTA DESC", GetSQLValueString($colname_rsBasesLicitacion, "int"));

//echo $query_rsPreguntas;
$rsPreguntas = mysql_query($query_rsPreguntas, $cn_licitaciones) or die(mysql_error());
$row_rsPreguntas = mysql_fetch_assoc($rsPreguntas);
$totalRows_rsPreguntas = mysql_num_rows($rsPreguntas);


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
<script src="js/jquery.countdown.js" type="text/javascript"></script>
<script src="js/jquery.countdown-es.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/jquery.countdown.css" type="text/css"/>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function zxcDHMS(zxcsecs,zxcd,zxcp,zxc0){
 zxcsecs=((Math.floor(zxcsecs/zxcd))%zxcp);
 return zxcsecs>9?zxcsecs:'0'+zxcsecs;
}

function zxcCountDown(zxcid,zxcmess,zxcsecs,zxcmins,zxchrs,zxcdays){
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
  this.obj.innerHTML=(this.mhd[2]?zxcDHMS(zxcsecs,86400,100000)+' dias ':'')+(this.mhd[1]||this.mhd[2]?zxcDHMS(zxcsecs,3600,24)+' horas ':'')+(this.mhd[0]||this.mhd[1]||this.mhd[2]?zxcDHMS(zxcsecs,60,24)+':':'')+zxcDHMS(zxcsecs,1,60)+'';
  this.to=setTimeout(function(zxcoop){return function(){zxcoop.cng();}}(this),1000);
 }
 else this.obj.innerHTML=this.mess||'';
}
/*]]>*/
</script>
<script type="text/javascript">
function serverTime() { 
    var time = null; 
    $.ajax({url: 'pages/servertime.php', 
        async: false, dataType: 'text', 
        success: function(text) { 
            time = new Date(text); 
        }, error: function(http, message, exc) { 
            time = new Date(); 
    }}); 
    return time; 
}

/*
$(function () {
var hasta = new Date(<?php echo date("Y",strtotime($row_rsLicitaciones['FECHA_CONSULTAS'])); ?>, <?php echo date("m",strtotime($row_rsLicitaciones['FECHA_CONSULTAS'])); ?> -1, <?php echo date("d",strtotime($row_rsLicitaciones['FECHA_CONSULTAS'])); ?>,<?php echo date("H",strtotime($row_rsLicitaciones['FECHA_CONSULTAS'])); ?>,<?php echo date("i",strtotime($row_rsLicitaciones['FECHA_CONSULTAS'])); ?>);


$('#defaultCountdown').countdown({ 
    until:hasta, serverSync: serverTime}); 
});
*/



</script>
<script>



function createPregunta(){
var data2 = $("#txt_pregunta").val();
var data1 = $("#idpostulaciones").val(); 	

if (data2 == "" || (data2.length < 3) ) {
	alert("Pregunta Vacia o Demasiado Corta");
	return false;
}

var dataString = "idpostulacion="+data1+"&texto="+data2+"&tipo=2";
//alert(dataString);
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_pregunta.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('PREGUNTA CREADA',true);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function deletePostulante(numero){
var data2 = $("#idlicitacion").val();
var dataString = "idlicitacion="+data2+"&idempresa="+numero;
//alert(dataString);
//return false;
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_postulacion.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('EMPRESA ELIMINADA',true);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ELIMINAR',false);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function parseHTML(html) {
	var root = document.createElement("div");
	root.innerHTML = html;
    var allChilds = root.childNodes;
	return allChilds[0].id;
}

function Borrar(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   deletePostulante(parseHTML(data));
   }); 
}; 

function Archivos(celDiv,id) { 
   $(celDiv).click(function () {
   		   var data = celDiv.innerHTML;
		   document.location.href = 'panelarchivos.php?ID_POSTULACIONES='+parseHTML(data);
   }); 
}; 

function habilitarPanel(){

$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 20, sortable : true, align: 'left'},
						{display: 'Empresa', name : 'empresa', width : 100, sortable : true, align: 'left'},
                        {display: 'Estado', name : 'estado', width : 70, sortable : true, align: 'left'},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar},
						{display: ' ', name : 'pregunta', width : 20, sortable : true, align: 'left'},
						{display: ' ', name : 'archivos', width : 20, sortable : true, align: 'left', process: Archivos}
            ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 180,
singleSelect: true
});
}



function alertme(who,msg){
		$(who).attr('alert',msg);
		$(who).tipsy({title: 'alert',gravity: 'w', fade: true});
   	    $(who).tipsy("show");
}

//CALL `insert_postulaciones`(NULL, NULL, @`result`)

function agregarEmpresa(){
var data1 = $("#idempresa").val();
var data2 = $("#idlicitacion").val();

if(data2==0){
alertme("#ID_LICITACION","INGRESA UNA LICITACION");
$("#ID_LICITACION").focus();
return false;
}

if(data1==0){
alertme("#idempresa","SELECCIONA UNA EMPRESA");
$("#idempresa").focus();
return false;
}

var dataString = "idempresa="+data1+"&idlicitacion="+data2;
$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_postulacion.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('EMPRESA AGREGADA',true);
			}else if(data == 0){
				$(this).msjAccion('EMPRESA YA EXISTE',false);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

$(window).load(function(){
   <?php echo counterProveedoresPostula($row_rsListadoPostulaciones["ID_POSTULACIONES"]); ?>
});


$(document).ready(function() {

$(".panel3").hide();
$(".panel1").hide();

<?php if(in_array($_SESSION['MM_UserGroup'], array("3"))){ ?>
$("#ver-empresas").hide();
$("#empresa").hide();
<? } ?>

var empresa = $("#idlicitacion").val();
if(empresa>0){
$(".panel3").fadeIn('slow');
$(".panel1").fadeIn('slow');
}

$('.upper').bestupper();
$(".panel2").hide();

habilitarPanel();

jQuery.fn.msjAccion = function(msg,key) {
				$("#mensajes").jGrowl(msg, { life: 600 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(key){
                    	location.reload();
					}
                }});
};

$("#ID_LICITACION").change(function(){ 
	$("#frm_salto").submit();
});

$("#btn_crear").click(function(){
	agregarEmpresa();
});

$("#btn_preguntar").click(function(){
	createPregunta();
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
<div id="contexto-largo">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel-largo">
<div id="ver-empresas">
<form id="frm_salto" action="panellecturapostulaciones.php" method="get">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">MIS LICITACIONES</td>
  </tr>
  <tr>
    <td><select name="ID_LICITACION" id="ID_LICITACION" class="s2" alert="">
      <option value="0" <?php if (!(strcmp(0, $_GET['ID_LICITACION']))) {echo "selected=\"selected\"";} ?>>-- SELECCIONE --</option>
      <?php
do {  
?><option value="<?php echo $row_rsTotalLicitaciones['ID_LICITACION']?>"<?php if (!(strcmp($row_rsTotalLicitaciones['ID_LICITACION'], $_GET['ID_LICITACION']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTotalLicitaciones['NOMBRELICI']?></option>
      <?php
} while ($row_rsTotalLicitaciones = mysql_fetch_assoc($rsTotalLicitaciones));
  $rows = mysql_num_rows($rsTotalLicitaciones);
  if($rows > 0) {
      mysql_data_seek($rsTotalLicitaciones, 0);
	  $row_rsTotalLicitaciones = mysql_fetch_assoc($rsTotalLicitaciones);
  }
?>
        </select>
      </td>
    </tr>
</table>
</form>

</div>

<div id="ver-postulaciones-lectura" class="panel1">
<div id="mensajes"></div>
<form name="frm_upusuario" id="frm_upusuario">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">DESCRIPCION DE LA LICITACION</td>
  </tr>
  <tr>
    <td>CODIGO</td>
    <td><input type="text" disabled="disabled" name="nombre" id="nombre" maxlength="8" class="upper" value="<?php echo $row_rsLicitaciones['NOMBRELICI']; ?>" />
      <input name="idlicitacion" type="hidden" id="idlicitacion" value="<?php echo $colname_rsListadoPostulaciones; ?>" />
      <input name="idempresa" type="hidden" id="idempresa" value="<?php echo $_SESSION['MM_UserID']; ?>" />
      <input name="idpostulaciones" type="hidden" id="idpostulaciones" value="<?php echo $row_rsListadoPostulaciones["ID_POSTULACIONES"]; ?>" />
       
            </td>
    <td>LICITACION</td>
    <td><input type="text" disabled="disabled" name="paterno" id="paterno" class="upper" value="<?php echo $row_rsLicitaciones['CODIGOLICI']; ?>" /><input type="text" disabled="disabled" name="tipolici" id="tipolici" class="upper" value="<?php echo $row_rsLicitaciones['TIPOLICITACION']; ?>" /></td>
     <td>INICIO LICITACION</td>
    <td><input name="materno" disabled="disabled" type="text" class="upper" id="materno" value="<?php echo date("d/m/Y",strtotime($row_rsLicitaciones['FECHA_INICIO'])); ?>" alert="" /></td>
  </tr>
  <tr>
    <td>AVANCE</td>
    <td><div class="bonus_entry_progress">
    <div class="bonus_entry_bar" style="width:<?php echo $progress ?>%; background-color:#CFD96C;">
&nbsp;    </div>
    <div class="bonus_progress_text">
        <?php echo $progress ?>%    </div>
</div></td>
<?PHP //echo tipoLicitacion($colname_rsListadoPostulaciones); ?>
    <td>AGREGAR</td>
    <td>
      <select name="empresa" id="empresa" class="s2" alert="" <?php if($_SESSION['MM_UserGroup'] == "1"){ ?> disabled="disabled"<?php } ?>>
      <option value="0">--SELECCIONE--</option>
        <?php
do {  
?>

        <option value="<?php echo $row_rsEmpresas['ID_EMPRESA']?>"<?php if($_SESSION['MM_UserGroup'] == "2"){ if (!(strcmp($row_rsEmpresas['ID_EMPRESA'], $_SESSION['MM_Empresa']))) {echo "selected=\"selected\"";} } ?>><?php echo $row_rsEmpresas['PROVEEDOR']?></option>
        <?php
} while ($row_rsEmpresas = mysql_fetch_assoc($rsEmpresas));
  $rows = mysql_num_rows($rsEmpresas);
  if($rows > 0) {
      mysql_data_seek($rsEmpresas, 0);
	  $row_rsEmpresas = mysql_fetch_assoc($rsEmpresas);
  }
?>
      </select>
      <span style="margin-left:10px;">
      <?php if(($row_rsLicitaciones['ID_ESTADOLICITA']=="1")){ ?>
      <input type="button"  id="btn_crear" <?php 
	  if($_SESSION['MM_UserGroup'] == "3"){ 
	  	if(EmpresaPostula($_SESSION['MM_UserID'],$colname_rsLicitaciones)>0){ 
				echo 'value="Ud ya está Participando" class="ingreso-btn-postular"  disabled="disabled"';
		}else{ 
				echo 'value="Participar de la Licitacion" class="ingreso-btn-postular"';
		}
	  }elseif($_SESSION['MM_UserGroup'] == "1"){ 
	  	echo 'value="Asignar" disabled="disabled" class="ingreso-btn"'; 
	  }?> alert="" />
      <?php } ?>
      </span>   </td>

    <td>TERMINO LICITACION</td>
    <td><input name="materno2" disabled="disabled" type="text" class="upper" id="materno2" value="<?php echo date("d/m/Y",strtotime($row_rsLicitaciones['FECHA_TERMINO'])); ?>" alert="" /></td>
  </tr>
  <tr>
    <td>GLOSA</td>
    <td colspan="3"><?php echo $row_rsLicitaciones['DESCLICI']; ?></td>
    <td>RESTANTE CONSULTAS</td>
    <td><span style="border:#666666 solid 1px; background-color:#A7DEC9; padding:3px;" id="counter<?php echo $row_rsListadoPostulaciones["ID_POSTULACIONES"]; ?>"></span></td>
  </tr>
  <tr>
    <td>BASES</td>
    <td colspan="5"><?php 
			if($totalRows_rsBasesLicitacion>0){
			$countx = 1;
			do { ?>
                
                <a href="pages/archivo_licitacion.php?ID_ARCHIVO=<?php echo $row_rsBasesLicitacion['ID_ARCHIVO']; ?>"><?php echo $row_rsBasesLicitacion['lici_nombre']; ?>&nbsp;<img src="img/download_16.png" />|</a>
            <?php 
			$countx++;
			} while ($row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion)); }?></td>
    </tr>
  </tbody>
</table>
</form>


</div>
<?php if(EmpresaPostula($_SESSION['MM_UserID'],$colname_rsLicitaciones)>0){ 



$deadtime = date("Y-m-d H:i",strtotime($row_rsLicitaciones['FECHA_CONSULTAS']));
$today = date("Y-m-d H:i");  

//echo $deadtime."_".$today;

if(($row_rsLicitaciones['ID_ESTADOLICITA']=="1")){
?>
<div id="ver-mini-preguntas">

<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="3" id="titulo_admin" align="left">PREGUNTAS</td>
  </tr>
  <?php if($today<=$deadtime){ ?>
  <tr>
    <td width="10%">PREGUNTA</td>
      <td width="80%"><textarea name="txt_pregunta" maxlength="600" id="txt_pregunta" cols="100" rows="3" class="upper"></textarea></td>
       <td width="10%"><input name="btn_preguntar" id="btn_preguntar" type="submit" value="ENVIAR" class="ingreso-btn" /></td>
    </tr>
    <?php }else{ ?>
      <tr>
    	<td colspan="3" width="100%" align="center"><FONT color="#990000">PLAZO DE PREGUNTAS VENCIDO</FONT></td>
    </tr>
    <?php } ?>
     <tr>
    <td colspan="3">
    <div id="lectura-preguntas-mini">
    <HR />
    <table width="70%" border="0" align="center" style="margin-top:10px;">
      <?php 
	  
	  //echo "TOTAL: ".$totalRows_rsPreguntas;
	  
	  if($totalRows_rsPreguntas>0){
	  do { ?>
          <tr>
            <td valign="top"><img src="img/chat_16.png" /></td>
            <td valign="top"><?php echo date("d/m/Y H:i",strtotime($row_rsPreguntas["FECHA_PREGUNTA"])); ?>&nbsp;</td>
            <td class="item-preguntas"><div style="text-align:left;"><?php echo $row_rsPreguntas["PREGUNTA"]; ?></div>
            
            <?php if($row_rsPreguntas["ID_RESPUESTA"]>0){ ?><div style="border:1 solid #666666; margin:3px; color:#CC6600;"><img src="img/user_close_16.png" /> <?php echo $row_rsPreguntas["DESC_RESPUESTA"]; ?></div><?php } ?>
            <div style="text-align:left; margin-bottom:15px;"></div>
            </td>
          </tr>
        
        <?php } while ($row_rsPreguntas = mysql_fetch_assoc($rsPreguntas)); 
				$rows = mysql_num_rows($rsPreguntas);
				 if($rows > 0) {
			 		 mysql_data_seek($rsPreguntas, 0);
			 		 $row_rsPreguntas = mysql_fetch_assoc($rsPreguntas);
		 		 }	
		} 
		?>
        </table>
        </div>
        </td>
  </tr>
</table>
</div>
<?php }else{ ?>
<div id="ver-mini-preguntas">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">LICITACION NO ESTÁ VIGENTE.</FONT></p>
</div>
<?php }}  ?>


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

mysql_free_result($rsEstados);

mysql_free_result($rsPreguntas);

mysql_free_result($rsLicitaciones);

mysql_free_result($rsEmpresas);

mysql_free_result($rsTotalLicitaciones);
?>