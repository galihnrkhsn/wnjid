
     <form method="post">
    <table class="table table-bordered" id="tbpokolibri">
      <thead>
        <tr>
          <th>No</th>
          <th><input type="checkbox" id="pilihsemua" onchange="checkAllDB(this)"/></th>
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Kemitraan</th>
          <th>Status PO</th>
          <th>Waktu Pembayaran</th>
        </tr>
      </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT pomitra.invoice,
                                                pomitra.idpomitra,
                                                pomitra.status,
                                                pomitra.tgl,
                                                pomitra.waktu,
                                                pomitra.ket,
                                                admin_mitra.namamitra,
                                                mitraagen.namaagen as agen,
                                                mitrareseller.namaagen as reseller,
                                                mitramarketer.namaagen as marketer
                  FROM pomitra
                  LEFT JOIN mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen 
                  LEFT JOIN mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller 
                  LEFT JOIN mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer 
                  LEFT JOIN admin_mitra on (pomitra.idmitra=admin_mitra.idadmin or 
                                            mitraagen.idadmin=admin_mitra.idadmin or 
                                            mitrareseller.idadmin=admin_mitra.idadmin or 
                                            mitramarketer.idadmin=admin_mitra.idadmin)  
                  WHERE pomitra.idpoproduk='$idpoproduk' 
                  and (pomitra.status='Belum DP' or pomitra.status='Belum Acc DB')
                  GROUP BY pomitra.invoice
                  ORDER BY pomitra.tgl DESC, pomitra.waktu desc 

                  ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                ?>
                <tr>
                    <td>
                     <?php echo $no++; ?>
                </td>     
                
                 <td>
                  <?php if ($tampilkan['status']<>'Lunas'): ?>
                    <input type="checkbox" class="check-item" name="idpomitra_dbcheck[]" id="iddb" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                    <?php else: ?>
                      <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                  <?php endif ?>
                    
                        </td> 
                  <td>
                    <?php echo $tampilkan['invoice']; ?>
                  </td>
                   <td>
                   <?php echo $tampilkan['namamitra']; ?>
                  </td>
                  <td>
                    <?php 
                    if($tampilkan['agen']<>''){ 
                      echo "<div class='badge bg-info text-white rounded-pill'>Agen</div>";
                            }
                    if($tampilkan['reseller']<>''){ 
                      echo "<div class='badge bg-warning text-white rounded-pill'>Reseller</div>";
                            }
                    if($tampilkan['marketer']<>''){ 
                      echo "<div class='badge bg-danger text-white rounded-pill'>Marketer</div>";
                            }
                    if($tampilkan['agen']=='' and $tampilkan['reseller']=='' and $tampilkan['marketer']=='' ){ 
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

                <?php elseif ($tampilkan['status']=='Sudah Confirm DP' or
                        $tampilkan['status']=='Sudah Confirm Pelunasan' or
                        $tampilkan['status']=='Sudah Konfirmasi Pembayaran 1' or
                        $tampilkan['status']=='Sudah Konfirmasi Pembayaran 2' or
                        $tampilkan['status']=='Sudah Konfirmasi Pembayaran 3' or
                        $tampilkan['status']=='Sudah DP'
                      ): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>

                <?php elseif ($tampilkan['status']=='Lunas'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>                  

                  <?php else: ?>
                      <?php echo $tampilkan['status']; ?>
                <?php endif ?>  
                  </td>
                  <td>
                    <center>
<?= $tampilkan['tgl']; ?>/<?= $tampilkan['waktu']; ?>
</center>
<br>                    
<?php 



?>
<center>
      <div class="badge bg-success text-white rounded-pill"  id="link2<?= $tampilkan['idpomitra']; ?>">
      Active
      </div>

      <p id="demomiki<?= $tampilkan['idpomitra']; ?>" style="color: red;"></p>
</center>      
<?php 
date_default_timezone_set('Asia/Jakarta');
// ==============Set Tanggal +1 hari==============
  $jumlahhari='+1 days'; 
$tgl1 = $tampilkan['tgl'];
$tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); 
$ket = $tampilkan['ket'];
// ==============Set Tanggal Bayar Bukapo==============
  $query_tgl = "SELECT bukapo.idpoproduk,
            bukapo.tgl_bayar,
            poproduk.jenis
        FROM bukapo  
        JOIN poproduk on poproduk.idpoproduk = bukapo.idpoproduk
        WHERE bukapo.idpoproduk='$idpoproduk'
        
        ";
  $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
  $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
  $tgl_bayar = $datapo_tgl['tgl_bayar']; 
  $jenis = $datapo_tgl['jenis']; 
  $waktu_bayar = '23:59:59';

if ($tgl_bayar=="") {
  $tgl_bayar=$tgl2;
}

if ($jenis=="Kolibri") {
  $tgl_bayar=$tgl2;
  $waktu_bayar = $tampilkan['waktu']; 
}

if ($ket=="Perpanjang") {
  $tgl_bayar=$tgl2;
  $waktu_bayar = $tampilkan['waktu'];  
}

 ?>

<script>

// Mengatur waktu akhir perhitungan mundur
var countDownDatemiki<?= $tampilkan['idpomitra']; ?>= new Date("<?php echo $tgl_bayar; ?> <?php echo $waktu_bayar; ?>").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDatemiki<?= $tampilkan['idpomitra']; ?> - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demomiki<?= $tampilkan['idpomitra']; ?>").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demomiki<?= $tampilkan['idpomitra']; ?>").innerHTML = "<div class='badge bg-danger text-white rounded-pill'>Expired</div>";
      var x = document.getElementById("linkmiki<?= $tampilkan['idpomitra']; ?>");
      var y = document.getElementById("link2<?= $tampilkan['idpomitra']; ?>");
 
    y.style.display = "none";
    x.style.display = "none";
    }
}, 1000);
</script>

                        </td>

                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
