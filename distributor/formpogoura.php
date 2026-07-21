<?php
  session_start();
  include 'koneksi.php'; 
//   if(!isset($_SESSION["admin_mitra"])){
//     echo "<script>alert('anda harus login terlebih dahulu');</script>";
//     echo "<script>location='login2.php';</script>";
//     header('location:login2.php');
//     exit();
//   }

    $idpoproduk = 244;
    $idadmin=$_GET["idadmin"];
    $query = "SELECT COUNT(*) as jumlah,
                poproduk.idpoproduk,
                poproduk.namapo,
                poproduk.status 
                FROM poproduk 
                inner join pomitra 
                on poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE poproduk.idpoproduk='$idpoproduk' 
                AND pomitra.idmitra='$idadmin'
            ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        <h5>Form Po Goura Customm</h5>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="row">
            <?php
              $idpoproduk = 244;
              $total = 0;
              $index = 0;
              $number = 1;
              $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' and podetail.variant LIKE '%Bergo Goura%' order by podetail.idpodetail asc";
              $query = $koneksi->query($sql);
              while($row = $query->fetch_assoc()){
            ?>
              <div class="col-sm-12">
                <label for="podetail" class="form-label"><?= $row['variant']; ?></label>
                <input type="number" name="provinsi" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0" class="form-control" min="0">
                <div id="demo<?php echo $row['idpodetail']; ?>"></div>
              </div>

              <script type="text/javascript">
              function tampil<?php echo $row['idpodetail']; ?>(provinsi<?php echo $row['idpodetail']; ?>)
              {
                var text = "";
                var i;
              
                for (i = 0; i < provinsi<?php echo $row['idpodetail']; ?>; i++) {
                  text += "No. "+ (i+1) +"<?php
                    echo "<div class='row'>";
                      echo "<div class='form-group col-sm-6'>";
                        echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                        echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                        echo "<input type='text' class='form-control' name='nama[]' required maxlength='10' placeholder='Tulis Nama Custom'><br>";
                      echo "</div>"; 
                      echo "<div class='form-group col-sm-6'>";
                        echo "<select class='form-control' name='font[]' required>";
                          echo "<option value='' disabled='disabled' selected>~Pilih Jenis Huruf~</option>";            
                          echo "<option value='Jugenull'>Jugenull</option>";
                          echo "<option value='Poetses One'>Poetses One</option>";
                          echo "</select>";      
                      echo "</div>";
                    echo "</div>"; 
                  ?>";
                }
                document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
              }
              </script>

              <div class="position-absolute">
                <input type="hidden" name="harga" class="form-control" value="<?= $row['harga']; ?> | <?= $row['idpodetail'] ?>">
              </div>
              
              <div class="col-sm-12">
                <hr>
              </div>

            <?php 
                $namapo2 = $row["namapo"];
                $id = $row["idpoproduk"];
                $idadmin = $_GET["idadmin"];
              }
            ?>
            
            <div class="col-sm-12 my-2">
                <?php // echo $idadmin ?>
              <button type="submit" class="btn btn-sm btn-primary" name="save">
                Kirim
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php
    if (isset($_POST["save"])) {
        include 'koneksi.php';

        if (!$koneksi) {
            die("Koneksi Gagal : " . mysqli_connect_error());
        // } else {
        //     die("Koneksi Gagal : " . mysqli_connect_error());
        }
        
        $idadmin = $_GET["idadmin"];
        
        date_default_timezone_set('Asia/Jakarta');
        $today = date('s');
        $waktu = date('H:i:s');
        $idpo = $_POST["idpo"];
        $qty = $_POST["provinsi"];
        $custom = $_POST["nama"];
        $font = $_POST["font"];
        $harga = $_POST["harga"];
        $idpodetail = $_POST["idpodetail"];
        
        // var_dump($today, $waktu, $idpo, $qty, $custom, $font, $harga, $idpodetail);
        $invoice =  'BR' . $idadmin . '-' . $idpoproduk;
        $queries = array();
        $countQty = count($custom);
        
        // var_dump($invoice . " | " . $countQty);
        
        for ($i = 0; $i < $countQty; $i++) {
            $idpodetail_item = $_POST["idpodetail"][$i];
            $idpo_item = $idpo[$i];
            $custom_item = isset($custom[$i]) && $custom[$i] != '' ? mysqli_real_escape_string($koneksi, $custom[$i]) : null;
            $font_item = isset($font[$i]) && $font[$i] != '' ? mysqli_real_escape_string($koneksi, $font[$i]) : null;
            $harga_item = $harga[$i];
            
            if (!empty($idpodetail_item) && $qty_item >= 0 && $custom_item !== null && $font_item !== null) {
                // Ambil harga dari database
                $query_variant = "SELECT podetail.harga FROM podetail WHERE podetail.idpodetail='$idpodetail_item'";
                $sql_variant = mysqli_query($koneksi, $query_variant);
                $data_variant = mysqli_fetch_array($sql_variant);
                $harga_item = $data_variant['harga'];
    
                // Hitung total berdasarkan harga dan jumlah
                $total_item = $harga_item * $qty_item;
    
                // var_dump($idadmin . " | " . $idpoproduk . " | " . $idpo_item . " | " . $idpodetail_item . " | " . $custom_item . " | " . $font_item . " | " . $harga_item . " | " . $invoice);
    
                // Menambahkan query ke dalam array
                $queries[] = "INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, font, total, invoice, status, tgl, waktu) VALUES (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '1', '$custom_item', '$font_item', '$harga_item', '$invoice', 'Belum DP', NOW(), '$waktu')";
            }
            // var_dump($idpodetail_item);
        }
        
        if (!empty($queries)) {
            $queries_string = implode("; ", $queries);
            
            if (mysqli_multi_query($koneksi, $queries_string)) {
                echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice'; </script>";
            } else {
                echo "Gagal!";
            }
        } else {
            echo "Data tidak terkirim!";
        }
    }
  ?>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>
</html>