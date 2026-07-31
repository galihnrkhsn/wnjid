<?php
    // Konversi file upload (jpg/jpeg/png) jadi WebP dan simpan ke $destDir.
    // Dipakai untuk upload bukti transfer (distributor & konsumen) supaya
    // ukurannya lebih ringan tanpa merusak keterbacaan nominal/teks di foto.
    if (!function_exists('convertUploadedImageToWebp')) {
        function convertUploadedImageToWebp(string $tmpPath, string $extension, string $destDir, string $baseName, int $quality = 85) {
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = @imagecreatefromjpeg($tmpPath);
                    break;
                case 'png':
                    $image = @imagecreatefrompng($tmpPath);
                    break;
                default:
                    $image = false;
            }

            if (!$image) {
                return null;
            }

            // Jaga transparansi kalau sumbernya PNG
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);

            $filename = $baseName . '.webp';
            $saved    = imagewebp($image, $destDir . $filename, $quality);
            imagedestroy($image);

            return $saved ? $filename : null;
        }
    }
