<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script></script>
</head>
<body>
<div id="espacio"></div>
<div id="cuerpo">
<div id="cabecera-panel"><p>LOGO O BANNER</p></div>
<div id="informacion-panel">
<p>IDENTIFICACION</p>
<span><a href="#" onclick="abandonar();">CERRAR SESION</a></span>
<p>NOMBRE:<strong><?php echo $row_rs_usuario['NOMBRE']; ?></strong></p>
<p>TIPO DE USUARIO:<strong><?php echo $row_rs_usuario['PERFIL']; ?></strong></p>
<p>EMPRESA: <strong> <?php echo $row_rs_usuario['EMPRESA']; ?></strong></p>
<p>DIVISION: <strong> <?php echo $row_rs_usuario['NOMDIVISION']; ?></strong></p>
</div>
<div id="contexto">
<div id="barra"><?php include("pages/menu.php"); ?></div>
<div class="limpiar" ></div>
<div style="height:40px; background-color:#7FA6A1;" ></div>
<div id="contenido-panel">
<div id="ver-usuarios">

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
    <td>&nbsp;</td>
    <td>COMUNA</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>PROVINCIA</td>
    <td><label></label>
      <select name="provincias" id="provincias" class="s2" alert="">
      <option value="0">-- SELECCIONE --</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsProvincias['ID_PROVINCIA']?>"><?php echo strtoupper($row_rsProvincias['CR_PROVINCIA'])?></option>
        <?php
} while ($row_rsProvincias = mysql_fetch_assoc($rsProvincias));
  $rows = mysql_num_rows($rsProvincias);
  if($rows > 0) {
      mysql_data_seek($rsProvincias, 0);
	  $row_rsProvincias = mysql_fetch_assoc($rsProvincias);
  }
?>
            </select>
      <input name="idempresa" type="hidden" id="idempresa" value="0" /></td>
    <td>RUBRO</td>
    <td>&nbsp;</td>
    <td>ESTADO</td>
    <td><label></label></td>
  </tr>
  
  <tr>
    <td colspan="6" align="center"><div style="margin-left:300px;"><input type="button" class="ingreso-btn" id="btn_crear" value="Crear" alert="" /><input type="button" class="ingreso-btn" id="btn_guardar" value="Guardar" alert="" /><input type="button" class="ingreso-btn" id="btn_cancela" value="Cancelar" alert="" /></div></td>
  </tr>
  </tbody>
</table>
</form>


</div>

<div id='listar-usuarios'>

<div id="mensajes" title="CREADA"></div>

    <table class="listadousuarios">
          <tbody>
      	   <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
           </tr>
        </tbody>
      </table>
      
   </div>


</div>



</div>
</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</body>
</html>