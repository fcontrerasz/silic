<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php require_once('pages/acceso_admin.php'); ?>
<?PHP

if (!isset($_SESSION)) {
  session_start();
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

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = "SELECT * FROM noticias ORDER BY noticia_pos ASC";
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Panel Principal</title>
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
<script src="js/jquery.limit-1.2.source.js" type="text/javascript"></script>

<script>

function HabilitarGrilla(){


$('.flexme5').flexigrid({
colModel : [
                        {display: 'N', name : 'iidiv', width : 20, sortable : true, align: 'left'},
						{display: 'Noticia', name : 'nomdiv', width : 180, sortable : true, align: 'left'},
						{display: 'Texto', name : 'texto', width : 580, sortable : true, align: 'left'},
						{display: ' ', name : 'eliminar', width : 20, sortable : true, align: 'left'}
            ],
height:'auto',
sortname: "",
sortorder: "asc",
resizable: false,
width: '100%',
height: 340,
singleSelect: true
});

}

function BorrarNoticia(idnoticia){
var dataString = "IDNOTICIA="+idnoticia;

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/delete_noticia.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('NOTICIA ELIMINADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO ELIMINAR LA NOTICIA');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function crearNoticia(){
var data1 = $("#titulo").val();
var data2 = $("#texto").val(); 

if (data1 == "") {
	$('#titulo').attr('alert','FALTA TITULO');
	$('#titulo').tipsy({title: 'alert',gravity: 'w'});
	$('#titulo').tipsy("show");
	$("#titulo").focus();
	return false;
}

if (data2 == "") {
	$('#texto').attr('alert','FALTA TEXTO');
	$('#texto').tipsy({title: 'alert',gravity: 'w'});
	$('#texto').tipsy("show");
	$("#texto").focus();
	return false;
}

var dataString = $("#form_notici").serialize();

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/insert_noticia.php",
	  data: dataString, 
	  success: function(data){ 
			if(data == 1){
				$(this).msjAccion('NOTICIA CREADA');
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR LA NOTICIA');
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});

}

jQuery.fn.msjAccion = function(msg) {
				$("#mensajes").jGrowl(msg, { life: 500 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
                    location.reload();
                }});
};

$(document).ready(function() {

$('.upper').bestupper();
$('#texto').limit('330');

$("#btn_crear").click(function(){
	crearNoticia();
});

HabilitarGrilla();

});
</script>
<style>
.noticia_item{
border:1PX solid #CCCCCC;
float:left;
width:650PX;
margin-bottom:10PX;

}
.dere_item{
float:right;
width:15px;

}
</style>
  
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
      <div id="barra">
        <?php include("pages/menu.php"); ?>
      </div>
      <div class="limpiar"></div>
      <div id="falso-salto"></div>
      <div id="contenido-panel-largo">
        <div id="ver-usuarios">
          <form id="form_notici" name="form_notici" >
            <table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="4" id="titulo_admin" align="center">ADMINISTRAR NOTICIAS</td>
              </tr>
              <tr>
                <td>TITULO</td>
                <td width="30%"><input type="text" name="titulo" id="titulo" size="30" class="upper" />
                </td>
                <td>TEXTO</td>
                <td><textarea name="texto" id="texto" cols="80" rows="5" class="upper"></textarea></td>
              </tr>
              <tr>
                <td colspan="4" align="center"><div style="margin-left:300px;"><input type="button" class="ingreso-btn" id="btn_crear" value="Agregar"  alert="" /></div></td>
              </tr>
            </table>
          </form>
          </div>
          <div id='listar-usuarios'>
          <table class="flexme5" width="100%" >
            <tbody>
             <?php 
		if($totalRows_rsBasesLicitacion>0){
		
		do { ?>
              <tr>
                <td><?php echo $row_rsBasesLicitacion["id_noticia"]; ?></td>
                <td><?php echo $row_rsBasesLicitacion["noticia_titulo"]; ?></td>
                <td><?php echo substr($row_rsBasesLicitacion["noticia_texto"],0,120)."..."; ?></td>
                <td><a href="#" onclick="BorrarNoticia(<?php echo $row_rsBasesLicitacion["id_noticia"]; ?>);"><img src="img/delete.png" class="deleteme" title="Eliminar" /></a></td>
              </tr>
             <?php } while ($row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion)); } ?>
            </tbody>
          </table>
          </DIV>
    </td>
  </tr>
</table>
          
       
        
        </div>
        <div id="mensajes" title="CREADA"></div>
        <div id="espacio"></div>
      </div>
    </div>
  </div>
  <div id="espacio">
    <center>
      DERECHOS RESERVADOS 2012
    </center>
  </div>
</div>
</body>
</html>
