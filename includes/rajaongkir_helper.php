<?php
    // Cek ongkir lewat API Komerce (RajaOngkir v2). Dipakai bareng oleh distributor & konsumen
    // supaya kredensial + logika retry-nya cuma ada di satu tempat.
    if (!function_exists('cekOngkirKomerce')) {
        /**
         * @return array daftar layanan (masing-masing: service, description, cost, etd),
         *               kosong kalau API gagal/tidak ada hasil.
         */
        function cekOngkirKomerce(int $idDistrictTujuan, int $beratGram, string $kurirKode): array
        {
            $configFile = __DIR__ . '/rajaongkir.config.php';
            if (!file_exists($configFile)) {
                return [];
            }
            $config  = require $configFile;
            $apiKeys = $config['api_keys'] ?? [];
            $asal    = $config['origin_district_id'] ?? 0;

            if (empty($apiKeys) || $asal <= 0 || $idDistrictTujuan <= 0 || $beratGram <= 0 || $kurirKode === '') {
                return [];
            }
            $postFields = http_build_query([
                'origin'          => $asal,
                'originType'      => 'district',
                'destination'     => $idDistrictTujuan,
                'destinationType' => 'district',
                'weight'          => $beratGram,
                'courier'         => $kurirKode,
            ]);

            $data = null;

            foreach ($apiKeys as $apiKey) {
                if ($apiKey === '') {
                    continue;
                }

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL            => 'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING       => '',
                    CURLOPT_MAXREDIRS      => 10,
                    CURLOPT_TIMEOUT        => 20,
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST  => 'POST',
                    CURLOPT_POSTFIELDS     => $postFields,
                    CURLOPT_HTTPHEADER     => [
                        'content-type: application/x-www-form-urlencoded',
                        'key: ' . $apiKey,
                    ],
                ]);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $err      = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    continue;
                }

                // Key ini kena rate limit, coba key berikutnya
                if ($httpCode === 429) {
                    continue;
                }

                $decoded = json_decode($response, true);
                if ($httpCode === 200 && json_last_error() === JSON_ERROR_NONE && !empty($decoded['data'])) {
                    $data = $decoded['data'];
                    break;
                }
            }

            if (empty($data)) {
                return [];
            }

            return array_map(function ($row) {
                return [
                    'service'     => $row['service'] ?? '',
                    'description' => $row['description'] ?? '',
                    'cost'        => (int) ($row['cost'] ?? 0),
                    'etd'         => $row['etd'] ?? '',
                ];
            }, $data);
        }
    }

    // Lacak resi lewat API Komerce (RajaOngkir v2). Sama pola dengan cekOngkirKomerce()
    // (kredensial + retry key di satu tempat) supaya dipakai bareng oleh halaman manapun.
    if (!function_exists('lacakResiKomerce')) {
        /**
         * @return array ['delivered' => bool, 'status' => string, 'manifest' => array[]],
         *               kosong kalau API gagal/resi tidak ditemukan/kurir tidak didukung.
         */
        function lacakResiKomerce(string $noResi, string $kurirKode): array
        {
            $configFile = __DIR__ . '/rajaongkir.config.php';
            if (!file_exists($configFile)) {
                return [];
            }
            $config  = require $configFile;
            $apiKeys = $config['api_keys'] ?? [];

            if (empty($apiKeys) || $noResi === '' || $kurirKode === '') {
                return [];
            }

            $url = 'https://rajaongkir.komerce.id/api/v1/track/waybill?';
            $postFields = http_build_query([
                'awb'          => urlencode($noResi),
                'courier'      => $kurirKode,
            ]);
            // $postFields = http_build_query(['last_phone_number' => $last5]);

            $data = null;

            foreach ($apiKeys as $apiKey) {
                if ($apiKey === '') {
                    continue;
                }

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL            => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING       => '',
                    CURLOPT_MAXREDIRS      => 10,
                    CURLOPT_TIMEOUT        => 20,
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST  => 'POST',
                    CURLOPT_POSTFIELDS     => $postFields,
                    CURLOPT_HTTPHEADER     => [
                        'content-type: application/x-www-form-urlencoded',
                        'key: ' . $apiKey,
                    ],
                ]);

                $response = curl_exec($curl);

                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $err      = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    continue;
                }

                // Key ini kena rate limit, coba key berikutnya
                if ($httpCode === 429) {
                    continue;
                }

                $decoded = json_decode($response, true);
                if ($httpCode === 200 && json_last_error() === JSON_ERROR_NONE && !empty($decoded['data'])) {
                    $data = $decoded['data'];
                    break;
                }
            }

            if (empty($data)) {
                return [];
            }

            $manifest = array_map(function ($row) {
                return [
                    'date'        => $row['manifest_date'] ?? '',
                    'time'        => $row['manifest_time'] ?? '',
                    'description' => $row['manifest_description'] ?? '',
                    'city'        => $row['city_name'] ?? '',
                ];
            }, $data['manifest'] ?? []);

            return [
                'delivered' => (bool) ($data['delivered'] ?? false),
                'status'    => $data['summary']['status'] ?? ($data['delivery_status']['status'] ?? ''),
                'manifest'  => $manifest,
            ];
        }
    }

    // Nama ekspedisi disimpan sebagai label tampilan di orderkonsumen (mis. "JNE"),
    // API tracking butuh kode kurir huruf kecil. Dipakai bareng oleh konsumen/detail.php
    // & cron pengecekan resi supaya mapping-nya cuma ada di satu tempat.
    if (!function_exists('kodeKurirDariEkspedisi')) {
        function kodeKurirDariEkspedisi(string $namaEkspedisi): string
        {
            $map = [
                'JNE'            => 'jne',
                'TIKI'           => 'tiki',
                'POS INDONESIA'  => 'pos',
                'WAHANA'         => 'wahana',
                'SICEPAT'        => 'sicepat',
                'J&T'            => 'jnt',
                'LION'           => 'lion',
                'ANTERAJA'       => 'anteraja',
                'ID EXPRESS'     => 'ide',
            ];
            return $map[strtoupper(trim($namaEkspedisi))] ?? '';
        }
    }

    // Cek resi lewat lacakResiKomerce(), dan kalau hasilnya sudah delivered langsung
    // tandai orderkonsumen.status = 'Terkirim'. Guard: tidak pernah menimpa order yang
    // sudah Terkirim/Selesai/Dibatalkan. Dipakai baik saat konsumen buka detail.php
    // (real-time, on-view) maupun cron di server (real-time meski konsumen tidak buka halaman).
    if (!function_exists('cekDanTandaiTerkirim')) {
        /**
         * @return array hasil tracking (['delivered'=>bool,'status'=>string,'manifest'=>array]),
         *               kosong kalau tidak sempat dicek (resi/kurir kosong).
         */
        function cekDanTandaiTerkirim(mysqli $koneksi, int $idorder, string $statusSaatIni, string $noResi, string $kurirKode): array
        {
            if ($noResi === '' || $kurirKode === '') {
                return [];
            }

            $tracking = lacakResiKomerce($noResi, $kurirKode);

            $sudahDelivered = ($tracking['delivered'] ?? false) === true
                || strtoupper($tracking['status'] ?? '') === 'DELIVERED';

            if ($sudahDelivered && !in_array($statusSaatIni, ['Terkirim', 'Selesai', 'Dibatalkan'], true)) {
                $stmt = $koneksi->prepare("UPDATE orderkonsumen SET status = 'Terkirim' WHERE idorder = ? AND status NOT IN ('Terkirim', 'Selesai', 'Dibatalkan')");
                $stmt->bind_param('i', $idorder);
                $stmt->execute();
            }

            return $tracking;
        }
    }
