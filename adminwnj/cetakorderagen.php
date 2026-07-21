<?php 

include 'koneksi.php'; 

$invoice=$_GET["invoice"];
  $datam=$koneksi->query("SELECT mitraagen.namaagen,admin_mitra.namamitra,admin_mitra.idadmin FROM orderagen inner join mitraagen on orderagen.idmitraagen=mitraagen.idmitraagen inner join admin_mitra on mitraagen.idadmin=admin_mitra.idadmin where orderagen.invoice='$invoice' ");
                        
  $tampilkanm=$datam->fetch_assoc();
  
  	$sql = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();

$idadminya = $tampilkanm['idadmin'];
  $datamitra=$koneksi->query("SELECT namacs
                                FROM admin_mitra_cs WHERE idadmin='$idadminya'");
                            $tampilnama=$datamitra->fetch_assoc();      
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Order Agen</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<br>
<br>
<br>
<div class="container">
            <center><h3>Invoice <?php echo $invoice; ?></h3></center><br> 
<table style="width:100%">
    <tr>
        <td style="float: left;"><h3>Agen : <?php echo $tampilkanm['namaagen']; ?></h3><br>
            <h3>DB   : <?php echo $tampilkanm['namamitra']; ?></h3>
        </td>
        <td></td>
        <td style="float: right;"><h3>CS : <?php echo $tampilnama['namacs']; ?></h3><br></td>
    </tr>
</table>           
           <h3>Tanggal : <?php echo date('d-m-Y'); ?></h3><br>

<h4>	<div class="table-responsive">
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>No</th>
						<th>Nama Produk</th>
						<th>Harga</th>
					    <th>Jumlah</th>
					    <th>Subtotal</th>
                         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                           	$jumlah=0;
					        $subtotal=0;
					        $ongkir=0;
                          if(isset($_POST["cari"])){
                                     $namaagen=$_POST["namaagen"];
                                     $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datamitra=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where administrator.nama LIKE '%$namaagen%'  ORDER BY orderagen.tgl  DESC");
                                    $total = mysqli_num_rows($datamitra);
                                    $pages = ceil($total/$halaman);
                
                                    $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where administrator.nama LIKE '%$namaagen%'  ORDER BY orderagen.tgl  DESC LIMIT $mulai, $halaman");
                                    $no=$mulai+1;
                                    
                          } else if(isset($_POST["carip"])){
                                   $payment=$_POST["payment"];
                                   $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datapayment=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where orderagen.payment LIKE '%$payment%'  ORDER BY orderagen.tgl  DESC");
                                    $total = mysqli_num_rows($datapayment);
                                    $pages = ceil($total/$halaman);
                             $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where orderagen.payment LIKE '%$payment%'  ORDER BY orderagen.tgl  DESC LIMIT $mulai, $halaman");  
                                    $no=$mulai+1;
                                    
                          } else if(isset($_POST["caritgl"])) {  
                              $tglawal=$_POST["tglawal"];
                              $tglakhir=$_POST["tglakhir"];
                              $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where orderagen.tgl>='$tglawal' and orderagen.tgl<='$tglakhir'");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderagen.tgl,orderagen.invoice,orderagen.payment,orderagen.status FROM `orderagen` inner join administrator on orderagen.idmitraagen=administrator.idmitra where orderagen.tgl>='$tglawal' and orderagen.tgl<='$tglakhir' LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else {
                           $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT mitraagen.namaagen,produk.namaproduk,orderagen.invoice,orderagen.idorder,orderagen.harga,orderagen.jumlah,orderagen.subtotal FROM orderagen inner join mitraagen inner join produk on orderagen.idproduk=produk.idproduk and orderagen.idmitraagen=mitraagen.idmitraagen where orderagen.invoice='$invoice' and orderagen.jumlah>0 ");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
                            
                            $datapo=$koneksi->query("SELECT mitraagen.namaagen,produk.namaproduk,orderagen.idorder,orderagen.invoice,orderagen.harga,orderagen.jumlah,orderagen.subtotal FROM orderagen inner join mitraagen inner join produk on orderagen.idproduk=produk.idproduk and orderagen.idmitraagen=mitraagen.idmitraagen where orderagen.invoice='$invoice' and orderagen.jumlah>0 ");
                            $no=$mulai+1;
                            
                          }
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['harga']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['subtotal']; ?>
                          </td>
							
						   <?php
							//$sum=array_sum($data['jumlah']);
						
                            $idpomitra=array($tampilkan['idorder']);						
							$jumlah=$jumlah+$tampilkan['subtotal'];
							$invoice=$tampilkan['invoice'];
							//$subtotal=$subtotal+$jumlah;
							 $dataongkir=$koneksi->query("SELECT ongkir FROM orderpengiriman where invoice='$invoice' ");
                            $tampilongkir=$dataongkir->fetch_assoc();
							$ongkir=$tampilongkir['ongkir'];
							?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                        
  <?php
    $totala=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori>0 and produk.idkategori<>2";
	$query = $koneksi->query($sql);
	while ($ga = $query->fetch_assoc()){
    ?>
    
    <?php  $totala +=  $ga['subtotal']; } ?>
    
      <?php
    $totalb=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=2";
	$query = $koneksi->query($sql);
	while ($gb = $query->fetch_assoc()){
    ?>
    
    <?php  $totalb +=  $gb['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

<?php
    $totald5=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=5";
	$query = $koneksi->query($sql);
	while ($d5 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald5 +=  $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald10=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=10";
	$query = $koneksi->query($sql);
	while ($d10 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald10 +=  $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=15";
	$query = $koneksi->query($sql);
	while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=15";
	$query = $koneksi->query($sql);
	while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald20=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=20";
	$query = $koneksi->query($sql);
	while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald25=0;
    $sql = "SELECT * FROM orderagen inner join produk on produk.idproduk=orderagen.idproduk WHERE orderagen.invoice='$invoice' and orderagen.jumlah>0 and produk.idkategori=25";
	$query = $koneksi->query($sql);
	while ($d25 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald25 +=  $d25['subtotal']; } ?>                        

    <hr>
                    
        <?php
     $apaja=$pengiriman['dropship'];
    $dropship=$pengiriman['berat'];
    
        if($dropship<=5000 and $dropship>=0 and $apaja=='ya') {
            $biayad=3000;
        }
        else if($dropship<=10000 and $dropship>=6000 and $apaja=='ya') {
            $biayad=5000;
        }
        else if($dropship<=20000 and $dropship>=11000 and $apaja=='ya') {
            $biayad=10000;
        }
         else if($dropship<=30000 and $dropship>=21000 and $apaja=='ya') {
            $biayad=15000;
        }
        else if($dropship<=40000 and $dropship>=31000 and $apaja=='ya') {
            $biayad=20000;
        }
         else if($dropship<=50000 and $dropship>=41000 and $apaja=='ya') {
            $biayad=25000;
        }
        else if($dropship<=60000 and $dropship>=51000 and $apaja=='ya') {
            $biayad=30000;
        }
         else if($dropship<=70000 and $dropship>=61000 and $apaja=='ya') {
            $biayad=35000;
        }
        else if($dropship<=80000 and $dropship>=71000 and $apaja=='ya') {
            $biayad=40000;
        }
         else if($dropship<=90000 and $dropship>=81000 and $apaja=='ya') {
            $biayad=45000;
         }
        else if($dropship<=100000 and $dropship>=91000 and $apaja=='ya') {
            $biayad=50000;
         }
        else if($dropship<=110000 and $dropship>=101000 and $apaja=='ya') {
            $biayad=55000;
        }
         else if($dropship<=120000 and $dropship>=111000 and $apaja=='ya') {
            $biayad=60000;
        }
        else if($dropship<=130000 and $dropship>=121000 and $apaja=='ya') {
            $biayad=65000;
        }
         else if($dropship<=140000 and $dropship>=131000 and $apaja=='ya') {
            $biayad=70000;
        }
        else if($dropship<=150000 and $dropship>=141000 and $apaja=='ya') {
            $biayad=75000;
        }
         else if($dropship<=160000 and $dropship>=151000 and $apaja=='ya') {
            $biayad=80000;
        }
        else if($dropship<=170000 and $dropship>=161000 and $apaja=='ya') {
            $biayad=85000;
        }
         else if($dropship<=180000 and $dropship>=171000 and $apaja=='ya') {
            $biayad=90000;
         }
        else if($dropship<=190000 and $dropship>=181000 and $apaja=='ya') {
            $biayad=95000;
        }
         else if($dropship<=200000 and $dropship>=191000 and $apaja=='ya') {
            $biayad=100000;
         }
        else if($apaja=='tidak'){
            $biayad=0;
        }    
        else{
            $biayad='0'; 
        }
        
        $tbiayad=number_format($biayad); 
        $ongkir=$pengiriman['ongkir'];
        $kurir=$pengiriman['ekspedisi'];
        $diskonramadhan=$pengiriman['diskonramadhan'];
        $idpengiriman=$pengiriman['idorderp'];
        $diskona=$totala*25/100;
        $diskonb=$totalb*10/100;
        $diskon5=$totald5*5/100;
        $diskon10=$totald10*10/100;
        $diskon15=$totald15*15/100;
        $diskon20=$totald20*20/100;
        $diskon25=$totald25*25/100;
        $tdiskona=number_format($diskona);
        $tdiskonb=number_format($diskonb);
        $tdiskon5=number_format($diskon5);
        $tdiskon10=number_format($diskon10);
        $tdiskon15=number_format($diskon15);
        $tdiskon20=number_format($diskon20);
        $tdiskon25=number_format($diskon25);
        $grandtotal=($totala+$totalb+$ongkir+$biayad)-($diskona+$diskonb+$diskon5+$diskon10+$diskon15+$diskon20+$diskon25);
        $tongkir=number_format($ongkir);
        $tgrandtotal=number_format($grandtotal);
        $test=$grandtotal-$diskonramadhan;
        $total=$totala+$totalb;
        $ttotal=number_format($total);

    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat') {
           echo "<p align='right'><b>Total : Rp. $ttotal</b>";
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
        echo "Ongkir : Rp. $tongkir<br>";
        echo "Diskon Agen 25% : Rp. -$tdiskona<br>";
        if($diskonb>0){
            echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
               }
        if($diskon5>0){
            echo "Diskon 5% : Rp. -$tdiskon5<br></p>";
                }
        if($diskon10>0){
            echo "Diskon 10% : Rp. -$tdiskon10<br></p>";
               }
        if($diskon15>0){
            echo "Diskon 15% : Rp. -$tdiskon15<br></p>";
                      }
        if($diskon20>0){
            echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
               }  
        if($diskon25>0){
            echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                      }                        
       //  echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
              //echo "<hr>";
       //echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
       echo "<hr>";
       echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
       echo "<p align='right'><b>GrandTotal : Rp. $test</b><br></br></p>";
        }else{
              echo "<p align='right'><b>Total : $ttotal</b>";
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            echo "Ongkir : Menunggu di Isi Admin<br>";
            echo "Diskon Agen 25% : Rp. -$tdiskona<br>";
            if($diskonb>0){
                echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
                   }
            if($diskon5>0){
                echo "Diskon 5% : Rp. -$tdiskon5<br></p>";
                    }
            if($diskon10>0){
                echo "Diskon 10% : Rp. -$tdiskon10<br></p>";
                   }
            if($diskon15>0){
                echo "Diskon 15% : Rp. -$tdiskon15<br></p>";
                          }
            if($diskon20>0){
                echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
                   }  
            if($diskon25>0){
                echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                          }              
         //   echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
                  //echo "<hr>";
       //echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
       echo "<hr>";
       echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
       echo "<p align='right'><b>GrandTotal : Rp. $test</b><br></br></p>";
       }
    }else{
    echo "<p align='right'><b>Total : Rp. $ttotal</b>";
    echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
    echo "Ongkir : Rp. $tongkir<br>";
    echo "Diskon Agen 25% : Rp. -$tdiskona<br>";
    if($diskonb>0){
        echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
           }
    if($diskon5>0){
        echo "Diskon 5% : Rp. -$tdiskon5<br></p>";
            }
    if($diskon10>0){
        echo "Diskon 10% : Rp. -$tdiskon10<br></p>";
           }
    if($diskon15>0){
        echo "Diskon 15% : Rp. -$tdiskon15<br></p>";
                  }
    if($diskon20>0){
        echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
           }  
    if($diskon25>0){
        echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                  }      
 //   echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
           //echo "<hr>";
       //echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
       echo "<hr>";
       echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
       echo "<p align='right'><b>GrandTotal : Rp. $test</b><br></br></p>";
    }
        ?>
                        
                         <?php
                        if(isset($_POST["done"])){
	
	                                 include "koneksi.php";
					               $invoice= $_POST['invoice'];
					               $koneksi->query("update orderagen set status='Sedang DiKirim' where invoice='$invoice';");
        		                   	echo "<script>alert('data sudah terupdate');</script>";
        		                   	echo "<script>location='orderagen.php';</script>";
        					                     }                  
                                 
                            ?>
                            
 

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

		                                                
</body>

<script>
window.print();
</script>

</html>

		                                                