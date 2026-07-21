<?php 
    session_start();
    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Tambah PO | Admin</title>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
    </head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <h1 class="h3 mb-4 text-gray-800">Tambah PO</h1>

            <?php 
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_all'])):
                    // 1. Simpan PO
                    $namapo     = $_POST['namapo'];
                    $status     = $_POST['status'];
                    $jenis      = $_POST['jenis'];
                    $tipe       = $_POST['tipe'];
                    $pembayaran = $_POST['pembayaran'];
                    $diskon     = $_POST['diskon'];
                    $tglselesai = date("M d, Y", strtotime($_POST['tglselesai']));

                    $koneksi->query("INSERT INTO poproduk 
                                            (namapo, status, tglselesai, jenis, diskon, tipe, pembayaran, updated_at, created_at)
                                        VALUES 
                                            ('$namapo', '$status', '$tglselesai 23:59:00', '$jenis', '$diskon', '$tipe', '$pembayaran', NOW(), NOW())");
                    $idpoproduk = $koneksi->insert_id;

                    // 2. Simpan Kategori
                    foreach ($_POST['kategori'] as $i => $nama) {
                        $stok   = $_POST['stok'][$i] ?? 0;
                        $koneksi->query("INSERT INTO pokategori (idpoproduk, namakategori, stok) VALUES ('$idpoproduk', '$nama', '$stok')");
                    }

                    // 3. Simpan Variant
                    foreach ($_POST['variant'] as $i => $variant) {
                        $harga      = $_POST['harga'][$i] ?? 0;
                        $berat      = $_POST['berat'][$i] ?? 0;
                        $kategori   = $_POST['variant_kategori'][$i];

                        // Cari idpo dari pokategori yang sesuai
                        $res        = $koneksi->query("SELECT idpo FROM pokategori WHERE idpoproduk = '$idpoproduk' AND namakategori = '$kategori' LIMIT 1");
                        $idpo       = $res->fetch_assoc()['idpo'] ?? 0;

                        $koneksi->query("INSERT INTO podetail (idpo, variant, harga, berat) VALUES ('$idpo', '$variant', '$harga', '$berat')");
                    }

                    echo "<script>alert('Data berhasil disimpan'); location='produkpo.php';</script>";
                endif; 
            ?>
<form method="POST">
    <h5 class="mt-3">Informasi PO</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="namapo">Nama PO</label>
            <input class="form-control" name="namapo" id="namapo" required>
        </div>
        <div class="col-md-2 mb-3">
            <label for="status">Status</label>
            <select class="form-control" name="status" id="status">
                <option value="open">Open</option>
                <option value="close">Close</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="jenis">Jenis PO</label>
            <select class="form-control" name="jenis" id="jenis">
                <option value="normal">Normal</option>
                <option value="custom">Custom</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="tipe">Tipe PO</label>
            <select class="form-control" name="tipe" id="tipe">
                <option value="normal">Normal</option>
                <option value="hide">Hide</option>
            </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="pembayaran">Pembayaran</label>
            <select class="form-control" name="pembayaran" id="pembayaran">
                <option value="DP">DP</option>
                <option value="Lunas">Lunas</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="diskon">Diskon Tambahan</label>
            <input type="number" class="form-control" name="diskon" id="diskon" value="0" min="0" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="tglselesai">Tanggal Selesai</label>
            <input type="date" class="form-control" name="tglselesai" id="tglselesai" required>
        </div>
    </div>

    <hr>
    <h5 class="mt-4">Input Kategori</h5>
    <div class="mb-3">
        <label for="jumlahKategori" class="mr-2">Jumlah Kategori</label>
        <input type="number" id="jumlahKategori" class="form-control d-inline w-25 mr-2" min="1">
        <button type="button" class="btn btn-sm btn-primary" onclick="generateKategori()">Generate</button>
    </div>
    <div id="kategoriContainer"></div>

    <hr>
    <h5 class="mt-4">Input Variant</h5>
    <div class="mb-3">
        <label for="jumlahVariant" class="mr-2">Jumlah Variant</label>
        <input type="number" id="jumlahVariant" class="form-control d-inline w-25 mr-2" min="1">
        <button type="button" class="btn btn-sm btn-success" onclick="generateVariant()">Generate</button>
    </div>
    <div id="variantContainer"></div>

    <button type="submit" name="save_all" class="btn btn-lg btn-primary mt-4">Simpan Semua Data</button>
</form>

        </div>
    </div>
    <script>
        function generateKategori() {
            const container = document.getElementById('kategoriContainer');
            container.innerHTML = '';
            const jumlah = parseInt(document.getElementById('jumlahKategori').value);
            for (let i = 0; i < jumlah; i++) {
                container.innerHTML += `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kategori${i}">Nama Kategori</label>
                        <input class="form-control" name="kategori[]" id="kategori${i}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="stok${i}">Stok</label>
                        <input type="number" class="form-control" name="stok[]" id="stok${i}" value="0" min="0" required>
                    </div>
                </div>`;
            }
        }

        function generateVariant() {
            const container = document.getElementById('variantContainer');
            container.innerHTML = '';
            const jumlah = parseInt(document.getElementById('jumlahVariant').value);
            const kategoriInputs = document.querySelectorAll('[name="kategori[]"]');

            if (kategoriInputs.length === 0) {
                alert('Isi kategori terlebih dahulu');
                return;
            }

            const options = Array.from(kategoriInputs).map(k => `<option value="${k.value}">${k.value}</option>`).join('');

            for (let i = 0; i < jumlah; i++) {
                container.innerHTML += `
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="variant_kategori${i}">Pilih Kategori</label>
                        <select class="form-control" name="variant_kategori[]" id="variant_kategori${i}">${options}</select>
                    </div>
                    <div class="col-md-3">
                        <label for="variant${i}">Variant (contoh: Dress Sz S)</label>
                        <input class="form-control" name="variant[]" id="variant${i}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="harga${i}">Harga</label>
                        <input type="number" class="form-control" name="harga[]" id="harga${i}" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label for="berat${i}">Berat</label>
                        <input type="number" class="form-control" name="berat[]" id="berat${i}" min="0" required>
                    </div>
                </div>`;
            }
        }
    </script>
</body>
</html>
