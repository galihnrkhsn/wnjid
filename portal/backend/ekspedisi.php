<?php
    include '../koneksi.php';
    if (isset($_POST['update_resi'])) {
        $tgl = $_POST['tgl'];
        $eks = $_POST['eks'];
        if (isset($_POST['checked_ids'])) { // Memeriksa apakah ada checkbox yang dicentang
            try {
                // Proses setiap item yang dipilih
                foreach ($_POST['checked_ids'] as $idlogistik) {
                    // Pastikan hanya data yang relevan yang diproses
                    $noresi         = isset($_POST['noresi'][$idlogistik]) ? $_POST['noresi'][$idlogistik] : null;
                    $biayakirim     = isset($_POST['biayakirim'][$idlogistik]) ? $_POST['biayakirim'][$idlogistik] : null;
                        // Update data berdasarkan idlogistik yang dipilih
                        $sql = $koneksi->prepare("UPDATE logistik3 SET noresi = ?, biayakirim = ? WHERE idlogistik = ?");
                        $sql->bind_param("ssi", $noresi, $biayakirim, $idlogistik); // Binding parameters
                        $sql->execute();
    
                        // Update data di tabel t_user jika diperlukan
                        $query = $koneksi->prepare("UPDATE t_user SET resi_pengiriman = ?, ongkir = ? WHERE idlogistik = ?");
                        $query->bind_param("sii", $noresi, $biayakirim, $idlogistik); // Binding parameters
                        $query->execute();
                }

                if ($query) {
                    // Redirect setelah berhasil
                    echo "
                        <script>
                            alert('Data berhasil diupdate!');
                            location='../ekspedisi.php?eks=$eks&tgl=$tgl.php';
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal diupdate!');
                            location='../ekspedisi.php?eks=$eks&tgl=$tgl.php';
                        </script>
                    ";

                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "
                <script>
                    alert('Tidak ada data yang dipilih untuk diupdate.');
                    location='../ekspedisi.php?eks=$eks&tgl=$tgl.php';
                </script>
            ";
        }
    }
    if (isset($_POST['terkirim'])) {
        $tgl = $_POST['tgl'];
        $eks = $_POST['eks'];
        if (isset($_POST['checked_ids'])) { // Memeriksa apakah ada checkbox yang dicentang
            try {
                // Proses setiap item yang dipilih
                foreach ($_POST['checked_ids'] as $idlogistik) {
                    // Pastikan hanya data yang relevan yang diproses
                    $status         = 'Terkirim';

                    // Update data berdasarkan idlogistik yang dipilih
                    $sql = $koneksi->prepare("UPDATE logistik3 SET status = ? WHERE idlogistik = ?");
                    $sql->bind_param("si", $status, $idlogistik); // Binding parameters
                    $sql->execute();
                    if ($sql) {
                        // Redirect setelah berhasil
                        echo "
                            <script>
                                alert('Data berhasil diupdate!');
                                location='../surat_jalan_eks.php?eks=$eks&tgl=$tgl';
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal diupdate!');
                                location='../surat_jalan_eks.php?eks=$eks&tgl=$tgl';
                            </script>
                        ";
    
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "
                <script>
                    alert('Tidak ada data yang dipilih untuk diupdate.');
                    location='../surat_jalan_eks.php.php?eks=$eks&tgl=$tgl.php';
                </script>
            ";
        }
    } elseif (isset($_POST['belum_terkirim'])) {
        $tgl = $_POST['tgl'];
        $eks = $_POST['eks'];
        if (isset($_POST['checked_ids'])) { // Memeriksa apakah ada checkbox yang dicentang
            try {
                // Proses setiap item yang dipilih
                foreach ($_POST['checked_ids'] as $idlogistik) {
                    // Pastikan hanya data yang relevan yang diproses
                    $status         = NULL;

                    // Update data berdasarkan idlogistik yang dipilih
                    $sql = $koneksi->prepare("UPDATE logistik3 SET status = ? WHERE idlogistik = ?");
                    $sql->bind_param("si", $status, $idlogistik); // Binding parameters
                    $sql->execute();
                    if ($sql) {
                        // Redirect setelah berhasil
                        echo "
                            <script>
                                alert('Data berhasil diupdate!');
                                location='../surat_jalan_eks.php?eks=$eks&tgl=$tgl';
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal diupdate!');
                                location='../surat_jalan_eks.php?eks=$eks&tgl=$tgl';
                            </script>
                        ";
    
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "
                <script>
                    alert('Tidak ada data yang dipilih untuk diupdate.');
                    location='../surat_jalan_eks.php?eks=$eks&tgl=$tgl.php';
                </script>
            ";
        }
    }
?>