<?php
session_start();
if(!isset($_SESSION["captcha"]))
{
    $captcha = rand(1000, 9999);
    $_SESSION["captcha"] = $captcha; 
}

$captcha = $_SESSION["captcha"];
$im = imagecreatetruecolor(50, 24); 

$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);

imagefill($im, 0, 0, $bg); 
imagestring($im, rand(1, 7), rand(1, 7),rand(1, 7), $captcha, $fg);

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-type: image/png');

imagepng($im); 
imagedestroy($im);
?>
