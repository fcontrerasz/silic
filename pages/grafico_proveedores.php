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
  echo "---> ".$d;
  if ($d > 0) return 0;
  if ($d < 0) return 1;
  return 2;
}

$filtroempre = "IN (".licitacionesProveedores($_SESSION['MM_UserID'],$_SESSION['MM_Empresa']).") ";

mysql_select_db($database_cn_licitaciones, $cn_licitaciones);
$query_rsListadoLicitaciones = "SELECT 
  COUNT(listar_maestro_licitaciones.ID_LICITACION) AS TOTAL,
  estado_licitacion.ESTADO_LICITA
FROM
  estado_licitacion
  INNER JOIN listar_maestro_licitaciones ON (estado_licitacion.ID_ESTADOLICITA = listar_maestro_licitaciones.ID_ESTADOLICITA)
WHERE
  listar_maestro_licitaciones.ID_LICITACION ".$filtroempre."
GROUP BY
  estado_licitacion.ESTADO_LICITA";

//echo "<p>".$query_rsListadoLicitaciones."</p>";
  
$rsListadoLicitaciones = mysql_query($query_rsListadoLicitaciones, $cn_licitaciones) or die(mysql_error());
$row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones);
$totalRows_rsListadoLicitaciones = mysql_num_rows($rsListadoLicitaciones);






$data = array();
$cont = 0;
do {
	$vendedor = $row_rsListadoLicitaciones["ESTADO_LICITA"];
	$total = $row_rsListadoLicitaciones["TOTAL"];
	$data[$cont] = array($vendedor,$total);
	$cont++;
} while ($row_rsListadoLicitaciones = mysql_fetch_assoc($rsListadoLicitaciones));

//var_dump($data);

$plot = new PHPlot(250,250);
$plot->SetPlotType('pie');
$plot->SetTitle("ESTADOS LICITACIONES");
//$plot->SetImageBorderType('none');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($data);
//$plot->SetPieLabelType('percent', 'data', 1, '', '%');
$plot->SetDefaultTTFont('verdana.ttf');
$plot->SetUseTTF(True);
$plot->SetFontTTF('y_label', 'verdana.ttf', '7');
$plot->SetFontTTF('x_label', 'verdana.ttf', '7');
$plot->SetFontTTF('title', 'verdana.ttf', '8');
$colors = array('#63A69F', '#F2E1AC', '#C2644F');
$plot->SetDataColors($colors);
$plot->SetGridColor('#302821');
//$plot->SetLegend($dataLab);
$plot->SetLegendPosition(0, 1, 'image', 0, 1, 7, -7);
$plot->SetPieAutoSize(False);
if($totalRows_rsListadoLicitaciones>0){
foreach ($data as $row)
$plot->SetLegend(implode(': ', $row));
}
$plot->SetShading(0);
$plot->SetLabelScalePosition(0.2);

$plot->DrawGraph();
?>