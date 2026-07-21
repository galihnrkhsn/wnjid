<!-- <a class="btn btn-info" href="mitratambah.php"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Mitra</a>&nbsp
          <br><br> -->

<!-- Start Table -->
                      <div class="table-responsive">
                        <button class="btn btn-outline-success btn-icon-split"  onclick="JavaScript:window.location.href='subdb/excel_agen.php';">
                                <span class="icon text-white-50 bg-success">
                                    <i class="fas fa-download" style="margin-top:20%;"></i>
                                </span>
                                <span class="text">Download Excel Agen</span>
                            </button>
                            <br>
                            <br>
                        <table class="table table-striped table-bordered table-hover" id="tbmitraagen">
                          <thead>
                            <tr>
                              <th style="text-align: center">No</th>
                              <th style="text-align: center">Nama SubDB</th>
                              <th style="text-align: center">Nama DB</th>
                              <th style="text-align: center">Email</th>
                              <th style="text-align: center">Password</th>
                              <th style="text-align: center">Whatsapp</th>
                              <th style="text-align: center"> Alamat</th>
                              <th style="text-align: right"><i class="fas fa-cog"></i></th>
                            </tr>
                          </thead>
                        <tbody>  
                        <?php
                          $no=1;
                          $tampil =$koneksi->query("SELECT admin_mitra.namamitra,mitraagen.namaagen,mitraagen.idmitraagen,mitraagen.password,
                            mitraagen.email,mitraagen.whatsapp,mitraagen.alamat,mitraagen.status 
                            FROM mitraagen INNER JOIN admin_mitra ON mitraagen.idadmin=admin_mitra.idadmin 
                            ORDER BY idmitraagen DESC");
                          while($tampilMas=$tampil->fetch_assoc()){  
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><i class="fas fa-users"></i> <?php echo $tampilMas['namaagen']; ?> (<?php echo $tampilMas['idmitraagen']; ?>)</td>
                            <td><i class="fas fa-user"></i> <?php echo $tampilMas['namamitra']; ?></td>
                            <td><i class="fas fa-envelope" style="color: red"></i> 
                              <?php echo $tampilMas['email']; ?></td>
                            <td> 
                              <?php echo $tampilMas['password']; ?></td>
                            <td><i class="fa fa-whatsapp" style="color: green"></i> 
                              <?php echo $tampilMas['whatsapp']; ?></td>
                            <td><i class="fas fa-map-marker-alt" style="color: red"></i>
                              <?php echo $tampilMas['alamat']; ?></td>
                            <td>
                             <!-- <form method="post"><input type="hidden" value="">
                                <input type="hidden" name="idagen" value="<?php echo $tampilMas['idagen']; ?>">
                                <button type="submit" class="btn btn-danger" name="hapusagen" onclick="return confirm('Yakin Ingin Menghapus Data?');"><span class="fa fa-trash"></span></button>
                                <a class="btn btn-success" href="mitraubah.php?id=<?php echo $tampilMas['idagen']; ?>&jenis=<?php echo $tampilMas['kodeakses']; ?>"><span class="fa fa-edit"></span></a>
                             </form> -->
                            </td>
                          </tr>
                        <?php } ?>    
                        </tbody>
                      </table>
                        
                      </div>
                      <!-- End Table -->


<!-- Script PHP -->
<?php
  if(isset($_POST["hapusagen"])){
    $idagen= $_POST['idagen'];
    $koneksi->query("delete from t_agen where idagen='$idagen';");
      echo "<script>alert('data sudah terhapus');</script>";
      echo "<script>location='mitradata.php';</script>";
    }   
    

                   
?>