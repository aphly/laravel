<?php
namespace Aphly\Laravel\Libs;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Support\Facades\Cookie;

class Seccode {

	public $code;
    public $width 	= 150;
    public $height 	= 60;
    public $scatter	= 0;

    public $adulterate	= false;
    public $angle 	= false;
    public $warping 	= false;
    public $color 	= false;
    public $size 	= false;
    public $shadow 	= false;
    public $animator 	= true;

	private $fontcolor;
	private $im;

    public function _check($code)
    {
        $seccode = Cookie::get('seccode');
        if(!$code || strtolower($code)!=strtolower($seccode)){
            throw new ApiException(['code'=>1,'msg'=>'no']);
        }
    }

	function display() {
		if(function_exists('imagecreate') && function_exists('imagecolorset') && function_exists('imagecopyresized') &&
			function_exists('imagecolorallocate') && function_exists('imagechar') && function_exists('imagecolorsforindex') &&
			function_exists('imageline') && function_exists('imagecreatefromstring') && (function_exists('imagegif') || function_exists('imagepng') || function_exists('imagejpeg'))) {
            return $this->image();
        }
	}

	function image() {
		$bgcontent = $this->background();
		if($this->animator == 1 && function_exists('imagegif')) {
			$trueframe = mt_rand(1, 9);
			for($i = 0; $i <= 9; $i++) {
				$this->im = imagecreatefromstring($bgcontent);
				$x[$i] = $y[$i] = 0;
				$this->adulterate && $this->adulterate();
				if($i == $trueframe) {
					function_exists('imagettftext') && $this->ttffont();
					$d[$i] = mt_rand(250, 400);
					$this->scatter && $this->scatter($this->im);
				} else {
					$this->adulteratefont();
					$d[$i] = mt_rand(5, 15);
					$this->scatter && $this->scatter($this->im, 1);
				}
				ob_start();
				imagegif($this->im);
				imagedestroy($this->im);
				$frame[$i] = ob_get_contents();
				ob_end_clean();
			}
			$anim = new GifMerge($frame, 255, 255, 255, 0, $d, $x, $y, 'C_MEMORY');
            return $anim->getAnimation();
		} else {
			$this->im = imagecreatefromstring($bgcontent);
			$this->adulterate && $this->adulterate();
			function_exists('imagettftext') && $this->ttffont();
			$this->scatter && $this->scatter($this->im);
            ob_start();
			if(function_exists('imagepng')) {
                imagepng($this->im);
			}
            imagedestroy($this->im);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
		}
	}

	function background() {
		$this->im = imagecreatetruecolor($this->width, $this->height);
        $c = array();
        for($i = 0;$i < 3;$i++) {
            $start[$i] = mt_rand(200, 255);$end[$i] = mt_rand(100, 150);$step[$i] = ($end[$i] - $start[$i]) / $this->width;$c[$i] = $start[$i];
        }
        for($i = 0;$i < $this->width;$i++) {
            $color = imagecolorallocate($this->im, $c[0], $c[1], $c[2]);
            imageline($this->im, $i, 0, $i, $this->height, $color);
            $c[0] += $step[0];$c[1] += $step[1];$c[2] += $step[2];
        }
        $c[0] -= 20;$c[1] -= 20;$c[2] -= 20;
        $this->fontcolor = $c;
		ob_start();
		if(function_exists('imagepng')) {
			imagepng($this->im);
		}
		imagedestroy($this->im);
		$bgcontent = ob_get_contents();
		ob_end_clean();
		return $bgcontent;
	}

