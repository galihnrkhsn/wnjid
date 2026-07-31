<?php
    // Mapping status orderkonsumen -> warna badge Bootstrap. Dipakai bareng oleh
    // detail.php & riwayat_pesanan.php supaya warnanya tidak pernah beda-beda.
    if (!function_exists('orderKonsumenStatusBadge')) {
        /**
         * @return array [$badgeColor, $badgeLabel]
         */
        function orderKonsumenStatusBadge(string $status): array
        {
            $map = [
                'Menunggu Alamat'           => ['secondary', 'Menunggu Alamat'],
                'Menunggu Pembayaran'       => ['secondary', 'Menunggu Pembayaran'],
                'Menunggu Konfirmasi Admin' => ['info', 'Menunggu Konfirmasi Admin'],
                'Diproses'                  => ['primary', 'Diproses'],
                'Menunggu Resi'             => ['warning', 'Menunggu Resi'],
                'Sedang dalam perjalanan'   => ['info', 'Sedang dalam perjalanan'],
                'Terkirim'                  => ['success', 'Terkirim'],
                'Selesai'                   => ['success', 'Selesai'],
                'Dibatalkan'                => ['danger', 'Dibatalkan'],
            ];

            return $map[$status] ?? ['secondary', $status];
        }
    }
