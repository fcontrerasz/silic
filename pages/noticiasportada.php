<?php require_once('Connections/cn_licitaciones.php'); ?>
<?php require_once('pages/library.php'); ?>
<?PHP

if (!isset($_SESSION)) {
  session_start();
}

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsBasesLicitacion = "SELECT * FROM noticias ORDER BY noticia_pos ASC";
$rsBasesLicitacion = mysql_query($query_rsBasesLicitacion, $cn_licitaciones) or die(mysql_error());
$row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion);
$totalRows_rsBasesLicitacion = mysql_num_rows($rsBasesLicitacion);

?>
       
         


<div id="news-container">
<?php 
		if($totalRows_rsBasesLicitacion>0){
		
		do { ?>
            
	<ul id="listticker">
    
	<li>
   		<a href="detalle.php?news=<?php echo $row_rsBasesLicitacion["id_noticia"]; ?>">
		<img src="img/6.png" width="40px" height="40px" />
		<span class="news-title"><?php echo $row_rsBasesLicitacion["noticia_titulo"]; ?></span>
		<span class="news-text"><?php echo substr( $row_rsBasesLicitacion["noticia_texto"], 0, 250 )."...<em>(ver mas)</em>"; ?></span>
        </a>
	</li>
    
    <?php } while ($row_rsBasesLicitacion = mysql_fetch_assoc($rsBasesLicitacion)); } ?>
</ul>
</div>