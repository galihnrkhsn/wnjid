<?php
  session_start();
  include 'koneksi.php';

  if(!isset($_SESSION["mitraagen"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login2.php';</script>";
    header('location:login2.php');
    exit();
  }
//   var_dump(isset($_SESSION["mitraagen"]));
//   die();

  $idpoproduk = $_GET['id'];
  $query = "SELECT poproduk.idpoproduk,poproduk.namapo 
            FROM poproduk 
            WHERE poproduk.idpoproduk='$idpoproduk'
          ";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);

  //   $idadmin=$_GET['idadmin'];
  $idadmin=$_SESSION["mitraagen"]["idmitraagen"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wanoja | Form Inner Custom</title>

  <!-- CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <!-- Icons Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    .navbaru {
      background: #eee  url("jumbotron-bg.png") center center;
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

  <!-- Navbar Start -->
  <div class="row fixed-top navbaru">
    <div class="col-2">
      <a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a>
    </div>

    <div class="col-8">
      <p>PRE ORDER</p>
    </div>

    <div class="col-2"></div>
  </div>
  <!-- Navbar End -->

  <div class="container mt-5 py-5">
    <div class="text-center">
      <h5>Formulir Pemesanan <?= $data['namapo'] ?></h5>
    </div>

    <?php
      $sql650 = "SELECT SUM(pomitra.jumlah) AS total_pcs FROM pomitra WHERE idpoproduk = 251";
      $query650 = $koneksi->query($sql650);
      while ( $row650 = $query650->fetch_assoc() ) {
        $dataMax = 650;
        $totalKeseluruhan = $dataMax - $row650['total_pcs'];
    ?>
      <?php if($totalKeseluruhan <= 0) : ?>
        <div class="row">
          <div class="col-sm-12 text-center">
            <h6 class="text-uppercase badge bg-danger">PO ini sudah melebihi batas stok yang tersedia</h6>
          </div>
        </div>
      <?php else : ?>
        <div class="col-sm-12 text-center">
          <h6 class="text-uppercase badge bg-success">tersisa <?= $totalKeseluruhan ?> pack</h6>
        </div>

        <hr>

        <div>
          <button class="btn btn-sm btn-success" id="buttonAddPack">Tambah Pack</button>
        </div>

        <div class="my-3">
          <form method="POST">

            <div class="row">
              <div class="col-sm-12">
                <h6 class="badge text-bg-info text-uppercase">Pack Ke - 1</h6>
              </div>

                <div class="col-sm-3">
                  <label for="qty">Pack</label>
                  <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[]">
                  <input type="hidden" class="form-control form-control-sm" min="0" value="50000" name="harga[0]">
                  <input type="hidden" class="form-control form-control-sm" id="idpodetail_0" name="idpodetail[0]">
                  <input type="hidden" class="form-control form-control-sm" id="idpo_0" name="idpo[0]">
                </div>

                <div class="col-sm-3">
                  <label for="variant">Variant Ke - 1</label>
                  <select name="customPertama[]" class="form-select form-select-sm" id="customPertama_0" required onChange="updateInput(0)">
                    <option value="" selected>Pilih Variant</option>
                    <?php
                      $idpoproduk = 251;
                      $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                              ON poproduk.idpoproduk = pokategori.idpoproduk
                              AND pokategori.idpo = podetail.idpo
                              WHERE poproduk.idpoproduk = '$idpoproduk'
                              ORDER BY podetail.idpodetail ASC
                              ";
                      $query = $koneksi->query($sql);

                      while($row = $query->fetch_assoc()) {
                    ?>
                      <option value="<?= $row['variant'] ?>" data-idpo="<?= $row['idpo'] ?>" data-idpodetail="<?= $row['idpodetail']; ?>"><?= $row['variant'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                
                <div class="col-sm-3">
                  <label for="variant">Variant Ke - 2</label>
                  <select name="customKedua[]" class="form-select form-select-sm" id="" required>
                    <option value="" selected>Pilih Variant</option>
                    <?php
                      $idpoproduk = 251;
                      $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                              ON poproduk.idpoproduk = pokategori.idpoproduk
                              AND pokategori.idpo = podetail.idpo
                              WHERE poproduk.idpoproduk = '$idpoproduk'
                              ORDER BY podetail.idpodetail ASC
                              ";
                      $query = $koneksi->query($sql);

                      while($row = $query->fetch_assoc()) {
                    ?>
                      <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-sm-3">
                  <label for="variant">Variant Ke - 3</label>
                  <select name="customKetiga[]" class="form-select form-select-sm" id="" required>
                    <option value="" selected>Pilih Variant</option>
                    <?php
                      $idpoproduk = 251;
                      $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                              ON poproduk.idpoproduk = pokategori.idpoproduk
                              AND pokategori.idpo = podetail.idpo
                              WHERE poproduk.idpoproduk = '$idpoproduk'
                              ORDER BY podetail.idpodetail ASC
                              ";
                      $query = $koneksi->query($sql);

                      while($row = $query->fetch_assoc()) {
                    ?>
                      <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                    <?php } ?>
                  </select>
                </div>
            </div>

            <hr />

            <!-- Form Yang akan di tampilkan ketika klik tambah pack -->
            <div id="demoPoInner"></div>

            <!-- Script Menampilkan demoPoInner -->
            <script type="text/javascript">
                const buttonAddPack = document.getElementById("buttonAddPack");
                const demoPoInner = document.getElementById("demoPoInner");
                let formCount = 0;

                buttonAddPack.addEventListener('click', function() {
                  formCount++;

                  // Buat Form Baru
                  const form = document.createElement('div');
                  form.innerHTML = `
                    <div class="row">
                      <div class="col-sm-12">
                        <h6 class="badge text-bg-info text-uppercase">Pack Ke - ${formCount + 1}</h6>
                      </div>

                      <div class="col-sm-3">
                        <label for="qty">Pack</label>
                        <input type="number" class="form-control form-control-sm" min="0" value="0" name="qty[${formCount}]">
                        <input type="hidden" class="form-control form-control-sm" min="0" value="50000" name="harga[${formCount}]">
                        <input type="hidden" class="form-control form-control-sm" id="idpodetail_${formCount}" name="idpodetail[${formCount}]">
                        <input type="hidden" class="form-control form-control-sm" id="idpo_${formCount}" name="idpo[${formCount}]">
                      </div>

                      <div class="col-sm-3">
                        <label for="variant">Variant Ke - 1</label>
                        <select name="customPertama[]" class="form-select form-select-sm" id="customPertama_${formCount}" required onChange="updateInput(${formCount})">
                          <option value="" selected>Pilih Variant</option>
                          <?php
                            $idpoproduk = 251;
                            $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                    ON poproduk.idpoproduk = pokategori.idpoproduk
                                    AND pokategori.idpo = podetail.idpo
                                    WHERE poproduk.idpoproduk = '$idpoproduk'
                                    ORDER BY podetail.idpodetail ASC
                                    ";
                            $query = $koneksi->query($sql);

                            while($row = $query->fetch_assoc()) {
                          ?>
                            <option value="<?= $row['variant'] ?>" data-idpo="<?= $row['idpo'] ?>" data-idpodetail="<?= $row['idpodetail']; ?>"><?= $row['variant'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      
                      <div class="col-sm-3">
                        <label for="variant">Variant Ke - 2</label>
                        <select name="customKedua[]" class="form-select form-select-sm" id="" required>
                          <option value="" selected>Pilih Variant</option>
                          <?php
                            $idpoproduk = 251;
                            $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                    ON poproduk.idpoproduk = pokategori.idpoproduk
                                    AND pokategori.idpo = podetail.idpo
                                    WHERE poproduk.idpoproduk = '$idpoproduk'
                                    ORDER BY podetail.idpodetail ASC
                                    ";
                            $query = $koneksi->query($sql);

                            while($row = $query->fetch_assoc()) {
                          ?>
                            <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                          <?php } ?>
                        </select>
                      </div>

                      <div class="col-sm-3">
                        <label for="variant">Variant Ke - 3</label>
                        <select name="customKetiga[]" class="form-select form-select-sm" id="" required>
                          <option value="" selected>Pilih Variant</option>
                          <?php
                            $idpoproduk = 251;
                            $sql = "SELECT * FROM poproduk INNER JOIN pokategori INNER JOIN podetail
                                    ON poproduk.idpoproduk = pokategori.idpoproduk
                                    AND pokategori.idpo = podetail.idpo
                                    WHERE poproduk.idpoproduk = '$idpoproduk'
                                    ORDER BY podetail.idpodetail ASC
                                    ";
                            $query = $koneksi->query($sql);

                            while($row = $query->fetch_assoc()) {
                          ?>
                            <option value="<?= $row['variant'] ?>"><?= $row['variant'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <hr />
                  `;

                  // Menambahkan form ke dalam container demoPoInner
                  demoPoInner.appendChild(form);

                  // const customPertamaSelect = document.querySelectorAll(".customPertama");
                  // customPertamaSelect.forEach((select, index) => {
                  //   select.addEventListener('change', function() {
                  //     const selectedOption = this.options[this.selectedIndex];
                  //     const idpodetail = selectedOption.getAttribute("data-idpodetail");
                  //     const hiddenInput = document.querySelector(`input[name="idpodetail[${index}]"]`);
                  //     hiddenInput.value = idpodetail;
                  //   })
                  // });

                  // Menambahkan event untuk tombol hapus pack
                  // const hapusPackButton = form.querySelector("#hapusPackButton");
                  // hapusPackButton.addEventListener('click', function() {
                  //   demoPoInner.removeChild(form);
                  // });
                });

                // Menambahkan evenet listener ke semua elemen
                function updateInput(formIndex) {
                  var select = document.getElementById('customPertama_' + formIndex);
                  var input = document.getElementById('idpodetail_' + formIndex);
                  var idpo = document.getElementById('idpo_' + formIndex);

                  var selectedOption = select.options[select.selectedIndex];
                  var idpodetailValue = selectedOption.getAttribute('data-idpodetail');
                  var idpoValue = selectedOption.getAttribute('data-idpo');

                  idpo.value =idpoValue;
                  input.value = idpodetailValue;
                }
            </script>

            <button class="btn btn-primary" name="save">Kirim</button>
          </form>
        </div>
      <?php endif; ?>
    <?php } ?>
  </div>

  <?php
    if(isset($_POST["save"])) {
      include "koneksi.php";

      if (!$koneksi) {
        die("Koneksi gagal : " . mysqli_connect_error());
      }

      $idpoproduk = 251;
      $idadmin = $_GET['idadmin'];
      date_default_timezone_set('Asia/Jakarta');
      $today = date('s');
      $waktu = date('H:i:s');
      $qty = $_POST['qty'];
      $qtyKedua = $_POST['qtyKedua'];
      $idpodetail = $_POST['idpodetail'];
      $idpo = $_POST['idpo'];
      $variantCustomPertama = $_POST['customPertama'];
      $variantCustomKedua = $_POST['customKedua'];
      $variantCustomKetiga = $_POST['customKetiga'];
      $harga = $_POST['harga'];

      $queries = array();
      $total = count($qty);

      $sqlInvoice = mysqli_query($koneksi, "SELECT idpomitra FROM pomitra ORDER BY idpomitra DESC LIMIT 1");
      $dataInvoice = mysqli_fetch_array($sqlInvoice);
      $no = $data['idpomitra'];
      $ab = 1;
      $nobaru = $no + $ab;
      $invoice = 'DI' . $idadmin . '-' . $idpoproduk;

      for ($i = 0; $i < $total; $i++) {
        $itemPertama = $variantCustomPertama[$i];
        $itemKedua = $variantCustomKedua[$i];
        $itemKetiga = $variantCustomKetiga[$i];
        $itemIdpodetail = $idpodetail[$i];
        $itemIdpo = $idpo[$i];
        $itemQty = $qty[$i];
        $itemHarga = $harga[$i];
        $itemTotal = $itemHarga * $itemQty;

        $custom_data = $itemPertama . " | " . $itemKedua . " | " . $itemKetiga;
        // var_dump($custom_data . " || " . $itemIdpodetail . " || " . $itemIdpo . " || " . $itemTotal . " || " . $itemQty);

        $queries[] = "INSERT INTO pomitra (idpomitra, idmitraagen, idpoproduk, idpo, idpodetail, jumlah, template, custom, font, total, invoice, status, tgl, waktu) VALUES (NULL, '$idadmin', '$idpoproduk', '$itemIdpo', '$itemIdpodetail', '$itemQty', NULL, '$custom_data', NULL, '$itemTotal', '$invoice', 'Belum DP', NOW(), '$waktu')";
      }

      if (!empty($queries)) {
        $queries_string = implode("; ", $queries);
        
        if (mysqli_multi_query($koneksi, $queries_string)) {
          // echo "Data berhasil ditambahkan";
          // echo $queries_string;
          echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice'</script>";
        } else {
          echo "Error : " . mysqli_error($koneksi);
        }
      } else {
        echo "<script>alert('Data tidak terkirim');</script>";
      }
    }
  ?>

  <!-- JavaScript Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>