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

    // Titik tunggal buat kirim email notifikasi status order ke konsumen - dipakai bareng
    // oleh checkout.php, konfirmasi pembayaran (manual & QRIS), sinkron resi, dan cek
    // resi terkirim, supaya lookup email/nama konsumennya cuma ada di satu tempat.
    if (!function_exists('kirimEmailStatusOrder')) {
        /**
         * No-op diam-diam kalau order/email tidak ditemukan - notifikasi email tidak boleh
         * pernah menggagalkan alur utama (checkout/update status tetap harus jalan).
         */
        function kirimEmailStatusOrder(mysqli $koneksi, int $idorder, string $subject, string $isiHtml): void
        {
            include_once __DIR__ . '/mail_helper.php';

            $stmt = $koneksi->prepare("SELECT o.nama_penerima, k.email, k.namamitra
                                        FROM orderkonsumen o
                                        LEFT JOIN konsumen k ON k.idkonsumen = o.idkonsumen
                                        WHERE o.idorder = ?");
            $stmt->bind_param('i', $idorder);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            if (!$row || empty($row['email'])) {
                return;
            }

            $namaTujuan = $row['nama_penerima'] ?: ($row['namamitra'] ?: '');
            kirimEmailNotifikasi($row['email'], $namaTujuan, $subject, emailTemplate($subject, $isiHtml));
        }
    }
