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

$colname_rsListadoArchivos = "-1";
if (isset($_GET['ID_LICITACION'])) {
  $colname_rsListadoArchivos = $_GET['ID_LICITACION'];
}else{
die;
}
mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoArchivos = sprintf("SELECT * FROM evaluaciones WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoArchivos, "int"));
$rsListadoArchivos = mysql_query($query_rsListadoArchivos, $cn_licitaciones) or die(mysql_error());
$row_rsListadoArchivos = mysql_fetch_assoc($rsListadoArchivos);
$totalRows_rsListadoArchivos = mysql_num_rows($rsListadoArchivos);


mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoEvalua = sprintf("SELECT * FROM listar_maestro_evalprovee WHERE ID_LICITACION = %s", GetSQLValueString($colname_rsListadoArchivos, "int"));
$rsListadoEvalua = mysql_query($query_rsListadoEvalua, $cn_licitaciones) or die(mysql_error());
$row_rsListadoEvalua = mysql_fetch_assoc($rsListadoEvalua);
$totalRows_rsListadoEvalua = mysql_num_rows($rsListadoEvalua);

//echo $totalRows_rsListadoEvalua." --->".$colname_rsListadoArchivos;

$editFormAction= $_SERVER['PHP_SELF']."?ID_LICITACION=".$colname_rsListadoArchivos;
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


jQuery.fn.msjAccion = function(msg,doit) {
				$("#mensajes").jGrowl(msg, { life: 2000 , sticky: false , easing: 'linear' ,
                beforeClose: function(e, m) {
					if(doit==1){
                    	location.href = "<?php echo $editFormAction; ?>";
					}
                }});
};

function guardarEvaluacion(data){
var data1 = $("#frm_evaluaciones"+data+" #idlicitacion"+data).val();
var dataString = $("#frm_evaluaciones"+data).serialize();

//alert(dataString);
//return false;

$.ajax({
	  type: "GET",
	  dataType: "text",
	  url: "pages/update_evaluacionproveedor.php",
	  data: dataString, 
	  success: function(data){ 
	  		//alert(data);
			if(data == 1){
				$(this).msjAccion('EVALUACION GUARDADA',1);
			}else if(data == 0){
				$(this).msjAccion('NO SE PUDO CREAR',2);
			}
	   },
	  error: function(uno, dos, tres){alert("Error");}
	});
}

function GuardarEval(data){
	guardarEvaluacion(data);
}

function sumarTotal(id){
var totalx = parseFloat($("#vcategoria1_"+id).val()) + parseFloat($("#vcategoria2_"+id).val()) + parseFloat($("#vcategoria3_"+id).val());
$("#vitemtotal"+id).val(totalx)
}

function sumarLinea(data, id, num){
var buffer = 0;
for(k=1;k<5;k++){
	numero1 = $("#"+data+""+k).val();
	numero2 = $("#"+data+""+k+"_"+id).val();
	if((numero1 != "") && (numero2 != "")){
		buffer += ((numero1*numero2)/100);
	}
}
$("#vcategoria"+num+"_"+id).val(buffer);
sumarTotal(id);
}



$(document).ready(function() {

$('.upper').blur(function() {
	var currentId = $(this).attr('id')
	var valor = $("#"+currentId).val();
	var data = currentId.split("_");
	var porcen = $("#"+data[0]).val();
	var linea = data[0].substring(0,6);
	var num = data[0].substring(5,6);
//	vitem1
	sumarLinea(linea, data[1], num);
	//sumarTotal(data[1]);
});



$('#frm_evaluaciones :input').attr('disabled','disabled');

for(k=1;k<5;k++){
	$("#pvitem1"+k).numeric();
	$("#pvitem2"+k).numeric();
	$("#pvitem3"+k).numeric();	
}

<?php if($totalRows_rsListadoEvalua>0){ 
do { ?>
sumarLinea("vitem1", "<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>", "1");
sumarLinea("vitem2", "<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>", "2");
sumarLinea("vitem3", "<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>", "3");
<?php 
	} while ($row_rsListadoEvalua = mysql_fetch_assoc($rsListadoEvalua));
	$rows = mysql_num_rows($rsListadoEvalua);
  if($rows > 0) {
      mysql_data_seek($rsListadoEvalua, 0);
	  $row_rsListadoEvalua = mysql_fetch_assoc($rsListadoEvalua);
  }
	
	
	 } ?>

});


</script>
</head>
<body>
<div id="fondositio-largo"><div id="cuerpo-largo-2">
  <div id="fondositio-largo2">
    <div id="espacio"></div>
    <div id="cuerpo-largo-">
      <div id="cabecera-panel"><img src="img/banner_tiny.jpg" width="550" height="150" /></div>
      <div id="informacion-panel" style="background-image:url(img/banner_data.jpg);">
        <p>IDENTIFICACION</p>
        <span><a href="#" onclick="abandonar();">CERRAR SESION</a></span>
        <p>NOMBRE:<strong><?php echo $row_rsUsuario['NOMBRE']; ?></strong></p>
        <p>TIPO DE USUARIO:<strong><?php echo $row_rsUsuario['PERFIL']; ?></strong></p>
        <p>EMPRESA:<strong><?php echo $row_rsUsuario['EMPRESA']; ?></strong></p>
        <p>DIVISION: <strong> <?php echo $row_rsUsuario['NOMDIVISION']; ?></strong></p>
      </div>
      <div id="contexto-largo-2">
        <div id="barra">
          <?php include("pages/menu.php"); ?>
        </div>
        <div class="limpiar" ></div>
        <div id="falso-salto"></div>
        <div id="contenido-panel-largo-2">
          <div id="ver-migadepan">
            <table width="100%" height="1%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><div style="width:600px; font-size:11px; float:left; text-align:left;height:20px;"> PANEL &gt; LICITACIONES &gt;<a style="text-decoration:underline;" href="panellicitaciones.php">VOLVER A <?php echo nombreLicitacion($colname_rsListadoArchivos);?></a> </div></td>
              </tr>
            </table>
          </div>
          <div id="campos_scroll">
          <div id="campos-slide">
          <div id="campoformulario-provee" style="width:300px;">
            <form id="frm_evaluaciones" name="frm_evaluaciones">
              <br />
              <p class="titulo-texto">EVALUACIONES PARA PROVEEDOR</p>
              <br />
              <br />
              <input type="hidden" name="idlicitacion" id="idlicitacion" value="<?php echo $colname_rsListadoArchivos; ?>" />
              <ul class="ordenterminados">
                   <li style="background-color:#8BAB8D;">CATEG. 1
                  <input type="text" class="xtext_data" size="30" title="INGRESA UNA CATEGORIA" name="categoria1" id="categoria1" value="<?php echo $row_rsListadoArchivos['CATEGORIA1']; ?>" />
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vcategoria1" id="vcategoria1" value="<?php echo $row_rsListadoArchivos['VCATEGORIA1']; ?>" maxlength="3" />
                  %</li>
              
                <li>ITEM 1.1 <?php echo $row_rsListadoArchivos['ITEM11']; ?>
                  <input type="text" size="2" class="xtext_data" title="INGRESA UNA VALOR" name="vitem11" id="vitem11" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM11']; ?>" />
                  %</li>
                <li>ITEM 1.2 <?php echo $row_rsListadoArchivos['ITEM12']; ?>
                  <input type="text" size="2" class="xtext_data" title="INGRESA UNA VALOR" name="vitem12" id="vitem12" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM12']; ?>" />
                  % </li>
                <li>ITEM 1.3 <?php echo $row_rsListadoArchivos['ITEM13']; ?>
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem13" id="vitem13" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM13']; ?>" />
                  % </li>
                <li>ITEM 1.4 <?php echo $row_rsListadoArchivos['ITEM14']; ?>
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem14" id="vitem14" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM14']; ?>" />
                  % </li>
                <li>ITEM 1.5 <?php echo $row_rsListadoArchivos['ITEM15']; ?>
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem15" id="vitem15" maxlength="3" value="<?php echo $row_rsListadoArchivos['VITEM15']; ?>" />
                  % </li>
              </ul>
              <br />
              <br />
               <ul class="ordenterminados">
                   <li style="background-color:#8BAB8D;">CATEG. 2
                  <input type="text" class="xtext_data" size="30" title="INGRESA UNA CATEGORIA" name="categoria2" id="categoria2" value="<?php echo $row_rsListadoArchivos['CATEGORIA2']; ?>" />
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vcategoria2" id="vcategoria2" value="<?php echo $row_rsListadoArchivos['VCATEGORIA2']; ?>" />
                  %</li>
              
                <li>ITEM 2.1 <?php echo $row_rsListadoArchivos['ITEM21']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem21" id="vitem21" value="<?php echo $row_rsListadoArchivos['VITEM21']; ?>" />
                  % </li>
                <li>ITEM 2.2 <?php echo $row_rsListadoArchivos['ITEM22']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem22" id="vitem22" value="<?php echo $row_rsListadoArchivos['VITEM22']; ?>" />
                  % </li>
                <li>ITEM 2.3 <?php echo $row_rsListadoArchivos['ITEM23']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem23" id="vitem23" value="<?php echo $row_rsListadoArchivos['VITEM23']; ?>" />
                  % </li>
                <li>ITEM 2.4 <?php echo $row_rsListadoArchivos['ITEM24']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem24" id="vitem24" value="<?php echo $row_rsListadoArchivos['VITEM24']; ?>" />
                  % </li>
                <li>ITEM 2.5 <?php echo $row_rsListadoArchivos['ITEM25']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem25" id="vitem25" value="<?php echo $row_rsListadoArchivos['VITEM25']; ?>" />
                  % </li>
              </ul>
              <br />
              <br />
              <ul class="ordenterminados">

                   <li style="background-color:#8BAB8D;">CATEG. 3
                  <input type="text" class="xtext_data" size="30" title="INGRESA UNA CATEGORIA" name="categoria3" id="categoria3" value="<?php echo $row_rsListadoArchivos['CATEGORIA3']; ?>" />
                  <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vcategoria3" id="vcategoria3" value="<?php echo $row_rsListadoArchivos['VCATEGORIA3']; ?>" />
                  %</li>
              
              
                <li>ITEM 3.1 <?php echo $row_rsListadoArchivos['ITEM31']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem31" id="vitem31" value="<?php echo $row_rsListadoArchivos['VITEM31']; ?>" />
                  % </li>
                <li>ITEM 3.2 <?php echo $row_rsListadoArchivos['ITEM32']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem32" id="vitem32" value="<?php echo $row_rsListadoArchivos['VITEM32']; ?>" />
                  % </li>
                <li>ITEM 3.3 <?php echo $row_rsListadoArchivos['ITEM33']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem33" id="vitem33" value="<?php echo $row_rsListadoArchivos['VITEM33']; ?>" />
                  % </li>
                <li>ITEM 3.4 <?php echo $row_rsListadoArchivos['ITEM34']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem34" id="vitem34" value="<?php echo $row_rsListadoArchivos['VITEM34']; ?>" />
                  % </li>
                <li>ITEM 3.5 <?php echo $row_rsListadoArchivos['ITEM35']; ?>
                    <input type="text" class="xtext_data" size="2" title="INGRESA UNA VALOR" name="vitem35" id="vitem35" value="<?php echo $row_rsListadoArchivos['VITEM35']; ?>" />
                  % </li>
              </ul>
              <br />
              <br />
              </form>
          </div>
          <?php if($totalRows_rsListadoEvalua>0){ 
		  do { ?>
		  <div id="campoformulario-provee-item">
          <form id="frm_evaluaciones<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" name="frm_evaluaciones">
              <br />
              <p class="titulo-texto-eval"><?php echo $row_rsListadoEvalua["USUPROVEEDOR"];?></p>
              <br />
              <br />
              <input type="hidden" name="idlicitacion" id="idlicitacion<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $colname_rsListadoArchivos; ?>" />
              <input type="hidden" name="ideval" id="ideval<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" />
              <input type="hidden" name="idempresa" id="idempresa<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $row_rsListadoEvalua["ID_EMPRESA"];?>" />
              <ul>
                   <li style="background-color:#AFC2AA;">
                  <input type="text" readonly="readonly" class="upper" size="2" title="TOTAL CATEGORIA" style="background-color:#c3c3c3;" name="vcategoria1" id="vcategoria1_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $row_rsListadoEvalua['PVCATEGORIA1']; ?>" maxlength="3" />
                  </li>
              
                <li>
                      <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem11" id="vitem11_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM11']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem12" id="vitem12_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM12']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem13" id="vitem13_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM13']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem14" id="vitem14_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM14']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem15" id="vitem15_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM15']; ?>" />
                  </li>
              </ul>
              <br />
              <br />
                <ul>
                   <li style="background-color:#AFC2AA;">
                  <input type="text" class="upper" readonly="readonly" size="2" title="TOTAL CATEGORIA" style="background-color:#c3c3c3;" name="vcategoria2" id="vcategoria2_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $row_rsListadoEvalua['PVCATEGORIA2']; ?>" maxlength="3" />
                  </li>
              
                <li>
                      <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem21" id="vitem21_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM21']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem22" id="vitem22_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM22']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem23" id="vitem23_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM23']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem24" id="vitem24_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM24']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem25" id="vitem25_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM25']; ?>" />
                  </li>
              </ul>
              <br />
              <br />
              <ul>
                   <li style="background-color:#AFC2AA;">
                  <input type="text" class="upper" readonly="readonly" size="2" title="TOTAL CATEGORIA" style="background-color:#c3c3c3;" name="vcategoria3" id="vcategoria3_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" value="<?php echo $row_rsListadoEvalua['PVCATEGORIA3']; ?>" maxlength="3" />
                  </li>
              
                <li>
                      <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem31" id="vitem31_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM31']; ?>" />
                  </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem32" id="vitem32_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM32']; ?>" />
                   </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem33" id="vitem33_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM33']; ?>" />
                   </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem34" id="vitem34_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM34']; ?>" />
                   </li>
                <li>
                           <input type="text" class="upper" size="2" title="INGRESA UNA VALOR" name="vitem35" id="vitem35_<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" maxlength="3" value="<?php echo $row_rsListadoEvalua['PVITEM35']; ?>" />
                   </li>
              </ul>
              <br />
 		<ul>
		<li>
                           <input type="text" class="upper" size="2" title="NOTA" name="vitemtotal" id="vitemtotal<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>" style="background-color:#FFFF66;" maxlength="3" value="" />
                   </li>
              </ul>
              <br />
              <div style="margin-left:5px;">
                <div id="enviardata"></div>
                <input type="button" class="ingreso-btn" onclick="GuardarEval('<?php echo $row_rsListadoEvalua["ID_EVALUACIONESPROV"];?>');"  value="Guardar" alert="" />
                <br /><br />
              </div>
          </div>
          </form>
          </div>
            <?php 
			} while ($row_rsListadoEvalua = mysql_fetch_assoc($rsListadoEvalua)); } ?>
          <!--- otro -->
           
          
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
</div>
</div>
</body>
  <script type='text/javascript'>
    $(function() {
      //$('#campoformulario [title]').tipsy({trigger: 'focus', gravity: 'w'});
    });
  </script>
</html>
<?php
mysql_free_result($rsListadoArchivos);
mysql_free_result($rsUsuario);
?>
