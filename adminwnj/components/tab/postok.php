<b>Sisa Stok</b>
<div class="row">
    <?php
        $sql    = "SELECT * from pokategori where idpoproduk = '$idpoproduk' ORDER BY namakategori";
        $query  = $koneksi->query($sql);
        while($stok = $query->fetch_assoc()){
    ?>
        <div class="col-2">
            <?php echo $stok['namakategori']; ?>
        </div>
        <div class="col-2">
            (<?php echo $stok['stok']; ?>)
        </div>
        <br>
    <?php } ?>
</div>
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
                                                pomitra.idpo,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga 
                                            FROM pomitra 
                                            INNER JOIN pokategori on pokategori.idpo=pomitra.idpo 
                                            INNER JOIN podetail on podetail.idpodetail=pomitra.idpodetail     
                                            WHERE pomitra.invoice='$invoice' 
                                            ORDER BY podetail.idpodetail asc
                                        ");
                while($data = mysqli_fetch_array($sql)){
            ?>
                <tr>
                    <td class="align-middle"><?php echo $no++; ?></td>
                    <td class="align-middle">
                        <form method="POST">
                            <input type="hidden" name="harga" value=<?php echo $data['harga']; ?>>
                            <input type="hidden" name="idpo" value=<?php echo $data['idpo']; ?>>
                            <input type="hidden" name="idpomitra" value=<?php echo $data['idpomitra']; ?>>
                            <input type="hidden" name="jumlahsebelum" value=<?php echo $data['jumlah']; ?>>
                            <div class="input-group mb-3">
                                <button type="submit" class="btn btn-primary" name="tambah">+</button>&nbsp;  
                                <input type="number" min="0" name="jmlh" class="form-control">&nbsp;
                                <button type="submit" class="btn btn-danger" name="kurang">-</button>
                            </div>
                        </form>
                    </td>   
                    <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                    <td class="align-middle"><?php echo $data['variant']; ?></td>                    
                </tr>
            <?php } ?>
            <?php 
                if(isset($_POST["tambah"])){
                    $idpomitra      = $_POST['idpomitra'];
                    $idpo           = $_POST["idpo"];
                    $jmlh           = $_POST["jmlh"];
                    $harga          = $_POST["harga"];    
                    $total          = $jmlh*$harga;
                            
                    $sql            = "SELECT stok FROM pokategori WHERE idpo='$idpo'";
                    $query          = $koneksi->query($sql);
                    $sisa           = $query->fetch_assoc();

                    if($sisa['stok'] >= $jmlh){
                        $koneksi->query("UPDATE pomitra set jumlah = jumlah+'$jmlh', total=jumlah*'$harga' WHERE idpomitra = '$idpomitra';");
                        $koneksi->query("UPDATE pokategori set stok = stok-'$jmlh' WHERE idpo = '$idpo'");

                        echo "<script>alert('data berhasil diubah');</script>";
                        echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                    } else {
                        echo "<script>alert('Stok tidak mencukupi');</script>";
                        echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                    }
                } elseif (isset($_POST["kurang"])){
                    $idpomitra      = $_POST['idpomitra'];
                    $idpo           = $_POST["idpo"];
                    $jmlh           = $_POST["jmlh"];
                    $harga          = $_POST["harga"];    
                    $total          = $jmlh*$harga;
                    $jumlahsebelum  = $_POST["jumlahsebelum"];
                    $subjumlah      = $jumlahsebelum - $jmlh;

                    if ($subjumlah<0) {
                        echo "<script>alert('Stok tidak mencukupi');</script>";
                        echo "<script>location='ubahpo.php?invoice=$invoice';</script>";
                    } else {
                        $koneksi->query("UPDATE pomitra set jumlah=jumlah-'$jmlh',total=jumlah*'$harga' where idpomitra='$idpomitra';");
                        $koneksi->query("UPDATE pokategori set stok=stok+'$jmlh' where idpo='$idpo'");

                        echo "<script>alert('data berhasil diubah ')</script>";
                        echo "<script>location='ubahpo.php?invoice=$invoice'</script>";
                    }
                }
            ?>
        </tbody>
    </table>
</div>