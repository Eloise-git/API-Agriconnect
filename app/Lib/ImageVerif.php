<?php
namespace App\Lib;
use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';

function verificationImage($imageverif){
    $imageType = $imageverif->getClientMediaType();
    $fileExtension = explode('/',$imageType)[1];
    $Extensionautoriser = ['jpg','jpeg','png'];
    if(!in_array($fileExtension,[''])){
        throw new Exception("Il n'y a pas d'image", 400);
    }

    if(!in_array($fileExtension,$Extensionautoriser)){
        throw new Exception("Extension non autoriser", 400);
    }
}


function uploadImage($image, $path)
{
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    $imageType = $image->getClientMediaType();
    $fileExtension = explode('/',$imageType)[1];
    $imageName = uniqid() . '.' . $fileExtension;
    $imagePath = $path .'/'. $imageName;
    $image->moveTo($imagePath);
    return $imageName;
}

?>
