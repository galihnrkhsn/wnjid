<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | Wanoja</title>

<?php
//header("Content-type: application/vnd-ms-excel");
//header("Content-Disposition: attachment; filename=Data PO Variant.xls");
?>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
                            Berat
                          </th>
                          <th>
                           Stock
                          </th>
                          <!-- <th>
                            Tanggal
                          </th> -->
                     
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                               $no    =1;  
                            $dataproduk=$koneksi->query("SELECT kategori.namakategori,produk.idproduk,produk.berat,produk.namaproduk,produk.harga,produk.tgl,produk.stock,produk.foto  FROM produk inner join kategori ON 
                            produk.idkategori=kategori.idkategori where produk.stock>0 and produk.status=0 order by produk.namaproduk asc");
                              
                            while($tampilkan=$dataproduk->fetch_assoc()){
                            ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>    
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                          <td>
                            Rp <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['berat']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['stock']; ?>
                          </td>
                          <!-- <td>
                            <?php echo $tampilkan['tgl']; ?>
                          </td> -->
							
				
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                 
<script>
window.print();
</script>

</html>

		                                    