<div class="table-responsive">
    <table class="table table-striped" id="tb_listpo_artikel">
        <thead>
            <tr>
                <th>No</th>
                <th>Ubah Qty</th>
                <th>Qty</th>
                <th>Nama Barang</th> 
            </tr>
        </thead>
        <tbody>
            <?php 
                $no     = 1;
                $sql    = mysqli_query($koneksi, 
                                            "SELECT 
                                                pokategori.namakategori,
                                                podetail.variant,
                                                pomitra.idpomitra,
                                                pomitra.idpodetail,
                                                pomitra.custom,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga 
                                            FROM pomitra 
                                            INNER JOIN pokategori on pokategori.idpo=pomitra.idpo 
                                            INNER JOIN podetail on podetail.idpodetail=pomitra.idpodetail     
                                            WHERE pomitra.invoice = '$invoice' 
                                            ORDER BY podetail.idpodetail asc
                                        ");

                while($data = mysqli_fetch_array($sql)){
            ?>
                <tr>
                    <td class="align-middle"><?php echo $no++; ?></td>
                    <td class="align-middle">
                        <form method="POST">
                            <input type="hidden" name="harga" value=<?php echo $data['harga']; ?>>
                            <input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
                            <input type="hidden" name="invoice" value=<?php echo $data['invoice']; ?>>
                            <div class="input-group mb-3">
                                <input type="number" min="0" name="jmlh" class="form-control">
                                <button type="submit" class="btn btn-success" name="edit">Ubah</button> 
                            </div>
                        </form>
                    </td>       
                    <?php 
                        if(isset($_POST["edit"])){
                            $idpomitra  = $_POST['idpomitra'];
                            $invoice    = $_POST['invoice'];
                            $jmlh       = $_POST["jmlh"];
                            $harga      = $_POST["harga"];    
                            $total      = $jmlh*$harga;
                            $sql        = $koneksi->query("UPDATE pomitra SET jumlah = '$jmlh', total = '$total' WHERE idpomitra='$idpomitra'");

                            if ($sql) {
                                echo "<script>alert('data berhasil diubah');</script>";
                                echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                            } else {
                                echo "<script>alert('data gagal diubah');</script>";
                                echo "<script>location='ubahpo.php?invoice=$invoice';</script>";      
                            }                     
                        }
                    ?>                                            
                    <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                    <td class="align-middle"><?php echo $data['variant']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form method="POST">		
                        <div style="padding: 0 15px;">
                            <ul class="nav nav-tabs">
                                <?php
                                    $noo    = 1;
                                    $sql    = "SELECT * FROM bukapo_tab 
                                                WHERE idpoproduk = '$idpoproduk' order by id asc";
                                    $query  = $koneksi->query($sql);
                                    while($row = $query->fetch_assoc()){
                                ?>	
                                    <?php if ($noo==1): ?>
                                        <li class="active"><a data-toggle="tab" href="#home<?= $row['id']; ?>"  class="nav-item nav-link active"><?= $row['nama_tab']; ?></a></li>
                                    <?php else: ?>				
                                        <li class=""><a data-toggle="tab" href="#home<?= $row['id']; ?>" class="nav-item nav-link"><?= $row['nama_tab']; ?></a></li>
                                    <?php endif ?>
                                    <?php $noo++; ?>	
                                <?php } ?>
                            </ul>
                            <br>
                            <?php
                                $firstLoopIds = [];
                                $frstloop = $koneksi->query("SELECT idpodetail, idmitra, idmitraagen, idmitrareseller, idmitramarketer 
                                                                FROM pomitra 
                                                                WHERE invoice = '$invoice'
                                                            ");
                                while ($dfrstloop = $frstloop->fetch_assoc()) {
                                    $firstLoopIds[]     = $dfrstloop['idpodetail'];
                                    $idadmin            = $dfrstloop['idmitra'];
                                    $idmitraagen        = $dfrstloop['idmitraagen'];
                                    $idmitrareseller    = $dfrstloop['idmitrareseller'];
                                    $idmitramarketer    = $dfrstloop['idmitramarketer'];
                                }

                                if (!empty($firstLoopIds)) {
                                    $idpodetailString = implode(separator: ",", array: $firstLoopIds);
                            ?>
                            <div class="tab-content">
                                <?php
                                    $no         = 1;
                                    $sql_isi    = "SELECT * FROM bukapo_tab WHERE idpoproduk = '$idpoproduk' order by id asc";
                                    $query_isi  = $koneksi->query($sql_isi);
                                    while($row_isi = $query_isi->fetch_assoc()){
                                        $id_awal    = $row_isi['id_awal'];
                                        $id_akhir   = $row_isi['id_akhir'];		
                                ?>	
                                    <?php if ($no==1): ?>
                                        <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade active show in">
                                    <?php else: ?>
                                        <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade ">					
                                    <?php endif ?>		
                                        <?php
                                            $no++;
                                            $sql_variant    = "SELECT * FROM poproduk 
                                                                inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk
                                                                inner join podetail on pokategori.idpo=podetail.idpo
                                                                where podetail.idpodetail NOT IN ($idpodetailString) AND poproduk.idpoproduk='$idpoproduk' and (podetail.idpodetail BETWEEN '$id_awal' AND '$id_akhir') order by pokategori.idpo asc";
                                            $query_variant  = $koneksi->query($sql_variant);
                                                while($row_variant = $query_variant->fetch_assoc()){
                                        ?>
                                            <div class="form-group">
                                                <label><?php echo $row_variant['variant']; ?></label>
                                                <input type="hidden" name="idpodetail[]" value="<?php echo $row_variant['idpodetail']; ?>">
                                                <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
                                            </div>	
                                        <?php } ?>
                                    </div>
                                <?php } ?>	
                            </div>
                            <?php 
                                }
                                if($data['jumlah']<1){
                                    echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                                } else {
                                    echo "# ";
                                }
                            ?>
                        </div>
                    </form>
                    <?php
                        if(isset($_POST["save"])){
                            include "koneksi.php";
                            date_default_timezone_set('Asia/Jakarta');
                            $today      = date("s");
                            $waktu      = date("H:i:s");
                            $idpodetail = $_POST["idpodetail"];
                            $jmlh       = $_POST["jmlh"];
                                
                            $jumlah_dipilih = count($jmlh);
                            $subtotal       = 0;  
                            $total          = 0;
                            $jmlhakhir      = 0;
                            $invoice        = $_GET['invoice'];

                            $idadmin            = empty($idadmin) ? "NULL" : "'$idadmin'";
                            $idmitraagen        = empty($idmitraagen) ? "NULL" : "'$idmitraagen'";
                            $idmitrareseller    = empty($idmitrareseller) ? "NULL" : "'$idmitrareseller'";
                            $idmitramarketer    = empty($idmitramarketer) ? "NULL" : "'$idmitramarketer'";

                            try {
                                for($x=0;$x<$jumlah_dipilih;$x++){
                                    if ($jmlh[$x] == 0 || $jmlh[$x] == "" || $jmlh[$x] == null) {
                                        continue;
                                    }
                                    $query_variant  = "SELECT podetail.harga, podetail.idpo
                                                        FROM podetail
                                                        WHERE podetail.idpodetail='$idpodetail[$x]'";
                                    $sql_variant    = mysqli_query($koneksi, $query_variant);  
                                    $data_variant   = mysqli_fetch_array($sql_variant);
                                    $harga          = $data_variant['harga'];
                                    $idpo           = $data_variant['idpo'];
                                    $total          = $jmlh[$x]*$harga;
                                    $tot            = $total;
                                    $jmlhakhir      += $jmlhakhir+$jmlh[$x];
                                    $tot            = 0;
    
                                    $sql            = $koneksi->query("INSERT into pomitra (idpomitra,idmitra,idmitraagen,idmitrareseller,idmitramarketer,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) 
                                                                        values
                                                                            (null,$idadmin,$idmitraagen,$idmitrareseller,$idmitramarketer,'$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')"); 
                                }
                                echo "<script>alert('data berhasil dikirim');</script>";
                                echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                            } catch (Exception $e) {
                                $msg = $e->getMessage();
                                $escapedMessage = addslashes($msg);
                                echo "<script>alert('Terjadi kesalahan! Data gagal dikirim. Detail: {$escapedMessage}');</script>";
                                echo "<script>location='ubahpo?invoice=$invoice';</script>";	
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>