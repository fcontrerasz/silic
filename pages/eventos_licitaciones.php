<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php require_once('../pages/library.php'); ?>
<?php
	if (!isset($_SESSION)) {
	  session_start();
	}
	
	if($_SESSION['MM_UserGroup'] == "2"){	
	$empresacrt = "-1";
	if (isset($_SESSION['MM_Empresa'])) {
	$query_rsLicitacionesCal = "select ID_LICITACION,COLOR,PROVEEDOR, CODIGOLICI,NOMBRELICI,FECHA_INICIO,FECHA_TERMINO from listar_maestro_eventos where ID_EMPRESACREADORA = ".$_SESSION['MM_Empresa'];
	}
	}elseif($_SESSION['MM_UserGroup'] == "1"){
	$query_rsLicitacionesCal = "select ID_LICITACION,COLOR,PROVEEDOR, CODIGOLICI,NOMBRELICI,FECHA_INICIO,FECHA_TERMINO from listar_maestro_eventos";
	}
	
	
	mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
	
	$rsLicitacionesCal = mysql_query($query_rsLicitacionesCal, $cn_licitaciones) or die(mysql_error());
	$row_rsLicitacionesCal = mysql_fetch_assoc($rsLicitacionesCal);
	$totalRows_rsLicitacionesCal = mysql_num_rows($rsLicitacionesCal);
	
	$year = date('Y');
	$month = date('m');
	
	$datos=array();
	$licitaciones=array();
	
	
	do {  
	
	$mid = $row_rsLicitacionesCal["ID_LICITACION"];
	$color = $row_rsLicitacionesCal["COLOR"];
	$nombre = $row_rsLicitacionesCal["CODIGOLICI"]."_".$row_rsLicitacionesCal["NOMBRELICI"]." (".$row_rsLicitacionesCal["PROVEEDOR"].")";
	$datetime = strtotime($row_rsLicitacionesCal["FECHA_INICIO"]);
	$desde = date("Y-m-d",$datetime);
	$datetime = strtotime($row_rsLicitacionesCal["FECHA_TERMINO"]);
	$hasta = date("Y-m-d",$datetime);
	$datos[]= array('id' => $mid,
			'title' => $nombre,
			'start' => $desde,
			'end' => $hasta,
			'borderColor' => '#000',
			'backgroundColor' => $color);
	} while ($row_rsLicitacionesCal = mysql_fetch_assoc($rsLicitacionesCal));
	$rows = mysql_num_rows($rsLicitacionesCal);
	if($rows > 0) {
	  mysql_data_seek($rsLicitacionesCal, 0);
	  $row_rsLicitacionesCal = mysql_fetch_assoc($rsLicitacionesCal);
	}
	
	
	echo json_encode($datos);
	
	//var_dump($datos);

?>
