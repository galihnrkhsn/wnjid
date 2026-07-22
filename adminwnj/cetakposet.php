<?php
include "koneksi.php";
$id=$_GET["id"];
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

  <title>Laporan PO</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
         

     <?php 
                           // $subtotal=0;
                            $datapoku=$koneksi->query("SELECT * from poproduk where idpoproduk='$id'");
                            //$no=1;
                         
                           $tampilkanuy=$datapoku->fetch_assoc();
                            ?>         

<h3><strong><?php echo $tampilkanuy['namapo']; ?></strong></h3>
      
        
  <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
              <th style="width:1px">No</th>
            <th>Dress</th>
            <th>Jumlah</th>
            <th>Khimar</th>
            <th>Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $subtotal=0;
                            $datapo=$koneksi->query("SELECT podetail.variant, sum(pomitra.jumlah) as jumlah FROM poproduk INNER JOIN pomitra INNER JOIN pokategori INNER JOIN podetail on poproduk.idpoproduk=pomitra.idpoproduk and pokategori.idpo=pomitra.idpo and podetail.idpodetail=pomitra.idpodetail where poproduk.idpoproduk='$id' and pomitra.status<>'Belum Acc DB' GROUP by podetail.variant order by podetail.variant DESC");
                            $no=1;
                         
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
<?php 
$result_explode = explode('Khimar', $tampilkan['variant']);
$dress=$result_explode[0];
$khimar = $result_explode[1];
 ?>                                  
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo str_replace("Set","",$dress); ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            Khimar <?php echo str_replace(["Paket","Satuan"],"",$khimar); ?>
                          </td>
                           <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
              
                        <?php $subtotal=$subtotal+$tampilkan['jumlah'] ?>
                        </tr>
                        <?php } ?>
                                    <tr>
                            <td colspan="2">
                              <b>  Total</b>
                            </td>
                            <td>
                            <b><?php echo $subtotal; ?> </b> 
                            </td> 
                            <td>
                              Total
                            </td>
                            <td>
                            <b><?php echo $subtotal; ?> </b> 
                            </td>   
                        </tr>
                      </tbody>
                    </table><br><br>
                