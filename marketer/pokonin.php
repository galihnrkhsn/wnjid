<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesMarketer.php';

    $invoice = $_GET['invoice'];
    $idpoproduk = $_GET['id'];
    $idmitramarketer = $_SESSION["idmitramarketer"];
    $sqlmitra = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $datamitra = $sqlmitra->fetch_assoc();
    $idadmin = $datamitra['idadmin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ | <?= $datamitra["namaagen"] ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid row">
            <div class="col-2 text-center">
                <a class="navbar-brand" href="listnewpo.php"><i class="bi bi-chevron-left"></i></a>
            </div>

            <div class="col-8 text-center">
                <h5 class="p-0 m-0 fw-semibold text-uppercase">pre order</h5>
            </div>
            
            <div class="col-2"></div>
        </div>
    </nav>

    <div class="container my-3">
        <form method="post" class="container row">
            <div class="col-lg-5">
                <div class="form-group mb-2">
                    <label class="form-label mb-1 text-muted" for="penerima">Nama Penerima / Keluarga <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="penerima" name="penerima" placeholder="Nama Penerima / Keluarga" required>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label mb-1 text-muted" for="telp-penerima">Telepon Penerima / Keluarga <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" id="telp-penerima" name="telp_penerima" placeholder="000000000000" required>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label mb-1 text-muted" for="pengirim">Nama Pengirim <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" id="pengirim" name="pengirim" placeholder="Nama Pengirim" required>
                </div>

                <div class="form-group mb-2">
                    <label class="form-label mb-1 text-muted" for="telp-pengirim">Telepon Pengirim <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" id="telp-pengirim" name="telp_pengirim" placeholder="000000000000" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary" name="kirim">Kirim</button>
                </div>
            </div>
        </form>
    </div>

    <?php
        if (isset($_POST['kirim'])) {
            try {
                $nama_penerima = addslashes(htmlspecialchars($_POST['penerima']));
                $telp_penerima = addslashes(htmlspecialchars($_POST['telp_penerima']));
                $nama_pengirim = addslashes(htmlspecialchars($_POST['pengirim']));
                $telp_pengirim = addslashes(htmlspecialchars($_POST['telp_pengirim']));

                function generateAngkaAcak($length) {
                    $angka_acak = '';
                    for ($i = 0; $i < $length; $i++) {
                        // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                        $angka_acak .= mt_rand(0, 9);
                    }
                    return $angka_acak;
                }

                // Set timezone PHP ke Indonesia (WIB)
                date_default_timezone_set('Asia/Jakarta'); // Untuk WIB

                // Dapatkan waktu sekarang
                $current_time = date('Y-m-d H:i:s');

                // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                $angka_acak = generateAngkaAcak(7);
                $invoice = 'M' . $idmitramarketer . '-' . $idpoproduk . $angka_acak;
                $sql = $koneksi->query("INSERT INTO podropship VALUES (
                                            NULL, '$idadmin', '0',
                                            '0', '$idmitramarketer', '$idpoproduk',
                                            '$invoice', NULL, NULL,
                                            '$nama_pengirim', '$telp_pengirim',
                                            '$nama_penerima', '$telp_penerima',
                                            '-', NULL, NULL, NULL, 'PO Konin 2025',
                                            '-', NULL, NULL, NULL, NULL
                                        )
                                    ");
                if ($sql) {
                    $iddropship = $koneksi->insert_id;

                    $insongkir = $koneksi->query("INSERT INTO ongkir VALUES
                                                    (
                                                        NULL, '$iddropship', '$idadmin',
                                                        0, '$invoice', 'Konfirmasi Ongkir',
                                                        'Pre Order Marketer', NULL,
                                                        '$current_time', '$current_time'
                                                    )
                                                ");
                    if ($insongkir) {
                        echo "
                            <script>
                                alert('Data Berhasil Dikirim!')
                                location='formpo_konin.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data Gagal Dikirim!')
                                location='formpo_konin.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    }
                } else {
                    echo "
                        <script>
                            alert('Data Gagal Dikirim!')
                            location='pokonin2.php?id=$idpoproduk'
                        </script>
                    ";
                }
            } catch (Exception $e) {
                echo "
                    <script>
                        alert('500 Error Server!')
                        location='pokonin2.php'
                    </script>
                ";
                // echo "Error: " . $e->getMessage();
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>