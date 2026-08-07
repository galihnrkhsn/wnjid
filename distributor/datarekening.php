<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <title>Distributor | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container pt-5">
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah Rekening</button>
        </div>
        <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Rekening</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="rekeningForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="mb-3">
                                <label for="masukkanBank" class="col-form-label">Nama Bank:</label>
                                <input type="text" class="form-control" id="masukkanBank"  name="bank" required >
                            </div>
                            <div class="mb-3">
                                <label for="masukkanNama" class="col-form-label">Nama Pemilik:</label>
                                <input type="text" class="form-control" id="masukkanNama"  name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="masukkanRekening" class="col-form-label">No Rekening:</label>
                                <input type="number" class="form-control" id="masukkanRekening"  name="rekening" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" form="rekeningForm" class="btn btn-primary" name="tambah">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tbpesan">
                <thead>
                    <tr>
                        <th >No</th>
                        <th >Nama Bank</th>
                        <th >Nama Pemilik</th> 
                        <th >No Rekening</th>
                        <th >Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $idadmin    = $_SESSION["idadmin"];
                        $no         = 1;
                        $ambil      = $koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                        while($distributor=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $no++;?></td>
                        <td><?php echo $distributor['bank'];?></td>
                        <td><?php echo $distributor['namapemilik'];?></td>
                        <td><?php echo $distributor['rekening'];?></td>
                        <td><form method="post"><input type="hidden" name="idrekening" value="<?php echo $distributor['idrek'];?>"><button class="btn btn-danger" name="hapus"><span class="fa fa-trash"></span></button></form></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
                <?php
                    if(isset($_POST["hapus"])){

                    $idrekening = $_POST['idrekening'];
                    //$status=$_POST['status'];
                    
                    $query = "DELETE FROM rekeningku where idrek='$idrekening'";    
                    $sql = mysqli_query( $koneksi, $query);
                        if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                            // Jika Sukses, Lakukan :
                            echo "<script>alert('Rekening Berhasil Dihapus');</script>";
                                echo "<script>location='datarekening.php'</script>";
                        }else{
                            // Jika Gagal, Lakukan :
                            echo "Maaf, Terjadi kesalahan saat mencoba untuk menghapus data";
                                echo "<script>location='datarekening.php'</script>";
                            // echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                        }
                    }    
                ?>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <?php
        include 'koneksi.php';
        if (isset($_POST['tambah'])) {
            $idadmin = $_SESSION["idadmin"];
            $bank = $_POST['bank'];
            $nama = $_POST['nama'];
            $rekening = $_POST['rekening'];

            $checkRekening = "SELECT rekening FROM rekeningku WHERE rekening = '$rekening'";
            $checkRekeningResult = $koneksi->query($checkRekening);
            
            if ($checkRekeningResult->num_rows > 0) {
                echo "<script>alert('Rekening sudah terdaftar. Silakan gunakan Rekening lain.'); window.location.href = 'datarekening.php';</script>";
                exit();
            }
            
            $sql = "INSERT INTO rekeningku (idrek, idadmin, bank, namapemilik, rekening) VALUES (NULL, '$idadmin', $bank', '$nama', '$rekening')";
            $resultSql = $koneksi->query($sql);
            
            if ($resultSql) {
                echo "<script>alert('Data berhasil disimpan.'); window.location.href = 'datarekening.php';</script>";
                exit();
            } else {
                echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'datarekening.php';</script>";
            } 
        }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>