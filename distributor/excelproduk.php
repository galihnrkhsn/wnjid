<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>WNJ</title>

<?php
//header("Content-type: application/vnd-ms-excel");
//header("Content-Disposition: attachment; filename=Data PO Variant.xls");
?>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
              

<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                      <thead>
                        <tr>
                            <th>
                            No    
                            </th>    
                            <th>
                            Nama Produk
                          </th>
                           <th>
                            Harga
                          </th>
                          <th>
                           Stock
                          </th>
                           <th>
                            Berat
                          </th>
                          <!-- <th>
                            Tanggal
                          </th> -->
                     
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                             
                               $no=1; 
$sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and status=0 order by namaproduk asc");
          
          $no = 1;
          while($data = mysqli_fetch_array($sql)){                   
                            ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>    
                          <td>
                            <?php echo $data['namaproduk']; ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($data['harga']); ?>
                          </td>
                          <td>
                           <?php echo $data['stock']; ?>
                          </td>
                          <td>
                            <?php echo $data['berat']; ?>
                          </td>
                          <!-- <td>
                            <?php echo $data['tgl']; ?>
                          </td> -->
							
				
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                 
<script>
window.print();
</script>

</html>

		                                    