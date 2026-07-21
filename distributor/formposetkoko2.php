<?php
  session_start();
  include 'koneksi.php';
  if(!isset($_SESSION["admin_mitra"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }

  $idpoproduk = $_GET['id'];
  $idadmin = $_SESSION["admin_mitra"]["idadmin"];
  $invoice = 'SKS'.$idpoproduk.'-'.$idadmin;
  $query = "SELECT COUNT(*) AS jumlah,
            poproduk.idpoproduk,
            poproduk.namapo,
            poproduk.status
            FROM poproduk
            INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
            WHERE poproduk.idpoproduk = '$idpoproduk'
            AND pomitra.idmitra = '$idadmin'
            AND pomitra.invoice = '$invoice'
          ";
  $sql = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_array($sql);
  $id = $data['idpoproduk'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ | Mitra <?= $_SESSION['admin_mitra']['namamitra']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    .navbaru {
      background: #eee center center;
      margin: auto;
      text-align: center;
      overflow: hidden;
    }
    .navbaru p {
      padding: 12px 0;
      font-size: 20px;
      color: #0f0f0a;
      text-align: center;
    }
    .navbaru i {
      padding: 15px 0;
      font-size: 23px;
      color: #0f0f0a;
      text-align: center;
    }
    .navbaru2 {
      margin: auto;
      text-align: center;
      overflow: hidden;
    }
  </style>
</head>
<body>

  <div class="row fixed-top navbaru py-3">
    <div class="col-2">
      <a href="listnewpo.php">
        <i class="bi bi-chevron-left"></i>
      </a>
    </div>
    <div class="col-8">
      <h4>PRE ORDER</h4>
    </div>
    <div class="col-2"></div>
  </div>

  <div class="container" style="margin-top: 5rem; margin-bottom: 5rem; font-size: .85rem">
    <div class="row">
      <div class="col-sm-12 text-center my-2">
        <h5 class="text-uppercase">Formulir pemesanan</h5>
      </div>
      <form method="post">
        <div class="col-sm-12 mb-3">
          <div class="row">
            <div class="col-sm-12 d-flex align-items-center gap-2">
              <p class="text-uppercase fw-bold mb-1 mr-2">Broken White</p>
              <!-- Button Tambah -->
              <a class="btn btn-sm btn-success mb-1" style="padding: 0 .2rem;" id="buttonAdd"><i class="bi bi-plus" style="font-size: 1rem"></i></a>
              <!-- Button Kurang -->
              <a class="btn btn-sm btn-danger mb-1" style="padding: 0 .2rem;"><i class="bi bi-dash" style="font-size: 1rem"></i></a>
            </div>

            <!-- Duplicate Input Di bawah ini -->
            <div class="col-sm-4">
              <?php
                $sql = "SELECT * FROM poproduk
                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                        AND podetail.variant LIKE '%Sarung Etnic Broken White%'
                        ORDER BY podetail.idpodetail ASC
                      ";
                $query = $koneksi->query($sql);
                while($row = $query->fetch_assoc()) {
              ?>
                <input type="hidden" name="idpodetail[]" id="" value="<?= $row['idpodetail'] ?>">
                <input type="hidden" name="idpo[]" id="" value="<?= $row['idpo'] ?>">
                <input type="hidden" name="harga[]" id="" value="<?= $row['harga'] ?>">
                <input type="text" value="<?= $row['variant'] ?>" class="form-control" id="<?= $row['variant'] ?>" disabled>
              <?php } ?>
            </div>
            <div class="col-sm-6">
              <select  id="selectIdpodetail" onChange="ubahIdpodetail()" class="form-select form-control-sm">
                <option value="">~ Pilih Variant Koko ~</option>
                <?php
                  $sql = "SELECT * FROM poproduk
                          INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                          INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                          WHERE poproduk.idpoproduk = '$idpoproduk'
                          AND podetail.variant LIKE '%Koko Lophura Bordir Royal White%'
                          ORDER BY podetail.idpodetail ASC
                        ";
                  $query = $koneksi->query($sql);
                  while($row = $query->fetch_assoc()) {
                ?>
                  <option value="<?= $row['variant'] ?>" data-warna="<?= $row['idpodetail'] ?>" data-idpo="<?= $row['idpo'] ?>">
                    <?= $row['variant'] ?>
                  </option>
                <?php } ?>
              </select>
              <input type="text" class="form-control" id="idpodetail" name="idpodetail[]" disabled>
              <input type="text" class="form-control" id="idpo" name="idpo[]" disabled>
            </div>
            <div class="col-sm-2">
              <input type="number" name="qty[]" value="0" class="form-control" min="0">
            </div>

            <div class="col-sm-12">
              <hr class="my-2" />
            </div>

            <div id="newForm"></div>
          </div>
        </div>
        
        <div class="col-sm-12 mb-3">
          <div class="row">
            <div class="col-sm-12 col-sm-12 d-flex align-items-center gap-2">
              <p class="text-uppercase fw-bold mb-1">Royal Black</p>
              <button class="btn btn-sm btn-success mb-1" style="padding: 0 .2rem;"><i class="bi bi-plus" style="font-size: 1rem"></i></button>
              <button class="btn btn-sm btn-danger mb-1" style="padding: 0 .2rem;"><i class="bi bi-dash" style="font-size: 1rem"></i></button>
            </div>
            <div class="col-sm-4">
              <?php
                $sql = "SELECT * FROM poproduk
                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                        AND podetail.variant LIKE '%Sarung Etnic Royal Black%'
                        ORDER BY podetail.idpodetail ASC
                      ";
                $query = $koneksi->query($sql);
                while($row = $query->fetch_assoc()) {
              ?>
                <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                <input type="text" value="<?= $row['variant'] ?>" class="form-control" id="<?= $row['variant'] ?>" disabled>
              <?php } ?>
            </div>

            <div class="col-sm-6">
              <select name="" id="" class="form-select form-control-sm">
                <?php
                  $sql = "SELECT * FROM poproduk
                          INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                          INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                          WHERE poproduk.idpoproduk = '$idpoproduk'
                          AND podetail.variant LIKE '%Koko Lophura Bordir Royal Black%'
                          ORDER BY podetail.idpodetail ASC
                        ";
                  $query = $koneksi->query($sql);
                  while($row = $query->fetch_assoc()) {
                ?>
                  <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2">
              <input type="number" name="qty[]" value="0" class="form-control" min="0">
            </div>
          </div>
        </div>

        <div class="col-sm-12 mb-3">
          <div class="row">
            <div class="col-sm-12 col-sm-12 d-flex align-items-center gap-2">
              <p class="text-uppercase fw-bold mb-1">Soft Lavender</p>
              <button class="btn btn-sm btn-success mb-1" style="padding: 0 .2rem;"><i class="bi bi-plus" style="font-size: 1rem"></i></button>
              <button class="btn btn-sm btn-danger mb-1" style="padding: 0 .2rem;"><i class="bi bi-dash" style="font-size: 1rem"></i></button>
            </div>
            <div class="col-sm-4">
              <?php
                $sql = "SELECT * FROM poproduk
                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                        AND podetail.variant LIKE '%Sarung Etnic Soft Lavender%'
                        ORDER BY podetail.idpodetail ASC
                      ";
                $query = $koneksi->query($sql);
                while($row = $query->fetch_assoc()) {
              ?>
                <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                <input type="text" value="<?= $row['variant'] ?>" class="form-control" id="<?= $row['variant'] ?>" disabled>
              <?php } ?>
            </div>

            <div class="col-sm-6">
              <select name="" id="" class="form-select form-control-sm">
                <?php
                  $sql = "SELECT * FROM poproduk
                          INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                          INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                          WHERE poproduk.idpoproduk = '$idpoproduk'
                          AND podetail.variant LIKE '%Koko Lophura Bordir Soft Choco%'
                          ORDER BY podetail.idpodetail ASC
                        ";
                  $query = $koneksi->query($sql);
                  while($row = $query->fetch_assoc()) {
                ?>
                  <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2">
              <input type="number" name="qty[]" value="0" class="form-control" min="0">
            </div>
          </div>
        </div>

        <div class="col-sm-12 mb-3">
          <div class="row">
            <div class="col-sm-12 col-sm-12 d-flex align-items-center gap-2">
              <p class="text-uppercase fw-bold mb-1">Soft Tosca</p>
              <button class="btn btn-sm btn-success mb-1" style="padding: 0 .2rem;"><i class="bi bi-plus" style="font-size: 1rem"></i></button>
              <button class="btn btn-sm btn-danger mb-1" style="padding: 0 .2rem;"><i class="bi bi-dash" style="font-size: 1rem"></i></button>
            </div>
            <div class="col-sm-4">
              <?php
                $sql = "SELECT * FROM poproduk
                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                        AND podetail.variant LIKE '%Sarung Etnic Soft Tosca%'
                        ORDER BY podetail.idpodetail ASC
                      ";
                $query = $koneksi->query($sql);
                while($row = $query->fetch_assoc()) {
              ?>
                <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                <input type="text" value="<?= $row['variant'] ?>" class="form-control" id="<?= $row['variant'] ?>" disabled>
              <?php } ?>
            </div>

            <div class="col-sm-6">
              <select name="" id="" class="form-select form-control-sm">
                <?php
                  $sql = "SELECT * FROM poproduk
                          INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                          INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                          WHERE poproduk.idpoproduk = '$idpoproduk'
                          AND podetail.variant LIKE '%Koko Lophura Bordir Soft Tosca%'
                          ORDER BY podetail.idpodetail ASC
                        ";
                  $query = $koneksi->query($sql);
                  while($row = $query->fetch_assoc()) {
                ?>
                  <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-2">
              <input type="number" name="qty[]" value="0" class="form-control" min="0">
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <button class="btn btn-primary" name="save">Kirim</button>
        </div>
      </form>
    </div>
  </div>

  <?php
    if(isset($_POST["save"])){
      date_default_timezone_set('Asia/Jakarta');
      $today = date("s");
      $waktu = date("H:i:s");
      $idpodetail = $_POST["idpodetail"];
      $jmlh = $_POST["qty"];
      $jumlah_dipilih = count($jmlh);

      for($x=0; $x<$jumlah_dipilih; $x++){
        $sql = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail[$x]'";
        $query = $koneksi->query($sql);
        $detail = $query->fetch_assoc();
        $harga = $detail['harga'];
        $idpo = $detail['idpo'];
        $total = $jmlh[$x]*$harga;
        $koneksi->query("INSERT INTO pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) VALUES
        (NULL,'$idadmin','$idpoproduk','$idpo','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum DP',NOW(),'$waktu')");

        echo "<script>alert('data berhasil dikirim');</script>";
        echo "<script>location='datapom2.php?id=$idpoproduk&invoice=$invoice';</script>";
      }
    }
  ?>

  <script>

    // Mengubah Form
    function ubahIdpodetail() {
      var selectIdpodetail = document.getElementById("selectIdpodetail");
      var inputIdpodetail = document.getElementById("idpodetail");
      var inputIdpo = document.getElementById("idpo");
      var warna = selectIdpodetail.options[selectIdpodetail.selectedIndex].getAttribute("data-warna");
      var idpo = selectIdpodetail.options[selectIdpodetail.selectedIndex].getAttribute("data-idpo");
      inputIdpodetail.value = warna;
      inputIdpo.value = idpo;
    }

    const buttonAdd = document.getElementById("buttonAdd");
    const newForm = document.getElementById("newForm");
    let formCount = 0;

    buttonAdd.addEventListener('click', function() {
      formCount++;
      
      // New Form
      const form = document.createElement('div');
      form.innerHTML = `
        <div class="row">
          <div class="col-sm-4">
            <?php
              $sql = "SELECT * FROM poproduk
                      INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                      INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                      WHERE poproduk.idpoproduk = '$idpoproduk'
                      AND podetail.variant LIKE '%Sarung Etnic Broken White%'
                      ORDER BY podetail.idpodetail ASC
                    ";
              $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()) {
            ?>
              <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
              <input type="text" value="<?= $row['variant'] ?>" class="form-control" id="<?= $row['variant'] ?>" disabled>
            <?php } ?>
          </div>

          <div class="col-sm-6">
            <select name="" id="" class="form-select form-control-sm">
              <?php
                $sql = "SELECT * FROM poproduk
                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                        WHERE poproduk.idpoproduk = '$idpoproduk'
                        AND podetail.variant LIKE '%Koko Lophura Bordir Royal White%'
                        ORDER BY podetail.idpodetail ASC
                      ";
                $query = $koneksi->query($sql);
                while($row = $query->fetch_assoc()) {
              ?>
                <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-2">
            <input type="number" name="qty[]" value="0" class="form-control" min="0">
          </div>

          <div class="col-sm-12">
            <hr class="my-2" />
          </div>
        </div>
      `;

      newForm.appendChild(form);
    });

    function updateInput(formIndex) {
      var select = document.getElementById('idpodetail_' + formIndex);
      var input = document.getElementById('idpo_' + formIndex);

      var selectedOption = select.options[select.selectedIndex];
      var idpodetailValue = selectedOption.getAttribute('data-idpodetail');
      var idpoValue = selectedOption.getAttribute('data-idpo');
      
      idpo.value =idpoValue;
      input.value = idpodetailValue;
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
