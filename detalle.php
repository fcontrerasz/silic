<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$colname_rs_new = "0";
if (isset($_GET['news'])) {
 $colname_rs_new = $_GET['news'];
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = "SELECT * FROM noticias where id_noticia=".$colname_rs_new." ORDER BY noticia_pos ASC";
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OTIC</title>
<link type="text/css" href="css/reset.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<script src="js/jquery-1.4.2.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/jquery.vticker-min.js"></script>
<script src="js/jquery.numeric.js" type="text/javascript" ></script>
<script src="js/jquery.bestupper.min.js" type="text/javascript" ></script>
<script>

$(document).ready(function() {


});




</script>
</head>
<body><div id="fondositio">
<div id="espacio"></div>
<div id="cuerpo">

<div id="cabecera-portada"><img src="img/banner_9.jpg" /></div>
<div id="contexto-portada">
<div id="informacion-portada-detalle">
<div id="noticias-portada-detalle">
<p class="titulo-texto">NOTICIAS</p>
<BR />
<a href="" class="d-news-title"><?php echo $row_rsBasesLicitacion["noticia_titulo"]; ?></a>
<span class="d-news-text"><?php echo $row_rsBasesLicitacion["noticia_texto"]; ?></span>
<br />
<br />
</div>
<p class="titulo-texto"><a href="inicio.php">VOLVER A LA PORTADA</a></p>
</div>

</div>
</div>
<div id="espacio"><center>DERECHOS RESERVADOS 2012</center></div>
</div></body>
</html>