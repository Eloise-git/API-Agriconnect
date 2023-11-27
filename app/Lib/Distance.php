<?php
namespace App\Lib;

function getDistance($adresse1, $adresse2)
{
    $adresse1 = str_replace(" ", "+", $adresse1);
    $adresse2 = str_replace(" ", "+", $adresse2);
    $apiKey = $_ENV['GOOGLE_API_TOKEN'];
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations='.$adresse1.'&language=fr-FR&mode=car&origins='.$adresse2.'&key='.$apiKey;
    
    $data = json_decode(file_get_contents($url), true);

    var_dump($url);
    $distance = $data['rows'][0]['elements'][0]['distance']['value'];

    if (isset($data['rows'][0]['elements'][0]['distance']['text'])) {
        $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        return $distance;

    }

}
?>