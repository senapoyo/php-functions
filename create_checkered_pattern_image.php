<?php
/**
 * create_checkered_pattern_image
 *
 * @version    1.0.0
 * @author     senapoyo
 *
 * 市松模様を出力する
 * みる人がみたら透明にみえる不思議
 *
 * @param  integer  $width      画像幅
 * @param  integer  $height     画像高
 * @param  integer  $gridSize   グリッド(升目)の大きさ
 * @param  string   $bgColor    背景色(6桁、もしくはアルファを含む8桁のカラーコード(#000000 or #00000000))
 * @param  string   $fillColor  塗り色(6桁、もしくはアルファを含む8桁のカラーコード(#000000 or #00000000))
 * @param  string   $format     画像フォーマット(jpeg, gif, pngいずれか)
 *
 * // 直接出力する場合
 * header('content-type: image/' . $format);
 * create_checkered_pattern_image($width, $height, $gridSize, $bgColor, $fillColor, $format);
 *
 * // htmlタグを出力する場合、$base64encodeを真にする
 * echo '<img src="data:image/' . $format . ';base64,' . create_checkered_pattern_image($width, $height, $gridSize, $bgColor, $fillColor, $format, false, true) . '">';
 */

// 例: 透明っぽい市松模様 (e.g. transparent?checkered pattern)

// 画像幅
$width = 80;
// 画像高
$height = 80;
// グリッド(升目)の大きさ
$gridSize = 20;
// 背景色(6桁、もしくはアルファを含む8桁のカラーコード(#000000 or #00000000))
$bgColor = '#ffffff';
// 塗り色(6桁、もしくはアルファを含む8桁のカラーコード(#000000 or #00000000))
$fillColor = '#afafaf';
// 画像フォーマット(jpeg, gif, pngいずれか)
$format = 'png';

create_checkered_pattern_image($width, $height, $gridSize, $bgColor, $fillColor, $format);

function create_checkered_pattern_image($width, $height, $gridSize, $bgColor, $fillColor, $format = 'png', $reverse = false, $base64encode = false) {

    $colorCodeRegex = '/^(?:#)?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})?$/i';

    if ($format == 'jpg') $format = 'jpeg';

    if (preg_match($colorCodeRegex, $fillColor, $color)) {
        $fillRed = $color[1];
        $fillGreen = $color[2];
        $fillBlue = $color[3];
        if (isset($color[4])) $fillAlpha = $color[4];
    } else {
        return false;
    }
    if (preg_match($colorCodeRegex, $bgColor, $color)) {
        $bgRed = $color[1];
        $bgGreen = $color[2];
        $bgBlue = $color[3];
        if (isset($color[4])) $bgAlpha = $color[4];
    } else {
        return false;
    }

    if (!($format == 'gif' || $format == 'jpeg' || $format == 'png')) return false;

    $image = imagecreate($width, $height);
    if (isset($bgAlpha)) {
        $bg = imagecolorallocatealpha($image, hexdec($bgRed), hexdec($bgGreen), hexdec($bgBlue), _alphaConvert($bgAlpha));
    } else {
        $bg = imagecolorallocate($image, hexdec($bgRed), hexdec($bgGreen), hexdec($bgBlue));
    }
    if (isset($fillAlpha)) {
        $fill = imagecolorallocatealpha($image, hexdec($fillRed), hexdec($fillGreen), hexdec($fillBlue), _alphaConvert($fillAlpha));
    } else {
        $fill = imagecolorallocate($image, hexdec($fillRed), hexdec($fillGreen), hexdec($fillBlue));
    }

    $i = 0;
    for ($y = 0; $y <= $height; $y += $gridSize) {
        if (!$reverse) {
            $i++;
        }
        $i % 2 == 0 ? $s = 0 : $s = $gridSize;
        for ($x = $s; $x < $width; $x += $gridSize * 2) {
            imagefilledrectangle($image, $x, $y, $x + $gridSize - 1, $y + $gridSize - 1, $fill);
        }
        if ($reverse) {
            $i++;
        }
    }

    if ($base64encode) {
        ob_start();
    } else {
        header('content-type: image/' . $format);
    }

    switch ($format) {
        case 'gif':
            imagegif($image);
            break;
        case 'jpeg':
        case 'jpg':
            imagejpeg($image, null, 100);
            break;
        case 'png':
            imagepng($image);
            break;
    }

    if ($base64encode) {
        $ob = ob_get_contents();
        ob_clean();
        return base64_encode($ob);
    }

}

function _alphaConvert($in)
{
    return (int) floor((hexdec($in) / 2 ) - 127) * -1;
}
