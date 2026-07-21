<?php

function compressResizeImage($source, $destination, $quality = 75, $maxWidth = 1200)
{
    $info = getimagesize($source);

    if (!$info) {
        return false;
    }

    $width  = $info[0];
    $height = $info[1];
    $mime   = $info['mime'];

    // hitung resize
    if ($width > $maxWidth) {
        $newWidth  = $maxWidth;
        $newHeight = floor(($height / $width) * $maxWidth);
    } else {
        $newWidth  = $width;
        $newHeight = $height;
    }

    $canvas = imagecreatetruecolor($newWidth, $newHeight);

    switch ($mime) {

        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
        break;

        case 'image/png':
            $image = imagecreatefrompng($source);

            // transparansi png
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        break;

        case 'image/webp':
            $image = imagecreatefromwebp($source);
        break;

        default:
            return false;
    }

    imagecopyresampled(
        $canvas,
        $image,
        0,0,0,0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    // convert ke WEBP (paling optimal)
    $destination = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $destination);

    imagewebp($canvas, $destination, $quality);

    imagedestroy($image);
    imagedestroy($canvas);

    return $destination;
}