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
    <title>Reseller | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container pt-3">
        <h3 class="text-center">Informasi Penting Mitra Wanoja</h3>
        <div class="accordion pt-3" id="accordionExample">
            <?php
            include "koneksi.php";
            $sql = mysqli_query($koneksi, "SELECT * FROM tutorial WHERE idmitra=0 ORDER BY idtutorial ASC");
            while ($data = mysqli_fetch_array($sql)) {
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $data['idtutorial']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $data['idtutorial']; ?>">
                            <?php echo $data['judul']; ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $data['idtutorial']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col">
                                    <iframe width="250" src="https://www.youtube.com/embed/<?php echo $data['link']; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="col">
                                    <a class="btn btn-danger" href="viewpdf.php?id=<?php echo $data['idtutorial']; ?>">Download PDF</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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