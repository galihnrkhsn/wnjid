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
    <title>Marketer | WNJ.ID</title>
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
    <?php
        $idmitramarketer = $_SESSION["idmitramarketer"];
        $query = "SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'";
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
                        <a class="link" href="form_ubah.php?idmitramarketer=<?php echo $_SESSION ['idmitramarketer']; ?>"><i class="fa fa-edit"></i> Ubah</a>
                    </button>
                </div>
            </div>
        </div>
    <?php } ?>
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