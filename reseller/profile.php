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
    <?php
    $idmitrareseller = $_SESSION["idmitrareseller"];
    $query = "SELECT * FROM mitrareseller WHERE idmitrareseller='$idmitrareseller'";
    $result = $koneksi->query($query);

    while ($row = $result->fetch_assoc()) {
    ?>
    <div class="container pt-5">
        <div class="card mx-auto text-center" style="width: 18rem;">
			<div class="card-body">
                <h5 class="card-title"><?php echo $row["namaagen"]; ?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="fa-brands fa-whatsapp"></i> <?php echo $row['whatsapp']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-telegram"></i> <?php echo $row['telegram']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-facebook"></i> <?php echo $row['facebook']; ?></li>
                <li class="list-group-item"><i class="fa-brands fa-instagram"></i> <?php echo $row['instagram']; ?></li>
                <li class="list-group-item"><i class="fa-solid fa-location-dot"></i> <?php echo $row['alamat']; ?></li>
            </ul>
            <div class="card-body">
                <button type="button" class="btn btn-primary">
                    <a class="link" href="form_ubah.php?idmitrareseller=<?php echo $_SESSION ['idmitrareseller']; ?>"><i class="fa fa-edit"></i> Ubah</a>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <? 
       $idmitrareseller=$_SESSION ['idmitrareseller'];
            if(isset($_POST['save'])){
            $nama= $_FILES['foto']['name'];
            $lokasi=$_FILES['foto']['tmp_name'];
            move_uploaded_file($lokasi, "../foto/".$nama);
            $koneksi->query("UPDATE admin_mitra SET foto='$nama' WHERE idmitrareseller='$idmitrareseller'");

            echo "<div class='alert alert-info'>upload photo berhasil</div>";
            echo "<script>location='index3.php';</script>";
        }
    ?>
    <? include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>