<button type="submit" class="btn btn-danger" name="hapusdbcheck"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Stok Tidak Akan Dikembalikan');"><span class="fas fa-trash"></span> Hapus</button>  

<button type="submit" class="btn btn-warning" name="hapusdbcheckstok"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Stok Akan Dikembalikan');"><span class="fas fa-trash"></span> Hapus Stock</button>  

<button type="submit" class="btn btn-success" name="perpanjangdbcheck"  onclick="return confirm('Yakin Akan Perpanjang Pesanan?');"><span class="fas fa-edit"></span> Perpanjang</button>
<button type="submit" class="btn btn-info" name="bataldbcheck"  onclick="return confirm('Yakin Akan Batal Perpanjang Pesanan?');"><span class="fas fa-trash"></span> Batal Perpanjang</button>

<button type="submit" class="btn btn-primary" name="approvedbcheck"  onclick="return confirm('Yakin Akan Approve Pesanan?');"><span class="fas fa-check"></span> Approve</button>                   
                    </form>
<?php 
         if(isset($_POST["hapusdbcheck"])){

          $invoice = $_POST['idpomitra_dbcheck'];
          $jumlah_dipilih=count($invoice);
              for($x=0;$x<$jumlah_dipilih;$x++){
              $query = $koneksi->query("DELETE FROM pomitra WHERE invoice='$invoice[$x]'" );
                // $delete = "DELETE FROM podropship where invoice='$invoice[$x]'";
                // $sql = mysqli_query( $koneksi, $delete);
                }
              

            
              if ($sql) {
              echo "<script>alert('data berhasil dihapus');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }else{
                echo "<script>alert('data gagal dihapus');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }
              

              }

         if(isset($_POST["hapusdbcheckstok"])){

          $invoice = $_POST['idpomitra_dbcheck'];
          $jumlah_dipilih=count($invoice);
              for($x=0;$x<$jumlah_dipilih;$x++){
                $datapo=$koneksi->query("SELECT pomitra.invoice,
                                                pomitra.idpo,
                                                pomitra.jumlah
                  FROM pomitra 
                  WHERE pomitra.invoice='$invoice[$x]' 
                  and pomitra.jumlah>0

                  ");
                while($tampilkan=$datapo->fetch_assoc()){   
                $idpo = $tampilkan['idpo'];     
                $jumlah = $tampilkan['jumlah'];             
              $sqlnya = $koneksi->query("UPDATE pokategori set stok=stok+'$jumlah'
                      where idpo='$idpo'");
              }
              if ($sqlnya) {
                $sql = $koneksi->query("DELETE FROM pomitra WHERE invoice='$invoice[$x]'" );
              }
              
                // $delete = "DELETE FROM podropship where invoice='$invoice[$x]'";
                // $sql = mysqli_query( $koneksi, $delete);
                }
              

            
              if ($sql) {
              echo "<script>alert('data berhasil dihapus');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }else{
                echo "<script>alert('data gagal dihapus');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }
              

              }              

         if(isset($_POST["perpanjangdbcheck"])){
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date("H:i:s");          

          $invoice = $_POST['idpomitra_dbcheck'];
          $jumlah_dipilih=count($invoice);
              for($x=0;$x<$jumlah_dipilih;$x++){
                    $sqlnya = $koneksi->query("UPDATE pomitra set ket='Perpanjang', 
                      tgl=NOW(),waktu='$waktu'
                      where invoice='$invoice[$x]'");
                }
              

            
              if ($sqlnya) {
              echo "<script>alert('data berhasil diperpanjang');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }else{
                echo "<script>alert('data gagal diperpanjang');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }
              

              }  

         if(isset($_POST["bataldbcheck"])){
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date("H:i:s");          

          $invoice = $_POST['idpomitra_dbcheck'];
          $jumlah_dipilih=count($invoice);
              for($x=0;$x<$jumlah_dipilih;$x++){
                    $sqlnya = $koneksi->query("UPDATE pomitra set ket= NULL
                      where invoice='$invoice[$x]'");
                }
              

            
              if ($sqlnya) {
              echo "<script>alert('data berhasil diproses');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }else{
                echo "<script>alert('data gagal diproses');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }
              

              }                

         if(isset($_POST["approvedbcheck"])){
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date("H:i:s");          

          $invoice = $_POST['idpomitra_dbcheck'];
          $jumlah_dipilih=count($invoice);
              for($x=0;$x<$jumlah_dipilih;$x++){

$data_s=$koneksi->query("SELECT status FROM pomitra where invoice='$invoice[$x]' ");
$tampil_s=$data_s->fetch_assoc();
$status = $tampil_s['status'];                
                    if ($status=="Belum Acc DB") {
                    $sqlnya = $koneksi->query("UPDATE pomitra set status='Approve DB'
                      where invoice='$invoice[$x]'");
                    }
                }
              

            
              if ($sqlnya) {
              echo "<script>alert('data berhasil diapprove');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }else{
                echo "<script>alert('data gagal diapprove');</script>";
              echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
              }
              

              }                            

 ?>
