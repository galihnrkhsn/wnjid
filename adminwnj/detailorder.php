<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$invoice=$_GET["invoice"];

	$sql = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();


  
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

 <?php include "sidebar.php"; ?>
 
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

     <a class="btn btn-success" href="cetakordermitra.php?invoice=<?php echo $invoice; ?>" target="blank">Cetak</a>    
<h3><strong>Invoice <?php echo $invoice ?></strong></h3><br>
          <!-- Content Row -->
          <div class="row">
            


            
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>No</th>
  						<th>Nama Produk</th>
  						<th>Harga</th>
					    <th>Jumlah</th>
					    <th>Subtotal</th>
					    <th style="text-align: right"><i class="fas fa-cog"></i></th>
                         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                           	$jumlah=0;
          					        $subtotal=0;
          					        $ongkir=0;
 if (substr($invoice,0,1)=="F") {
$datapo=$koneksi->query("SELECT
            admin_mitra.namamitra, 
            produk.namaproduk,
            ordermitra.idorder,
            ordermitra.idproduk,
            ordermitra.invoice,
            ordermitra.harga,
            SUM(ordermitra.jumlah) as jumlah, 
            SUM(ordermitra.subtotal) as subtotal 
            FROM ordermitra
            INNER JOIN admin_mitra on ordermitra.idmitra=admin_mitra.idadmin 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            GROUP BY produk.idproduk");

 }else{
$datapo=$koneksi->query("SELECT 
            admin_mitra.namamitra,
            produk.namaproduk,
            ordermitra.idorder,
            ordermitra.idproduk,
            ordermitra.invoice,
            ordermitra.harga,
            ordermitra.jumlah,
            ordermitra.subtotal
            FROM ordermitra 
            INNER JOIN admin_mitra on ordermitra.idmitra=admin_mitra.idadmin
            INNER JOIN produk on ordermitra.idproduk=produk.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 ");
 }  
                            $no=1;
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
                           <?php $sub=$tampilkan['harga']*$tampilkan['jumlah'];
                           echo $sub; ?>
                          </td>
                          <?php if($_SESSION["administrator"]["nama"]=='Delita' or $_SESSION["administrator"]["nama"]=='Master'){
                            echo "<td>";
						                echo "<form method='post'>"; 
                            echo "<input type='hidden' name='id' value='$tampilkan[idorder]'>";
                            echo "<input type='hidden' name='idproduk' value='$tampilkan[idproduk]'>";
                            echo "<input type='hidden' name='jumlah' value='$tampilkan[jumlah]'>";
                            echo "<button class='btn btn-danger' name='hapus'><span class='fas fa-trash'></span></button>";
                            echo "</form>";
						                echo "</td>";	
                            }?>
							
						  <?php
						  $idpomitra=array($tampilkan['idorder']);						
							$jumlah=$jumlah+$sub;
							$invoice=$tampilkan['invoice'];
							//$subtotal=$subtotal+$jumlah;
							$dataongkir=$koneksi->query("SELECT ongkir FROM orderpengiriman where invoice='$invoice' ");
              $tampilongkir=$dataongkir->fetch_assoc();
							$ongkir=$tampilongkir['ongkir'];
              $total_qty +=  $tampilkan['jumlah'];
							?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                        
  <?php
    $totala=0;
 if (substr($invoice,0,1)=="F") {
          $jumlah_produknya = $total_qty/2;
          $sql = "SELECT produk.harga as subtotal
            FROM ordermitra 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            and produk.idkategori BETWEEN 11 AND 13
            ORDER BY produk.harga desc
            LIMIT ".$jumlah_produknya."
            ";
 }else{
    $sql = "SELECT * 
            FROM ordermitra 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            and produk.idkategori>0 
            and produk.idkategori<>2
            ";
 }
	$query = $koneksi->query($sql);
	while ($ga = $query->fetch_assoc()){
    ?>
    
    <?php  $totala +=  $ga['subtotal']; } ?>
    
      <?php
    $totalb=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=2";
	$query = $koneksi->query($sql);
	while ($gb = $query->fetch_assoc()){
    ?>

    <?php  $totalb +=  $gb['subtotal']; } ?>

        <!----------------------------------------------------------------------------------------------------------------------------->

        <?php
    $totald5=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=5";
	$query = $koneksi->query($sql);
	while ($d5 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald5 +=  $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald10=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=10";
	$query = $koneksi->query($sql);
	while ($d10 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald10 +=  $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=15";
	$query = $koneksi->query($sql);
	while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald17=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=17";
	$query = $koneksi->query($sql);
	while ($d17 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald17 +=  $d17['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald20=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=20";
	$query = $koneksi->query($sql);
	while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald25=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=25";
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
    $diskona=$totala*35/100;
    $diskonb=$totalb*55/100;
    $diskon5=$totald5*5/100;
    $diskon10=$totald10*10/100;
    $diskon15=$totald15*15/100;
    $diskon17=$totald17*17/100;
    $diskon20=$totald20*20/100;
    $diskon25=$totald25*25/100;
    $tdiskona=number_format($diskona);
    $tdiskonb=number_format($diskonb);
    $tdiskon5=number_format($diskon5);
    $tdiskon10=number_format($diskon10);
    $tdiskon15=number_format($diskon15);
    $tdiskon17=number_format($diskon17);
    $tdiskon20=number_format($diskon20);
    $tdiskon25=number_format($diskon25);
    $grandtotal=($totala+$totalb+$ongkir+$biayad)-($diskona+$diskonb+$diskon5+$diskon10+$diskon15+$diskon17+$diskon20+$diskon25);
    $tongkir=number_format($ongkir);
    $tgrandtotal=number_format($grandtotal);
    $test=$grandtotal-$diskonramadhan;
    $total=$totala+$totalb;
    $ttotal=number_format($total);

    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat' or $kurir=='Disatukan') {
           echo "<p align='right'><b>Total : Rp. $ttotal</b><br>";
            if($biayad>0){
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            }
           
        echo "Ongkir : Rp. $tongkir<br>";
           
        echo "<font color='red'>Diskon DB 35% : Rp. -$tdiskona</font><br>";
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br></p>";
                   }                   
            if($diskon20>0){
         echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
            }  
            if($diskon25>0){
                echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                   }                               
      // echo "<hr>";
      // echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
      echo "<hr>";
      // echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
      echo "<p align='right' style='color:green;'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
        }else{
              echo "<p align='right'><b>Total : $ttotal</b><br>";
                if($biayad>0){
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
                }             
            echo "Ongkir : Menunggu di Isi Admin<br>";
                 
            echo "<font color='red'>Diskon DB 35% : Rp. -$tdiskona</font><br>";
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br></p>";
                   }                                      
            if($diskon20>0){
         echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
            }  
            if($diskon25>0){
                echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
                   }             
            //echo "<hr>";
            //echo "<p align='right'><b>GrandTotal : Pending</b><br></br></p>";
            echo "<hr>";
       // echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
       echo "<p align='right' style='color:green;'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
       }
    }
    
    else{
    echo "<p align='right'><b>Total : Rp. $ttotal</b><br>";
     if($biayad>0){
    echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
     }
    
    echo "Ongkir : Rp. $tongkir<br>";
     
    echo "<font color='red'>Diskon DB 35% : Rp. -$tdiskona</font><br>";
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
            if($diskon17>0){
                echo "Diskon 17% : Rp. -$tdiskon17<br></p>";
                   }                              
    if($diskon20>0){
 echo "Diskon 20% : Rp. -$tdiskon20<br></p>";
    }  
    if($diskon25>0){
        echo "Diskon 25% : Rp. -$tdiskon25<br></p>";
           }     
    //echo "<hr>";
    //echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    echo "<hr>";
       // echo "<p align='right'>Diskon Ramadhan : Rp. -$diskonramadhan<br>";
       echo "<p align='right' style='color:green;'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    }
        ?>
<!-- 
                  <form method="post">
                      Diskon Ramadhan <input type="text" name="diskonramadhan"/>
                      <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                  </form>    -->
                        
                         <?php
                        if(isset($_POST["done"])){
	
	                                 include "koneksi.php";
					               $invoice= $_POST['invoice'];
					               $koneksi->query("update ordermitra set status='Sedang DiKirim' where invoice='$invoice';");
        		                   	echo "<script>alert('data sudah terupdate');</script>";
        		                   	echo "<script>location='ordermitra.php';</script>";
        					                     }                  
                                 
                            ?>
                            
                             <?php if(isset($_POST["hapus"])){
         
                                $id = $_POST['id'];
                                $idproduk = $_POST['idproduk'];
                                $jumlah = $_POST['jumlah'];
                                
                                $koneksi->query("DELETE FROM ordermitra WHERE idorder='$id'" );
                                 $koneksi->query("UPDATE produk set stock=stock+'$jumlah' where idproduk='$idproduk' " );
                                 
                                echo "<script>alert('data berhasil dihapus');</script>";
		                        echo "<script>location='detailorder.php?invoice=$invoice';</script>";
                        }
		               ?>

                     <!--  <?php
                        if(isset($_POST["simpan"])){
	
	                               include "koneksi.php";
					               $diskonramadhan= $_POST['diskonramadhan'];
					               $koneksi->query("update orderpengiriman set diskonramadhan='$diskonramadhan' where idorderp='$idpengiriman';");
        		                   echo "<script>alert('data sudah terupdate');</script>";
        		                   echo "<script>location='detailorder.php?invoice=$invoice';</script>";
        					                     }                  
                            ?> -->
                            
 <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

		                                                