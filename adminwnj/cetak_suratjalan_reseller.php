<?php 

include 'koneksi.php'; 

$invoice=$_GET["invoice"];

$sqlnya = $koneksi->query("UPDATE orderreseller set no_sj='SJ-$invoice' where invoice='$invoice'");
  $datam=$koneksi->query("SELECT mitrareseller.namaagen,admin_mitra.namamitra,admin_mitra.idadmin FROM orderreseller inner join mitrareseller on orderreseller.idmitrareseller=mitrareseller.idmitrareseller inner join admin_mitra on mitrareseller.idadmin=admin_mitra.idadmin where orderreseller.invoice='$invoice' ");
                        
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

  <title>Order Marketer</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style type="text/css">

body{
  color: black;
}
</style>
<br>
<br>
<br>
<div class="container">
            <center><h1>Surat Jalan WNJ</h1></center><br>
            <center><h2>No. SJ-<?php echo $invoice; ?></h2></center><br> 
<table style="width:100%">
    <tr>
        <td style="float: left;"><h3>Reseller : <?php echo $tampilkanm['namaagen']; ?></h3><br>
            <h3>DB   : <?php echo $tampilkanm['namamitra']; ?></h3>
        </td>
        <td></td>
        <td style="float: right;"><h3>CS : <?php echo $tampilnama['namacs']; ?></h3><br></td>
    </tr>
</table>             
           <h3>Tanggal : <?php echo date('d-m-Y'); ?></h3><br>
            
<h4>	<div class="table-responsive">
	<div class="table-responsive">
        <table class="table table-bordered" style="border: 2px solid; border-color: black; color: black;" >
          <tr style="border: 2px solid;">
              <th style="border: 2px solid;">No</th>
            <th style="border: 2px solid;">Nama Produk</th>
              <th style="border: 2px solid;">QTY</th>
            <th style="border: 2px solid;">Checker</th>
              <th style="border: 2px solid;">Penerima</th>
                         
                        </tr>
                      </thead>
                      <tbody style="border: 2px solid;">
                          <?php 
                           	$jumlah=0;
					        $subtotal=0;
					        $ongkir=0;
                          if(isset($_POST["cari"])){
                                     $namaagen=$_POST["namaagen"];
                                     $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datamitra=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where administrator.nama LIKE '%$namaagen%'  ORDER BY orderreseller.tgl  DESC");
                                    $total = mysqli_num_rows($datamitra);
                                    $pages = ceil($total/$halaman);
                
                                    $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where administrator.nama LIKE '%$namaagen%'  ORDER BY orderreseller.tgl  DESC LIMIT $mulai, $halaman");
                                    $no=$mulai+1;
                                    
                          } else if(isset($_POST["carip"])){
                                   $payment=$_POST["payment"];
                                   $halaman = 50; /* page halaman*/
                                   $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                                   $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                                    $datapayment=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where orderreseller.payment LIKE '%$payment%'  ORDER BY orderreseller.tgl  DESC");
                                    $total = mysqli_num_rows($datapayment);
                                    $pages = ceil($total/$halaman);
                             $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where orderreseller.payment LIKE '%$payment%'  ORDER BY orderreseller.tgl  DESC LIMIT $mulai, $halaman");  
                                    $no=$mulai+1;
                                    
                          } else if(isset($_POST["caritgl"])) {  
                              $tglawal=$_POST["tglawal"];
                              $tglakhir=$_POST["tglakhir"];
                              $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where orderreseller.tgl>='$tglawal' and orderreseller.tgl<='$tglakhir'");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
        
                            $datapo=$koneksi->query("SELECT DISTINCT administrator.nama,orderreseller.tgl,orderreseller.invoice,orderreseller.payment,orderreseller.status FROM `orderreseller` inner join administrator on orderreseller.idmitrareseller=administrator.idmitra where orderreseller.tgl>='$tglawal' and orderreseller.tgl<='$tglakhir' LIMIT $mulai, $halaman");
                            $no=$mulai+1;
                          } else {
                           $halaman = 50; /* page halaman*/
                           $page    =isset($_GET["halaman"]) ? (int)$_GET["halaman"] : 1;
                           $mulai    =($page>1) ? ($page * $halaman) - $halaman : 0;
                            $datasaldo=$koneksi->query("SELECT mitrareseller.namaagen,produk.namaproduk,orderreseller.invoice,orderreseller.idorder,orderreseller.harga,orderreseller.jumlah,orderreseller.subtotal FROM orderreseller inner join mitrareseller inner join produk on orderreseller.idproduk=produk.idproduk and orderreseller.idmitrareseller=mitrareseller.idmitrareseller where orderreseller.invoice='$invoice' and orderreseller.jumlah>0 ");
                            $total = mysqli_num_rows($datasaldo);
                            $pages = ceil($total/$halaman);
                            
                            $datapo=$koneksi->query("SELECT mitrareseller.namaagen,produk.namaproduk,orderreseller.idorder,orderreseller.invoice,orderreseller.harga,orderreseller.jumlah,orderreseller.subtotal FROM orderreseller inner join mitrareseller inner join produk on orderreseller.idproduk=produk.idproduk and orderreseller.idmitrareseller=mitrareseller.idmitrareseller where orderreseller.invoice='$invoice' and orderreseller.jumlah>0 ");
                            $no=$mulai+1;
                            
                          }
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr style="border: 2px solid;">
                         
                         <td style="border: 2px solid;">
                             <?php echo $no++; ?>
                        </td>     
                          <td style="border: 2px solid;">
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                           <td style="border: 2px solid;">
                           <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td style="border: 2px solid;">
                           
                          </td>
                          <td style="border: 2px solid;">
                           
                          </td>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>


                     
<table style="font-size: 25px;">
  <tr>
    <td width="80%" style="border-color: white;">
      
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
      <td width="80%" style="border-color: white;">
      
    </td>
    <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_______________________</span>)
      </center>
    </td>
  </tr>
</table>    

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

		                                                
</body>

<script>
window.print();
</script>

</html>

		                                                