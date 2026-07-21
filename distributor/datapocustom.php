<?php 
session_start();

include 'koneksi.php'; 

 $invoice = $_GET['invoice'];
 $idpoproduk = $_GET['id'];
  $idadmin= $_SESSION['idadmin'];
  $query = "SELECT COUNT(*) as jumlah,
  poproduk.idpoproduk,
  poproduk.namapo,
  poproduk.status,
  poproduk.note,
  poproduk.diskon,
  poproduk.pembayaran,
  pomitra.ket,
  pomitra.tgl,
  pomitra.waktu,
  pomitra.proses,
  pomitra.status as statuspo
  FROM poproduk 
  inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
  WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.invoice='$invoice'";
  $sql = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sql);
$statuspo = $datapo['statuspo'];
$persen_tambahan = $datapo['diskon'];
$pembayaranpo = $datapo['pembayaran'];
$note=$datapo['note'];
$prosespo=$datapo['proses'];


  $query_tgl = "SELECT bukapo.idpoproduk,
            bukapo.tgl_bayar
        FROM bukapo  
        WHERE bukapo.idpoproduk='$idpoproduk'
        
        ";
  $sqlpo_tgl = mysqli_query($koneksi, $query_tgl);  
  $datapo_tgl = mysqli_fetch_array($sqlpo_tgl); 
  $tgl_bayar = $datapo_tgl['tgl_bayar']; 
     $waktu_bayar = '23:59:59';    

     $findUser = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
     $queryUser = $findUser->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

	</head>
	<body>
<!--================ NAVBARU  =================-->


<!--================ NAVBARU END =================-->


		<div class="container" align="center">

      <p align="center"><strong>SALES INVOICE</strong></p>
      <p align="center"><strong><?php echo $datapo['namapo']; ?></strong></p><br>
      <p align="left">Nama Mitra  : <?php echo $queryUser["namamitra"]; ?> </p>
      <p align="left">Alamat  : <?php echo $queryUser["alamat"]; ?> </p>
	  <p align="left">No Invoice  : <?php echo $invoice; ?> </p>

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					 <th>No</th>
					 <?php if ($datapo['idpoproduk'] = 237) : ?>
    					 <th>Nama Bundling</th>
    					 <th>Nama Barang</th>
					 <?php else : ?>
    					 <th>Nama Barang</th>
    					 <th>Custom</th>
    				 <?php endif; ?>
					  <th>Font Teks</th> 
					 <th>Qty</th>
					 <th>Satuan</th>
					 <th>Total</th>
					</tr>
					<?php
					
					$no=1;
					$sql = mysqli_query($koneksi, "SELECT 
						podetail.variant,
						pokategori.namakategori,
						pomitra.idpomitra,
						pomitra.jumlah,
						pomitra.invoice,
						pomitra.total,
						podetail.harga,
						pomitra.custom,
            pomitra.template,
						pomitra.font
					FROM pomitra 
					inner JOIN pokategori on pokategori.idpo=pomitra.idpo 
					inner join podetail on podetail.idpodetail=pomitra.idpodetail
					WHERE pomitra.invoice='$invoice' 
					and pomitra.jumlah>0 
					ORDER BY pomitra.idpomitra ASC");
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							<td class="align-middle"><?php echo $no++; ?></td>
							
							<?php if($data['idpoproduk'] = 237) : ?>
        						<td class="align-middle"><?php echo $data['namakategori']; ?></td>
					    	<?php else : ?>
							    <td class="align-middle"><?php echo $data['variant']; ?></td>
					    	<?php endif; ?>
					    	<td class="align-middle">
                  <?php if ($data['template']): ?>
                    <b>Template : </b>
                    <br>
                    <?= $data['template'] ?>
                    <br>
                  <?php endif ?>
								<?php 
								if (substr($invoice,0,2)=="RM") {
									 $datacustom=$data['custom'];
									  $result_explode = explode('|', $datacustom);
								    $baris1=$result_explode[0];
								    $baris2=$result_explode[1];
								   echo  nl2br($baris1);
								   echo "<br>";
								   echo  nl2br($baris2);	
								}else{

								 ?>
                 <!--<b>Karakter : </b>-->
                 <!--<br>					    	-->
													    	<?php echo  nl2br($data['custom']); ?>
								<?php 
								}
								?>

					    	</td>
					     	 <td class="align-middle"><?php echo $data['font']; ?></td> 
					     	<td class="align-middle"><?php echo $data['jumlah']; ?></td>
					   
					     	<td class="align-middle"><?php echo $data['harga'] ?></td>
					     	<td class="align-middle"><?php echo $data['harga']*$data['jumlah']; ?></td>
   				     <?php
              $sum+= $data['jumlah'];     
              $jumlah+=$data['jumlah']*$data['harga'];
							?>
						</tr>
					<?php
					}
					
					?>
				</table>
			</div>

				<br>
