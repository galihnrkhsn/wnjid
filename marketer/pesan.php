<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
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
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
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
                $idmitramarketer = $_SESSION["idmitramarketer"];
                $ambil = $koneksi->query("SELECT * FROM tinbox WHERE idmitramarketer='$idmitramarketer' ORDER BY idnbox DESC");
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

        <?php
        if(isset($_POST["tutup"])) {
            $idnbox = $_POST["idnbox"];
            $sql = $koneksi->query("UPDATE tinbox SET status ='Sudah Dibaca' WHERE idnbox='$idnbox'");
            echo "<script>location='pesan.php';</script>";
        }
        ?>
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
    <script type="text/javascript">
        function checkAll(box) 
        {
            let checkboxes = document.getElementsByTagName('input');

            if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                    }
                }
            } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                    }
                }
            }
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>