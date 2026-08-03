<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container pt-3">
    <h3 class="text-center">Informasi Penting Mitra Wanoja</h3>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Kumpulan Link
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:10%;"></th>
                                        <th style="width:30%;">Download</th>
                                        <th style="width:60%;">Item</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><a href="https://drive.google.com/drive/u/1/folders/1As2o3qWU0mDTrQ-5sRWwA7NbHc7owiX1" style="color: #7e7fe5;"><i class="fa-solid fa-download"></i>wlink</a></td>
                                        <td>Foto Produk</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><a href="https://wlink.id/unduhlogo" style="color: #7e7fe5;"><i class="fa-solid fa-download"></i>wlink</a></td>
                                        <td>Unduh Logo</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Kontak Penting
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Teh Ulfah :</strong>(628156030650)<br>
                                <strong>Abah Asep :</strong>(6285722289656)<br>
                                <strong>Pa Miftah :</strong>(6285222668906)<br>
                                <hr>
                                <strong>IT Support CS : Rohman </strong>(628996483941)<br>
                                <strong>Admin CS : Delita </strong>(6289655775486)<br>
                                <strong>CS1 : Intan </strong>(6289655775471)<br>
                                <strong>CS2 : Daniar </strong>(628986480323)<br>
                                <strong>CS3 : Zahra </strong>(6281286236131)<br>
                                <strong>CS4 : Yara </strong>(6289655775473)<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Social Media
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Instagram Official :</strong> @wnj.id<br>
                                <strong>Instagram Katalog :</strong> @wnj.id<br>                    
                                <strong>Fanspage :</strong> @wnj.id<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Rekening Pusat
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div>
                                a.n. <strong>Maria Ulfah Fathimah, M.Hum.</strong><br>
                                Muamalat 110 000 3930<br>
                                Bank Syariah Indonesia (BSI) 7105696706<br>
                                BRI 076201007469504<br>
                                BCA 7751043434
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Alamat Pusat
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div>
                            <strong>Wanoja Hijab</strong><br>Jl.Guntursariwetan I no.04 RT.03/11<br>Kel.Turangga Kec.Lengkong <br> Kota Bandung 40264 Jawa Barat <br>Indonesia
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- END SCRIPT -->
</body>
</html>