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
                    <td class="align-middle"><?php echo $data['variant']; ?>
                        <?php if ($data['idpodetail']==8920 or $data['idpodetail']==8921): ?>
                            <form method="POST">
                                <input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="custom" value="<?php echo $data['custom']; ?>">
                                    <button class="btn btn-outline-primary" type="submit" name="ubahvoal">Ubah</button>
                                </div>
                            </form>
                            <?php 
                                if(isset($_POST["ubahvoal"])){                                                        
                                    $idpomitra  = $_POST['idpomitra'];
                                    $custom     = $_POST["custom"]; 
                                    $sql        = $koneksi->query("UPDATE pomitra set custom = '$custom' WHERE idpomitra = '$idpomitra'");
                            
                                    if ($sql) {
                                        echo "<script>alert('data berhasil diubah');</script>";
                                        echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                                    } else {
                                        echo "<script>alert('data gagal diubah');</script>";
                                        echo "<script>location='ubahpo.php?invoice=$invoice';</script>";      
                                    }                 
                                }
                            ?> 
                        <?php endif ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>>