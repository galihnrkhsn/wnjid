<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $iddropship=$_GET["id"];      

    $querynamapo = "SELECT poproduk.namapo 
                      FROM podropship 
                      JOIN poproduk ON poproduk.idpoproduk = podropship.idpoproduk 
                      WHERE podropship.idpoproduk='".$idpoproduk."'";
    $sqlnamapo = mysqli_query($koneksi, $querynamapo);  
    $datanamapo = mysqli_fetch_array($sqlnamapo); 
  
    $namapo=$datanamapo['namapo'];
  
  
    $query_ds = "SELECT podropship.namapengirim, 
                        podropship.tlppengirim, 
                        podropship.namapenerima, 
                        podropship.tlppenerima, 
                        podropship.alamatpenerima,
                        podropship.invoice,
                        podropship.idpoproduk,
                        tb_ro_provinces.province_name as provinsi,
                        tb_ro_cities.city_name as kota,
                        tb_ro_subdistricts.subdistrict_name as kecamatan 
  
                        FROM podropship 
                        LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                        LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                        LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                        WHERE podropship.iddropship='".$iddropship."'";
    $sql_ds = mysqli_query($koneksi, $query_ds);  
    $data_ds = mysqli_fetch_array($sql_ds); 
  
  $invoice = $data_ds['invoice'];
  $idpoproduk = $data_ds['idpoproduk'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <h1>Data Dropship</h1>
        <strong>Pengirim</strong>
        <p><?= $data_ds['namapengirim']; ?> / <?= $data_ds['tlppengirim']; ?></p>

        <strong>Penerima</strong>
        <p><?= $data_ds['namapenerima']; ?> / <?= $data_ds['tlppenerima']; ?></p>

        <strong>Alamat Penerima</strong>
        <p><?= $data_ds['alamatpenerima']; ?></p>
        <p><?= $data_ds['provinsi']; ?>, <?= $data_ds['kota']; ?>, <?= $data_ds['kecamatan']; ?></p>

        <h3>Tambah Variant Baru</h3>
        <label>Jumlah Variant</label>
        <div class="container">  
            <form method="post">  
                <div class='form-group row'>    
                    <div class="col-sm-3">    
                        <input type="number" class="form-control" name="jumlah" min=0 required>
                    </div>
                    <div class="col-sm-3">    
                        <button type="submit" name="kirim" class="btn btn-info">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php 
            if(isset($_POST['kirim'])){
                $jumlah = $_POST['jumlah'];
        ?>    
            <form method="POST">
                <?php for ($x = 0; $x < $jumlah; $x++) { ?>
                    <div class="row">
                        <div class="col-6"> 
                            <label>Pilih Variant</label>
                            <select style="width: auto;" class="form-control" name="idpodetail[]" required>
                                <option value="">~ Pilih Variant ~</option>
                                <?php 
                                    $ambil=$koneksi->query("SELECT podetail.variant, podetail.idpodetail 
                                                            FROM pomitra
                                                            JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                            where pomitra.invoice='$invoice'
                                                            and pomitra.jumlah > 0
                                                            "); 
                                    while($data=$ambil->fetch_assoc()){
                                ?>
                                <option value='<?php echo $data['idpodetail']; ?>'><?php echo $data['variant']; ?></option>
                                <?php } ?>  
                            </select> 
                        </div> 
                        <div class="col-3">  
                            <label>Jumlah</label>
                            <input type="number" name="jumlah[]" class="form-control" min=0 required>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-6">
                    <button class="btn btn-success mt-4 mb-5" type="submit" name="save">Simpan</button> 
                </div>
            </form>
        <?php } ?>       

        <?php
            if(isset($_POST["save"])){
                $idpodetail=$_POST["idpodetail"];
                $jumlah=$_POST["jumlah"];
                $jmlh=count($idpodetail);
                date_default_timezone_set('Asia/Jakarta');
                $today = date("Y-m-d H:i:s");   

                $sql_ds = mysqli_query($koneksi, "SELECT iddropship FROM podropship order by iddropship desc limit 1");
                $data = mysqli_fetch_array($sql_ds);
                $no=$data['iddropship'];
                $ab=1;
                $nobaru=$no+$ab;
                $no_ds = $invoice.'-'.$iddropship;

                $sql_update = $koneksi->query("UPDATE podropship set no_ds='$no_ds' WHERE iddropship= '$iddropship'"); 
                for($x=0;$x<$jmlh;$x++){
                    $sql = $koneksi->query("INSERT INTO pods (id,no_ds,invoice,idpodetail,jumlah,waktu) 
                                            values (null,'$no_ds','$invoice','$idpodetail[$x]','$jumlah[$x]','$today') ");    
                }
                if ($sql) {
                    echo "<script>alert('Data Berhasil Disimpan');</script>";
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                } else {
                    echo "<script>location='detail_ds?no_ds=$no_ds';</script>";
                }
            }    
        ?>   
    </div>

    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>