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
if (isset($_GET['ID_POSTULACIONES'])) {
  $colname_rsListadoArchivos = $_GET['ID_POSTULACIONES'];
}elseif(isset($_GET["ID_LICITACION"])){
	$colname_rsListadoArchivos = postulacionLicitaciones($_GET["ID_LICITACION"],$_SESSION['MM_UserID']);
}

$colname_rsBasesLicitacion = "-1";

if($_SESSION['MM_UserGroup']==1){
$idlicitacionx = licitacionesPostulacion($colname_rsListadoArchivos);
if ($idlicitacionx!="0") {
  $colname_rsBasesLicitacion = $idlicitacionx;
}
}elseif($_SESSION['MM_UserGroup']==1){
$idlicitacionx = $_GET["ID_LICITACION"];
}elseif($_SESSION['MM_UserGroup']==3){
$colname_rsBasesLicitacion = $_GET["ID_LICITACION"];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = sprintf("SELECT * FROM archivos_licitacion WHERE ID_LICITACION = %s ORDER BY ARCHIVO ASC", GetSQLValueString($colname_rsBasesLicitacion, "int"));
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

//echo $query_rsBasesLicitacion;
//echo "----------------->".$totalRows_rsBasesLicitacion."<--------";

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoArchivos = sprintf("SELECT *, LENGTH(DOCU_ARCHIVO) AS LARGO FROM documentacion WHERE ID_POSTULACIONES = %s ORDER BY DOCU_ARCHIVO ASC", GetSQLValueString($colname_rsListadoArchivos, "int"));
$rsListadoArchivos = mysql_query($query_rsListadoArchivos, $cn_licitaciones) or die(mysql_error());
$row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos);
$totalRows_rsListadoArchivos = mysql_num_rows($rsListadoArchivos);

