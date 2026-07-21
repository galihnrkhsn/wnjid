<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitraagen = $_SESSION["idmitraagen"];
    
    $query = $koneksi->query("SELECT 
                                    poproduk.idpoproduk, poproduk.namapo, podropship.invoice
                                FROM
                                    poproduk
                                        JOIN
                                    podropship ON podropship.idpoproduk = poproduk.idpoproduk
                                WHERE
                                    podropship.invoice = '$invoice'
                            ");
    $data = $query->fetch_assoc();
    $idpoproduk = $data['idpoproduk'];
    $namapo = $data['namapo'];

    $sqlmitra = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$idmitraagen'");
    $datamitra = $sqlmitra->fetch_assoc();
    $idadmin = $datamitra['idadmin'];
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WNJ | Form <?= $namapo ?></title>
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
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">    </head>
    </head>

    <body>
        <nav class="navbar bg-body-tertiary">
            <div class="container d-flex align-items-center">
                <a href="pokonin.php?id=<?= $idpoproduk ?>" class="text-muted fw-semibold"><i class="bi bi-chevron-left"></i></a>
                <p class="navbar-brand text-uppercase fw-semibold mb-0" href="#">Pre Order</p>
                <i class="opacity-0 bi bi-chevron-right"></i>
            </div>
        </nav>

        <div class="container mt-3">
            <div class="col-sm-12 text-center">
                <h4>Formulir Pemesanan <?= $namapo ?><br /><?= $data['invoice'] ?></h4>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form method="POST">		
                                <div style="padding: 0 15px;">
                                    <ul class="nav nav-tabs">
                                        <?php
                                            $noo    = 1;
                                            $sql    = "SELECT * FROM bukapo_tab 
                                                        WHERE idpoproduk = '$idpoproduk' order by id asc";
                                            $query  = $koneksi->query($sql);
                                            while($row = $query->fetch_assoc()){
                                        ?>	
                                            <?php if ($noo==1): ?>
                                                <li class="active"><a data-toggle="tab" href="#home<?= $row['id']; ?>"  class="nav-item nav-link active"><?= $row['nama_tab']; ?></a></li>
                                            <?php else: ?>				
                                                <li class=""><a data-toggle="tab" href="#home<?= $row['id']; ?>" class="nav-item nav-link"><?= $row['nama_tab']; ?></a></li>
                                            <?php endif ?>
                                            <?php $noo++; ?>	
                                        <?php } ?>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        <?php
                                            $no         = 1;
                                            $sql_isi    = "SELECT * FROM bukapo_tab WHERE idpoproduk='$idpoproduk' order by id asc";
                                            $query_isi  = $koneksi->query($sql_isi);
                                            while($row_isi = $query_isi->fetch_assoc()){
                                                $id_awal    = $row_isi['id_awal'];
                                                $id_akhir   = $row_isi['id_akhir'];		
                                        ?>	
                                            <?php if ($no==1): ?>
                                                <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade active show in">
                                            <?php else: ?>
                                                <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade ">					
                                            <?php endif ?>		
                                                <?php
                                                    $no++;
                                                    $idpoproduk     = $_GET['id'];
                                                    $sql_variant    = "SELECT * FROM poproduk 
                                                                        inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk
                                                                        inner join podetail on pokategori.idpo=podetail.idpo
                                                                        where poproduk.idpoproduk='$idpoproduk' and (podetail.idpodetail BETWEEN '$id_awal' AND '$id_akhir') order by pokategori.idpo asc";
                                                    $query_variant  = $koneksi->query($sql_variant);
                                                        while($row_variant = $query_variant->fetch_assoc()){
                                                ?>
                                                    <div class="form-group">
                                                        <label><?php echo $row_variant['variant']; ?></label>
                                                        <input type="hidden" name="idpodetail[]" value="<?php echo $row_variant['idpodetail']; ?>">
                                                        <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
                                                    </div>	
                                                <?php } ?>
                                            </div>
                                        <?php } ?>	
                                    </div>
                                    <?php 
                                        if($data['jumlah']<1){
                                            echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
                                        } else {
                                            echo "# ";
                                        }
                                    ?>
                                </div>
                            </form>            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if(isset($_POST["save"])){
                include "koneksi.php";
                date_default_timezone_set('Asia/Jakarta');
                $today          = date("s");
                $waktu          = date("H:i:s");
                $idmitraagen    = $_SESSION["idmitraagen"];
                $idpodetail     = $_POST["idpodetail"];
                $jmlh           = $_POST["jmlh"];
                    
                $jumlah_dipilih = count($jmlh);
                $subtotal       = 0;  
                $total          = 0;
                $jmlhakhir      = 0;

                if (isset($_GET['invoice'])) {
                    $invoice = $_GET['invoice'];
                } else {  
                    // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                    function generateAngkaAcak($length) {
                        $angka_acak = '';
                        for ($i = 0; $i < $length; $i++) {
                        // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                        $angka_acak .= mt_rand(0, 9);
                        }
                        return $angka_acak;
                    }
                    // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                    $angka_acak = generateAngkaAcak(5);
                    $invoice = 'A' . $idpoproduk . '-' . $idadmin . $angka_acak;
                }

                for($x=0;$x<$jumlah_dipilih;$x++){
                    if ($jmlh[$x] == 0 || $jmlh[$x] == "" || $jmlh[$x] == null) {
                        continue;
                    }
                    $query_variant  = "SELECT podetail.harga, podetail.idpo
                                        FROM podetail
                                        WHERE podetail.idpodetail='$idpodetail[$x]'";
                    $sql_variant    = mysqli_query($koneksi, $query_variant);  
                    $data_variant   = mysqli_fetch_array($sql_variant);
                    $harga          = $data_variant['harga'];
                    $idpo           = $data_variant['idpo'];
                    $total          = $jmlh[$x]*$harga;
                    $tot            = $total;
                    $jmlhakhir      += $jmlhakhir+$jmlh[$x];
                    $tot            = 0;

                    $sql            = $koneksi->query("INSERT into pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) 
                                                        values
                                                            (null,'$idmitraagen','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum Acc DB',NOW(),'$waktu')"); 
                }
                if ($sql) {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='datapokonin.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('data gagal dikirim');</script>";
                    echo "<script>location='datapokonin?id=$idpoproduk';</script>";	
                }
            } 
        ?>   

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>