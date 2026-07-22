<?php 
    session_start();
    include 'koneksi.php'; 
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $invoice    = $_GET["invoice"];
    $jenis      = $_GET["jenis"];
	$sql        = "SELECT * FROM orderpengiriman WHERE invoice = '$invoice' ";
	$query      = $koneksi->query($sql);
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

<body id="page-top">

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

   <h3><strong>Invoice <?php echo $invoice ?></strong></h3><br>

          <!-- Content Row -->
          <div class="row">
            
           <!--    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
             <label><b>Cari Berdasarkan Tanggal : </b></label> <input type="date" class="form-control bg-light border-0 small" placeholder="Tanggal Awal..." aria-label="Search" aria-describedby="basic-addon2" name="tglawal">-<input type="date" class="form-control bg-light border-0 small" placeholder="Tanggal Akhir..." aria-label="Search" aria-describedby="basic-addon2" name="tglakhir">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" name="caritgl">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
            </form><br> -->
           <a class="btn btn-success" href="cetakordermarketer2.php?invoice=<?php echo $invoice; ?>" target="blank">Cetak</a>    
            
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th>No</th>
						<th>Nama Produk</th>
						<th>Variant</th>
						<th>Size</th>
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
                         
                           $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT mitramarketer.namaagen,variants.variant,variants.size,products.namaproduk,ordermarketer.invoice,ordermarketer.idorder,ordermarketer.harga,ordermarketer.jumlah,ordermarketer.subtotal 
                                                        FROM ordermarketer 
                                                        INNER JOIN mitramarketer ON ordermarketer.idmitramarketer = mitramarketer.idmitramarketer 
                                                        INNER JOIN variants ON ordermarketer.idproduk = variants.id
                                                        INNER JOIN products ON products.id = variants.idproducts 
                                                        where ordermarketer.invoice = '$invoice' and ordermarketer.jumlah > 0 ");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
                            
                            $datapo=$koneksi->query("SELECT mitramarketer.namaagen,variants.variant,variants.size,products.namaproduk,ordermarketer.idorder,ordermarketer.invoice,ordermarketer.harga,ordermarketer.jumlah,ordermarketer.subtotal 
                                                        FROM ordermarketer 
                                                        INNER JOIN mitramarketer ON ordermarketer.idmitramarketer = mitramarketer.idmitramarketer
                                                        INNER JOIN variants ON ordermarketer.idproduk = variants.id
                                                        INNER JOIN products ON products.id = variants.idproducts  
                                                        where ordermarketer.invoice='$invoice' and ordermarketer.jumlah > 0 ");
                            $no=$mulai+1;
                            
                          
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
                            <?php echo $tampilkan['variant']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['size']; ?>
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
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice='$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori > 0 and products.idkategori<>2";
	$query = $koneksi->query($sql);
	while ($ga = $query->fetch_assoc()){
    ?>
    
    <?php  $totala +=  $ga['subtotal']; } ?>
    
      <?php
    $totalb=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 2";
	$query = $koneksi->query($sql);
	while ($gb = $query->fetch_assoc()){
    ?>
    
    <?php  $totalb +=  $gb['subtotal']; } ?>

<!----------------------------------------------------------------------------------------------------------------------------->

<?php
    $totald5=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 5";
	$query = $koneksi->query($sql);
	while ($d5 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald5 +=  $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald10=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 10";
	$query = $koneksi->query($sql);
	while ($d10 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald10 +=  $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 15";
	$query = $koneksi->query($sql);
	while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald17=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 17";
	$query = $koneksi->query($sql);
	while ($d17 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald17 +=  $d17['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald20=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 20";
	$query = $koneksi->query($sql);
	while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald25=0;
    $sql = "SELECT * FROM ordermarketer 
            INNER JOIN variants on variants.id = ordermarketer.idproduk
            INNER JOIN products on products.id = variants.idproducts 
            WHERE ordermarketer.invoice = '$invoice' 
            and ordermarketer.jumlah > 0 and products.idkategori = 25";
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
        $diskona=$totala*10/100;
        $diskonb=$totalb*55/100;
        $diskon5=$totald5*5/100;
        $diskon10=$totald10*10/100;
        $diskon15=$totald15*15/100;
        $diskon17=$totald17*17/100;
        $diskon20=$totald20*20/100;
        $diskon25=$totald25*25/100;
        if ($jenis == 'Flash') {
            $diskonFlash = $totala*20/100;
        } else {
            $diskonFlash = 0;
        }
        $flashSale      = number_format($diskonFlash);
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
        $total=$totala+$totalb+$totald5+$totald10+$totald15+$totald20+$totald25;
        $ttotal=number_format($total);

    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat') {
           echo "<p align='right'><b>Total : Rp. $ttotal</b>";
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
        echo "Ongkir : Rp. $tongkir<br>";
        echo "Diskon Marketer 10% : Rp. -$tdiskona<br>";
        if ($jenis == 'Flash') {
            echo "Diskon Tambahan 20% : Rp. -$FlashSale";
        }
       //echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
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
       echo "<hr>";
       echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
        }else{
              echo "<p align='right'><b>Total : $ttotal</b>";
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            echo "Ongkir : Menunggu di Isi Admin<br>";
            echo "Diskon Marketer 10% : Rp. -$tdiskona<br>";
            if ($jenis == 'Flash') {
                echo "Diskon Tambahan 20% : Rp. -$FlashSale";
            }
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
         //echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
            echo "<hr>";
            echo "<p align='right'><b>GrandTotal : Pending</b><br></br></p>";
       }
    }else{
            echo "<p align='right'><b>Total : Rp. $ttotal</b>";
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            echo "Ongkir : Rp. $tongkir<br>";
            echo "Diskon Marketer 10% : Rp. -$tdiskona<br>";
            if ($jenis == 'Flash') {
                echo "Diskon Tambahan 20% : Rp. -$FlashSale";
            }
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
         //echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
            echo "<hr>";
            echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    }
        ?>
                        
                         <?php
                        if(isset($_POST["done"])){
	
	                                 include "koneksi.php";
					               $invoice= $_POST['invoice'];
					               $koneksi->query("update ordermarketer set status='Sedang DiKirim' where invoice='$invoice';");
        		                   	echo "<script>alert('data sudah terupdate');</script>";
        		                   	echo "<script>location='ordermarketer.php';</script>";
        					                     }                  
                                 
                            ?>
                            
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

		                                                