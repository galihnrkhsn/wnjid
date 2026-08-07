<?php 
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';

    $invoice         = $_GET["id"];
    $berat           = $_GET["berat"];
    $idmitrareseller = $_SESSION['idmitrareseller'];

    $queryReseller   = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller = '$idmitrareseller'");
    $getReseller     = $queryReseller->fetch_assoc();

?>
  
<html lang="en">
<head>
    <title>Mitra <?= $dataUser['namamitra']?>| WNJ.ID</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
    <script type="text/javascript"> 
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
<body>
    <?php include "assets/components/Navbar/navbar2.php"; ?>
    <div class="container mt-3">
        <table id="myJudul" class="w3-table-all w3-centered">
            <tr>
                <th style="width:5%;"></th>
                <th style="width:90%;">Data Pengiriman</th>
                <th style="width:5%;"></th>
            </tr>
        </table>
    </div>
    <div class="container">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p align="right"><input type="radio" onclick="javascript:window.location.href='formpengiriman.phpid=<?php echo $invoice; ?>&berat=<?php echo $berat; ?>'; " checked="checked"> Dropship  <input type="radio" onclick="javascript:window.location.href='formpengiriman2.php?id=<?php echo $invoice; ?>&berat=<?php echo $berat; ?>'; "> Kirim Ke Alamat Pribadi</p>
                    <form method="post">
                        <div class="form-group">
                            <label>Nama Pengirim</label>
                            <input type="text" class="form-control" name="namapengirim" value="<?= $getReseller['namaagen'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Pengirim</label>
                            <input type="text" class="form-control" name="tlppengirim" value="<?= $getReseller['whatsapp'] ?>" required maxlength="17">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input type="text" class="form-control" name="namapenerima" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon Penerima</label>
                            <input type="text" class="form-control" name="tlppenerima" required maxlength="17">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Kode POS</label>
                            <input type="text" class="form-control" name="kodepos" value="<?= $getReseller['kodepos']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prov">Provinsi Tujuan</label><br>
                            <select class="form-control" id="prov" name="prov" required>
                                <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                                <?php
                                    $ambil  = $koneksi->query("SELECT * FROM tb_ro_provinces");
                                    
                                    while($row = $ambil->fetch_assoc()){
                                ?>
                                    <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                            <select class="form-control" id="kabupaten" name="kabupaten" required value=""></select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan Tujuan</label><br>
                            <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                        </div>
                        <div class="form-group">
                            <label for="berat">Berat (gram)</label><br>
                            <input class="form-control" id="berat" type="text" name="berat" value="<?php echo $berat; ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label for="kurir">Kurir</label><br>
                            <select class="form-control" id="kurir" name="kurir" required>
                                <option disabled='disabled' selected>~Pilih Kurir Pengiriman~</option>
                                <option value="OR|jne">JNE</option>
                                <option value="OR|tiki">TIKI</option>
                                <option value="OR|pos">POS INDONESIA</option>
                                <option value="OR|wahana">WAHANA</option>
                                <option value="OR|sicepat">SICEPAT</option>
                                <option value='OR|jnt'>J&T</option>
                                <option value='OR|lion'>LION</option>
                                <option value='OR|anteraja'>Anteraja</option>
                                <option value='OR|ide'>ID Express</option>
                                <optgroup label="Lainnya (Ongkir Manual)">
                                <option value='OM|idetruck'>ID Express Truck</option>
                                <option value='OM|jntcargo'>J&T Cargo</option>
                                <option value='OM|jtr'>JTR</option>
                                <option value='OM|Ahsan'>Ahsan</option>
                                <option value='OM|Baraka'>Baraka</option>
                                <option value='OM|Dakota'>Dakota</option>
                                <option value='OM|IndahCargo'>IndahCargo</option>
                                <option value='OM|Adam Cargo'>Adam Cargo</option>
                                <option value='OM|Pegasus'>Pegasus</option>
                                <option value='OM|Gosend'>GoSend</option>
                                <option value='OM|KALOG'>KALOG</option>
                                <option value='OM|Sentral'>Sentral</option>
                                <option value='OM|CMC KARGO'>CMC CARGO</option>
                                <option value='OM|Triplogic'>Triplogic</option>
                                <option value='OM|Ambil ke Pusat'>Ambil Ke Pusat</option>
                                <option value='OM|Disatukan'>Disatukan Paket Lainnya</option>
                            </select>
                        </div>		
                        <div class="form-group" id="ongkir">
                            <label for="layanan">Layanan</label><br>
                            <select class="form-control" name="layanan" id="layanan" >
                                <option value='layanan'>-kosong-</option>           
                            </select>
                            <label><font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font></label>
                        </div>		
				    </div>
                </div>
            </div>
        </div>
			
    <script type="text/javascript">
        $(document).ready(function(){
            $('#prov').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var provinsi = $('#prov').val();

                $.ajax({
                    type : 'GET',
                    url : 'cek_kabupaten2.php',
                    data :  'prov_id=' + provinsi,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kabupaten").html(data);
                    }
                });
            });
            
        
            $('#kabupaten').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var kabupaten = $('#kabupaten').val();

                $.ajax({
                    type : 'GET',
                    url : 'cek_kecamatan2.php',
                    data :  'kabupaten_id=' + kabupaten,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kecamatan").html(data);
                    }
                });
            });



            $("#kurir").change(function(){
                //Mengambil value dari option select provinsi asal, kabupaten, kurir, berat kemudian parameternya dikirim menggunakan ajax
                var asal = $('#asal').val();
                var kab = $('#kabupaten').val();
                var kec = $('#kecamatan').val();
                var kurir = $('#kurir').val();
                var berat = $('#berat').val();

                $.ajax({
                    type : 'POST',
                    url : 'cek_ongkir.php',
                    data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
                        $("#layanan").html(data);
                    }
                });
            });
        });
    </script>
    </div>
    

    <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
    </form>
    <br><br><br><br>

