<?php 
require_once("../../vendor/autoload.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require "../../config.php";

    $files = array_filter($_FILES['upload']['name']); //something like that to be used before processing files.

    // Count # of uploaded files in array
    $total = count($_FILES['upload']['name']);
    $x = 0;

    // Loop through each file
    foreach( $_FILES['upload']['tmp_name'] as $file ){
        $path="../../properties/".$_POST['unique']."/";
        $new_name_image = 'img_' . $x . ".jpg";
        $quality = 60;
        $pngQuality = 9;

        $image_compress = new Eihror\Compress\Compress($file, $new_name_image, $quality, $pngQuality, $path);
        $image_compress->compress_image();
        $x++;
    }

    header("Location: ../controlpanel.php?addproperty=success");
    exit();
} else {
    header("Location: ../../index.php");
    exit();
}

