<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
$jenisnya = $_GET['jenis'];

 $result_explode = explode('|', $jenisnya);
        $jenis=$result_explode[0];
        $invoice=$result_explode[1];

$ambil_pengiriman=$koneksi->query("SELECT podetail.berat, pomitra.jumlah 
                        FROM pomitra
                        JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                        WHERE pomitra.invoice = '$invoice'
                        ");
                  while($row_pengiriman=$ambil_pengiriman->fetch_assoc()){
                    $berat = $row_pengiriman['berat'];
                    $jumlah = $row_pengiriman['jumlah'];
                    $total_berat += $berat*$jumlah;
                  }
 ?>

 <?php if ($jenis=='Dropship'): ?>
    <input type="hidden" name="jenisnya" value="<?= $jenis; ?>">
         <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" name="alamat" required></textarea>
      </div>

      <div class="form-group">
        <label for="prov">Provinsi Tujuan</label><br>
        <select class="form-control" id="prov" name="prov" required>
           <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
              <?php
                $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces");
                  while($row=$ambil->fetch_assoc()){
              ?>
           <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
          <?php } ?>
         </select>
      </div>
   

                      <div class="form-group">
                        <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                        <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                      </div>
                      
                        <div class="form-group">
                        <label for="kecamatan">Kecamatan Tujuan</label><br>
                        <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                      </div>
                      
                        <div class="form-group">
                        <label for="berat">Berat (gram)</label><br>
                        <input class="form-control" id="berat" type="number" name="berat" value="<?= $total_berat; ?>" readonly />
                      </div>
                      
                      <div class="form-group">
                        <label for="kurir">Kurir</label><br>
                        <select class="form-control" id="kurir" name="kurir" required>
                          <option disabled='disabled' value="" selected>~Pilih Kurir Pengiriman~</option>
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
                          <option value="layanan">-kosong-</option>           
                        </select>
                        <label>
                          <font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font>
                        </label>
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
              url : 'cek_ongkirpusat.php',
              data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
          $("#layanan").html(data);
        }
            });
    });
  });
</script>

 <?php endif ?>


  <?php if ($jenis=='Pribadi'): ?>
    <input type="hidden" name="jenisnya" value="<?= $jenis; ?>">
         <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" name="alamat" required><?= $_SESSION["mitraagen"]["alamat"]; ?></textarea>
      </div>

      <div class="form-group">
        <label for="prov">Provinsi Tujuan</label><br>
        <select class="form-control" id="prov" name="prov" required>
         <?php
         $idprov=$_SESSION["mitraagen"]["provinsi"];
         
         if($idprov==''){
             $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces");
              echo "<option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>";
           }else{
           $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces where province_id='$idprov' ");
            }
         while($row=$ambil->fetch_assoc()){
         ?>
            <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
         <?php } ?>
         </select>
      </div>
   

                      <div class="form-group">
                        <label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
                        <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                      </div>
                      
                        <div class="form-group">
                        <label for="kecamatan">Kecamatan Tujuan</label><br>
                        <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                      </div>
                      
                        <div class="form-group">
                        <label for="berat">Berat (gram)</label><br>
                        <input class="form-control" id="berat" type="number" name="berat" value="<?= $total_berat; ?>" readonly />
                      </div>
                      
                      <div class="form-group">
                        <label for="kurir">Kurir</label><br>
                        <select class="form-control" id="kurir" name="kurir" required>
                          <option disabled='disabled' value="" selected>~Pilih Kurir Pengiriman~</option>
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
                          <option value="layanan">-kosong-</option>           
                        </select>
                        <label>
                          <font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font>
                        </label>
                    </div>



      
<script type="text/javascript">

  $(document).ready(function(){
    $('#prov').ready(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var provinsi = $('#prov').val();

          $.ajax({
              type : 'GET',
              url : 'cek_kabupaten.php',
              data :  'prov_id=' + provinsi,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kabupaten").html(data);
        }
            });
    });
    
        $('#prov').change(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var provinsi = $('#prov').val();

          $.ajax({
              type : 'GET',
              url : 'cek_kabupaten.php',
              data :  'prov_id=' + provinsi,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kabupaten").html(data);
        }
            });
    });
    
  
    $('#kabupaten').ready(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var kabupaten = $('#kabupaten').val();

          $.ajax({
              type : 'GET',
              url : 'cek_kecamatan.php',
              data :  'kabupaten_id=' + kabupaten,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kecamatan").html(data);
        }
            });
    });
    
      $('#kabupaten').change(function(){

      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var kabupaten = $('#kabupaten').val();

          $.ajax({
              type : 'GET',
              url : 'cek_kecamatan.php',
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
              url : 'cek_ongkirpusat.php',
              data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
          $("#layanan").html(data);
        }
            });
    });
  });
</script>
 <?php endif ?>