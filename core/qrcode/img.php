<?php 
// Create image instances 
 $html_code = "
".$_REQUEST["slot"];
$img = imagecreate("500", "100"); 
  $c2 = imagecolorallocate($img,255, 255, 255); 
 //$c2 = imagecolorallocate($img,70,70,70); 
 //imageline($img,0,0,300,600,$c2); 
 //imageline($img,300,0,0,600,$c2);
 $black = imagecolorallocate($img, 0,0,0);
imagettftext($img, 48, 0, 0, 0, $black, "D:/xampp/htdocs/parking_app_admin/core/qrcode/Verdana.ttf", $html_code); 
 
 
//$src = imagecreatefromjpeg('a.jpg');
$errorCorrectionLevel = 'L';
$matrixPointSize = 4;
 include "qrlib.php"; 
 $filename = 'test.png';
        QRcode::png($_REQUEST['token'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);   
$src = imagecreatefrompng( 
'test.png');
  
// Copy and merge 
imagecopymerge($img,$src,  400, 0,0, 0, 100, 200, 100); 
  
// Output and free from memory 
header('Content-Type: image/png');
imagepng($img); 
  
imagedestroy($src); 
imagedestroy($img); 
?> 