<?php if ($idpoproduk<>163): ?>
          
     
<table style="float: right;width: 100%">
 <tbody  style="float: right;">
    <tr>
        <th style="padding-bottom: 5%;">Total Qty</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
        <?php echo $sum; ?>

        </td>
    </tr>
    <tr>
        <th>JUMLAH</th>
        <td>:</td>
        <td>
<?php 
$sqlharga2 = "SELECT MAX(total) as totalnya,invoice FROM `pomitra` WHERE `idpoproduk`= '$idpoproduk' and idmitra = '$idadmin' and invoice = '$invoice'";
$queryharga2 = $koneksi->query($sqlharga2);
$sisaharga2 = $queryharga2->fetch_assoc(); 
$stokharga2 = $sisaharga2['totalnya']; 
if ($idpoproduk=="120") {

  $jumlah=$stokharga2;

}
 ?>        
Rp. <?php echo number_format($jumlah); ?>                     
        </td>
    </tr>
                    
<?php 
  $persen=35;
  $diskon=35/100*$jumlah;
  $subtotal=$jumlah-$diskon; 
$diskon_tambahan = $persen_tambahan/100*$jumlah;
$subtotal=$jumlah-$diskon-$diskon_tambahan;
?>
    <tr>
        <th>Diskon DB <?= $persen; ?>%</th>
        <td>:</td>
        <td>
          Rp. <?php echo number_format($diskon); ?>               
        </td>
    </tr>
<?php if ($diskon_tambahan > 0): ?>
      
    <tr>
        <th>Diskon Tambahan</th>
        <td>:</td>
        <td>      
        Rp. <?= number_format($diskon_tambahan); ?>                     
        </td>
    </tr>                           
    <?php endif ?>      
    <tr>
        <th style="padding-bottom: 5%;">Total Bayar</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
Rp. <?php echo number_format($subtotal); ?>           
        </td>
    </tr>

                    
                <?php
                $dp=$subtotal*50/100;
                $namapayemnt='DP';
                $jenispayment='dp';
                if ($pembayaranpo == 'Lunas') {
                  $dp = $subtotal;
                  $namapayemnt='Pembayaran';
                }
?>
      <?php if ($pembayaranpo == 'DP'): ?>
        

          <tr>
              <th>Jumlah DP PO 50%</th>
              <td>:</td>
              <td>
      Rp. <?php echo number_format($dp); ?>       
              </td>
          </tr>  
      <?php endif ?>

    <?php 
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sqldp = mysqli_query($koneksi, "SELECT popembayaran.invoice,popembayaran.jmlhtransfer,popembayaran.jmlh_lunas
                                            FROM `popembayaran` 
                                            WHERE popembayaran.invoice ='$invoice' ");
          
          $datadp = mysqli_fetch_array($sqldp) // Ambil semua data dari hasil eksekusi $sql

          
          ?>

    <!--<tr>-->
    <!--    <th style="padding-top: 5%;">Status PO</th>-->
    <!--    <td style="padding-top: 5%;">:</td>-->
    <!--    <td style="padding-top: 5%;"><?= $datapo['statuspo']; ?></td>-->
    <!--</tr>  -->
<?php if ($datadp['invoice'] <> ""): ?>     
    <tr>
        <th>Konfirmasi DP</th>
        <td>:</td>
        <td>        
            Rp. <?php echo number_format($datadp['jmlhtransfer']); ?> 
        </td>
    </tr>
    <tr>
        <th>Konfirmasi Pelunasan</th>
        <td>:</td>
        <td>        
            Rp. <?php echo number_format($datadp['jmlh_lunas']); ?> 
        </td>
    </tr>     

   
    <tr>
        <th>Sisa Tagihan</th>
        <td>:</td>
        <td>
          <?php 
              $sisa = $datadp['jmlhtransfer'] + $datadp['jmlh_lunas'] - $subtotal;
              $sisalunas = $subtotal - $datadp['jmlhtransfer']- $datadp['jmlh_lunas'];
          ?>
          
          Rp. 
          <?php if ($sisa>0): ?>
          +             
          <?php endif ?> 
          <?php echo number_format($sisa); ?> 
        </td>
    </tr>      
 <?php endif ?>  
</tbody>
</table> 				          

        <?php endif ?>   
				          	<p align="left"><strong>Note : </strong><?php echo $note ?></p><br>
                    <?php echo "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$dp&bayar=$subtotal&idpo=$idpoproduk&jenis=$jenispayment'>Konfirmasi $namapayemnt</a>";   ?>

    


		</div>
	</body>
</html>