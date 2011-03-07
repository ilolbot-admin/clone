<?php
session_start();
class CaptchaSecurityImages {
   var $font = 'C:\Windows\Fonts\ARIALN.TTF';
   function generateCode($characters) {
      $possible = '1234567890';
      $code = '';
      $i = 0;
      while ($i < $characters) { 
         $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
         $i++;
      }
      return $code;
   }
   function CaptchaSecurityImages($width='120',$height='40',$characters='4') {
      $code = $this->generateCode($characters);
      $font_size = $height * 0.875;
      $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
      $background_color = imagecolorallocate($image, 51, 51, 51);
      $text_color = imagecolorallocate($image, 187, 187, 187);
      $noise_color = imagecolorallocate($image, 187, 187, 187);

      for( $i=0; $i<($width*$height)/5; $i++ ) {
         imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
      }
      for( $i=0; $i<($width*$height)/160; $i++ ) {
         imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
      }
	  
      $textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
      $x = ($width - $textbox[4])/2;
      $y = ($height - $textbox[5])/2;
      imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
	  
	  for( $i=0; $i<($width*$height)/10; $i++ ) {
         imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $background_color);
      }
	  for( $i=0; $i<($width*$height)/320; $i++ ) {
         imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $background_color);
      }
	  
      header('Content-Type: image/jpeg');
      imagejpeg($image);
      imagedestroy($image);
      // this is the variable we need for checking
      $_SESSION['code'] = $code;
   }
}
$width = isset($_GET['width']) && $_GET['height'] < 600 ? $_GET['width'] : '60';
$height = isset($_GET['height']) && $_GET['height'] < 200 ? $_GET['height'] : '40';
$characters = isset($_GET['characters']) && $_GET['characters'] > 2 ? $_GET['characters'] : '2';
$captcha = new CaptchaSecurityImages($width,$height,$characters);
?>