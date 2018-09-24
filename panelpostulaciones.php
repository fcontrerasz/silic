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
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);

if($_SESSION['MM_UserGroup'] == "2"){
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_postulaciones_proveedores WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}else{
$query_rsListadoPostulaciones = sprintf("SELECT * FROM listar_postulaciones_proveedores WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoPostulaciones, "int"));
}
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


$query_rsProveedores = "SELECT USUPROVEEDOR,ID_USUARIO FROM listar_maestro_usuarios WHERE ID_ESTUSU = 1";
if($_SESSION['MM_UserGroup'] == "2"){
$query_rsProveedores = "SELECT USUPROVEEDOR,ID_USUARIO FROM listar_maestro_usuarios WHERE ID_ESTUSU = 1 AND PERFIL='PROVEEDOR' AND ID_EMPRESA = ".$_SESSION['MM_Empresa'];
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$rsProveedores = mysql_query($query_rsProveedores, $cn_licitaciones) or die(mysql_error());
$row_rsProveedores = mysql_fetch_assoc($rsProveedores);
$totalRows_rsProveedores = mysql_num_rows($rsProveedores);




$filtroempre = "";
if($_SESSION['MM_UserGroup'] == "2"){
//$filtroempre = "WHERE ID_LICITACION IN (".licitacionesEmpresas($_SESSION['MM_Empresa']).")";
$filtroempre = "WHERE ID_EMPRESACREADORA=".$_SESSION['MM_Empresa'];
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

$(function () {
var hasta = new Date(<?php echo date("Y",strtotime($row_rsLicitaciones['FECHA_TERMINO'])); ?>, <?php echo date("m",strtotime($row_rsLicitaciones['FECHA_TERMINO'])); ?> -1, <?php echo date("d",strtotime($row_rsLicitaciones['FECHA_TERMINO'])); ?>,10,2);

$('#defaultCountdown').countdown({ 
    until:hasta, serverSync: serverTime}); 
 
});


</script>
<script>

function deletePostulante(numero){
if(confirm("¿Está seguro de borrar la postulacion del Proveedor?")) {
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
						{display: 'Proveedor', name : 'empresa', width : 100, sortable : true, align: 'left'},
                        {display: 'Estado', name : 'estado', width : 70, sortable : true, align: 'left'},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar},
						{display: ' ', name : 'pregunta', width : 20, sortable : true, align: 'left', hide:true},
						{display: ' ', name : 'archivos', width : 20, sortable : true, align: 'left', process: Archivos <?php if($_SESSION['MM_UserGroup']=="2"){ ?>, hide:true<?php } ?> }
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
var data1 = $("#empresa option:selected").val();
var data2 = $("#idlicitacion").val();

if(data2==0){
alertme("#ID_LICITACION","INGRESA UNA LICITACION");
$("#ID_LICITACION").focus();
return false;
}

if(data1==0){
alertme("#empresa","SELECCIONA UN PROVEEDOR");
$("#empresa").focus();
return false;
}

var dataString = "idempresa="+data1+"&idlicitacion="+data2;

//alert(dataString);
//return false;

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
   <?php echo counterPostulaLici($colname_rsLicitaciones); ?>
});

$(document).ready(function() {
$(".panel3").hide();
$(".panel1").hide();

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

<div id="ver-empresas">

<form id="frm_salto" action="panelpostulaciones.php" method="get">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">LICITACIONES</td>
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

<div id="ver-postulaciones" class="panel1">

<form name="frm_upusuario" id="frm_upusuario">
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">ADMINISTRAR POSTULACIONES</td>
  </tr>
  <tr>
    <td>CODIGO</td>
    <td><input type="text" disabled="disabled" name="nombre" id="nombre" maxlength="8" class="upper" value="<?php echo $row_rsLicitaciones['NOMBRELICI']; ?>" />
      <input name="idlicitacion" type="hidden" id="idlicitacion" value="<?php echo $colname_rsListadoPostulaciones; ?>" /></td>
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
    <?php if($row_rsLicitaciones['TIPOLICITACION']=="CERRADA"){ ?>
      <select name="empresa" id="empresa" class="s2" alert="" <?php if($_SESSION['MM_UserGroup'] == "1"){ ?>disabled="disabled"<?php } ?>>
      <option value="0">--SELECCIONE--</option>
        <?php
do {  
?>

        <option value="<?php echo $row_rsProveedores['ID_USUARIO']?>"><?php echo $row_rsProveedores['USUPROVEEDOR']?></option>
        <?php
} while ($row_rsProveedores = mysql_fetch_assoc($rsProveedores));
  $rows = mysql_num_rows($rsProveedores);
  if($rows > 0) {
      mysql_data_seek($rsProveedores, 0);
	  $row_rsProveedores = mysql_fetch_assoc($rsProveedores);
  }
?>
      </select>
      <?php } ?>
      <span style="margin-left:10px;">
      
	  <?php if($_SESSION['MM_UserGroup'] == "2" && $row_rsLicitaciones['TIPOLICITACION']=="CERRADA"){ ?>
      <input type="button"  id="btn_crear" value='Asignar' class='ingreso-btn' alert="" />
      <?php } ?>
      
      <?php if($_SESSION['MM_UserGroup'] == "3" && $row_rsLicitaciones['TIPOLICITACION']=="ABIERTA"){ ?>
      <input type="button"  id="btn_crear" value='Participar de la Licitacion' class='ingreso-btn-postular' alert="" />
      <?php } ?>
      
      </span>   </td>

    <td>TERMINO LICITACION</td>
    <td><input name="materno2" disabled="disabled" type="text" class="upper" id="materno2" value="<?php echo date("d/m/Y",strtotime($row_rsLicitaciones['FECHA_TERMINO'])); ?>" alert="" /></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td>CONSULTAS HASTA</td>
    <td colspan="1"><span style="border:#666666 solid 1px; background-color:#A7DEC9; padding:3px;" id="counter<?php echo $colname_rsListadoPostulaciones; ?>"></span></td>
    
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
		if($totalRows_rsListadoPostulaciones > 0){
		do { ?>
          <tr>
          <td><?php echo $row_rsListadoPostulaciones['ID_EMPRESA']; ?></td>
          <td><?php echo $row_rsListadoPostulaciones['USUPROVEEDOR']; ?></td>
          <td><?php echo $row_rsListadoPostulaciones['CR_USRESTAD']; ?></td>
          <td><a href="#" id="<?php echo $row_rsListadoPostulaciones['ID_EMPRESA']; ?>"><img src="img/delete.png" class="editme" title="Eliminar" /></a></td>
          <td><a href="#" id="<?php echo $row_rsListadoPostulaciones['ID_POSTULACIONES']; ?>"><img src="img/questionmark_16.png" class="editme" title="Preguntas" /></a></td>
          <td><a href="#" id="<?php echo $row_rsListadoPostulaciones['ID_POSTULACIONES']; ?>"><img src="img/filepath_16.png"  class="editme" title="Archivos Licitacion" /></a></td>
          </tr>
              <?php } while ($row_rsListadoPostulaciones = mysql_fetch_assoc($rsListadoPostulaciones)); }?>
          </tbody>
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

mysql_free_result($rsEstados);

mysql_free_result($rsLicitaciones);

mysql_free_result($rsEmpresas);

mysql_free_result($rsTotalLicitaciones);
?>