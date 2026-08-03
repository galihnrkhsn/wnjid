<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller | Wanoja</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="list-group">
                            <div class="btn btn-primary border border-dark mb-3">
                                <a href="datarekening.php">
                                    <h5 class="mb-1">Data Rekeningku</h5>
                                </a>
                            </div>
                            <!-- <div class="btn btn-primary border border-dark mb-3">
                                <a href="ubahmode.php">
                                    <h5 class="mb-1">Set Mode Default</h5>
                                </a>
                            </div> -->
                            <div class="btn btn-primary border border-dark mb-3">
                                <a href="dataalamat.php">
                                    <h5 class="mb-1">Setting Alamat-mu</h5>
                                </a>
                            </div>
                            <div class="btn btn-primary border border-dark mb-3">
                                <a href="form_ubah_password.php?idadmin=<?php echo $_SESSION['idadmin']; ?>">
                                    <h5 class="mb-1">Ubah Password</h5>
                                </a>
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

    <!-- PHP -->
    <?php include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>