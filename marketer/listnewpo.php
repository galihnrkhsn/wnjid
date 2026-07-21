<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';

    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $ambil              = $koneksi->query("SELECT mitramarketer.idmitramarketer, mitramarketer.namaagen, mitramarketer.mode FROM mitramarketer where idmitramarketer='$idmitramarketer' ");
    $mode               = $ambil->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
    <style>
        .aText {
            color: red;
        }
        .bText {
            color: green;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container">
        <!--<div class="jumbotron" >-->

        <!--  <div class="row ">-->
            
        
        <!--  <div class="col-2">-->
        <!--    <img src="foto/<?php echo $mode['foto'] ?>" style="width:55px;height:55px;border-radius: 50%;">-->
        <!--  </div>-->
        <!--  <div class="col-6"><b><?php echo $mode['namamitra'] ?></b><br>-->
        <!--<b style="font-size: 13px"><?php echo "(Distributor_".$mode['idmitramarketer'].')';?></b><br>-->
        
        <!--  </div>-->
        
        <!--  <div class="col-1"> -->
            
        <!--  </div>-->
        <!--  <div class="col-2">-->
        <!--    <a href="logout.php"><i class="fa fa-power-off logout-mobile" style="font-size:36px;color:primary;"></i></a>-->
        <!--  </div>-->
        <!--  <div class="col-1"> -->
            
        <!--  </div>-->
        <!--</div>-->
        <!--</div>-->
    </div>

    <!--================ NAVBARU END =================-->


    <div class="container mt-5" align="center">
    <!-- 
    <button type="submit" class="btn btn-info btn-lg" name="cari" id="linkinner">
        <a  style="color:white" href="formpoori?id=158">Link PO Sample Bergo Fulica</a>
    </button>
    <p id="demoinner"></p>
    <br>  -->

    <script>
    // // Mengatur waktu akhir perhitungan mundur
    // var countDownDateinner = new Date("2022-12-26 09:00:00").getTime();

    // // Memperbarui hitungan mundur setiap 1 detik
    // var x = setInterval(function() {

    //   // Untuk mendapatkan tanggal dan waktu hari ini
    //   var now = new Date().getTime();
        
    //   // Temukan jarak antara sekarang dan tanggal hitung mundur
    //   var distance = countDownDateinner - now;
        
    //   // Perhitungan waktu untuk hari, jam, menit dan detik
    //   var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    //   var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //   var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    //   var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
    //   // Keluarkan hasil dalam elemen dengan id = "demo"
    //   document.getElementById("demoinner").innerHTML = days + "d " + hours + "h "
    //   + minutes + "m " + seconds + "s ";
        
    //   // Jika hitungan mundur selesai, tulis beberapa teks 
    //   if (distance < 0) {
    //     clearInterval(x);
    //     document.getElementById("demoinner").innerHTML = "Link PO tidak tersedia";
    //       var x = document.getElementById("linkinner");
    
    //     //x.style.display = "block";
    //     x.style.display = "none";
    //     }
    // }, 1000);
    </script>

    <!-- 
    <button type="submit" class="btn btn-info btn-lg" name="cari" id="linkinnernya">
        <a  style="color:white" href="formpobrooch_customstok.php?id=137">Link PO Brooch Custom Name Batch V WNJ</a>
    </button>
    <p id="demoinnernya"></p>
    <br>  -->

    <script>
    // Mengatur waktu akhir perhitungan mundur
    // var countDownDateinner1 = new Date("Oct 10, 2022 23:59:59").getTime();

    // // Memperbarui hitungan mundur setiap 1 detik
    // var x = setInterval(function() {

    //   // Untuk mendapatkan tanggal dan waktu hari ini
    //   var now = new Date().getTime();
        
    //   // Temukan jarak antara sekarang dan tanggal hitung mundur
    //   var distance = countDownDateinner1 - now;
        
    //   // Perhitungan waktu untuk hari, jam, menit dan detik
    //   var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    //   var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //   var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    //   var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
    //   // Keluarkan hasil dalam elemen dengan id = "demo"
    //   document.getElementById("demoinnernya").innerHTML = days + "d " + hours + "h "
    //   + minutes + "m " + seconds + "s ";
        
    //   // Jika hitungan mundur selesai, tulis beberapa teks 
    //   if (distance < 0) {
    //     clearInterval(x);
    //     document.getElementById("demoinnernya").innerHTML = "Link PO tidak tersedia";
    //       var x = document.getElementById("linkinnernya");
    
    //     //x.style.display = "block";
    //     x.style.display = "none";
    //     }
    // }, 1000);
    </script>


    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    <?php
    $dataproduk=$koneksi->query("SELECT bukapo.idbpo,
                                        bukapo.jenis_mitra,
                                        bukapo.jenis_po,
                                        bukapo.idpoproduk,
                                        bukapo.tgl,
                                        bukapo.tgl_dropship,
                                        bukapo.status,
                                        poproduk.namapo 
                                    FROM bukapo inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                                    WHERE bukapo.status = 'PUBLISH' 
                                    and (bukapo.jenis_mitra = 'Semua Mitra') 
                                    order by bukapo.tgl desc
                                ");
    while($tampilkan=$dataproduk->fetch_assoc()){
    ?>

        <button type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>" style="white-space: normal;width: auto;">
            <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
                <a  style="color:white" href="formpostok?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO tanpa Stok" && $tampilkan['idpoproduk'] != '299'): ?>
                <a  style="color:white" href="formpoku?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
                <a  style="color:white" href="formpo_tab?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO Custom Tab Stok"): ?>
                <a  style="color:white" href="formpo_tabstok?id=<?php echo $tampilkan['idpoproduk']; ?>">Link <?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>      
            <?php if ($tampilkan['jenis_po']=="PO Custom Tab Stok Max"): ?>
                <a  style="color:white" href="formpo_tabstokmax?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>      

            <?php if ($tampilkan['jenis_po']=="PO Konin"): ?>
                <a  style="color:white" href="pokonin?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO Kolibri"): ?>
                <a  style="color:white" href="pokolibri?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO Miki Custom"): ?>
                <a  style="color:white" href="formpomikicustomstock?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
            <?php endif ?>
            <?php if ($tampilkan['jenis_po']=="PO Miki Polos"): ?>
                <a  style="color:white" href="formpomikipolosstock?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Polos)</a>
            <?php endif ?>


            <?php if ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
                <a  style="color:white" href="formpobrooch_custom?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>  
            <?php if ($tampilkan['jenis_po']=="PO Bagi Rata"): ?>
                <a  style="color:white" href="formbagirata?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>    
            <?php if ($tampilkan['jenis_po']=="PO Hampers"): ?>
                <a  style="color:white" href="formpo_thr?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>  

            <?php if ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
                <a  style="color:white" href="formpo_karakterstok?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
            <?php endif ?>                          
            
            <?php
                include 'koneksi.php';
                $idmitramarketer=$_SESSION ['idmitramarketer'];
            ?>
            
            <?php if ($tampilkan['jenis_po']=="PO custom Rocela"): ?>
                <a  style="color:white" href="formporocela?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
            <?php endif ?>                          
            
            <?php if ($tampilkan['jenis_po']=="PO custom Goura"): ?>
                <a  style="color:white" href="formpogoura?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
            <?php endif ?>                          
            
            <?php if ($tampilkan['jenis_po']=="PO custom Bundling"): ?>
                <a  style="color:white" href="formpocustomgabungan?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?> (Custom)</a>
            <?php endif ?>
            
            <?php if ($tampilkan['jenis_po']=="PO Custom Inner"): ?>
                <a  style="color:white" href="formpoinner.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            
            <?php if ($tampilkan['idpoproduk'] == 260 ): ?>
                <!--nama file nya formpocustomlegging.php?id=260-->
                <a  style="color:white" href="formpocustomlegging?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>
            
            <?php if ($tampilkan['idpoproduk'] == 261 ): ?>
                <!--nama file nya formpocustomlegging2.php?id=261-->
                <a  style="color:white" href="formpocustomlegging2?id=<?php echo $tampilkan['idpoproduk']; ?>&idmitramarketer=<?= $idmitramarketer ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>
            
            <?php if ($tampilkan['idpoproduk'] == 269 ): ?>
                <!--nama file nya formpocustomlegging2.php?id=261-->
                <a  style="color:white" href="formpomatari.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>
            
            <?php if ($tampilkan['jenis_po'] == "PO Ducula Stok" ): ?>
                <!--nama file nya formpocustomlegging2.php?id=261-->
                <a  style="color:white" href="formpoducula.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>

            <?php if ($tampilkan['jenis_po'] == "PO Tazmahal" ): ?>
                <!--nama file nya formpocustomlegging2.php?id=261-->
                <a  style="color:white" href="formpotazmahal.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>
            <?php if ($tampilkan['jenis_po'] == "PO Bundling 2"): ?>
                <a  style="color:white" href="formpobundling2?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $idmitra ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif ?>
            <?php if ($tampilkan['idpoproduk'] == "289" ): ?>
                <a  style="color:white" href="formposongkok.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>

            <?php if ($tampilkan['idpoproduk'] == "331" ): ?>
                <a  style="color:white" href="pocustom.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>

            <?php if ($tampilkan['jenis_po']=="PO Bundling Custom"): ?>
                <a  style="color:white" href="formpobundling.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>
            
            <?php if ($tampilkan['idpoproduk'] == "299" or $tampilkan['idpoproduk'] == "301"): ?>
                <a  style="color:white" href="formpovoal_custom.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
            <?php endif; ?>


        </button>
        <p id="demomiki<?= $tampilkan['idbpo']; ?>" >


        </p>
        <br>


    <script>

    // Mengatur waktu akhir perhitungan mundur
    var countDownDatemiki<?= $tampilkan['idbpo']; ?>= new Date("<?php echo $tampilkan['tgl']; ?> 23:59:00").getTime();

    // Memperbarui hitungan mundur setiap 1 detik
    var x = setInterval(function() {

    // Untuk mendapatkan tanggal dan waktu hari ini
    var now = new Date().getTime();
        
    // Temukan jarak antara sekarang dan tanggal hitung mundur
    var distance = countDownDatemiki<?= $tampilkan['idbpo']; ?> - now;
        
    // Perhitungan waktu untuk hari, jam, menit dan detik
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
    // Keluarkan hasil dalam elemen dengan id = "demo"
    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";
        
    // Jika hitungan mundur selesai, tulis beberapa teks 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = "Link PO tidak tersedia";
        var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
    
        //x.style.display = "block";
        x.style.display = "none";
        }
    }, 1000);
    </script>

    <?php } ?>  
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

    
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------->

        
        <div class="text-center mt-5 mb-5" style="color: var(--color1)">
            <h3>PO Regular</h3>
        </div> 

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th class="col-4 text-center">Tanggal</th>
                        <th class="col-6 text-center">Nama PO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include / load file koneksi.php
                    include "koneksi.php";
                    $idmitramarketer = $_SESSION['idmitramarketer'];

                    $sql = mysqli_query($koneksi, "SELECT
                            pomitra.tgl,
                            pomitra.status AS pomitra_status,
                            pomitra.invoice,
                            poproduk.namapo,
                            poproduk.idpoproduk,
                            poproduk.status AS poproduk_status,
                            poproduk.jenis
                        FROM 
                            pomitra
                        INNER JOIN 
                            poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
                        LEFT JOIN 
                            mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                        LEFT JOIN 
                            admin_mitra ON mitramarketer.idadmin = admin_mitra.idadmin  
                        WHERE 
                            (pomitra.idmitramarketer = '$idmitramarketer')
                            AND poproduk.status = 'Open'
                            AND poproduk.tipe = 'Normal'
                            AND poproduk.idpoproduk > 233
                        GROUP BY 
                            poproduk.idpoproduk, pomitra.tgl, pomitra.status, pomitra.invoice, poproduk.namapo, poproduk.status, poproduk.jenis
                        ORDER BY 
                            pomitra.tgl DESC
                    ");
                    
                    while($data = mysqli_fetch_array($sql)){
                        $idpoproduk = $data['idpoproduk'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo htmlspecialchars($data['tgl']); ?></td>
                        <td class="text-center">
                            <?php if ($data['jenis'] == 'Kolibri'): ?>
                                <a href="detailkolibri?id=<?php echo $data['idpoproduk']; ?>"><?php echo htmlspecialchars($data['namapo']); ?></a>
                            <?php elseif($data['idpoproduk'] == '259' || $data['idpoproduk'] == '267'): ?>
                                <a href="detailkonin?id=<?php echo $data['idpoproduk']; ?>"><?php echo htmlspecialchars($data['namapo']); ?></a>
                            <?php else: ?>
                                <a href="detailpo?id=<?php echo $data['idpoproduk']; ?>" class="aText"><?php echo htmlspecialchars($data['namapo']); ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <? include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>