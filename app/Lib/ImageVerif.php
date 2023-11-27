<?php
namespace App\Lib;
use Exception;

function verificationImage($imageverif){
    $imageType = $imageverif->getClientMediaType();
    $fileExtension = explode('/',$imageType)[1];
    $Extensionautoriser = ['jpg','jpeg','png'];

    if(!in_array($fileExtension,$Extensionautoriser)){
        throw new Exception("Le format de l'image n'est pas valide", 400);
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
