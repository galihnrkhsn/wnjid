<!-- MODE  -->


<div class="container">
    
    
  
       
            <div class="row">
                      <?php
          if(isset($_POST["cari"])){
          // Include / load file koneksi.php
          include "koneksi.php";
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
          $namaproduk=$_POST['namaproduk'];
          //$idkategori=$_GET['idkategori'];          
          // Cek apakah terdapat data page pada URL
          $page = (isset($_GET['page']))? $_GET['page'] : 1;
          
          $limit = 20; // Jumlah data per halamannya
          
          // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
          $limit_start = ($page - 1) * $limit;
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and namaproduk LIKE '%$namaproduk%' and status=0");
          
          $no = $limit_start + 1; // Untuk penomoran tabel
          
          }elseif(isset($_POST["tampil"])){
         // Include / load file koneksi.php
          include "koneksi.php";
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
          //$idkategori=$_GET['idkategori'];          
          // Cek apakah terdapat data page pada URL
          $page = (isset($_GET['page']))? $_GET['page'] : 1;
          
          $limit = 20; // Jumlah data per halamannya
          
          // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
          $limit_start = ($page - 1) * $limit;
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 LIMIT ".$limit_start.",".$limit);
          
          $no = $limit_start + 1; // Untuk penomoran tabel
          }else{
              // Include / load file koneksi.php
          include "koneksi.php";
          $idmitrareseller=$_SESSION['mitraagen']['idmitrareseller'];
          //$idkategori=$_GET['idkategori'];          
          // Cek apakah terdapat data page pada URL
          $page = (isset($_GET['page']))? $_GET['page'] : 1;
          
          $limit = 20; // Jumlah data per halamannya
          
          // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
          $limit_start = ($page - 1) * $limit;
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 order by idproduk desc LIMIT ".$limit_start.",".$limit);
          
          $no = $limit_start + 1; // Untuk penomoran tabel
          }
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6">
                    <!-- MODIF AWAL -->
                   <div class="card img" style="width:500px">
                    <img class="card-img-top" src="../distributor/foto/<?php echo $data['foto']; ?>" alt="Card image" style="width:100%">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $data['namaproduk']; ?> <br></h4>
                      <p class="card-text">
            (<?php echo $data['stock']; ?>)Pcs  &nbsp&nbsp&nbsp
                      <a href="add_chart2.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>" class="btn btn-primary">Beli</a></p>
                    </div>
                    </div>
                    <!-- MODIF AKHIR -->
                    
                    <!-- ASLINA AWAL 
                    <div class="single-popular-items mb-50 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s">
                        <div class="popular-img">
                                <img src="foto/<?php echo $data['foto']; ?>" alt="">
                            <div class="img-cap">
                                 <span><?php echo $data['namaproduk']; ?> <br>
            (<?php echo $data['stock']; ?>)Pcs <br> <a class="btn btn-info btn-lg" href="add_chart.php?id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>"> +<i class="glyphicon glyphicon-shopping-cart"></i> </a></span>
        
                            </div>
                            
                        </div>
                    </div>
                      ASLINA AKHIR -->
                </div>
                
                 <?php } ?>
            
        
    </div>
      
      <!--
      -- Buat Paginationnya
      -- Dengan bootstrap, kita jadi dimudahkan untuk membuat tombol-tombol pagination dengan design yang bagus tentunya
      -->
      <ul class="pagination">
        <!-- LINK FIRST AND PREV -->
        <?php
        if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
        ?>
          <li class="disabled"><a href="#">First</a></li>
          <li class="disabled"><a href="#">&laquo;</a></li>
        <?php
        }else{ // Jika page bukan page ke 1
          $link_prev = ($page > 1)? $page - 1 : 1;
        ?>
          <li><a href="?page=1">First</a></li>
          <li><a href="?page=<?php echo $link_prev; ?>">&laquo;</a></li>
        <?php
        }
        ?>
        
        <!-- LINK NUMBER -->
        <?php
        // Buat query untuk menghitung semua jumlah data
        $sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM produk where stock>0 and harga>0 and idkategori>0 and status=0");
        $get_jumlah = mysqli_fetch_array($sql2);
        
        $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
        $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
        $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
        $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
        
        for($i = $start_number; $i <= $end_number; $i++){
          $link_active = ($page == $i)? ' class="active"' : '';
        ?>
          <li<?php echo $link_active; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php
        }
        ?>
        
        <!-- LINK NEXT AND LAST -->
        <?php
        // Jika page sama dengan jumlah page, maka disable link NEXT nya
        // Artinya page tersebut adalah page terakhir 
        if($page == $jumlah_page){ // Jika page terakhir
        ?>
          <li class="disabled"><a href="#">&raquo;</a></li>
          <li class="disabled"><a href="#">Last</a></li>
        <?php
        }else{ // Jika Bukan page terakhir
          $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
        ?>
          <li><a href="?page=<?php echo $link_next; ?>">&raquo;</a></li>
          <li><a href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
        <?php
        }
        ?>
      </ul>
      <br>
      <br>
      <br>
      <br>
      <br>
    
    </div>