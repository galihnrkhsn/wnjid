<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';
    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION['idadmin'];

    $query = $koneksi->query("SELECT 
                                    poproduk.idpoproduk,
                                    poproduk.namapo,
                                    pomitra.tgl,
                                    pomitra.waktu,
                                    pomitra.status
                                FROM
                                    poproduk
                                        INNER JOIN
                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                WHERE
                                    pomitra.invoice = '$invoice'
                            ");
    $data = $query->fetch_assoc();
    $namapo = $data['namapo'];

    $sql_alamat = $koneksi->query("SELECT 
                                        podropship.namapengirim,
                                        podropship.tlppengirim,
                                        podropship.namapenerima,
                                        podropship.tlppenerima,
                                        podropship.alamatpenerima,
                                        podropship.ekspedisi,
                                        podropship.layanan,
                                        podropship.ongkir,
                                        podropship.dropship,
                                        tb_ro_provinces.province_name,
                                        tb_ro_cities.city_name,
                                        tb_ro_subdistricts.subdistrict_name
                                    FROM
                                        podropship
                                            LEFT JOIN
                                        tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                                            LEFT JOIN
                                        tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                                            LEFT JOIN
                                        tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                                    WHERE
                                        podropship.invoice = '$invoice'
                                ");
    $datapengiriman = $sql_alamat->fetch_assoc();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Konin <?= $invoice ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <style>
            .custom-width {
                width: 60%; /* Default width untuk layar kecil */
            }

            @media (min-width: 768px) { /* Mulai dari layar ukuran medium (tablet) */
                .custom-width {
                    width: 40%;
                }
            }

            @media (min-width: 992px) { /* Mulai dari layar ukuran besar (desktop) */
                .custom-width {
                    width: 20%;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="pokonin.php?id=<?= $idpoproduk ?>" class="text-muted fw-semibold"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-semibold mb-0" href="#">Pre Order</p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-2">
            <div class="col-sm-12 text-center">
                <h5 class="text-uppercase mb-0 fw-normal">Invoice <?= $namapo ?></h5>
                <h3><?= $invoice ?></h3>
            </div>

            <div class="card shadow-sm mb-5">
                <div class="card-body" style="font-size: .875rem">
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pesanan:</u></p>
                        <p class="mb-0 fw-medium">Tanggal: <span class="fw-normal"><?= $data['tgl'] ?></span></p>
                        <p class="mb-0 fw-medium">Status: <span class="fw-normal"><?= $data['status'] ?></span></p>
                    </div>
                    <hr />
                    <div>
                        <p class="mb-0 fw-semibold" style="font-size: 1rem"><u>Informasi Pengiriman:</u></p>
                        <p class="mb-0 fw-medium">Pengirim: <span class="fw-normal"><?= $datapengiriman['namapengirim'] ?></span></p>
                        <p class="mb-0 fw-medium">Penerima: <span class="fw-normal"><?= $datapengiriman['namapenerima'] ?></span></p>
                        <p class="mb-0 fw-medium">
                            Alamat:
                            <span class="fw-normal">
                                <?php if (!isset($datapengiriman['provinsi'])) : ?>
                                    Alamat Belum Diisi
                                <?php else : ?>
                                    <?= $datapengiriman['alamatpenerima'] ?>
                                <?php endif; ?>
                            </span>
                        </p>
                        <p class="mb-0 fw-medium">
                            Ekspedisi:
                            <span class="fw-normal">
                                <?php if ($datapengiriman['ekspedisi'] == '-') : ?>
                                    Ekspedisi Belum Diisi
                                <?php else : ?>
                                    <?= $datapengiriman['ekspedisi'] ?>
                                <?php endif; ?>
                            </span>
                        </p>
                        <?php if (!isset($datapengiriman['provinsi'])) : ?>
                            <hr />
                            <p class="mb-0">Alamat belum diisi, silahkan isi alamat <a href="formdropship.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>">disini.</a></p>
                        <?php endif; ?>
                    </div>

                    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Invoice</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Progres</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-4" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Satuan</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = $koneksi->query("SELECT 
                                                                            podetail.variant,
                                                                            podetail.harga,
                                                                            pomitra.idpomitra,
                                                                            pomitra.jumlah,
                                                                            pomitra.total
                                                                        FROM
                                                                            pomitra
                                                                                LEFT JOIN
                                                                            podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                        WHERE
                                                                            pomitra.invoice = '$invoice'
                                                                                AND jumlah > 0
                                                                ");
                                            $no = 1;
                                            while ($datapo = $sql->fetch_assoc()) {
                                                $total_harga = $datapo['harga'] * $datapo['jumlah'];
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= str_replace('Konin 2025', '', $datapo['variant']) ?></td>
                                                <td>Rp. <?= number_format(num: $datapo['harga']) ?></td>
                                                <td><?= $datapo['jumlah'] ?></td>
                                                <td>Rp. <?= number_format($total_harga) ?></td>
                                            </tr>
                                        <?php
                                                $total_barang += $datapo['jumlah'];
                                                $total_harga += $datapo['total_harga'];
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                                $persen = 35;
                                $diskon = $persen/100 * $total_harga;

                                $ongkir = $datapengiriman['ongkir'];
                                $dropship = $datapengiriman['dropship'];
                                $subtotal = $total_harga + $dropship + $ongkir - $diskon;

                                $sql_pembayaran = $koneksi->query("SELECT * FROM popembayaran WHERE invoice = '$invoice'");
                                $data_pemabayaran = $sql_pembayaran->fetch_assoc();
                                $dibayar = $data_pemabayaran['jmlhtransfer'];
                                $sisa_tagihan = $subtotal - $dibayar;
                                // Jika sisa tagihan kurang dari 0, setel ke 0 dan hitung lebihnya
                                if ($sisa_tagihan < 0) {
                                    $lebih = abs($sisa_tagihan); // Ambil nilai positif dari sisa_tagihan
                                    $sisa_tagihan = 0; // Setel sisa_tagihan menjadi 0
                                } else {
                                    $lebih = 0; // Jika tidak ada lebihnya
                                }
                            ?>

                            <div class="table-responsive mb-4 float-end custom-width">
                                <table border="0" width="100%">
                                    <tr>
                                        <th width="60">Qty</th>
                                        <td class="text-center" width="10">:</td>
                                        <td class="text-end" width="40"><?= $total_barang ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Barang</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">Rp. <?= number_format($total_harga) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Diskon DB <?= $persen ?>%</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">- Rp. <?= number_format($diskon) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ongkir</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">Rp. <?= number_format($ongkir) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Biaya Dropship</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">Rp. <?= number_format($dropship) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Bayar</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end">Rp. <?= number_format($subtotal) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sisa Tagihan</th>
                                        <td class="text-center">:</td>
                                        <td class="text-end <?php echo ($sisa_tagihan > 0) ? "text-danger" : "text-success"; ?>">
                                            Rp. <?= number_format($sisa_tagihan) ?>
                                        </td>
                                    </tr>
                                    <?php if ($lebih > 0): ?>
                                        <tr>
                                            <th>Lebih Bayar</th>
                                            <td class="text-center">:</td>
                                            <td class="text-end text-success">
                                                Rp. <?= number_format($lebih) ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">...</div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
  $idpoproduk = $_GET['idpo'];
   $invoice = $_GET['invoice'];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
 
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- Load File bootstrap.min.css yang ada difolder css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Dropship</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
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

<!--================ NAVBARU END =================-->
   <div class="container"> 

 
  <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
    
    
    <div style="padding: 0 15px;">
              
    <form method="post">
      
      <div class="form-group">
          <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" required>
    </div>
    
     <div class="form-group">
          <label>Telepon Pengirim</label>
    <input type="number" class="form-control" name="tlppengirim" required>
    </div>    
    
    <hr>
    
      <div class="form-group">
          <label>Nama Penerima</label>
    <input type="text" class="form-control" name="namapenerima" required>
    </div>
    
    <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="number" class="form-control" name="tlppenerima" required>
    </div>
    
        <div class="form-group">
    <label>Alamat Penerima</label>
    <textarea class="form-control" name="alamatpenerima" required></textarea>
    </div>

    <div class="form-group">
      <label for="prov">Provinsi Tujuan</label><br>
      <select class="form-control" id="prov" name="prov" required>
         <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
         <?php
         include "koneksi.php";
         $idprov=$_SESSION["admin_mitra"]["provinsi"];
         
        
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
    <label>Keterangan</label>
    <textarea class="form-control" name="keterangan"></textarea>
    </div>
    
             <div class="form-group">
          <label>Ekspedisi</label>  
          <select class="form-control" name="ekspedisi">
              <option value="jne oke">JNE OKE</option>
              <option value="jne reg">JNE REG</option>
              <option value="jtr">JTR</option>
             <option value="jne yes">JNE YES</option>
              <option value="wahana">WAHANA</option>
              <option value="sicepat">SICEPAT</option>
              <option value="lion">LION PARCEL</option>
              <option value="j&t">J&T</option>
              <option value="tiki">TIKI</option>
              <option value='ide'>ID Express</option>
              <option value="pos kilat">POS KILAT</option>
              <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
              <option value="gosend">Gosend</option>              
          </select>      
            </div>
    
   <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
  </form>
</body>

<script type="text/javascript">

    $(document).ready(function(){

        $('#prov').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var provinsi = $('#prov').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_kabupaten_dropship.php',
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
              url : 'cek_kecamatan_dropship.php',
              data :  'kabupaten_id=' + kabupaten,
          success: function (data) {

          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#kecamatan").html(data);
        }
            });
    });


        
    });  
  
</script>

<?php
include "koneksi.php";

   
  if(isset($_POST['kirim'])){
    $namapengirim=addslashes(htmlspecialchars($_POST["namapengirim"]));
   $tlppengirim=addslashes(htmlspecialchars($_POST["tlppengirim"]));
    $namapenerima=addslashes(htmlspecialchars($_POST["namapenerima"]));
   $tlppenerima=addslashes(htmlspecialchars($_POST["tlppenerima"]));
   $alamatpenerima=addslashes(htmlspecialchars($_POST["alamatpenerima"]));
   $keterangan=addslashes(htmlspecialchars($_POST["keterangan"]));
   $ekspedisi=$_POST["ekspedisi"];


   $provinsi_id=$_POST["prov"];
    $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[0];
   
   $kabupaten_id=$_POST["kabupaten"];
   $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[0];
   
   $kecamatan_id=$_POST["kecamatan"];
   $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[0];
   

   
    $query = "insert into podropship (iddropship,idadmin,idmitraagen,idmitrareseller,idmitramarketer,idpoproduk,invoice,namapengirim,tlppengirim,namapenerima,tlppenerima,alamatpenerima,provinsi,kota,kecamatan,keterangan,ekspedisi) values
    (null,'$idadmin','0','0','0','$idpoproduk','$invoice','$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamatpenerima','$provinsi','$kabupaten','$kecamatan','$keterangan','$ekspedisi')";
    $sql = mysqli_query( $koneksi, $query);  

    if ($sql) {
     echo "<script>alert('data berhasil ditambah');</script>";
    echo "<script>location='listnewpo.php'</script>";
    }else{
      echo "<script>alert('data gagal ditambah');</script>";
    echo "<script>location='listnewpo.php'</script>";
    }
    
    } 
?>  
</html>