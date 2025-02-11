<?php


include("../_infra/functions.php");

//print_r($_GET);

/* 
    $points is de array waar alle gevonden adressen inkomen alvorens er geojson van te maken.
    Want anders vallen er adamlink adressen uit verschillende bronnen / jaren precies over elkaar heen
    Bovendien wil je misschien iets met het aantal resultaten per punt
*/
$points = array();


/*
    limieten instellen is wat lastig omdat er allemaal verschillende queries zijn
*/
$limit = 5000;
$bronnen = 0;
if(isset($_GET['marktkaarten'])){
    $bronnen++;
}
if(isset($_GET['joodsmonument'])){
    $bronnen++;
}
if(isset($_GET['beeldbank'])){
    $bronnen++;
}
if(isset($_GET['diamantwerkers'])){
    $bronnen++;
}
$limitperbron = floor($limit/$bronnen);
$limitbereikt = false;


if(isset($_GET['marktkaarten'])){
    include("query-marktkaarten.php");
}

$colprops = array(
    "limited" => $limitbereikt,
    "nrfound" => count($points)
);

$fc = array("type"=>"FeatureCollection", "properties"=>$colprops, "features"=>array());
//$fc = array("type"=>"FeatureCollection", "features"=>array());

foreach ($points as $key => $value) {

    //print_r($value);
    
    $adres = array("type"=>"Feature");

    $wkt = $key;
    $ll = explode(" ",str_replace(array("POINT(",")"),"",$wkt));
    $adres['geometry'] = array(
        "type" => "Point",
        "coordinates" => array((float)$ll[0],(float)$ll[1])
    );
    $props = array(
        "cnt" => $value['cnt'],
        "labels" => $value['labels'],
        "adressen" => $value['adressen']
    );
    $adres['properties'] = $props;
    $fc['features'][] = $adres;
    
}


//echo $i;
//print_r($streetlist);
//die;

$geojson = json_encode($fc);

header('Content-Type: application/json');
echo $geojson;










?>