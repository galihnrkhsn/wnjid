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
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tbpesan">
                <thead class="table-primary">
                    <tr>
                        <th style="width:25%">Judul</th>
                        <th style="width:10%"><i class="fa fa-cog" aria-hidden="true"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $idadmin = $_SESSION["idadmin"];
                    $ambil = $koneksi->query("SELECT * FROM tinbox WHERE idadmin='$idadmin' ORDER BY idnbox DESC");
                    while($data = $ambil->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['judul']); ?></td>
                        <td>
                            <!-- <button type="button" class="btn <?php echo ($data['status'] == 'Belum Dibaca') ? 'btn-warning' : 'btn-secondary'; ?>" data-toggle="modal" data-target="#myModal<?php echo $data['idnbox']; ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button> -->
                            <button type="button" class="btn <?php echo ($data['status'] == 'Belum Dibaca') ? 'btn-warning' : 'btn-secondary'; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $data['idnbox']; ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal<?php echo $data['idnbox']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $data['idnbox']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $data['idnbox']; ?>"><?php echo htmlspecialchars($data['judul']); ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><?php echo nl2br(htmlspecialchars($data['isi'])); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <form method="post">
                                        <input type="hidden" name="idnbox" value="<?php echo $data['idnbox']; ?>">
                                        <button type="submit" class="btn btn-primary" name="tutup">Tutup Pesan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <!-- PHP SYNTAK -->
    <?php
        if(isset($_POST["tutup"])) {
            $idnbox = $_POST["idnbox"];
            $sql = $koneksi->query("UPDATE tinbox SET status ='Sudah Dibaca' WHERE idnbox='$idnbox'");
            echo "<script>location='pesan.php';</script>";
        }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>