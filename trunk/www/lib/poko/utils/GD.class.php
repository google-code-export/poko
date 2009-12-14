<?php

class poko_utils_GD {
	public function __construct(){}
	static function gdInfo() {
		return php_Lib::hashOfAssociativeArray(gd_info());
	}
	static function getImageSize($filename, $imageinfo) {
		$types = new _hx_array(array(poko_utils_ImageType::$GIF, poko_utils_ImageType::$JPG, poko_utils_ImageType::$PNG, poko_utils_ImageType::$SWF, poko_utils_ImageType::$PSD, poko_utils_ImageType::$BMP, poko_utils_ImageType::$TIFF_II, poko_utils_ImageType::$TIFF_MM, poko_utils_ImageType::$JPC, poko_utils_ImageType::$JP2, poko_utils_ImageType::$JPX, poko_utils_ImageType::$JB2, poko_utils_ImageType::$SWC, poko_utils_ImageType::$IFF, poko_utils_ImageType::$WBMP, poko_utils_ImageType::$XBM));
		$a = getimagesize($filename);
		$bits = null;
		$channels = null;
		$mime = null;
		try {
			$bits = ($a[4] !== null ? $a[4] : null);
			$channels = ($a[5] !== null ? $a[5] : null);
			$mime = ($a[6] !== null ? $a[6] : null);
		}catch(Exception $�e) {
		$_ex_ = ($�e instanceof HException) ? $�e->e : $�e;
		;
		{ $e = $_ex_;
		{
			;
		}}}
		return _hx_anonymous(array("width" => $a[0], "height" => $a[1], "type" => $types[$a[2]], "bits" => $bits, "channels" => $channels, "mime" => $mime));
	}
	static function image_type_to_extension($imagetype, $include_dot) {
		$t = eval("if(isset(\$this)) \$�this =& \$this;switch(\$imagetype) {
			case poko_utils_ImageType::\$GIF:{
				\$�r = \"gif\";
			}break;
			case poko_utils_ImageType::\$JPG:{
				\$�r = \"jpg\";
			}break;
			case poko_utils_ImageType::\$PNG:{
				\$�r = \"png\";
			}break;
			case poko_utils_ImageType::\$SWF:{
				\$�r = \"swf\";
			}break;
			case poko_utils_ImageType::\$PSD:{
				\$�r = \"psd\";
			}break;
			case poko_utils_ImageType::\$BMP:{
				\$�r = \"bmp\";
			}break;
			case poko_utils_ImageType::\$TIFF_II:{
				\$�r = \"tiff\";
			}break;
			case poko_utils_ImageType::\$TIFF_MM:{
				\$�r = \"tiff\";
			}break;
			case poko_utils_ImageType::\$JPC:{
				\$�r = \"jpc\";
			}break;
			case poko_utils_ImageType::\$JP2:{
				\$�r = \"jp2\";
			}break;
			case poko_utils_ImageType::\$JPX:{
				\$�r = \"jpf\";
			}break;
			case poko_utils_ImageType::\$JB2:{
				\$�r = \"jb2\";
			}break;
			case poko_utils_ImageType::\$SWC:{
				\$�r = \"swc\";
			}break;
			case poko_utils_ImageType::\$IFF:{
				\$�r = \"aiff\";
			}break;
			case poko_utils_ImageType::\$WBMP:{
				\$�r = \"wbmp\";
			}break;
			case poko_utils_ImageType::\$XBM:{
				\$�r = \"xbm\";
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
		return ($include_dot ? "." . $t : $t);
	}
	static function imageTypeToMimeType($imagetype) {
		return eval("if(isset(\$this)) \$�this =& \$this;switch(\$imagetype) {
			case poko_utils_ImageType::\$GIF:{
				\$�r = \"image/gif\";
			}break;
			case poko_utils_ImageType::\$JPG:{
				\$�r = \"image/jpeg\";
			}break;
			case poko_utils_ImageType::\$PNG:{
				\$�r = \"image/png\";
			}break;
			case poko_utils_ImageType::\$SWF:{
				\$�r = \"application/x-shockwave-flash\";
			}break;
			case poko_utils_ImageType::\$PSD:{
				\$�r = \"image/psd\";
			}break;
			case poko_utils_ImageType::\$BMP:{
				\$�r = \"image/psd\";
			}break;
			case poko_utils_ImageType::\$TIFF_II:{
				\$�r = \"image/tiff\";
			}break;
			case poko_utils_ImageType::\$TIFF_MM:{
				\$�r = \"image/tiff\";
			}break;
			case poko_utils_ImageType::\$JPC:{
				\$�r = \"image/octet-stream\";
			}break;
			case poko_utils_ImageType::\$JP2:{
				\$�r = \"image/jp2\";
			}break;
			case poko_utils_ImageType::\$JPX:{
				\$�r = \"image/octet-stream\";
			}break;
			case poko_utils_ImageType::\$JB2:{
				\$�r = \"image/octet-stream\";
			}break;
			case poko_utils_ImageType::\$SWC:{
				\$�r = \"image/x-shockwave-flash\";
			}break;
			case poko_utils_ImageType::\$IFF:{
				\$�r = \"image/iff\";
			}break;
			case poko_utils_ImageType::\$WBMP:{
				\$�r = \"image/vnd.wap.wbmp\";
			}break;
			case poko_utils_ImageType::\$XBM:{
				\$�r = \"image/xbm\";
			}break;
			default:{
				\$�r = null;
			}break;
			}
			return \$�r;
		");
	}
	static function imageToWBMP($image, $filename, $threshold) {
		return image2wbmp($image, $filename, $threshold);
	}
	static function imageAlphaBlending($image, $blendmode) {
		return imagealphablending ($image, $blendmode);
	}
	static function imageAntiAlias($image, $on) {
		return imagealphablending ($image, $on);
	}
	static function imageArc($image, $cx, $cy, $w, $h, $s, $e, $color) {
		return imagearc ($image, $cx, $cy, $w, $h, $s, $e, $color);
	}
	static function imageChar($image, $font, $x, $y, $c, $color) {
		return imagechar($image, $font, $x, $y, $c, $color);
	}
	static function imageCharUp($image, $font, $x, $y, $c, $color) {
		return imagecharup($image, $font, $x, $y, $c, $color);
	}
	static function imageColorAllocate($image, $red, $green, $blue) {
		return imagecolorallocate($image, $red, $green, $blue);
	}
	static function imageColorAllocateAlpha($image, $red, $green, $blue, $alpha) {
		return imagecolorallocatealpha($image, $red, $green, $blue, $alpha);
	}
	static function imageColorAt($image, $x, $y) {
		return imagecolorat($image, $x, $y);
	}
	static function imageColorClosest($image, $red, $green, $blue) {
		return imagecolorclosest($image, $red, $green, $blue);
	}
	static function imageColorClosestAlpha($image, $red, $green, $blue, $alpha) {
		return imagecolorclosestalpha($image, $red, $green, $blue, $alpha);
	}
	static function imageColorDeallocate($image, $color) {
		return imagecolordeallocate($image, $color);
	}
	static function imageColorExact($image, $red, $green, $blue) {
		return imagecolorexact($image, $red, $green, $blue);
	}
	static function imageColorExactAlpha($image, $red, $green, $blue, $alpha) {
		return imagecolorexact($image, $red, $green, $blue, $alpha);
	}
	static function imageColorMatch($image1, $image2) {
		return imagecolormatch($image1, $image2);
	}
	static function imageColorResolve($image, $red, $green, $blue) {
		return imagecolorresolve($image, $red, $green, $blue);
	}
	static function imageColorResolveAlpha($image, $red, $green, $blue, $alpha) {
		return imagecolorresolvealpha($image, $red, $green, $blue, $alpha);
	}
	static function imageColorSet($image, $index, $red, $green, $blue) {
		imagecolorset($image, $index, $red, $green, $blue);
	}
	static function imageColorsForIndex($image, $index) {
		$a = imagecolorsforindex($image, $index);
		$a2 = php_Lib::hashOfAssociativeArray($a);
		return _hx_anonymous(array("red" => $a2->get("red"), "green" => $a2->get("green"), "blue" => $a2->get("blue"), "alpha" => $a2->get("alpha")));
	}
	static function imageColorsTotal($image) {
		return imagecolorstotal($image);
	}
	static function imageColorTransparent($image, $color) {
		return imagecolortransparent($image, $color);
	}
	static function imageConvolution($image, $matrix3x3, $div, $offset) {
		return imageconvolution($image, $matrix3x3, $div, $offset);
	}
	static function imageCopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h) {
		return imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	}
	static function imageCopyMerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		return imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
	}
	static function imageCopyMergeGray($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		return imagecopymergegray($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
	}
	static function imageCopyResampled($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
		return imagecopyresampled($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	}
	static function imageCopyResized($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
		return imagecopyresized($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	}
	static function imageCreate($x_size, $y_size) {
		$img = imagecreate($x_size, $y_size);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromGif($filename) {
		$img = imagecreatefromgif($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromJpeg($filename) {
		$img = imagecreatefromjpeg($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromPng($filename) {
		$img = imagecreatefrompng($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromString($filename) {
		$img = imagecreatefromstring($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromBmp($filename) {
		$img = imagecreatefromwbmp($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromXbm($filename) {
		$img = imagecreatefromxbm($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateFromXpm($filename) {
		$img = imagecreatefromxpm($filename);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageCreateTrueColor($x_size, $y_size) {
		$img = imagecreatetruecolor($x_size, $y_size);
		return ((Std::is($img, _hx_qtype("Bool"))) ? null : $img);
	}
	static function imageEllipse($image, $cx, $cy, $w, $h, $color) {
		return imageellipse($image, $cx, $cy, $w, $h, $color);
	}
	static function imageFill($image, $x, $y, $color) {
		return imagefill($image, $x, $y, $color);
	}
	static function imageFilledArc($image, $cx, $cy, $w, $h, $s, $e, $color, $style) {
		return imagefilledarc($image, $cx, $cy, $w, $h, $s, $e, $color, $style);
	}
	static function imageFilledEllipse($image, $cx, $cy, $w, $h, $s, $e, $color, $style) {
		return imagefilledellipse($image, $cx, $cy, $w, $h, $color);
	}
	static function imageFilledPolygon($image, $points, $num_points, $color) {
		return imagefilledpolygon($image, $points, $num_points);
	}
	static function imageFilledRectangle($image, $x1, $y1, $x2, $y2, $color) {
		return imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
	}
	static function imageFillToBorder($image, $x, $y, $border, $color) {
		return imagefilltoborder($image, $x, $y, $border, $color);
	}
	static function imageFilter($src_im, $filtertype, $arg1, $arg2, $arg3) {
		$filters = new _hx_array(array(poko_utils_ImageFilter::$NEGATE, poko_utils_ImageFilter::$GRAYSCALE, poko_utils_ImageFilter::$BRIGHTNESS, poko_utils_ImageFilter::$CONTRAST, poko_utils_ImageFilter::$COLORIZE, poko_utils_ImageFilter::$EDGEDETECT, poko_utils_ImageFilter::$EMBOSS, poko_utils_ImageFilter::$GAUSSIAN_BLUR, poko_utils_ImageFilter::$SELECTIVE_BLUR, poko_utils_ImageFilter::$MEAN_REMOVAL, poko_utils_ImageFilter::$SMOOTH));
		$c = 0;
		{
			$_g = 0;
			while($_g < $filters->length) {
				$i = $filters[$_g];
				++$_g;
				if($i === $filtertype) {
					break;
				}
				$c++;
				unset($i);
			}
		}
		return imagefilter($src_im, $c, $arg1, $arg2, $arg3);
	}
	static function imageFontHeight($font) {
		return imagefontheight($font);
	}
	static function imageFontWidth($font) {
		return imagefontwidth($font);
	}
	static function imageFTBBox($size, $angle, $font_file, $text, $extrainfo) {
		return imageftbbox($size, $angle, $font_file, $text, $extrainfo);
	}
	static function imageFTText($image, $size, $angle, $x, $y, $col, $font_file, $text, $extrainfo) {
		return imagefttbox($image, $size, $angle, $x, $y, $col, $font_file, $text, $extrainfo);
	}
	static function imageGammaCorrect($image, $inputgamma, $outputgamma) {
		return imagegammacorrect($image, $inputgamma, $outputgamma);
	}
	static function imageGD2($image, $filename, $chunk_size, $type) {
		return imagegd2($image, $filename, $chunk_size, $type);
	}
	static function imageGD($image, $filename) {
		return imagegd($image, $filename);
	}
	static function imageGif($image, $filename) {
		return imagegif($image, $filename);
	}
	static function imageInterlace($image, $interlace) {
		return imageinterlace($image, $interlace);
	}
	static function imageIsTrueColor($image) {
		return imageistruecolor ($image);
	}
	static function imageJpeg($image, $filename, $quality) {
		if($quality === null) {
			$quality = 80;
		}
		return imagejpeg($image, $filename, $quality);
	}
	static function imageLayerEffect($image, $effect) {
		$filters = new _hx_array(array(poko_utils_ImageLayerEffect::$REPLACE, poko_utils_ImageLayerEffect::$NORMAL, poko_utils_ImageLayerEffect::$OVERLAY));
		$c = 0;
		{
			$_g = 0;
			while($_g < $filters->length) {
				$i = $filters[$_g];
				++$_g;
				if($i === $effect) {
					break;
				}
				$c++;
				unset($i);
			}
		}
		return imagelayereffect($image, $c);
	}
	static function imageLine($image, $x1, $y1, $x2, $y2, $color) {
		return imageline($image, $x1, $y1, $x2, $y2, $color);
	}
	static function imageLoadFont($file) {
		return imageloadfont($file);
	}
	static function imagePaletteCopy($destination, $source) {
		imagepalettecopy($destination, $source);
	}
	static function imagePng($image, $filename) {
		return imagepng($image, $filename);
	}
	static function imagePolygon($image, $points, $num_points, $color) {
		return imagepolygon($image, $points, $num_points, $color);
	}
	static function imagePSBBox($text, $font, $size, $space, $tightness, $angle) {
		return imagepsbbox($text, $font, $size, $space, $tightness, $angle);
	}
	static function imagePSEncodeFont($font_index, $encodingfile) {
		return imagepsencodefont($font_index, $encodingfile);
	}
	static function imagePSExtendFont($font_index, $extend) {
		return imagepsextendfont($font_index, $extend);
	}
	static function imagePSFreeFont($fontindex) {
		return imagepsfreefont($fontindex);
	}
	static function imagePSLoadFont($filename) {
		return imagepsloadfont($filename);
	}
	static function imagePSSlantFont($font_index, $slant) {
		return imagepsslantfont($font_index, $slant);
	}
	static function imagePSText($image, $text, $font, $size, $foreground, $background, $x, $y, $space, $tightness, $angle, $antialias_steps) {
		return imagepstext($image, $text, $font, $size, $foreground, $background, $x, $y, $space, $tightness, $angle, $antialias_steps);
	}
	static function imageRectangle($image, $x1, $y1, $x2, $y2, $col) {
		return imagerectangle($image, $x1, $y1, $x2, $y2, $col);
	}
	static function imageRotate($src_im, $angle, $bgd_color, $ignore_transparent) {
		return imagerotate($src_im, $angle, $bgd_color, $ignore_transparent);
	}
	static function imageSaveAlpha($image, $saveflag) {
		return imagesavealpha ($image, $saveflag);
	}
	static function imageSetBrush($image, $brush) {
		return imageSetBrush($image, $brush);
	}
	static function imageSetPixel($image, $x, $y, $color) {
		return imagesetpixel($image, $x, $y, $color);
	}
	static function imageSetStyle($image, $style) {
		return imagesetstyle($image, $style);
	}
	static function imageSetThickness($image, $thickness) {
		return imagesetthickness($image, $thickness);
	}
	static function imageSetTile($image, $tile) {
		return imagesettile($image, $tile);
	}
	static function imageString($image, $font, $x, $y, $s, $col) {
		return imagestring($image, $font, $x, $y, $s, $col);
	}
	static function imageStringUp($image, $font, $x, $y, $s, $col) {
		return imagestringup($image, $font, $x, $y, $s, $col);
	}
	static function imageSX($image) {
		return imagesx($image);
	}
	static function imageSY($image) {
		return imagesy($image);
	}
	static function imageTrueColorToPalette($image, $dither, $ncolors) {
		return imagetruecolortopalette($image, $dither, $ncolors);
	}
	static function imageTTFBBox($size, $angle, $fontfile, $text) {
		return imagettfbbox($size, $angle, $fontfile, $text);
	}
	static function imageTTFText($image, $size, $angle, $x, $y, $color, $fontfile, $text) {
		return imagettftext($image, $size, $angle, $x, $y, $color, $fontfile, $text);
	}
	static function imageTypes() {
		return imagetypes();
	}
	static function imageWBmp($image, $filename, $foreground) {
		return imagewbmp($image, $filename, $foreground);
	}
	static function imageXbm($image, $filename, $foreground) {
		return imagexbm($image, $filename, $foreground);
	}
	static function iptcEmbed($iptcdata, $jpeg_file_name, $spool) {
		return iptcembed($iptcdata, $jpeg_file_name, $spool);
	}
	static function iptcParse($iptcblock) {
		return iptcparse($iptcblock);
	}
	static function jpeg2wbmp($jpegname, $wbmpname, $d_height, $d_width, $threshold) {
		return jpeg2wbmp($jpegname, $wbmpname, $d_height, $d_width, $threshold);
	}
	static function png2wbmp($pngname, $wbmpname, $d_height, $d_width, $threshold) {
		return png2wbmp($pngname, $wbmpname, $d_height, $d_width, $threshold);
	}
	function __toString() { return 'poko.utils.GD'; }
}
