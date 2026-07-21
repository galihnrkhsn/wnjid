<?php 
  include "koneksi.php";
  
  $iddb = $_GET['iddb'];

// echo "$iddb";

$result_explode = explode('|', $iddb);
$id=$result_explode[0];
$nama=$result_explode[1];
echo "<br>";
echo "Mitra DB : $nama ($id)";
echo "<br>";
 ?>
<br>
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home11" class="nav-item nav-link active">Ready Stok</a></li>
		<li><a data-toggle="tab" href="#menu11" class="nav-item nav-link">Pre Order</a></li>
	</ul>
<div class="tab-content">
	<div id="home11" class="tab-pane fade show active" role="tabpanel">
<br>
<label>Ready Stock</label>

          <ul class="nav nav-tabs">
          	<li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Tagihan</a></li>
          	<li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Histori</a></li>
          </ul>

          <div class="tab-content">
          <div id="home" class="tab-pane fade show active" role="tabpanel">

          <div class="table-responsive">
          	<br>
          <table class="table table-striped" id="tbtagihan">
            <thead>
              <tr>
              	<th>No</th>
                <th>Tanggal Order</th>
                <th>invoice</th>
                <th>Total Tagihan</th>
                <th>Payment</th>                
              </tr>
            </thead>
            <tbody>
            <?php 
            $no=1;
            $tagihan=$koneksi->query("SELECT ordermitra.tgl,
            								 ordermitra.subtotal,
            								 ordermitra.invoice,
            								 ordermitra.payment,
            								 SUM(ordermitra.jumlah) as jumlah
            							FROM ordermitra
            							WHERE ordermitra.idmitra='$id'
            							and (ordermitra.payment = 'Belum Bayar' 
            							or ordermitra.payment = 'Sudah Konfirmasi')
            							and ordermitra.jumlah>0
            							GROUP BY ordermitra.invoice
                                      ORDER BY ordermitra.tgl DESC");
            while($tampilkan=$tagihan->fetch_assoc()){
$invoice = $tampilkan['invoice'];
$total_qty =  $tampilkan['jumlah'];          	
            ?>
              <tr>
              	<td><?= $no++; ?></td>
                <td>
                  <?php echo $tampilkan['tgl']; ?>
                </td>
                <td>
                  <a href="detailorder.php?invoice=<?php echo $tampilkan['invoice']; ?>" target=_blank()><?php echo $tampilkan['invoice']; ?></a>
                </td>

  <?php

    $totala=0;
    $jumlah_produknya = 0;
 if (substr($invoice,0,1)=="F") {
          $jumlah_produknya = $total_qty/2;
          $sql = "SELECT produk.harga as subtotal
            FROM ordermitra 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            and produk.idkategori BETWEEN 11 AND 13
            ORDER BY produk.harga desc
            LIMIT ".$jumlah_produknya."
            ";
 }else{
    $sql = "SELECT * 
            FROM ordermitra 
            inner join produk on produk.idproduk=ordermitra.idproduk 
            WHERE ordermitra.invoice='$invoice' 
            and ordermitra.jumlah>0 
            and produk.idkategori>0 
            and produk.idkategori<>2
            ";
 }
	$query = $koneksi->query($sql);
	while ($ga = $query->fetch_assoc()){
$totala +=  $ga['subtotal']; 
	} 
?>   
     <?php
    $totalb=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=2";
	$query = $koneksi->query($sql);
	while ($gb = $query->fetch_assoc()){
    ?>

    <?php  $totalb +=  $gb['subtotal']; } ?>

        <!----------------------------------------------------------------------------------------------------------------------------->

        <?php
    $totald5=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=5";
	$query = $koneksi->query($sql);
	while ($d5 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald5 +=  $d5['subtotal']; } ?>
    
    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald10=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=10";
	$query = $koneksi->query($sql);
	while ($d10 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald10 +=  $d10['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald15=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=15";
	$query = $koneksi->query($sql);
	while ($d15 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald15 +=  $d15['subtotal']; } ?> 

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald17=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=17";
	$query = $koneksi->query($sql);
	while ($d17 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald17 +=  $d17['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald20=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=20";
	$query = $koneksi->query($sql);
	while ($d20 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald20 +=  $d20['subtotal']; } ?>

    <!----------------------------------------------------------------------------------------------------------------------------->

    <?php
    $totald25=0;
    $sql = "SELECT * FROM ordermitra inner join produk on produk.idproduk=ordermitra.idproduk WHERE ordermitra.invoice='$invoice' and ordermitra.jumlah>0 and produk.idkategori=25";
	$query = $koneksi->query($sql);
	while ($d25 = $query->fetch_assoc()){
    ?>
    
    <?php  $totald25 +=  $d25['subtotal']; } ?>  

<?php 
	$sqlpengiriman = "SELECT * FROM orderpengiriman WHERE invoice='$invoice' ";
	$querypengiriman = $koneksi->query($sqlpengiriman);
	$pengiriman = $querypengiriman->fetch_assoc();
 ?>
         <?php
     $apaja=$pengiriman['dropship'];
    $dropship=$pengiriman['berat'];
    
        if($dropship<=5000 and $dropship>=0 and $apaja=='ya') {
            $biayad=3000;
        }
        else if($dropship<=10000 and $dropship>=6000 and $apaja=='ya') {
            $biayad=5000;
        }
        else if($dropship<=20000 and $dropship>=11000 and $apaja=='ya') {
            $biayad=10000;
        }
         else if($dropship<=30000 and $dropship>=21000 and $apaja=='ya') {
            $biayad=15000;
        }
        else if($dropship<=40000 and $dropship>=31000 and $apaja=='ya') {
            $biayad=20000;
        }
        else if($apaja=='tidak'){
            $biayad=0;
        }    
        else{
            $biayad='0'; 
        }
$ongkir=$pengiriman['ongkir'];
    $diskona=$totala*35/100;
    $diskonb=$totalb*55/100;
    $diskon5=$totald5*5/100;
    $diskon10=$totald10*10/100;
    $diskon15=$totald15*15/100;
    $diskon17=$totald17*17/100;
    $diskon20=$totald20*20/100;
    $diskon25=$totald25*25/100;
    $grandtotal=($totala+$totalb+$ongkir+$biayad)-($diskona+$diskonb+$diskon5+$diskon10+$diskon15+$diskon17+$diskon20+$diskon25);    
?>
                <td>                  
                  Rp. <?php echo number_format($grandtotal); ?>
                </td>
              	<td>
                <?php if ($tampilkan['payment']=='Sudah Konfirmasi'): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>
                   <?php if ($tampilkan['payment']=='Belum Bayar'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>                              		
              	</td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
<!------------------------------------------------------------------------------------------------------->
      <div id="menu1" class="tab-pane fade">
                  <div class="table-responsive">
          <table class="table table-striped" id="tbtagihanpo">
            <thead>
              <tr>
              	<th>No</th>
                <th>Tanggal Order</th>
                <th>invoice</th>
                <th>Jumlah Transfer</th>
                <th>Metode Bayar</th>
                <th>Payment</th>  
              </tr>
            </thead>
            <tbody>
            <?php 
            $no=1;
            $histori=$koneksi->query("SELECT ordermitra.tgl,
            								 ordermitra.subtotal,
            								 ordermitra.invoice,
            								 ordermitra.payment,
            								 orderpembayaran.jmlhtransfer,
            								 orderpembayaran.metodebayar
            							FROM ordermitra
            							JOIN orderpembayaran on orderpembayaran.invoice = ordermitra.invoice
            							WHERE ordermitra.idmitra='$id'
            							and (ordermitra.payment = 'Lunas' 
            							or ordermitra.payment = 'Sudah Konfirmasi')
            							and ordermitra.jumlah>0
            							GROUP BY ordermitra.invoice
                                      ORDER BY ordermitra.tgl DESC");
            while($tampilkan_histori=$histori->fetch_assoc()){
            ?>
              <tr>
              	<td>
              		<?= $no++; ?>
              	</td>
                <td>
                  <?php echo $tampilkan_histori['tgl']; ?>
                </td>
                <td>
                	<a href="detailorder.php?invoice=<?php echo $tampilkan_histori['invoice']; ?>" target=_blank()>
                		<?php echo $tampilkan_histori['invoice']; ?>                			
                	</a>
                  
                </td>
                <td>
                  Rp. <?php echo number_format($tampilkan_histori['jmlhtransfer']); ?>
                </td>
                <td>
                  <?php echo $tampilkan_histori['metodebayar']; ?>
                </td>
                <td>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan_histori['payment']; ?>
                  </div>                	
                </td>
              
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      </div> 
<!--------------------------------------HOME11------------------------------------------------------>
</div>
      <div id="menu11" class="tab-pane fade">
<br>
<label>Pre Order</label>

          <ul class="nav nav-tabs">
          	<li class="active"><a data-toggle="tab" href="#home22" class="nav-item nav-link active">Tagihan</a></li>
          	<li><a data-toggle="tab" href="#menu22" class="nav-item nav-link">Histori</a></li>
          </ul>

          <div class="tab-content">
          	<div id="home22" class="tab-pane fade show active" role="tabpanel"> 

<div class="table-responsive">
          <table class="table table-striped" id="tbhistoripo">
            <thead>
              <tr>
              	<th>No</th>
                <th>Tanggal Order</th>
                <th>invoice</th>
                <th>Total Tagihan</th>
                <th>Jumlah DP</th>
                <th>Jumlah Pelunasan</th>
                <th>Sisa Tagihan</th>
                <th>Status</th>  
              </tr>
            </thead>
            <tbody>
            <?php 
            $no=1;
            $tagihan_po=$koneksi->query("SELECT pomitra.tgl,
            									pomitra.idpoproduk,
            								 pomitra.invoice,
            								 pomitra.status,
            								 SUM(pomitra.total) as total,
            								 popembayaran.jmlhtransfer,
            								 popembayaran.jmlh_lunas

            							FROM pomitra
            							LEFT JOIN popembayaran ON popembayaran.invoice = pomitra.invoice
            							WHERE pomitra.idmitra='$id'
            							and pomitra.status <> 'Lunas'
            							and pomitra.jumlah>0
            							and pomitra.idpoproduk > 101
            							GROUP BY pomitra.invoice
                                      ORDER BY pomitra.tgl DESC");
            while($tampilkan_tagihan_po=$tagihan_po->fetch_assoc()){
            	$diskon = $tampilkan_tagihan_po['total']*35/100;
            	$sisa = ($tampilkan_tagihan_po['jmlhtransfer']+$tampilkan_tagihan_po['jmlh_lunas'])-($tampilkan_tagihan_po['total']-$diskon);
            ?>
              <tr>
              	<td>
              		<?= $no++; ?>
              	</td>
                <td>
                  <?php echo $tampilkan_tagihan_po['tgl']; ?>
                </td>
                <td>
					<a href="detailinvoice.php?invoice=<?php echo $tampilkan_tagihan_po['invoice']; ?>&idpoproduk=<?php echo $tampilkan_tagihan_po['idpoproduk']; ?>" target=_blank()>
                  		<?php echo $tampilkan_tagihan_po['invoice']; ?>   
                	</a>
                  
                </td>
                <td>
                  Rp. <?php echo number_format($tampilkan_tagihan_po['total']-$diskon); ?>
                </td>
                <td>
                 Rp. <?php echo number_format($tampilkan_tagihan_po['jmlhtransfer']); ?>
                </td>
                <td>
                	Rp. <?php echo number_format($tampilkan_tagihan_po['jmlh_lunas']); ?>
                </td>
                <td>
                	Rp. <?php echo number_format($sisa); ?>
                </td>
                <td>
                <?php if ($tampilkan_tagihan_po['status']=='Belum DP'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan_tagihan_po['status']; ?>
                  </div>
                  <?php elseif($tampilkan_tagihan_po['status']=='Sudah DP'): ?>
                 	<div class="badge bg-info text-white rounded-pill">
                  		<?php echo $tampilkan_tagihan_po['status']; ?>
                  	</div> 
                  	<?php else: ?> 
                 		<div class="badge bg-warning text-white rounded-pill">
                  			<?php echo $tampilkan_tagihan_po['status']; ?>
                  		</div>                   	                
                <?php endif ?>                	
                </td>
              
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
          	</div>

      	  <div id="menu22" class="tab-pane fade">
<div class="table-responsive">
          <table class="table table-striped" id="tbhistori">
            <thead>
              <tr>
              	<th>No</th>
                <th>Tanggal Order</th>
                <th>invoice</th>
                <th>Total Tagihan</th>
                <th>Jumlah DP</th>
                <th>Jumlah Pelunasan</th>
                <th>Metode Bayar</th>
                <th>Status</th>  
              </tr>
            </thead>
            <tbody>
            <?php 
            $no=1;
            $histori_po=$koneksi->query("SELECT pomitra.tgl,
            									pomitra.idpoproduk,
            								 pomitra.invoice,
            								 pomitra.status,
            								 SUM(pomitra.total) as total,
            								 popembayaran.jmlhtransfer,
            								 popembayaran.jmlh_lunas,
            								 popembayaran.metodebayar

            							FROM pomitra
            							LEFT JOIN popembayaran ON popembayaran.invoice = pomitra.invoice
            							WHERE pomitra.idmitra='$id'
            							and pomitra.status = 'Lunas'
            							and pomitra.jumlah>0
            							and pomitra.idpoproduk > 101
            							GROUP BY pomitra.invoice
                                      ORDER BY pomitra.tgl DESC");
            while($tampilkan_histori_po=$histori_po->fetch_assoc()){
            	$diskon = $tampilkan_histori_po['total']*35/100;
            	$sisa = ($tampilkan_histori_po['jmlhtransfer']+$tampilkan_histori_po['jmlh_lunas'])-($tampilkan_histori_po['total']-$diskon);
            ?>
              <tr>
              	<td>
              		<?= $no++; ?>
              	</td>
                <td>
                  <?php echo $tampilkan_histori_po['tgl']; ?>
                </td>
                <td>
					<a href="detailinvoice.php?invoice=<?php echo $tampilkan_histori_po['invoice']; ?>&idpoproduk=<?php echo $tampilkan_histori_po['idpoproduk']; ?>" target=_blank()>
                  		<?php echo $tampilkan_histori_po['invoice']; ?>   
                	</a>
                  
                </td>
                <td>
                  Rp. <?php echo number_format($tampilkan_histori_po['total']-$diskon); ?>
                </td>
                <td>
                 Rp. <?php echo number_format($tampilkan_histori_po['jmlhtransfer']); ?>
                </td>
                <td>
                	Rp. <?php echo number_format($tampilkan_histori_po['jmlh_lunas']); ?>
                </td>
                <td>
                	<?php echo $tampilkan_histori_po['metodebayar']; ?>
                </td>
                <td>
                 	<div class="badge bg-success text-white rounded-pill">
                  		<?php echo $tampilkan_histori_po['status']; ?>
                  	</div>                  	
                </td>
              
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      	  </div>
          </div>
      </div>
</div>     

<script type="text/javascript">
        $(document).ready( function () {
    $('#tbtagihan').DataTable();
} );
</script>      
<script type="text/javascript">
        $(document).ready( function () {
    $('#tbhistori').DataTable();
} );
</script> 
<script type="text/javascript">
        $(document).ready( function () {
    $('#tbtagihanpo').DataTable();
} );
</script> 
<script type="text/javascript">
        $(document).ready( function () {
    $('#tbhistoripo').DataTable();
} );
</script> 