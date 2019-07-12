<?PHP
  $png = @imagecreatetruecolor(200, 50);	//dimensions

  // set background and allocate drawing colors
  $bgcolor = imagecolorallocate($png, 0x00, 0xAA, 0x00);	//for bg
  $color1 = imagecolorallocate($png, 0x00, 0x00, 0x00);	//for lines
  $color2 = imagecolorallocate($png, 0x00, 0x00, 0x00);	//for text
  $color3 = imagecolorallocate($png, 0xFF, 0xFF, 0xFF);	//for text

  //put background
  imagefill($png, 0, 0, $bgcolor);
  
  // using a mixture of TTF fonts
  $fonts = array();
  $fonts[] = "cap/1 (1).ttf";
  $fonts[] = "cap/1 (2).ttf";
  $fonts[] = "cap/1 (3).ttf";
  $fonts[] = "cap/1 (4).ttf";
  $fonts[] = "cap/1 (5).ttf";

  // add random digits to canvas using random black/white colour
  $captcha = '';
  $string="abcdefghjkmnpqrstuvwxyz23456789!?ABCDEFGHJKMNPQRSTUVWXYZ23456789!?";	//possible characters
  
  for($x = 10; $x <= 180; $x += 30) {	//no. of characters
	  
	  $rstr = $string[rand(0, strlen($string)-1)];
    $textcolor = (rand() % 2) ? $color2 : $color3;
    $captcha .= $rstr;
    imagettftext($png, 30, rand(-20,20), $x, rand(30, 42), $textcolor, $fonts[array_rand($fonts)], $rstr);
	imagesetthickness($png, rand(1,3));
    imageline($png, rand(0,200), rand(0,50), rand(0,200), rand(0,50), $color1);			//draw random lines
	//imagettftext()
  }

  // make the value available only in the server
  session_start();
  $_SESSION['captcha'] = strtoupper($captcha);

  // finally
  header('Content-type: image/png');
  imagepng($png);
  imagedestroy($png);
?>