<?php
  session_start();
  include 'koneksi.php';

  if(!isset($_SESSION["mitraagen"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }

  $idpoproduk = $_GET['id'];
  $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
  $invoice='A'.$idpoproduk.'-'.$idmitraagen;
  $query = "SELECT COUNT(*) as jumlah,
            poproduk.idpoproduk,
            poproduk.namapo,
            poproduk.status 
            FROM poproduk 
            inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
            WHERE poproduk.idpoproduk='$idpoproduk' 
            AND pomitra.idmitraagen='$idmitraagen'
            AND pomitra.invoice = '$invoice'
            ";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);

  //   $idmitraagen=$_GET['idmitraagen'];
  $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ.ID | Form <?= $data['namapo']; ?></title>

  <!-- CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <!-- Icons Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    .navbaru {
      background: #eee  url("jumbotron-bg.png") center center;
      text-align: center;
      overflow: hidden;
    }

    .navbaru p {
      padding: 12px 0;
      font-size: 20px;
      color: #0f0f0a;
      text-align: center;
    }

    .navbaru i {
      padding: 15px 0;
      font-size: 23px;
      color: #0f0f0a;
      text-align: center;
    }

    .navbaru2 {
      margin: auto;
      text-align: center;
      overflow: hidden;
    }
  </style>

</head>
<body>

  <!-- Navbar Start -->
  <div class="row fixed-top navbaru">
    <div class="col-2">
      <a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a>
    </div>

    <div class="col-8">
      <p>PRE ORDER</p>
    </div>

    <div class="col-2"></div>
  </div>
  <!-- Navbar End -->

  <div class="container mt-5 py-5">
    <div class="text-center">
      <h5>Formulir Pemesanan <?= $data['namapo'] ?></h5>
    </div>

    <?php
      $sql650 = "SELECT SUM(pomitra.jumlah) AS total_pcs FROM pomitra WHERE idpoproduk = 292";
      $query650 = $koneksi->query($sql650);
      while ( $row650 = $query650->fetch_assoc() ) {
        $dataMax = 1000;
        $totalKeseluruhan = $dataMax - $row650['total_pcs'];
    ?>
      <?php if($totalKeseluruhan <= 0) : ?>
        <div class="row">
          <div class="col-sm-12 text-center">
            <h6 class="text-uppercase badge bg-danger">PO ini sudah melebihi batas stok yang tersedia</h6>
          </div>
        </div>
      <?php else : ?>
        <div class="col-sm-12 text-center">
            <!--<h6 class="text-uppercase badge bg-success">tersisa <?//= $totalKeseluruhan ?> qty</h6>-->
            <?php if ($data['jumlah'] > 1) : ?>
                <a href="datapo.php?idmitra=<?= $idmitraagen; ?>&id=<?= $idpoproduk; ?>&invoice=<?= $invoice ?>" class="badge btn btn-primary">Invoice</a>
            <?php endif; ?>
        </div>
        

        <hr>
        <div class="my-3">
            <form method="POST">
		        <div style="padding: 0 15px;">
    				<?php
    					$idpoproduk = $_GET['id'];
    					$total = 0;
    					$index = 0;
    					$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo 
                                where poproduk.idpoproduk='$idpoproduk'
                                and podetail.variant NOT LIKE '%Custom%'
                                order by podetail.idpodetail asc
                            ";
    					$query = $koneksi->query($sql);
    					while($row = $query->fetch_assoc()){
    				?>
    					<div class="form-group">
    					    <label><?php echo $row['variant']; ?></label>
    					    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
    					    <input type="number" min="0" name="jmlh[]" class="form-control" style="width:300px;" value=0 required>
    					</div>
                    <?php
                            $namapo2=$row['namapo'];
                            $id=$row['idpoproduk'];
                            $idmitraagen=$_SESSION["mitraagen"]["idmitraagen"]; 
    				    }
    				?>
                    <p><strong><font color="red" size="5px">*</font></strong>Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memesan</p>			
    		        <?php
    		            if($data['jumlah']<1){
                			echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                        } else{
                            echo "# ";
                        }
        			?>
        		</div>
            </form>
        </div>
      <?php endif; ?>
    <?php } ?>
  </div>

  <?php
    if(isset($_POST["save"])){
        date_default_timezone_set('Asia/Jakarta');
        $today = date("s");
        $waktu = date("H:i:s");
        $idpodetail=$_POST["idpodetail"];
        $jmlh=$_POST["jmlh"];
        $jumlah_dipilih = count($jmlh);
        
        for($x=0;$x<$jumlah_dipilih;$x++){
            $sql = "Select * from podetail where idpodetail='$idpodetail[$x]'";
            $query = $koneksi->query($sql);
            $detail = $query->fetch_assoc();
            $harga = $detail['harga'];
            $idpo = $detail['idpo'];
            $total=$jmlh[$x]*$harga;
            $koneksi->query("INSERT into pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
            (null,'$idmitraagen','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");
            if($idpoproduk == '271') {
               	echo "<script>alert('data berhasil dikirim');</script>";
                echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
            } else {
               	echo "<script>alert('data berhasil dikirim');</script>";
                echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
            }
        }
    }
  ?>

  <!-- JavaScript Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>