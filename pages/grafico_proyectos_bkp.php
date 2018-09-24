<?php require_once('../Connections/cn_licitaciones.php'); ?>
<?php require_once('library.php'); ?>
<?php
require '../library/phplot-5.8.0/phplot.php';

if (!isset($_SESSION)) {
  session_start();
}

function pickcolor($img, $data_array, $row, $col)
{
  $d = $data_array[$row][$col+1]; 
  $f = $data_array[$row][$col+2]; 
  
  //echo "------------>".$f."}}}}";
  
  if ($f == "VIGENTE") return 0;
  if ($d == "PENDIENTE") return 1;
  return 2;
}

$filtroempre = "ID_LICITACION IN (".misLicitacionesProveedores($_SESSION['MM_UserID']).")";

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
//(FECHA_INICIO >=  DATE_FORMAT(NOW(), '%d-%m-%Y'))
$query_rsListadoLicitaciones = "SELECT * FROM listar_maestro_licitaciones WHERE ".$filtroempre."  ORDER BY CODIGOLICI ASC";

//echo $query_rsListadoLicitaciones."<br>";

$rsListadoLicitaciones = mysql_query($query_rsListadoLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones);
$totalRows_rsListadoLicitaciones = mysql_num_rows($rsListadoLicitaciones);



$data = array();
$cont = 0;

do {
	$vendedor = $row_rsListadoLicitaciones["NOMBRELICI"]."(".$row_rsListadoLicitaciones["CODIGOLICI"].")";
	$total = $row_rsListadoLicitaciones["DIASPARTIDA"];
	$ntotal = ($total>=0)?$total:$total*-1;
	$data[$cont] = array($vendedor,$ntotal,$row_rsListadoLicitaciones["ESTADO_LICITA"]);
	$cont++;
} while ($row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones));

//var_dump($data);

/*
$data2 = array(
  array('PROYECTO 1', 20.11),
  array('PROYECTO 2', 7.5),
  array('PROYECTO 3', 8.3),
  array('PROYECTO 4', 49.7),
);*/

$alto = 380;

if($totalRows_rsListadoLicitaciones<5){
$alto = 180;
}

$plot = new PHPlot(570, $alto);
$plot->SetImageBorderType('none'); // Improves presentation in the manual
$plot->SetTitle("DIAS PARA EL INICIO DE MIS LICITACIONES");
$plot->SetDefaultTTFont('verdana.ttf');
$plot->SetUseTTF(True);
$plot->SetFontTTF('y_label', 'verdana.ttf', '8');
$plot->SetFontTTF('x_label', 'verdana.ttf', '8');
$plot->SetFontTTF('title', 'verdana.ttf', '8');
$plot->SetBackgroundColor('white');
$plot->SetDataBorderColors('white');
$plot->SetPlotAreaWorld(0);
$plot->SetYTickPos('none');
//$plot->SetXTickPos('none');
$plot->SetXTickLabelPos('none');
$plot->SetXDataLabelPos('plotin');
$plot->SetDrawXGrid(FALSE);
//$plot->SetDataColors('salmon');
$plot->SetShading(1);
$plot->SetDataValues($data);
$plot->SetDataType('text-data-yx');
$plot->SetPlotBorderType('none');
$plot->SetXTickPos('none');  
$plot->SetDrawXAxis(False);
$plot->SetDrawYAxis(False);
$plot->SetCallback('data_color', 'pickcolor', $data);
$plot->SetDataColors(array('#999044', '#FFCA3D', '#8C3A40'));
$plot->SetPlotType('bars');
//$plot->SetYTickPos('none');
//$plot->SetDefaultTTFont('arialbi.ttf')

$plot->DrawGraph();
?>