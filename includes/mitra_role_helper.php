<?php
    // Konfigurasi terpusat 4 role mitra (distributor/agen/reseller/marketer) buat directory
    // mitra/ yang baru. Sebelumnya diskon tiap role hardcode berulang di ratusan file
    // (mis. "35/100" ditulis ulang di puluhan file distributor/*.php) - sekarang cukup di sini.
    //
    // "level" = urutan hierarki (1 paling tinggi/dekat ke pabrik). Dipakai buat validasi
    // rantai induk (mis. reseller cuma boleh punya induk agen/distributor, bukan sesama reseller).
    if (!function_exists('mitraRoleConfig')) {
        function mitraRoleConfig(?string $role = null)
        {
            $config = [
                'distributor' => [
                    'label'          => 'Distributor',
                    'diskon'         => 35,
                    'level'          => 1,
                    'table'          => 'admin_mitra',
                    'pk'             => 'idadmin',
                    'nama_kolom'     => 'namamitra',
                    'session_alias'  => 'idadmin',
                ],
                'agen' => [
                    'label'          => 'Agen',
                    'diskon'         => 25,
                    'level'          => 2,
                    'table'          => 'mitraagen',
                    'pk'             => 'idmitraagen',
                    'nama_kolom'     => 'namaagen',
                    'session_alias'  => 'idmitraagen',
                ],
                'reseller' => [
                    'label'          => 'Reseller',
                    'diskon'         => 15,
                    'level'          => 3,
                    'table'          => 'mitrareseller',
                    'pk'             => 'idmitrareseller',
                    'nama_kolom'     => 'namaagen',
                    'session_alias'  => 'idmitrareseller',
                ],
                'marketer' => [
                    'label'          => 'Marketer',
                    'diskon'         => 10,
                    'level'          => 4,
                    'table'          => 'mitramarketer',
                    'pk'             => 'idmitramarketer',
                    'nama_kolom'     => 'namaagen',
                    'session_alias'  => 'idmitramarketer',
                ],
            ];

            if ($role === null) {
                return $config;
            }
            return $config[$role] ?? null;
        }
    }

    if (!function_exists('mitraDiskonPersen')) {
        function mitraDiskonPersen(string $role): int
        {
            return mitraRoleConfig($role)['diskon'] ?? 0;
        }
    }

    if (!function_exists('mitraResolveIdentitas')) {
        /**
         * Ambil baris identitas mitra (admin_mitra/mitraagen/mitrareseller/mitramarketer)
         * berdasarkan iduser, sekaligus resolve idadmin_induk (distributor pemilik akhir)
         * dan idmitraagen_induk (opsional, kalau reseller/marketer direkrut lewat agen).
         *
         * @return array{idmitra:int, idadmin_induk:int, idmitraagen_induk:?int, nama:string}|null
         */
        function mitraResolveIdentitas(mysqli $koneksi, string $role, int $iduser): ?array
        {
            $cfg = mitraRoleConfig($role);
            if ($cfg === null) {
                return null;
            }

            $stmt = $koneksi->prepare("SELECT * FROM {$cfg['table']} WHERE iduser = ?");
            $stmt->bind_param('i', $iduser);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if (!$row) {
                return null;
            }

            $idmitra = (int) $row[$cfg['pk']];

            if ($role === 'distributor') {
                // Distributor adalah induk dirinya sendiri, tidak punya induk agen.
                $idAdminInduk     = $idmitra;
                $idMitraAgenInduk = null;
            } elseif ($role === 'agen') {
                // Kolom "idmitraagen" di tabel mitraagen adalah PK-nya SENDIRI (bukan FK induk) -
                // agen cuma bisa punya induk distributor, tidak ada induk agen di atasnya.
                $idAdminInduk     = (int) $row['idadmin'];
                $idMitraAgenInduk = null;
            } else {
                // reseller/marketer: idmitraagen di sini genuinely FK opsional ke induk agen.
                $idAdminInduk     = (int) $row['idadmin'];
                $idMitraAgenInduk = isset($row['idmitraagen']) && $row['idmitraagen'] !== null
                    ? (int) $row['idmitraagen']
                    : null;
            }

            return [
                'idmitra'           => $idmitra,
                'idadmin_induk'     => $idAdminInduk,
                'idmitraagen_induk' => $idMitraAgenInduk,
                'nama'              => $row[$cfg['nama_kolom']] ?? '',
            ];
        }
    }

    // Kolom di tabel pomitra yang harus diisi sesuai role - dulu form-form PO di distributor/
    // selalu hardcode ke kolom "idmitra" siapa pun yang order (dari portal manapun), jadi
    // riwayat/laporan PO agen/reseller/marketer tercampur/salah tempat. Ini titik tunggal
    // supaya setiap form PO di mitra/ otomatis catat ke kolom yang benar sesuai role login.
    if (!function_exists('mitraKolomPomitra')) {
        function mitraKolomPomitra(string $role): string
        {
            $kolom = [
                'distributor' => 'idmitra',
                'agen'        => 'idmitraagen',
                'reseller'    => 'idmitrareseller',
                'marketer'    => 'idmitramarketer',
            ];
            return $kolom[$role] ?? 'idmitra';
        }
    }

    // Prefix invoice per role - distributor tetap "D" (menjaga format invoice lama yang
    // sudah beredar/dicetak), role lain dapat huruf sendiri supaya dari invoice-nya saja
    // sudah kelihatan itu order dari portal mana.
    if (!function_exists('mitraPoInvoicePrefix')) {
        function mitraPoInvoicePrefix(string $role): string
        {
            $prefix = [
                'distributor' => 'D',
                'agen'        => 'A',
                'reseller'    => 'R',
                'marketer'    => 'M',
            ];
            return $prefix[$role] ?? 'X';
        }
    }

    if (!function_exists('mitraBuatInvoicePO')) {
        function mitraBuatInvoicePO(string $role, int $idMitra, int $idpoproduk): string
        {
            return mitraPoInvoicePrefix($role) . $idpoproduk . '-' . $idMitra;
        }
    }

    // Nama + alamat mitra buat ditampilkan di invoice/pembayaran - dulu halaman-halaman ini
    // (datapo.php dkk) selalu query admin_mitra langsung, jadi kalau dipakai agen/reseller/
    // marketer datanya jadi punya distributor induknya, bukan punya mereka sendiri.
    if (!function_exists('mitraDetailIdentitas')) {
        function mitraDetailIdentitas(mysqli $koneksi, string $role, int $idMitra): array
        {
            $cfg = mitraRoleConfig($role);
            if ($cfg === null) {
                return ['nama' => '', 'alamat' => ''];
            }

            $stmt = $koneksi->prepare("SELECT {$cfg['nama_kolom']} AS nama, alamat FROM {$cfg['table']} WHERE {$cfg['pk']} = ?");
            $stmt->bind_param('i', $idMitra);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            return [
                'nama'   => $row['nama'] ?? '',
                'alamat' => $row['alamat'] ?? '',
            ];
        }
    }
