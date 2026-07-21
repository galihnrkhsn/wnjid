<?php
  session_start();
  include 'koneksi.php'; 
//   if(!isset($_SESSION["admin_mitra"])){
//     echo "<script>alert('anda harus login terlebih dahulu');</script>";
//     echo "<script>location='login2.php';</script>";
//     header('location:login2.php');
//     exit();
//   }

    $idpoproduk = 240;
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
  <title>WNJ | Form Bundling</title>
</head>
<body>
  <div class="container my-5">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Polos Rocela + Polos Goura</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Polos Rocela + Custom Goura</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Custom Rocela + Polos Goura</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Custom Rocela + Custom Goura</button>
      </li>
    </ul>
    <form method="POST">
        <div class="tab-content" id="myTabContent">
          <?php
            $no = 1;
            $sqlLoop = "SELECT * FROM bukapo_tab WHERE idpoproduk"
          ?>
          <!-- Rocela Polos + Goura Polos -->
          <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="card my-2">
              <div class="card-body">
                  <div class="row">
                    <?php
                      $idpoproduk = 240;
                      $total = 0;
                      $index = 0;
                      $number = 1;
                      $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' and pokategori.namakategori LIKE '%Bundling Rocela%' AND podetail.harga = 228000 order by podetail.idpodetail asc";
                      $query = $koneksi->query($sql);
                      while($row = $query->fetch_assoc()){
                        $namaBundling = $row['namakategori'];
                        $parts = explode(' ', $namaBundling);
                        $result = $parts[0] . ' ' . $parts[2] . ' ' . $parts[3];
                    ?>
                      <div class="col-sm-12">
                        <label for="podetail" class="form-label"><?= $result; ?></label>
                        <input type="number" name="provinsi[]" onChange="tampil<?php echo $row['idpodetail']; ?>(this.value)" value="0" class="form-control" min="0">
                        <input type="hidden" name="harga[]" value="<?= $row['harga'] ?>" class="form-control" disabled>
                        <input type="hidden" name="tab[]" value="1" class="form-control" disabled>
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
                                echo "<select class='form-control' disabled name='setPertama[]'>";
                                  echo "<option selected>" . $row['variant'] . "</option>";
                                echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6'>";
                                  echo "<select class='form-select' name='setKedua[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Set ~</option>";            
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz M'>Goura ".explode('Rocela', $row['variant'])[1]." Sz M</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz L'>Goura ".explode('Rocela', $row['variant'])[1]." Sz L</option>";
                                    // echo "<option value='Goura Honey Ginger Sz M'>Goura Honey Ginger Sz M</option>";
                                    // echo "<option value='Goura Honey Ginger Sz L'>Goura Honey Ginger Sz L</option>";
                                    // echo "<option value='Goura Midnight Blue Sz M'>Goura Midnight Blue Sz M</option>";
                                    // echo "<option value='Goura Midnight Blue Sz L'>Goura Midnight Blue Sz L</option>";
                                    // echo "<option value='Goura Black Sz M'>Goura Black Sz M</option>";
                                    // echo "<option value='Goura Black Sz L'>Goura Black Sz L</option>";
                                    // echo "<option value='Goura Red Mahogani Sz M'>Goura Red Mahogani Sz M</option>";
                                    // echo "<option value='Goura Red Mahogani Sz L'>Goura Red Mahogani Sz L</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz M'>Goura Dark Sage Green Sz M</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz L'>Goura Dark Sage Green Sz L</option>";
                                    // echo "<option value='Goura White Sz M'>Goura White Sz M</option>";
                                    // echo "<option value='Goura White Sz L'>Goura White Sz L</option>";
                                    // echo "<option value='Goura Soft Choco Sz M'>Goura Soft Choco Sz M</option>";
                                    // echo "<option value='Goura Soft Choco Sz L'>Goura Soft Choco Sz L</option>";
                                    // echo "<option value='Goura Dusty Purple Sz M'>Goura Dusty Purple Sz M</option>";
                                    // echo "<option value='Goura Dusty Purple Sz L'>Goura Dusty Purple Sz L</option>";
                                    // echo "<option value='Goura Soft Lavender Sz M'>Goura Soft Lavender Sz M</option>";
                                    // echo "<option value='Goura Soft Lavender Sz L'>Goura Soft Lavender Sz L</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz M'>Goura Odyssey Grey Sz M</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz L'>Goura Odyssey Grey Sz L</option>";
                                    // echo "<option value='Goura Peach Rose Sz M'>Goura Peach Rose Sz M</option>";
                                    // echo "<option value='Goura Peach Rose Sz L'>Goura Peach Rose Sz L</option>";
                                  echo "</select>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='variant[]' value='$row[variant]'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='is_custom[]' value='0'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                              echo "</div>";
                              echo "<input type='hidden' name='namacustom[]' placeholder='Nama Custom' maxlength='10' class='form-control'/>";
                              echo "<input type='hidden' name='font[]' class='form-control'/>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='harga[]' value='228000'>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demo<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                      </script>
                      
                      <div class="col-sm-12">
                        <hr>
                      </div>
    
                    <?php 
                        $namapo2 = $row["namapo"];
                        $id = $row["idpoproduk"];
                        $idadmin = $_GET["idadmin"];
                      }
                    ?>
                    
                    <!--<div class="col-sm-12 my-2">-->
                    <!--  <button type="submit" class="btn btn-sm btn-primary" name="save">-->
                    <!--    Kirim-->
                    <!--  </button>-->
                    <!--</div>-->
                  </div>
              </div>
            </div>
          </div>
          
          <!-- Rocela Polos + Goura Custom -->
          <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <div class="card my-2">
              <div class="card-body">
                  <div class="row">
                    <?php
                      $idpoproduk = 240;
                      $total = 0;
                      $index = 0;
                      $number = 1;
                      $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' and pokategori.namakategori LIKE '%Bundling%' AND podetail.harga = 238000 order by podetail.idpodetail asc";
                      $query = $koneksi->query($sql);
                      while($row = $query->fetch_assoc()){
                        $namaBundling = $row['namakategori'];
                        $parts = explode(' ', $namaBundling);
                        $result = $parts[0] . ' ' . $parts[2] . ' ' . $parts[3];
                    ?>
                      <div class="col-sm-12">
                        <label for="podetail" class="form-label"><?= $result; ?></label>
                        <input type="number" name="provinsiKedua[]" onChange="tampilKedua<?php echo $row['idpodetail']; ?>(this.value)" value="0" class="form-control" min="0">
                        <input type="hidden" name="harga[]" value="<?= $row['harga'] ?>" class="form-control" disabled>
                        <input type="hidden" name="tab[]" value="2" class="form-control" disabled>
                        <div id="demoKedua<?php echo $row['idpodetail']; ?>"></div>
                      </div>
    
                      <script type="text/javascript">
                      function tampilKedua<?php echo $row['idpodetail']; ?>(provinsiKedua<?php echo $row['idpodetail']; ?>)
                      {
                        var text = "";
                        var i;
                      
                        for (i = 0; i < provinsiKedua<?php echo $row['idpodetail']; ?>; i++) {
                          text += "No. "+ (i+1) +"<?php
                            echo "<div class='row'>";
                              echo "<div class='form-group col-sm-6'>";
                                echo "<select class='form-control' disabled name='setPertama[]'>";
                                  echo "<option selected>" . $row['variant'] . "</option>";
                                echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6'>";
                                // var_dump($row['variant']);
                                  echo "<select class='form-select' name='setKedua[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Set ~</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz M'>Goura ".explode('Rocela', $row['variant'])[1]." Sz M</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz L'>Goura ".explode('Rocela', $row['variant'])[1]." Sz L</option>";
                                    // echo "<option value='Goura Honey Ginger Sz M'>Goura Honey Ginger Sz M</option>";
                                    // echo "<option value='Goura Honey Ginger Sz L'>Goura Honey Ginger Sz L</option>";
                                    // echo "<option value='Goura Midnight Blue Sz M'>Goura Midnight Blue Sz M</option>";
                                    // echo "<option value='Goura Midnight Blue Sz L'>Goura Midnight Blue Sz L</option>";
                                    // echo "<option value='Goura Black Sz M'>Goura Black Sz M</option>";
                                    // echo "<option value='Goura Black Sz L'>Goura Black Sz L</option>";
                                    // echo "<option value='Goura Red Mahogani Sz M'>Goura Red Mahogani Sz M</option>";
                                    // echo "<option value='Goura Red Mahogani Sz L'>Goura Red Mahogani Sz L</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz M'>Goura Dark Sage Green Sz M</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz L'>Goura Dark Sage Green Sz L</option>";
                                    // echo "<option value='Goura White Sz M'>Goura White Sz M</option>";
                                    // echo "<option value='Goura White Sz L'>Goura White Sz L</option>";
                                    // echo "<option value='Goura Soft Choco Sz M'>Goura Soft Choco Sz M</option>";
                                    // echo "<option value='Goura Soft Choco Sz L'>Goura Soft Choco Sz L</option>";
                                    // echo "<option value='Goura Dusty Purple Sz M'>Goura Dusty Purple Sz M</option>";
                                    // echo "<option value='Goura Dusty Purple Sz L'>Goura Dusty Purple Sz L</option>";
                                    // echo "<option value='Goura Soft Lavender Sz M'>Goura Soft Lavender Sz M</option>";
                                    // echo "<option value='Goura Soft Lavender Sz L'>Goura Soft Lavender Sz L</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz M'>Goura Odyssey Grey Sz M</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz L'>Goura Odyssey Grey Sz L</option>";
                                    // echo "<option value='Goura Peach Rose Sz M'>Goura Peach Rose Sz M</option>";
                                    // echo "<option value='Goura Peach Rose Sz L'>Goura Peach Rose Sz L</option>";
                                  echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<input type='text' name='namacustom[]' placeholder='Nama Custom' maxlength='10' class='form-control' required />";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<select class='form-select' name='font[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Jenis Font ~</option>";            
                                    echo "<option value='Jugenull'>Jugenull</option>";
                                    echo "<option value='Poetses One'>Poetses One</option>";
                                  echo "</select>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='text' name='is_custom[]' value='1'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='text' name='variant[]' value='$row[variant]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='text' name='idpo[]' value='$row[idpo]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='text' name='idpodetail[]' value='$row[idpodetail]'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='text' name='harga[]' value='$row[harga]'>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demoKedua<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                      </script>
                      
                      <div class="col-sm-12">
                        <hr>
                      </div>
    
                    <?php 
                        $namapo2 = $row["namapo"];
                        $id = $row["idpoproduk"];
                        $idadmin = $_GET["idadmin"];
                      }
                    ?>
              </div>
            </div>
          </div>
          </div>
          
          <!-- Custom Rocela + Goura Polos -->
          <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
              <div class="card my-2">
              <div class="card-body">
                  <div class="row">
                    <?php
                      $idpoproduk = 240;
                      $total = 0;
                      $index = 0;
                      $number = 1;
                      $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' and pokategori.namakategori LIKE '%Bundling Rocela%' AND podetail.harga = 238000 order by podetail.idpodetail asc";
                      $query = $koneksi->query($sql);
                      while($row = $query->fetch_assoc()){
                        $namaBundling = $row['namakategori'];
                        $parts = explode(' ', $namaBundling);
                        $result = $parts[0] . ' ' . $parts[2] . ' ' . $parts[3];
                    ?>
                      <div class="col-sm-12">
                        <label for="podetail" class="form-label"><?= $result; ?></label>
                        <input type="number" name="provinsiKetiga[]" onChange="tampilKetiga<?php echo $row['idpodetail']; ?>(this.value)" value="0" class="form-control" min="0">
                        <input type="hidden" name="harga[]" value="<?= $row['harga'] ?>" class="form-control" disabled>
                        <input type="hidden" name="tab[]" value="3" class="form-control" disabled>
                        <div id="demoKetiga<?php echo $row['idpodetail']; ?>"></div>
                      </div>
    
                      <script type="text/javascript">
                      function tampilKetiga<?php echo $row['idpodetail']; ?>(provinsiKetiga<?php echo $row['idpodetail']; ?>)
                      {
                        var text = "";
                        var i;
                      
                        for (i = 0; i < provinsiKetiga<?php echo $row['idpodetail']; ?>; i++) {
                          text += "No. "+ (i+1) +"<?php
                            echo "<div class='row'>";
                              echo "<div class='form-group col-sm-6'>";
                                echo "<select class='form-control' disabled name='setPertama[]'>";
                                  echo "<option selected>" . $row['variant'] . "</option>";
                                echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6'>";
                                  echo "<select class='form-select' name='setKedua[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Set ~</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz M'>Goura ".explode('Rocela', $row['variant'])[1]." Sz M</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz L'>Goura ".explode('Rocela', $row['variant'])[1]." Sz L</option>";
                                    // echo "<option value='Goura Honey Ginger Sz M'>Goura Honey Ginger Sz M</option>";
                                    // echo "<option value='Goura Honey Ginger Sz L'>Goura Honey Ginger Sz L</option>";
                                    // echo "<option value='Goura Midnight Blue Sz M'>Goura Midnight Blue Sz M</option>";
                                    // echo "<option value='Goura Midnight Blue Sz L'>Goura Midnight Blue Sz L</option>";
                                    // echo "<option value='Goura Black Sz M'>Goura Black Sz M</option>";
                                    // echo "<option value='Goura Black Sz L'>Goura Black Sz L</option>";
                                    // echo "<option value='Goura Red Mahogani Sz M'>Goura Red Mahogani Sz M</option>";
                                    // echo "<option value='Goura Red Mahogani Sz L'>Goura Red Mahogani Sz L</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz M'>Goura Dark Sage Green Sz M</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz L'>Goura Dark Sage Green Sz L</option>";
                                    // echo "<option value='Goura White Sz M'>Goura White Sz M</option>";
                                    // echo "<option value='Goura White Sz L'>Goura White Sz L</option>";
                                    // echo "<option value='Goura Soft Choco Sz M'>Goura Soft Choco Sz M</option>";
                                    // echo "<option value='Goura Soft Choco Sz L'>Goura Soft Choco Sz L</option>";
                                    // echo "<option value='Goura Dusty Purple Sz M'>Goura Dusty Purple Sz M</option>";
                                    // echo "<option value='Goura Dusty Purple Sz L'>Goura Dusty Purple Sz L</option>";
                                    // echo "<option value='Goura Soft Lavender Sz M'>Goura Soft Lavender Sz M</option>";
                                    // echo "<option value='Goura Soft Lavender Sz L'>Goura Soft Lavender Sz L</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz M'>Goura Odyssey Grey Sz M</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz L'>Goura Odyssey Grey Sz L</option>";
                                    // echo "<option value='Goura Peach Rose Sz M'>Goura Peach Rose Sz M</option>";
                                    // echo "<option value='Goura Peach Rose Sz L'>Goura Peach Rose Sz L</option>";
                                  echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<input type='text' name='namacustom[]' placeholder='Nama Custom' maxlength='10' class='form-control' required />";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<select class='form-select' name='font[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Jenis Font ~</option>";            
                                    echo "<option value='Jugenull'>Jugenull</option>";
                                    echo "<option value='Poetses One'>Poetses One</option>";
                                  echo "</select>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='is_custom[]' value='2'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='variant[]' value='$row[variant]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='harga[]' value='$row[harga]'>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demoKetiga<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                      </script>
                      
                      <div class="col-sm-12">
                        <hr>
                      </div>
    
                    <?php 
                        $namapo2 = $row["namapo"];
                        $id = $row["idpoproduk"];
                        $idadmin = $_GET["idadmin"];
                      }
                    ?>
                  </div>
              </div>
            </div>
          </div>
          
          <!-- Custom Rocela + Custom Goura -->
          <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
              <div class="card my-2">
              <div class="card-body">
                  <div class="row">
                    <?php
                      $idpoproduk = 240;
                      $total = 0;
                      $index = 0;
                      $number = 1;
                      $sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' and pokategori.namakategori LIKE '%Bundling Rocela%' AND podetail.harga = 248000 order by podetail.idpodetail asc";
                      $query = $koneksi->query($sql);
                      while($row = $query->fetch_assoc()){
                        $namaBundling = $row['namakategori'];
                        $parts = explode(' ', $namaBundling);
                        $result = $parts[0] . ' ' . $parts[2] . ' ' . $parts[3];
                    ?>
                      <div class="col-sm-12">
                        <label for="podetail" class="form-label"><?= $result; ?></label>
                        <input type="number" name="provinsiKeempat[]" onChange="tampilKeempat<?php echo $row['idpodetail']; ?>(this.value)" value="0" class="form-control" min="0">
                        <input type="hidden" name="harga[]" value="<?= $row['harga'] ?>" class="form-control" disabled>
                        <input type="hidden" name="tab[]" value="4" class="form-control" disabled>
                        <div id="demoKeempat<?php echo $row['idpodetail']; ?>"></div>
                      </div>
    
                      <script type="text/javascript">
                      function tampilKeempat<?php echo $row['idpodetail']; ?>(provinsiKeempat<?php echo $row['idpodetail']; ?>)
                      {
                        var text = "";
                        var i;
                      
                        for (i = 0; i < provinsiKeempat<?php echo $row['idpodetail']; ?>; i++) {
                          text += "No. "+ (i+1) +"<?php
                            echo "<div class='row'>";
                              echo "<div class='form-group col-sm-6'>";
                                echo "<select class='form-control' disabled name='setPertama[]'>";
                                  echo "<option selected>" . $row['variant'] . "</option>";
                                echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6'>";
                                  echo "<select class='form-select' name='setKedua[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Set ~</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz M'>Goura ".explode('Rocela', $row['variant'])[1]." Sz M</option>";
                                    echo "<option value='Goura ".explode('Rocela', $row['variant'])[1]." Sz L'>Goura ".explode('Rocela', $row['variant'])[1]." Sz L</option>";
                                    // echo "<option value='Goura Honey Ginger Sz M'>Goura Honey Ginger Sz M</option>";
                                    // echo "<option value='Goura Honey Ginger Sz L'>Goura Honey Ginger Sz L</option>";
                                    // echo "<option value='Goura Midnight Blue Sz M'>Goura Midnight Blue Sz M</option>";
                                    // echo "<option value='Goura Midnight Blue Sz L'>Goura Midnight Blue Sz L</option>";
                                    // echo "<option value='Goura Black Sz M'>Goura Black Sz M</option>";
                                    // echo "<option value='Goura Black Sz L'>Goura Black Sz L</option>";
                                    // echo "<option value='Goura Red Mahogani Sz M'>Goura Red Mahogani Sz M</option>";
                                    // echo "<option value='Goura Red Mahogani Sz L'>Goura Red Mahogani Sz L</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz M'>Goura Dark Sage Green Sz M</option>";
                                    // echo "<option value='Goura Dark Sage Green Sz L'>Goura Dark Sage Green Sz L</option>";
                                    // echo "<option value='Goura White Sz M'>Goura White Sz M</option>";
                                    // echo "<option value='Goura White Sz L'>Goura White Sz L</option>";
                                    // echo "<option value='Goura Soft Choco Sz M'>Goura Soft Choco Sz M</option>";
                                    // echo "<option value='Goura Soft Choco Sz L'>Goura Soft Choco Sz L</option>";
                                    // echo "<option value='Goura Dusty Purple Sz M'>Goura Dusty Purple Sz M</option>";
                                    // echo "<option value='Goura Dusty Purple Sz L'>Goura Dusty Purple Sz L</option>";
                                    // echo "<option value='Goura Soft Lavender Sz M'>Goura Soft Lavender Sz M</option>";
                                    // echo "<option value='Goura Soft Lavender Sz L'>Goura Soft Lavender Sz L</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz M'>Goura Odyssey Grey Sz M</option>";
                                    // echo "<option value='Goura Odyssey Grey Sz L'>Goura Odyssey Grey Sz L</option>";
                                    // echo "<option value='Goura Peach Rose Sz M'>Goura Peach Rose Sz M</option>";
                                    // echo "<option value='Goura Peach Rose Sz L'>Goura Peach Rose Sz L</option>";
                                  echo "</select>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<input type='text' name='namacustom[]' placeholder='Nama Custom' maxlength='10' class='form-control' required />";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-6 my-2'>";
                                echo "<select class='form-select' name='font[]' required>";
                                    echo "<option value='' disabled='disabled' selected>~ Pilih Jenis Font ~</option>";            
                                    echo "<option value='Jugenull'>Jugenull</option>";
                                    echo "<option value='Poetses One'>Poetses One</option>";
                                  echo "</select>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='is_custom[]' value='3'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='variant[]' value='$row[variant]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpo[]' value='$row[idpo]'>";
                              echo "</div>";
    
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='idpodetail[]' value='$row[idpodetail]'>";
                              echo "</div>";
                              
                              echo "<div class='form-group col-sm-12'>";
                                echo "<input type='hidden' name='harga[]' value='$row[harga]'>";
                              echo "</div>";
                            echo "</div>"; 
                          ?>";
                        }
                        document.getElementById("demoKeempat<?php echo $row['idpodetail']; ?>").innerHTML = text;
                      }
                      </script>
                      
                      <div class="col-sm-12">
                        <hr>
                      </div>
    
                    <?php 
                        $namapo2 = $row["namapo"];
                        $id = $row["idpoproduk"];
                        $idadmin = $_GET["idadmin"];
                      }
                    ?>
                  </div>
              </div>
            </div>
          </div>
      </div>
      <div class="col-sm-12 my-2">
        <button type="submit" class="btn btn-sm btn-primary" name="save">
          Kirim
        </button>
      </div>
    </form>
  <?php
    // Paket Bundling Polos Rocela + Goura
    if (isset($_POST["save"])) {
        include 'koneksi.php';
   
        if (!$koneksi) {
            die("Koneksi Gagal : " . mysqli_connect_error());
        // } else {
        //     die("Koneksi Gagal : " . mysqli_connect_error());
        }
        
        $idpoproduk = 240;
        $idadmin = $_GET["idadmin"];
        
        date_default_timezone_set('Asia/Jakarta');
        $today = date('s');
        $waktu = date('H:i:s');;
        $qty = $_POST["provinsi"];
        $rocela = $_POST["setPertama"];
        $goura = $_POST["setKedua"];
        $font = $_POST["font"];
        $harga = $_POST["harga"];
        $variant_item = $_POST['variant'];
        $isCustom = $_POST['is_custom'];
        $tab=$_POST['tab'];

        // $custom = $goura . " | " . $variant_item;
        // $gouraString = implode(', ', $goura);
        // $variantString = implode(', ', $variant_item);
        
        // $custom = $variantString . " | " . $gouraString;
        // $customArray = explode(" | ", $custom);
        // var_dump($gouraString);

        // var_dump($today, $waktu, $idpo, $qty, $custom, $font, $harga, $idpodetail);
        $invoice =  'RPGP' . $idadmin . '-' . $idpoproduk;
        $queries = array();
        if(!$goura){
            echo "<script>alert('data kosong!');</script>";
            header("Refresh:0");
            return false;
        }
        $countQty = count($goura);
        
        // var_dump($invoice);
        
         
        for ($i = 0; $i < $countQty; $i++) {
            // $custom = $variantString . " | " . $gouraString;
            $idpodetail_item = $_POST["idpodetail"][$i];
            $idpo_item = $_POST["idpo"][$i];
            if(isset($_POST["namacustom"]) && $_POST["namacustom"][$i] !== null  && $_POST["namacustom"][$i] !== "") {
                if($isCustom[$i] == 1) {
                    $custom_item = $variant_item[$i] . " | " . $goura[$i]. " ( Nama Custom: " . $_POST["namacustom"][$i] . " )";    
                }
                if($isCustom[$i] == 2) {
                    $custom_item = $variant_item[$i] . " ( Nama Custom: " . $_POST["namacustom"][$i] . " ) | " . $goura[$i];    
                }
                if($isCustom[$i] == 3) {
                    $custom_item = $variant_item[$i] . " ( Nama Custom: " . $_POST["namacustom"][$i] . " ) | " . $goura[$i]. " ( Nama Custom: " . $_POST["namacustom"][$i] . " )";    
                }
            } else $custom_item = $variant_item[$i] . " | " . $goura[$i];

            $font_item = isset($font[$i]) && $font[$i] != '' ? mysqli_real_escape_string($koneksi, $font[$i]) : null;
            $harga_item = $harga[$i];
            $qty_item = $qty[$i];
            $isCustom_item = $isCustom[$i];

            
            if (!empty($idpodetail_item) && $qty_item >= 0 && $custom_item !== null) {
                // Ambil harga dari database
                $query_variant = "SELECT podetail.harga FROM podetail WHERE podetail.idpodetail='$idpodetail_item'";
                $sql_variant = mysqli_query($koneksi, $query_variant);
                $data_variant = mysqli_fetch_array($sql_variant);
                $harga_item = $data_variant['harga'];
    
                // Hitung total berdasarkan harga dan jumlah
                $total_item = $harga_item * $qty_item;
    
                // var_dump($idadmin . " | " . $idpoproduk . " | " . $idpo_item . " | " . $idpodetail_item . " | " . $custom_item . " | " . $harga_item . " | " . $qty_item . " | " . $total_item . " | " . $invoice);
    
                // Menambahkan query ke dalam array
                $queries[] = "INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, font, total, invoice, status, tgl, waktu, is_custom) VALUES (NULL, '$idadmin', '$idpoproduk', '$idpo_item', '$idpodetail_item', '1', '$custom_item', '$font_item', '$harga_item', '$invoice', 'Belum DP', NOW(), '$waktu', '$isCustom_item')";
            }

        }
        
        if (!empty($queries)) {
            $queries_string = implode("; ", $queries);
            
            if (mysqli_multi_query($koneksi, $queries_string)) {
                echo "<script>location='datapom.php?id=$idpoproduk&invoice=$invoice&idadmin=$idadmin'; </script>";
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