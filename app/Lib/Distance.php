<?php
namespace App\Lib;

class Exception extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

function getDistance($adresse1, $adresse2)
{
    $apiKey = $_ENV['GOOGLE_API_TOKEN'];
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations='.$adresse1.'&language=fr-FR&mode=car&origins='.$adresse2.'&key='.$apiKey;
    
    $data = json_decode(file_get_contents($url), true);

    $distance = $data['rows'][0]['elements'][0]['distance']['value'];

    if (isset($data['rows'][0]['elements'][0]['distance']['text'])) {
        $distance = $data['rows'][0]['elements'][0]['distance']['value'];
        return $distance;

    }

}

function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2) {
    $earthRadius = 6371;

    $deltaLatitude = deg2rad($latitude2 - $latitude1);
    $deltaLongitude = deg2rad($longitude2 - $longitude1);

    $a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) +
         cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) *
         sin($deltaLongitude / 2) * sin($deltaLongitude / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c;

    return round($distance, 2);
}
?>