	function adulterate() {
		$linenums = $this->height / 10;
		for($i = 0; $i <= $linenums;$i++) {
			$color = $this->color ? imagecolorallocate($this->im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)) : imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
			$x = mt_rand(0, $this->width);
			$y = mt_rand(0, $this->height);
			if(mt_rand(0, 1)) {
				$w = mt_rand(0, $this->width);
				$h = mt_rand(0, $this->height);
				$s = mt_rand(0, 360);
				$e = mt_rand(0, 360);
				for($j = 0;$j < 3;$j++) {
					imagearc($this->im, $x + $j, $y, $w, $h, $s, $e, $color);
				}
			} else {
				$xe = mt_rand(0, $this->width);
				$ye = mt_rand(0, $this->height);
				imageline($this->im, $x, $y, $xe, $ye, $color);
				for($j = 0;$j < 3;$j++) {
					imageline($this->im, $x + $j, $y, $xe, $ye, $color);
				}
			}
		}
	}

	function adulteratefont() {
		$seccodeunits = 'BCEFGHJKMPQRTVWXY2346789';
		$x = $this->width / 4;
		$y = $this->height / 10;
		$text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
		for($i = 0; $i <= 3; $i++) {
			$adulteratecode = $seccodeunits[mt_rand(0, 23)];
			imagechar($this->im, 5, $x * $i + mt_rand(0, $x - 10), mt_rand($y, $this->height - 10 - $y), $adulteratecode, $text_color);
		}
	}

	function ttffont() {
		$seccode = $this->code;
		$seccoderoot = public_path('vendor/laravel/font');
		$dirs = opendir($seccoderoot);
		$seccodettf = array();
		while($entry = readdir($dirs)) {
			if($entry != '.' && $entry != '..' && in_array(strtolower($this->fileext($entry)), array('ttf', 'ttc'))) {
				$seccodettf[] = $entry;
			}
		}
		if(empty($seccodettf)) {
			return;
		}
		$seccodelength = 4;
		$widthtotal = 0;
		for($i = 0; $i < $seccodelength; $i++) {
			$font[$i]['font'] = $seccoderoot.'/'.$seccodettf[array_rand($seccodettf)];
			$font[$i]['angle'] = $this->angle ? mt_rand(-30, 30) : 0;
			$font[$i]['size'] = $this->width / 6;
			$this->size && $font[$i]['size'] = mt_rand($font[$i]['size'] - $this->width / 40, $font[$i]['size'] + $this->width / 20);
			$box = imagettfbbox($font[$i]['size'], 0, $font[$i]['font'], $seccode[$i]);
			$font[$i]['zheight'] = max($box[1], $box[3]) - min($box[5], $box[7]);
			$box = imagettfbbox($font[$i]['size'], $font[$i]['angle'], $font[$i]['font'], $seccode[$i]);
			$font[$i]['height'] = max($box[1], $box[3]) - min($box[5], $box[7]);
			$font[$i]['hd'] = $font[$i]['height'] - $font[$i]['zheight'];
			$font[$i]['width'] = (max($box[2], $box[4]) - min($box[0], $box[6])) + mt_rand(0, $this->width / 8);
			$font[$i]['width'] = $font[$i]['width'] > $this->width / $seccodelength ? $this->width / $seccodelength : $font[$i]['width'];
			$widthtotal += $font[$i]['width'];
		}
		$font1 = $font[0]['angle'] > 0 ? cos(deg2rad(90 - $font[0]['angle'])) * $font[0]['zheight'] : 1;
        $font2 = $this->width - $widthtotal;
		$x = mt_rand(min($font1,$font2),max($font1,$font2));
		!$this->color && $text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
		for($i = 0; $i < $seccodelength; $i++) {
			if($this->color) {
				$this->fontcolor = array(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
				$this->shadow && $text_shadowcolor = imagecolorallocate($this->im, 0, 0, 0);
				$text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
			} elseif($this->shadow) {
				$text_shadowcolor = imagecolorallocate($this->im, 0, 0, 0);
			}
			$y = $font[0]['angle'] > 0 ? mt_rand($font[$i]['height'], $this->height) : mt_rand($font[$i]['height'] - $font[$i]['hd'], $this->height - $font[$i]['hd']);
			$this->shadow && imagettftext($this->im, $font[$i]['size'], $font[$i]['angle'], $x + 1, $y + 1, $text_shadowcolor, $font[$i]['font'], $seccode[$i]);
			imagettftext($this->im, $font[$i]['size'], $font[$i]['angle'], $x, $y, $text_color, $font[$i]['font'], $seccode[$i]);
			$x += $font[$i]['width'];
		}
		$this->warping && $this->warping($this->im);
	}

	function warping(&$obj) {
		$rgb = array();
		$direct = rand(0, 1);
		$width = imagesx($obj);
		$height = imagesy($obj);
		$level = $width / 20;
		for($j = 0;$j < $height;$j++) {
			for($i = 0;$i < $width;$i++) {
				$rgb[$i] = imagecolorat($obj, $i , $j);
			}
			for($i = 0;$i < $width;$i++) {
				$r = sin($j / $height * 2 * M_PI - M_PI * 0.5) * ($direct ? $level : -$level);
				imagesetpixel($obj, $i + $r , $j , $rgb[$i]);
			}
		}
	}

	function scatter(&$obj, $level = 0) {
		$rgb = array();
		$this->scatter = $level ? $level : $this->scatter;
		$width = imagesx($obj);
		$height = imagesy($obj);
		for($j = 0;$j < $height;$j++) {
			for($i = 0;$i < $width;$i++) {
				$rgb[$i] = imagecolorat($obj, $i , $j);
			}
			for($i = 0;$i < $width;$i++) {
				$r = rand(-$this->scatter, $this->scatter);
				imagesetpixel($obj, $i + $r , $j , $rgb[$i]);
			}
		}
	}

    function fileext($filename) {
        return trim(substr(strrchr($filename, '.'), 1, 10));
    }
}

?>
