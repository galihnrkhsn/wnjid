<?php 
include 'koneksi.php'; 

if(isset($_POST["donemarketer"])){
            if(isset($_POST['idpomitra_marketer'])){
                foreach($_POST['idpomitra_marketer'] as $updateid){
                    $query = "UPDATE ordermarketer SET status= 'Sedang DiKirim' where invoice='$updateid'";
                    $sql = mysqli_query( $koneksi, $query);
                }
            if($sql){
              echo "<script>alert('Status diubah Menjadi Sedang Dikirim');</script>";
              echo "<script>location='ordermitra.php';</script>";
              }else{
              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
              echo "<script>location='ordermitra.php';</script>";
                    }                
            
            }    
}    
                            
if(isset($_POST["selesaimarketer"])){
            if(isset($_POST['idpomitra_marketer'])){
                foreach($_POST['idpomitra_marketer'] as $updateid){  
                    $query = "UPDATE ordermarketer SET status= 'Selesai' where invoice='$updateid'";
                    $sql = mysqli_query( $koneksi, $query);             
                }
            if($sql){
              echo "<script>alert('Status diubah Menjadi Selesai');</script>";
              echo "<script>location='ordermitra.php';</script>";
              }else{
              echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
              echo "<script>location='ordermitra.php';</script>";
                    }            

            }	
}

if(isset($_POST["bayar_marketer"])){
            if(isset($_POST['idpomitra_marketer'])){
                foreach($_POST['idpomitra_marketer'] as $updateid){  
                    $invoice = $updateid;
$tampil =$koneksi->query("SELECT ordermarketer.payment FROM ordermarketer where invoice='$updateid' ");
         $tampilMas=$tampil->fetch_assoc(); 
if ($tampilMas['payment']=='Belum Bayar'){                        
                            $query = "UPDATE ordermarketer SET status= 'Proses', payment='Lunas', status_progres=0 where invoice='$invoice'";
                            $sql = mysqli_query( $koneksi, $query);
                                    $dataorder=$koneksi->query("SELECT SUM(subtotal) as totalnya, idmitramarketer FROM ordermarketer where invoice='$invoice' ");
                                    $tampildeui=$dataorder->fetch_assoc();
                                    $idmarketer=$tampildeui['idmitramarketer'];
                                    $total=$tampildeui['totalnya'];
                                    
                                    $datadb=$koneksi->query("SELECT * FROM mitramarketer where idmitramarketer='$idmarketer' ");
                                    $tampildb=$datadb->fetch_assoc();
                                    $idadmin=$tampildb['idadmin'];
                                    $namamarketer=$tampildb['namaagen'];    
                                    
                                    $saldo=$total*20/100;

                                    if (substr($invoice,0,2)<>"MS") {
                                    
                                    $koneksi->query("INSERT INTO saldo (id_saldo,idadmin,tgl,transaksi,debit,credit)
                              VALUES (null,'$idadmin',NOW(),'Fee Order marketer ($namamarketer) invoice #$invoice','$saldo','0')"); 
                                    }

}                                             
                }
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    echo "<script>alert('Payment diubah Menjadi Lunas');</script>";
                                echo "<script>location='ordermitra.php';</script>";
                                }else{
                                    // Jika Gagal, Lakukan :

                                    echo "<script>alert('Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.');</script>";
                                    echo "<script>location='ordermitra.php';</script>";
                                    
                                    }                 
            
            }  
}                            
                             ?>


<?php 
if(isset($_POST["print_marketer"])){ ?>
 <style type="text/css">
  @media print {
  footer {page-break-after: always;}
}
</style>

<?php    
if(isset($_POST['idpomitra_marketer'])){
  foreach($_POST['idpomitra_marketer'] as $updateid){ 
    $invoice=$updateid;
  $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                    admin_mitra.namamitra, 
                                    admin_mitra_cs.namacs,
                                    mitramarketer.idmitramarketer
                                    FROM ordermarketer
                                    JOIN mitramarketer ON mitramarketer.idmitramarketer = ordermarketer.idmitramarketer
                                    JOIN admin_mitra on mitramarketer.idadmin = admin_mitra.idadmin
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    where ordermarketer.invoice='$updateid'");
                            $tampilnama=$datamitra->fetch_assoc();       
?>

  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style type="text/css">
  table, th, td, tr {
  border: 2px solid;
   border-collapse: collapse;
}
body{
  color: black;
}
</style>


<center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
<center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $invoice?></strong></p></center>

  <div class="row align-items-start">
    <div class="col">
       <h5><strong style="float:left">Mitra : <?php echo $tampilnama['idadmin']; ?>/<?php echo $tampilnama['idmitramarketer']; ?></strong></h5>
    </div>
    <div class="col">
    </div>
    <div class="col">
      <h5><strong style="float:rigth">Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
    </div>
  </div>
    <table style="width:100%;font-size: 28px">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Checker</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
<?php 
    $datapodropship=$koneksi->query("SELECT produk.namaproduk, 
                                            ordermarketer.jumlah
                                    FROM ordermarketer
                                    JOIN produk ON produk.idproduk = ordermarketer.idproduk
                                    WHERE ordermarketer.invoice= '$updateid'
                                    AND ordermarketer.jumlah>0
                                    ");
$no=1;
        while($tampilkan=$datapodropship->fetch_assoc()){
?>
            <tr>
                <td><?php echo $no++; ?></td>    
                <td><?= $tampilkan['namaproduk'];?></td>
                <td><center><?= $tampilkan['jumlah'];?></center></td>
                <td></td>
                <td></td>
            </tr>
<?php 
$qty += $tampilkan['jumlah'];
$totalbayar +=$total;
?>                       
<?php } ?>
        </tbody>
    </table>
<br>
<br>
<center>
<table style="font-size: 25px;border-color: white;">
  <tr>
    <td style="border-color: white;">
      <center>Gudang</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>
    <td style="border-color: white;">
      <center>Checker</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>    
    <td style="border-color: white;">
      <center>Penerima</center>
      <br>
        <br>
        <br>
        <br>
    </td>
    </tr>
    <tr>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>      
    </td >
    <td style="border-color: white;"></td>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>     
    </td>   
    <td style="border-color: white;"></td> 
    <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>
    </td>
  </tr>
</table>
    </center>

            <div>
                <p style="font-size: 22px"> 
                  Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
<br>
<br>
<?php if (substr($invoice,0,2)=="MV"): ?>
<strong>Keterangan Isi Box</strong>
<br>
<?php 
      $ambil_box=$koneksi->query("SELECT *
                                FROM hampers
                                WHERE hampers.no_ds= '$invoice'
                                ORDER BY nobox ASC"); 
      while($data_box=$ambil_box->fetch_assoc()){
    $result_explode = explode('|', $data_box['ucapan']);
    $dari=$result_explode[0];    
    $kepada=$result_explode[1];    
    $ucapan=$result_explode[2];        
 ?>
<label>Box <?= $data_box['nobox'] ?> : <?php echo str_replace("Voal Hampers","",str_replace("|",", ",$data_box['idpodetail'])); ?> <?php if ($dari or $kepada or $ucapan): ?>
  <span style="color: red">(Req. Kartu Ucapan)</span>
<?php endif ?></label>
<br>

<?php } ?>       
<?php endif ?>         
            </div>                  
                </p>
                <br>


    
    <div class="footer"></div>
    <footer></footer>


<?php
  }
}
?>
<script>
 window.print();
</script>
<?php
} 
 ?>                             