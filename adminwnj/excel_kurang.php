<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
  $idpodetail = $_GET['id'];
  $status = $_GET['status'];


  $sql_variant = "SELECT variant FROM podetail WHERE idpodetail='$idpodetail' ";
  $query_variant = $koneksi->query($sql_variant);
  $datapo_variant = $query_variant->fetch_assoc();
  $variant = $datapo_variant['variant'];
?>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Kekurangan Ambil Barang $variant.xls");
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

<h2><?= $datapo_variant['variant'] ?></h2>
  <table class="table table-striped">
                      <thead>
                        <tr>
                            <th style="width:1%">
                             No
                            </th>  
                          <th>
                            Invoce  
                          </th> 
                           <th>
                            Progres
                          </th>     
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $data_ambil=$koneksi->query("SELECT invoice, progres
                              FROM surat_jalan_po
                              WHERE idpodetail = '$idpodetail'
                              AND status = '$status'
                              ORDER BY invoice asc
                              ");
                            $no=1;
                          
                            while($tampilkan_ambil=$data_ambil->fetch_assoc()){
$total += $tampilkan_ambil['progres'];                            
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>    
                         <td>
                           <?= $tampilkan_ambil['invoice'] ?>
                         </td>
                         <td>
                           <?= $tampilkan_ambil['progres'] ?>
                         </td>

              
                        </tr>                     
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">Total</td>
                          <td><?= $total; ?></td>
                        </tr>
                      </tfoot>
  </table>

</body>
</html>
