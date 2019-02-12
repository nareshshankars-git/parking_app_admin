<?php    

    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
      

    $matrixPointSize = 4;


    if (isset($_REQUEST['data'])) { 
    
        
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } 
 $input_text = "RAJA";
    $width = 600;
    $height = 300;
    
    $textImage = imagecreate($width, $height);
    $color = imagecolorallocate($textImage, 0, 0, 0);
    imagecolortransparent($textImage, $color);
    imagettftext($textImage, 48, 0, 1, 1, $color, "D:/xampp/htdocs/rd/html_to_img/Verdana.ttf", $input_text);
 
    // create background image layer
    $background = imagecreatefrompng ($PNG_WEB_DIR.basename($filename));
    
    // Merge background image and text image layers
    imagecopymerge($textImage, $background, 200, 200, 400, 400, 100, 100, 100);
    
    
    $output = imagecreatetruecolor($width, $height);
    imagecopy($output, $background, 0, 0, 20, 13, $width, $height);
    
    
    ob_start();
    imagepng($output);
    printf('<img id="output" src="data:image/png;base64,%s" />', base64_encode(ob_get_clean()));	