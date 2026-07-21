<?php 
session_start();
$namalengkap= $_SESSION["management"]["namalengkap"];
$title = $namalengkap;
include 'template/header.php'; 


if(!isset($_SESSION["management"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$iduser= $_SESSION["management"]["id"];

  $sql = "SELECT * FROM management WHERE id='$iduser' ";
  $query = $koneksi->query($sql);
  $data = $query->fetch_assoc();

$tipe = $data['tipe'];
?>







<?php 
include 'template/topbar.php'; 
 ?>    


                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="m-0 font-weight-bold text-secondary">
                            Data Ads
                            <a href="index.php" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
                        </h2>

                        
                    </div>

                      
                 
                    <!-- Content Row -->
                    <div class="row">

<div class="table-responsive">
  <table class="table" id="tb_ads">
                      <thead>
                        <tr>   
                            <th>
                                No
                            </th>    
                            <th>
                           Update Status
                          </th> 
                          <th>
                            Tanggal
                          </th>
                          <th>
                           Program Ads
                          </th>
                          <th>
                           Target Kota
                          </th>
                          <th>
                           Nama DB
                          </th>
                          <th>
                           Whatsapp
                          </th>

                        </tr>
                      </thead>
                      <tbody>
                          <?php 

        
                            $data_ads=$koneksi->query("SELECT adsmitra.tgl, 
                                                              admin_mitra.namamitra, 
                                                              adsmitra.status,
                                                              adsmitra.program,
                                                              adsmitra.targetkota,
                                                              adsmitra.whatsapp,
                                                              adsmitra.idads
                                          FROM adsmitra 
                                          left join admin_mitra on admin_mitra.idadmin = adsmitra.idmitra
                                          
                                          order by adsmitra.tgl desc");
                            $no=1;
                          
                            while($tampilkan_ads=$data_ads->fetch_assoc()){
                            ?>
                        <tr>
                            <td><?= $no++; ?></td> 
                            <td class="align-middle">
<form method="post">
  <input type="hidden" name="id" value="<?= $tampilkan_ads['idads']; ?>">
                              <select name="status" class="form-control">
                                <option value="<?= $tampilkan_ads['status']; ?>"><?= $tampilkan_ads['status']; ?></option>
                                <option value="Antrian">Antrian</option>
                                <option value="Setting">Setting</option>
                                <option value="Running">Running</option>
                                <option value="Selesai">Selesai</option>
                              </select>  
                              <button type="submit" class="btn btn-primary" name="update">Update</button>
</form>                            
                            </td>
                            <td><?= $newDate = date("y-m-d", strtotime($tampilkan_ads['tgl'])) ?></td>
                            <td><?= $tampilkan_ads['program'] ?></td>
                            <td><?= $tampilkan_ads['targetkota'] ?></td>
                            <td><?= $tampilkan_ads['namamitra'] ?></td>
                            <td><?= $tampilkan_ads['whatsapp'] ?></td>
                        </tr>
<?php
  if(isset($_POST["update"])){
  
    $idads = $_POST['id'];
    $status = $_POST['status'];
                     
    $query = "UPDATE adsmitra SET status= '$status' where idads='$idads'";
    $sql = mysqli_query( $koneksi, $query);
                            
if ($sql) {
      echo "<script>alert('Status berhasil diubah');</script>";
      echo "<script>location='dataads.php';</script>";
          }
    else{
      echo "<script>alert('Status gagal diubah');</script>";
      echo "<script>location='dataads.php';</script>";      
    }                                   

    }
                         
?>
                        <?php } ?>
                      </tbody>
  </table>     

    
    
</div>


                    </div><!-- Content Row -->



<?php 
include 'template/footer.php'; 
 ?>

<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_ads').DataTable();
} );
</script>