<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

if(!isset($usingSQL)){
        include dirname(__FILE__).'/countryArray.inc.php';
        if($qtype && $query){
                $query = strtolower(trim($query));
                foreach($rows AS $key => $row){
                        if(strpos(strtolower($row[$qtype]),$query) === false){
                                unset($rows[$key]);
                        }
                }
        }
        //Make PHP handle the sorting
        $sortArray = array();
        foreach($rows AS $key => $row){
                $sortArray[$key] = $row[$sortname];
        }
        $sortMethod = SORT_ASC;
        if($sortorder == 'desc'){
                $sortMethod = SORT_DESC;
        }
        array_multisort($sortArray, $sortMethod, $rows);
        $total = count($rows);
        $rows = array_slice($rows,($page-1)*$rp,$rp);
}

header("Content-type: text/xml");
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$total</total>";
foreach($rows AS $row){
        $xml .= "<row id='".$row['iso']."'>";
        $xml .= "<cell><![CDATA[".$row['iso']."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
        //$xml .= "<cell><![CDATA[".print_r($_POST,true)."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['printable_name'])."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['iso3'])."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['numcode'])."]]></cell>";
        $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;

?>