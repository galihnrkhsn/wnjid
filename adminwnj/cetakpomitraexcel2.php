<?php
include "koneksi.php";
$namamitra=$_GET["namamitra"];
$namapo=$_GET["namapo"];
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data PO Variant.xls");
?>

 <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
         

<h3><strong><?php echo $namamitra ?></strong></h3>

<table class="table table-striped">
                      <thead>
                        <tr>
                             <th>
                                No
                            </th>    
                          <th>
                            Nama Mitra  
                          </th>          
                          <th>
                           Variant
                          </th>
                          <th>
                           Jumlah
                          </th>
                              <th>
                           No Invoice
                          </th>
                              <th>
                           Nama PO
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                          
                            $no=0;
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,pomitra.jumlah,podetail.variant,pomitra.invoice,poproduk.namapo FROM `pomitra` inner join admin_mitra inner join podetail inner join poproduk on admin_mitra.idadmin=pomitra.idmitra and pomitra.idpodetail=podetail.idpodetail and pomitra.idpoproduk=poproduk.idpoproduk where pomitra.jumlah>0 and admin_mitra.namamitra LIKE '%$namamitra%' and poproduk.namapo= '$namapo' order by podetail.idpodetail asc");
                      
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                          <td>
                             <?php echo $no++; ?>
                        </td>     
                         
                           <td>
                            <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['variant']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                            <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td> <td>
                            <?php echo $tampilkan['namapo']; ?>
                          </td>
                         
                            
                          <!--<form method="post"><input type="hidden" name="id" value="<?php echo $tampilkan['idpreorder']; ?>">
							                    <td class="align-middle"><select name="status">
							                                                <option value="proses">proses</option>
							                                                <option value="selesai">selesai</option>
							                                              </select>  
							                    <button type="submit" class="btn btn-primary" name="update">Update</button>
							                    </td>
							</form>-->
							
							<?php
                            if(isset($_POST["update"])){
	
                            $idpreorder = $_POST['id'];
	                        $status = $_POST['status'];
	                   
                            $query = "UPDATE list_po_mitra SET status= '".$status."' where idpreorder='$idpreorder'";
                            $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: index.php?page=listpomitra"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }
                            }    
                             ?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
                