</body>

    <?php
        include "koneksi.php";
        if(isset($_POST['kirim'])){
        
            $namapengirim   = addslashes(htmlspecialchars($_POST["namapengirim"]));
            $tlppengirim    = addslashes(htmlspecialchars($_POST["tlppengirim"]));
            $namapenerima   = addslashes(htmlspecialchars($_POST["namapenerima"]));
            $tlppenerima    = addslashes(htmlspecialchars($_POST["tlppenerima"]));
            $alamat         = addslashes(htmlspecialchars($_POST["alamat"]));
            
            $provinsi_id    = $_POST["prov"];
            $result_explode = explode('|', $provinsi_id);
            $provinsi       = $result_explode[1];
            
            $kabupaten_id   = $_POST["kabupaten"];
            $result_explode = explode('|', $kabupaten_id);
            $kabupaten      = $result_explode[1];
            
            $kecamatan_id   = $_POST["kecamatan"];
            $result_explode = explode('|', $kecamatan_id);
            $kecamatan      = $result_explode[1];
    
            $layanan        = $_POST["layanan"];
            $result_explode = explode('|', $layanan);
            $layananku      = $result_explode[0]; 
    
            $ekspedisinya   = $_POST["kurir"];
            $result_explode = explode('|', $ekspedisinya);
            $om             = $result_explode[0];
            $ekspedisi      = $result_explode[1];
    
            if ($layanan == 'layanan') {
                echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
                echo "<script>location='formpengiriman.php?id=$_GET[id]&berat=$_GET[berat]'</script>";
                return false;
            }
            if ($layanan == '' && $om <> 'OM') {
                echo "<script>alert('Gagal Simpan, Jenis layanan masih kosong');</script>";
                echo "<script>location='formpengiriman.php?id=$_GET[id]&berat=$_GET[berat]'</script>";
                return false;
            }
            
            $berat = $_POST["berat"];
            
            $result_explode = explode('|', $layanan);
            $ongkir2 = $result_explode[1];
            $ongkir = (int) $ongkir2;
            
            $kodepos = $_POST["kodepos"];
            $invoice = $_GET["id"];
            $total = 0;
    
            $sql 		= "SELECT SUM(ordermitra.subtotal) AS subtotal, SUM(ordermitra.jumlah) AS jumlah, variants.jenis 
                            FROM ordermitra 
                            INNER JOIN variants ON variants.id = ordermitra.idproduk
                            WHERE invoice = '$invoice'";
            $query 		= $koneksi->query($sql);

            while($row = $query->fetch_assoc()){
                $total 	= $row['subtotal'];
                $jumlah = $row['jumlah'];
                $jenis 	= $row['jenis']; 
            }
            
            if ($jenis == 'Bundling Short') {
                if ($jumlah >= 3) {
                    // Hitung kelipatan 3
                    $diskonPerKelipatan = 35000; // Nilai diskon untuk setiap 3 qty
                    $jumlahKelipatan = floor($jumlah / 3); // Hitung berapa kali kelipatan 3
                    $totalDiskon = $jumlahKelipatan * $diskonPerKelipatan; // Total diskon
                    $total -= $totalDiskon; // Kurangi total dengan diskon
                }
            }

            $total = $total + $ongkir;
            $sqlcek         = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
            $query          = $koneksi->query($sqlcek);
            $pengirimancek  = $query->fetch_assoc();
    
            if ($pengirimancek) {
                $querydelete    = "DELETE FROM orderpengiriman WHERE invoice = '$invoice'";
                $sqldelete      = mysqli_query($koneksi, $querydelete); 
                $query          = "INSERT INTO orderpengiriman (idorderp,namapengirim,tlppengirim,namapenerima,tlppenerima,
                                    alamat,provinsi,kota,kecamatan,ekspedisi,layanan,berat,ongkir,
                                    dropship,kodepos,invoice,total,diskonramadhan,tgl) values 
                                    (null,'$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamat','$provinsi',
                                    '$kabupaten','$kecamatan','$ekspedisi','$layananku',
                                    '$berat','$ongkir','ya','$kodepos','$invoice','$total',0,NOW())"; 
                $sql = mysqli_query($koneksi, $query); 
            } else {
                $query = "INSERT INTO orderpengiriman (idorderp,namapengirim,tlppengirim,namapenerima,tlppenerima,
                            alamat,provinsi,kota,kecamatan,ekspedisi,layanan,berat,ongkir,dropship,kodepos,invoice,
                            total,diskonramadhan,tgl) values 
                            (null,'$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamat','$provinsi',
                            '$kabupaten','$kecamatan','$ekspedisi','$layananku','$berat','$ongkir','ya',
                            '$kodepos','$invoice','$total',0,NOW())";
                $sql = mysqli_query($koneksi, $query); 
            }
                    
            if ($sql == TRUE) {
                if (substr($invoice, 0, 1) == "F") {
                    echo "<script>alert('data berhasil ditambah');</script>";
                    echo "<script>location='detailorder_get.php?id=$invoice'</script>";
                } elseif (substr($invoice, 0, 2) == "RP") {
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='dataorder2.php?id=$invoice'</script>";
                } elseif ($jenis == 'Bundling Short') { 
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailordershort.php?id=$invoice'</script>";
                } elseif ($jenis == 'Flash') { 
                    echo "<script>alert('Data berhasil ditambah');</script>";
                    echo "<script>location='detailorder2.php?id=$invoice&jenis=$jenis'</script>";
                } else {
                    echo "<script>alert('data berhasil ditambah');</script>";
                    echo "<script>location='detailorder2.php?id=$invoice'</script>";
                }
            } else {
                echo "<script>alert('Gagal Simpan, Coba Lagi $namapengirim,$tlppengirim,$namapenerima,$tlppenerima,$alamat,$provinsi,$kabupaten,$kecamatan,$ekspedisi,$layananku,$berat,$ongkir,tidak,$kodepos,$invoice,$total');</script>";
                echo "<script>location='formpengiriman.php?id=$_GET[id]&berat=$_GET[berat]'</script>";
            }
        }
    ?>	
</html>