$colname_rsListadolix = "-1";
if (isset($_GET["ID_LICITACION"])) {
  $colname_rsListadolix = $_GET["ID_LICITACION"];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListarLicitacion = sprintf("SELECT * FROM licitaciones WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadolix, "int"));
$rsListarLicitacion = mysql_query($query_rsListarLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsListarLicitacion = mysql_fetch_assoc($rsListarLicitacion);
$totalRows_rsListarLicitacion = mysql_num_rows($rsListarLicitacion);

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsTotalDivisiones = sprintf("SELECT * FROM requisitos_licitacion WHERE id_licitacion = %s", GetSQLValueString($colname_rsListadolix, "int"));
$rsTotalDivisiones = mysql_query($query_rsTotalDivisiones, $cn_licitaciones) or die(mysql_error());
$row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones);
$totalRows_rsTotalDivisiones = mysql_num_rows($rsTotalDivisiones);


mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsTotalRequisitos = sprintf("SELECT * FROM listado_requisitos WHERE id_licitacion = %s and ID_DOCUMENTACION is null", GetSQLValueString($colname_rsListadolix, "int"));
$rsTotalRequisitos = mysql_query($query_rsTotalRequisitos, $cn_licitaciones) or die(mysql_error());
$row_rsTotalRequisitos = mysql_fetch_assoc($rsTotalRequisitos);
$totalRows_rsTotalRequisitos = mysql_num_rows($rsTotalRequisitos);




//echo  "----------> TOTAL: ".$totalRows_rsListarLicitacion;
//echo $query_rsListarLicitacion;


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rs_inserta = "0";
if($_POST["idinserta"]>0 and isset($_POST["idpostulacion"]) and ($_POST["totalpostu"]<$_POST["totalbase"])){

//echo $_POST["idpostulacion"];

//die;
$archivo = $_FILES["ARCHIVO"]["tmp_name"]; 
$tamanio = $_FILES["ARCHIVO"]["size"];
$tipo    = $_FILES["ARCHIVO"]["type"];
$nombre  = $_FILES["ARCHIVO"]["name"];
$xnombre = $_POST["nombre"];
$glosa = $_POST["tipo"];
$idpostulacion = $_POST["idpostulacion"];



$peso_archivo = filesize($archivo);


//if($peso_archivo > 0 && $peso_archivo < 5100){

$fp = fopen($archivo, "rb");
$contenido = @fread($fp, $tamanio);
$contenido = addslashes($contenido);
fclose($fp); 

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsInsertaArchivos = "INSERT INTO `documentacion`
    (ID_POSTULACIONES, DOCU_ARCHIVO, DOCU_TIPO, DOCU_FECHA, DOCU_NOMBRE,docu_nomarchivo,docu_glosa)
VALUES
    (".$idpostulacion.", '".$contenido."', '".$tipo."', NOW(),'".$xnombre."','".strtoupper($nombre)."','".$glosa."')";

//echo $query_rsInsertaArchivos;

mysql_query($query_rsInsertaArchivos, $cn_licitaciones) or die(mysql_error());
if(mysql_affected_rows($cn_licitaciones) > 0){
       $colname_rs_inserta = 1;
    }else{
       $colname_rs_inserta = 2;
	}

/*
}else{
	$colname_rs_inserta = 3;
}*/


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

function deleteArchivo(numero){
if(confirm("¿Está seguro de borrar el Archivo?")) {
	var dataString = "idarchivo="+numero;
	//alert(dataString);
	//return false;
	$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_archivo.php",
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
		   //alert(data);
		   deleteArchivo(parseHTML(data));
   }); 
}; 

function habilitarPanel()
{

$('.flexme2').flexigrid({
colModel : [
                        {display: 'ID', name : 'id', width : 20, sortable : true, align: 'left'},
						{display: 'Nombre', name : 'nombre', width : 200, sortable : true, align: 'left'},
						{display: 'Archivo', name : 'archivo', width : 130, sortable : true, align: 'left'},
                        {display: 'Tamaño', name : 'tamano', width : 70, sortable : true, align: 'left'},
						{display: 'Fecha Creacion', name : 'tipo', width : 130, sortable : true, align: 'left'},
						{display: 'Tipo', name : 'tipo', width : 130, sortable : true, align: 'left'},
						{display: ' ', name : 'modificar', width : 20, sortable : true, align: 'left', process: Editar},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left', process: Borrar <?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?>, hide:true <?php }else{ if(($row_rsListarLicitacion['ID_ESTADOLICITA']!="1")){ ?>, hide:true<?php }} ?>}
                ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 260,
singleSelect: true
});

}


jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

function openFile(file) {
	var filename = $(this).val();
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
				$("#mensajes").jGrowl(msg, { life: 1000 , sticky: false , easing: 'linear' ,
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

<?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?>
$("#ver-archivos").hide();
<?php } ?>

<?php if($_SESSION['MM_UserGroup']==3){ ?>
$("#ver-archivos").hide().fadeIn('slow');
<?php } ?>


var llave = <?php echo $colname_rs_inserta; ?>;
if(llave==1){
$(this).msjAccion('ARCHIVO SUBIDO',1);
}
if(llave==2){
$(this).msjAccion('NO SE PUDO CARGAR EL ARCHIVO',2);
}
if(llave==3){
$(this).msjAccion('PROBLEMAS CON EL TAMAÑO DEL ARCHIVO',2);
}

$("#btn_guardar").hide();

$("#nombre").focus();
$('.upper').bestupper();

$("#btn_guardar").click(function(){
	updateEmpresa();
});

$("#btn_crear").click(function(){
	var data1 = $("#tipo").val();
	var data2 = $("#ARCHIVO").val();
	var data3 = $("#nombre").val();
	var data4 = $("#tiponame").val();
	var data5 = $("#idexiste").val();
	
	if(data5>0){
		alertme("#tiponame","YA EXISTE ESTE ARCHIVO SUBIDO");
		return false;
	}
	
	var arr = new Array();
	$('#requisitos_table ul li').each(function() { 
	  arr.push(this.innerHTML); 
	})
	
	var totalesigu = 0;
	for(k=0;k<arr.length;k++){
	//alert(data4+" "+arr[k]);
		if(data4 == arr[k]){
			totalesigu += 1;
		}
	}
/*
	if(totalesigu==0){
		alertme("#tiponame","NOMBRE DEL ARCHIVO INCORRECTO");
		return false;
	}
*/
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
	/*
	if(data1=="" || data1=="DESCONOCIDO"){
	alertme("#tipo","ARCHIVO INVALIDO");
	return false;
	}
	*/
	if(data1==""){
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
  var filename = $(this).val().split('\\').pop().toUpperCase();
  var licitacion = $("#idlicita").val();
  $("#tiponame").val(filename);
  $("#tipo").val(openFile($(this).val()));

	//alert($(this).val().split('\\').pop().length);

	if($(this).val().split('\\').pop().length>99){
	alert("NOMBRE DEL ARCHIVO DEMASIADO LARGO");
	$("#tipo").val('DESCONOCIDO');
	}else{

  $('#result').load('pages/datos_requisitos.php?IDLICITACION='+licitacion+'&NOMBRE='+filename, function(result) {
    var variable = $('#result').html();
	$("#idexiste").val(variable);
	});

	}
	

});

habilitarPanel();

});

</script>
</head>
<body>
<div id="result" style="display:none;"></div>
<div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">

<div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150"></div>
<div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
<p>IDENTIFICACION</p>
<br />
<p>NOMBRE: <strong><?php echo $row_rs_usuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO: <strong><?php echo $row_rs_usuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rs_usuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rs_usuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div id="falso-salto"></div>
<div id="contenido-panel">

<div id="ver-migadepan">
<input type="hidden" name="idlicita" id="idlicita" value="<?php echo $colname_rsBasesLicitacion; ?>" />
<input type="hidden" name="idexiste" id="idexiste" value="0" />
<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
<div style="width:300px; font-size:11px; float:left; text-align:left;height:20px;">
PANEL &gt; LICITACIONES &gt;
<?php if(in_array($_SESSION['MM_UserGroup'], array("1","2"))){ ?>
<a style="text-decoration:underline;" href="panelpostulaciones.php?ID_LICITACION=<?php echo $idlicitacionx; ?>">VOLVER A <?php echo nombreLicitacion($idlicitacionx);?></a>
<?php }elseif(in_array($_SESSION['MM_UserGroup'], array("3"))){ ?>
<a style="text-decoration:underline;" href="panellicitacionesproveedores.php">VOLVER A PANEL</a>
<?php } ?>

</div>
<?php if(EmpresaPostula($_SESSION['MM_UserID'],$colname_rsListadolix)>0){ ?>
    <div id="requisitos_table" style="width:500px;float:right; height:40px;">
    <span>REQUISITOS: </span>
    <ul>
     <?php 
	 		//echo "->".$totalRows_rsTotalDivisiones;
			if($totalRows_rsTotalDivisiones>0){
			$countx = 1;
			do { ?>
               <li><?php echo $row_rsTotalDivisiones['nombre_requisito']; ?></li>
            <?php 
			$countx++;
			} while ($row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones));
			  $rows = mysql_num_rows($rsTotalDivisiones);
  if($rows > 0) {
      mysql_data_seek($rsTotalDivisiones, 0);
	  $row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones);
  }
			
			 }?>
    
    
    </ul>
    </div>
    <?php } ?>
    </td>
    </tr>
</table>
</div>


<?php 
$today = date("Y-m-d H:i");  
//$today = date("Y-m-d H:i", strtotime('2013-06-11 15:09'));
//echo "hoy es: ".$today." vence -->".$row_rsListarLicitacion['FECHA_TERMINO']." hora: ".$row_rsListarLicitacion['HORA_CST']." consultas: ".$row_rsListarLicitacion['FECHA_CONSULTAS'];
?>
<?php if(EmpresaPostula($_SESSION['MM_UserID'],$colname_rsListadolix)>0){ ?>
<?php if(($row_rsListarLicitacion['ID_ESTADOLICITA']=="1")){ ?>
<?php //if($today<=$row_rsListarLicitacion['FECHA_CONSULTAS']){ ?>
<?php
$date1 = date("m/d/Y",strtotime($row_rsListarLicitacion['FECHA_INICIO']));
$date2 = date("H:i",strtotime($row_rsListarLicitacion['HORA_PST']));
$diasconsultasini = date("Y-m-d H:i", strtotime($date1.' '.$date2));
$date3 = date("m/d/Y",strtotime($row_rsListarLicitacion['FECHA_TERMINO']));
$date4 = date("H:i",strtotime($row_rsListarLicitacion['HORA_CST']));
$diasconsultasfin = date("Y-m-d H:i", strtotime($date3.' '.$date4));
//echo "INICIO: ".$diasconsultasini." TERMINO: ".$diasconsultasfin;
if($today<=$diasconsultasfin && $today>=$diasconsultasini ){ ?>
<?php if(($totalRows_rsBasesLicitacion>0)){ ?>
<?php 

//echo $totalRows_rsTotalRequisitos."_";
//echo $totalRows_rsBasesLicitacion."_";
//echo $totalRows_rsTotalDivisiones."_";

if(($totalRows_rsTotalRequisitos!=0)){ ?>

<div id="ver-archivos">

<form enctype="multipart/form-data" name="frm_uparchivo" id="frm_uparchivo" action="<?php echo $editFormAction; ?>" method="post">

<table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
    <td colspan="6" id="titulo_admin" align="center">ADMINISTRAR ARCHIVOS </td>
  </tr>
  
  <tr>
    <td>NOMBRE</td>
    <td><input type="text" name="nombre" id="nombre" size="30" class="upper" />
   <!-- <select name="nombre" id="nombre" class="s2">
    <option value="0" selected="selected">SELECCIONE</option>
<?php
//do {  
?><option value="<?php //echo $row_rsTotalDivisiones['nombre_requisito']; ?>"><?php //echo $row_rsTotalDivisiones['nombre_requisito']; ?></option>
      <?php
	  /*
} while ($row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones));
  $rows = mysql_num_rows($rsTotalDivisiones);
  if($rows > 0) {
      mysql_data_seek($rsTotalDivisiones, 0);
	  $row_rsTotalDivisiones = mysql_fetch_assoc($rsTotalDivisiones);
  }*/
?>
            
    </select>-->
    
    
    
    
    
      <input name="idpostulacion" type="hidden" id="idpostulacion" value="<?php echo $colname_rsListadoArchivos;?>" />
       <input name="idinserta" type="hidden" id="idinserta" value="0" />
       <input name="totalbase" type="hidden" id="totalbase" value="<?php echo $totalRows_rsBasesLicitacion;?>" />
       <input name="totalpostu" type="hidden" id="totalpostu" value="<?php echo $totalRows_rsListadoArchivos;?>" />
      </td>
    <td>ARCHIVO</td>
    <td><label>
      <input type="file" name="ARCHIVO" id="ARCHIVO" class="upper" />
    </label></td>
    <td>TIPO</td>
    <td><input type="text" name="tiponame" id="tiponame" size="25" class="upper" /><input type="text" name="tipo" id="tipo" size="15" class="upper" /></td>
  </tr>
  
  
  <tr>
    <td colspan="6" align="center">
    <!--<font color="#990000" size="1">EL ADMINISTRADOR HA SUBIDO <STRONG><?php echo $totalRows_rsBasesLicitacion; ?></STRONG> ARCHIVO(S).</font>-->
    <div style="margin-left:300px;"><input type="button" class="ingreso-btn" id="btn_crear" value="Agregar"  alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" /><input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div></td>
  </tr>
  </tbody>
</table>
</form>


</div>

<?php }else{ ?>
<div id="ver-archivos">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">HAZ CUMPLIDO CON LA CUOTA DE ARCHIVOS PARA LA POSTULACION.</FONT></p>
</div>
<?php }  ?>


<?php }else{ ?>
<div id="ver-archivos">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">LICITACION NO TIENE ARCHIVOS CREADOS.</FONT></p>
</div>
<?php }  ?>

<?php }else{ ?>
<div id="ver-archivos">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">TIEMPO CUMPLIDO, FINALIZO EL PERIODO DE POSTULACIONES.</FONT></p>
</div>
<?php }  ?>

<?php }else{ ?>
<div id="ver-archivos">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">LICITACION NO ESTÁ VIGENTE.</FONT></p>
</div>
<?php }  ?>


<?php }else{ ?>
<div id="ver-archivos">
<BR />
<BR />
<p align="center"><img src="img/information_16.png" /><FONT color="#990000" size="+1">UD. NO PARTICIPA DE ESTA LICITACION.</FONT></p>
</div>
<?php }  ?>

<div id='listar-usuarios'>

<div id="mensajes" title="CREADA"></div>

    <table class="flexme2">
          <tbody>
        <?php 
		if($totalRows_rsListadoArchivos>0){
		
		do { ?>
            
            <tr>
            <td><?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?></td>
            <td><?php echo $row_rsListadoArchivos['docu_nombre']; ?></td>
            <td><?php echo $row_rsListadoArchivos['docu_nomarchivo']; ?></td>
            <td><?php 
			$totaltm = round($row_rsListadoArchivos['LARGO']/1024);
			echo ($totaltm >= 1024)?round($totaltm/1024)."MB":$totaltm."KB"; 
			?></td>
            <td><?php echo date("d/m/Y",strtotime($row_rsListadoArchivos['DOCU_FECHA'])); ?></td>
            <td><?php echo $row_rsListadoArchivos['docu_glosa']; ?></td>
            
            <td><a href="pages/archivo_postulacion.php?ID_DOCUMENTACION=<?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?>"><img src="img/download_16.png" /></a></td>
            <td><?php //if($today<=$row_rsListarLicitacion['FECHA_CONSULTAS']){ ?><?php if($today<=$row_rsListarLicitacion['FECHA_TERMINO']){ ?><a href="#" id="<?php echo $row_rsListadoArchivos['ID_DOCUMENTACION']; ?>"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a><?php } ?></td>
                  
		      </tr>
              <?php } while ($row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos)); } ?>
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

mysql_free_result($rsListadoArchivos);

mysql_free_result($rsListarLicitacion);
?>