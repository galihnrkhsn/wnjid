
<form method="post">
    <table class="table table-striped" id="tbmaximus">
        <thead>
            <tr>       
                <th>No</th>
                <th>Check</th>
                <th>Invoice</th>
                <th>Nama DB</th>
                <th>Sub DB</th>
                <th>Kemitraan</th>
                <th>Status PO</th>
                <th>Proses PO</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $idpoproduk = $_GET["id"];
                $datapo     = $koneksi->query("SELECT 
                                            admin_mitra.idadmin,
                                            admin_mitra.namamitra,
                                            poproduk.namapo,
                                            mitraagen.namaagen AS agen,
                                            mitrareseller.namaagen AS reseller,
                                            mitramarketer.namaagen AS marketer,
                                            pomitra.idpomitra,
                                            pomitra.invoice,
                                            pomitra.status,
                                            pomitra.tgl,
                                            pomitra.waktu,
                                            pomitra.proses,
                                            pomitra.is_custom,
                                            poproduk.namapo,
                                            poproduk.idpoproduk
                                        FROM
                                            pomitra
                                                LEFT JOIN
                                            mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                                LEFT JOIN
                                            mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                                LEFT JOIN
                                            mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                                LEFT JOIN
                                            admin_mitra ON pomitra.idmitra = admin_mitra.idadmin
                                                OR mitraagen.idadmin = admin_mitra.idadmin
                                                OR mitrareseller.idadmin = admin_mitra.idadmin
                                                OR mitramarketer.idadmin = admin_mitra.idadmin
                                                INNER JOIN
                                            poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
                                        WHERE
                                            pomitra.idpoproduk = '$idpoproduk'
                                        GROUP BY pomitra.invoice
                                        ORDER BY pomitra.idpomitra DESC
                                    ");
                $no = 1;
                while($tampilkan = $datapo->fetch_assoc()){
            ?>
                <tr>
                    <td>
                        <strong><?php echo $no++; ?></strong>
                    </td>  
                    <td>
                        <input type="checkbox" class="check-item" name="update[]" id="update" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                    </td>
                    <td>
                        <?php if ($idpoproduk == '331') : ?>                        	
                            <a href="detailinvoice2.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                                <?php echo $tampilkan['invoice']; ?>   
                            </a>
                        <?php elseif ($idpoproduk == '355' || $idpoproduk == '361' || $idpoproduk == '366' || $idpoproduk == '371' || $idpoproduk == '374' || $jenis_po == 'PO Set' || $tampilkan['is_custom'] == 'BUNDLING 3') : ?>
                            <a href="detailinvoicebundling.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                                <?php echo $tampilkan['invoice']; ?>   
                        <?php elseif ($jenis_po == 'PO Bundling 2' || $jenis_po == 'PO Bundling 5') : ?>
                            <a href="detailinvoicebundling2.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                                <?php echo $tampilkan['invoice']; ?>   
                        <?php else : ?>
                            <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>">
                                <?php echo $tampilkan['invoice']; ?>   
                            </a>
                        <?php endif; ?>
                        <?php if(substr($tampilkan['invoice'],2,1)=="P"): ?>
                            (Polos)
                        <?php endif; ?>
                        <?php if(substr($tampilkan['invoice'],0,2)=="MH" and substr($tampilkan['invoice'],2,1)<>"P") : ?>
                            (Custom)
                        <?php endif; ?>                       
                    </td>   
                    <td><?php echo $tampilkan['namamitra']; ?></td>
                    <td>
                        <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?>
                    </td>
                    <td class="align-middle">
                        <?php 
                            if ($tampilkan['agen'] <> '') { 
                                echo "<div class='badge bg-info text-white rounded-pill'>Agen</div>";
                            }

                            if($tampilkan['reseller'] <> '') { 
                                echo "<div class='badge bg-warning text-white rounded-pill'>Reseller</div>";
                            }

                            if($tampilkan['marketer'] <> '') { 
                                echo "<div class='badge bg-danger text-white rounded-pill'>Marketer</div>";
                            }

                            if($tampilkan['agen'] == '' and $tampilkan['reseller'] == '' and $tampilkan['marketer'] == '' ) { 
                                echo "<div class='badge bg-success text-white rounded-pill'>Distributor</div>";
                            } 
                        ?>
                    </td>

                    <td>
                        <?php if ($tampilkan['status']=='Belum DP'): ?>
                            <div class="badge bg-danger text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>
                        <?php elseif ($tampilkan['status']=='Belum Acc DB'): ?>
                            <div class="badge bg-warning text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>
                        <?php 
                            elseif ($tampilkan['status']=='Sudah Confirm DP' or
                                $tampilkan['status']=='Sudah Confirm Pelunasan' or
                                $tampilkan['status']=='Sudah Konfirmasi Pembayaran 1' or
                                $tampilkan['status']=='Sudah Konfirmasi Pembayaran 2' or
                                $tampilkan['status']=='Sudah Konfirmasi Pembayaran 3' or
                                $tampilkan['status']=='Sudah Confirm Payment 1' or
                                $tampilkan['status']=='Sudah Confirm Payment 2' or
                                $tampilkan['status']=='Sudah Confirm Payment 3' or
                                $tampilkan['status']=='Sudah DP' or
                                $tampilkan['status']=='Approve DB'
                            ): 
                        ?>
                            <div class="badge bg-info text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>
                        <?php 
                            elseif ($tampilkan['status']=='Lunas' or
                                $tampilkan['status']=='Sudah Payment 1' or
                                $tampilkan['status']=='Sudah Payment 2' or
                                $tampilkan['status']=='Sudah Payment 3'
                            ) : 
                        ?>
                            <div class="badge bg-success text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>                  
                        <?php else: ?>
                            <?php echo $tampilkan['status']; ?>
                        <?php endif ?>  
                    </td>
                    <td>
                        <?php if (!$tampilkan['proses']): ?>
                            <div class="badge bg-danger text-white rounded-pill">
                                Belum Diproses
                            </div>
                        <?php else: ?>
                            <div class="badge bg-primary text-white rounded-pill">
                                <?= $tampilkan['proses'] ?>
                            </div>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $tampilkan['tgl'] ?> / <?= $tampilkan['waktu'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <button type="submit" class="btn btn-danger" name="hapusinvoice" onclick="return confirm('Yakin Akan Hapus Pesanan?');"><span class="fas fa-trash"></span> Hapus</button>  
    <button type="submit" class="btn btn-danger" name="kembali_ke_stok" onclick="return confirm('Yakin Akan Hapus Pesanan?');"><span class="fas fa-trash"></span> Kembali ke stok</button>  
    <button type="submit" class="btn btn-primary" name="prosesinvoice"  onclick="return confirm('Yakin Akan Proses Pesanan?');"><span class="fas fa-plus"></span> Proses PO</button>  
    <button type="submit" class="btn btn-warning" name="batalproses"  onclick="return confirm('Yakin Akan Batal Proses Pesanan?');"><span class="fas fa-minus"></span> Batal Proses</button>   
    <button type="submit" class="btn btn-primary" name="accDb"  onclick="return confirm('Yakin Akan ACC Sub-DB?');"><span class="fas fa-plus"></span> ACC DB</button>  
</form>
<?php 
    if (isset($_POST["hapusinvoice"])) {
        $invoices       = $_POST['update'];
        $jumlah_dipilih = count($invoices);
        $success        = true;
        
        for ($x = 0; $x < $jumlah_dipilih; $x++) {
            $invoice        = $koneksi->real_escape_string($invoices[$x]); // Mencegah SQL Injection
            $query          = $koneksi->query("DELETE FROM pomitra WHERE invoice='$invoice'");
            $deleteOngkir   = $koneksi->query("DELETE FROM ongkir WHERE invoice='$invoice'");
            
            if (!$query || !$deleteOngkir) {
                $success = false; // Menandai bahwa ada yang gagal
                break; // Keluar dari loop jika ada yang gagal
            }
        }

        if ($success) {
            echo "<script>alert('Data berhasil dihapus');</script>";
        } else {
            echo "<script>alert('Data gagal dihapus');</script>";
        }

        echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
    }
    if(isset($_POST["prosesinvoice"])){
        $invoice        = $_POST['update'];
        $jumlah_dipilih = count($invoice);
        for($x = 0; $x < $jumlah_dipilih; $x++) {
            $sqlnya = $koneksi->query("UPDATE pomitra set proses = 'Proses' WHERE invoice = '$invoice[$x]'");
        }
        if ($sql) {
            echo "<script>alert('data berhasil diproses');</script>";
            echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
        } else {
            echo "<script>alert('data gagal diproses');</script>";
            echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
        }
    } 
    if (isset($_POST['accDb'])) {
        $invoice    = $_POST['update'];
        $updated    = 0;
        $skipped    = 0;

        $stmt       = $koneksi->prepare("UPDATE pomitra SET status = 'Approve DB' WHERE invoice = ?");
        foreach ($invoice as $inv) {
            if (substr($inv, 0, 1) !== 'A') {
                $skipped++;
                continue;
            }

            $stmt->bind_param("s", $inv);
            if ($stmt->execute()) {
                $updated++;
            } else {
                echo "<script>location='listpokolibri.php?id={$idpoproduk}'</script>";
                die("Query preparation failed: " . $koneksi->error);
            }
        }

        $stmt->close();

        echo "<script>alert('{$updated} data berhasil diupdate, {$skipped} data dilewati');</script>";
        echo "<script>location='listpokolibri.php?id={$idpoproduk}'</script>";
    }
    if (isset($_POST["kembali_ke_stok"])) {
        $invoices       = $_POST['update'];
        $jumlah_dipilih = count($invoices);
        $success        = true;
        
        for ($x = 0; $x < $jumlah_dipilih; $x++) {
            $invoice    = $koneksi->real_escape_string($invoices[$x]); // Mencegah SQL Injection
            $sql        = $koneksi->query("SELECT
                                            pomitra.*,
                                            poproduk.*,
                                            pokategori.*,
                                            podetail.*
                                        FROM
                                            pomitra
                                            INNER JOIN
                                            poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                            INNER JOIN
                                            pokategori ON pokategori.idpo = pomitra.idpo
                                            INNER JOIN
                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                        WHERE pomitra.invoice = '$invoice'
                                    ");
                                    
            while ($row = $sql->fetch_assoc()) {
                $idpo   = $row['idpo'];
                $jumlah = $row['jumlah'];
                $backStok = $koneksi->query("UPDATE pokategori SET stok = stok + $jumlah WHERE idpo = '$idpo'");
            }

            $query          = $koneksi->query("DELETE FROM pomitra WHERE invoice = '$invoice'");
            $deleteOngkir   = $koneksi->query("DELETE FROM ongkir WHERE invoice = '$invoice'");
            
            if (!$backStok || !$query || !$deleteOngkir) {
                $success = false; // Menandai bahwa ada yang gagal
                break; // Keluar dari loop jika ada yang gagal
            }
        }

        if ($success) {
            echo "<script>alert('Data berhasil dihapus');</script>";
        } else {
            echo "<script>alert('Data gagal dihapus');</script>";
        }

        echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
    }

    if(isset($_POST["batalproses"])){
        $invoice        = $_POST['update'];
        $jumlah_dipilih = count($invoice);
       
        for($x = 0; $x < $jumlah_dipilih; $x++) {
            $sqlnya = $koneksi->query("UPDATE pomitra set proses= NULL WHERE invoice = '$invoice[$x]'");
        }

        if ($sql) {
            echo "<script>alert('data berhasil dibatalkan');</script>";
            echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
        } else {
            echo "<script>alert('data gagal dibatalkan');</script>";
            echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
        }
    }
?>