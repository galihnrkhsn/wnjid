<?php 
  include "koneksi.php";
  
  $metode = $_GET['metode'];


         

 ?>

 <?php if ($metode=="Transfer Bank"): ?>
    <div class="form-group">
          <label>Nama Bank Pengirim</label>
          <input type="text" class="form-control" name="bank" required>    
    </div>
    <div class="form-group">
          <label>Nama / Nomor Rekening Pengirim</label>
          <input type="text" class="form-control" name="norek" required>    
    </div>     
    <div class="form-group">
          <label>Jumlah Bayar</label>
          <input type="number" class="form-control" min="0" name="jmlh">    
    </div>  
    <div class="form-group">
    <label>Metode Pembayaran</label>
    <select class="form-control" name="metodebayar" required>
        <option value="">~Pilih Rekening Pembayaran~</option>
            <option>Mandiri 1300017715213</option>
            <option>Muamalat 1100003930</option>
            <option>Bank Syariah Indonesia (BSI) 7105696706</option>
            <option>BRI 076201007469504</option>
            <option>BCA 7751043434</option>      
    </select>    
    </div>   	


 <?php else: ?>
<?php 
 $result_explode = explode('|', $metode);
  $metode=$result_explode[0];
  $idadminnya=$result_explode[1];
  $invoicenya=$result_explode[2];

$datamitra=$koneksi->query("SELECT (SUM(debit)-SUM(credit)) as sisa_saldo FROM saldo
                            where idadmin='$idadminnya'");
                            $tampilsaldo=$datamitra->fetch_assoc();
$sisa_saldo = $tampilsaldo['sisa_saldo'];                            

$datapo=$koneksi->query("SELECT SUM(total) as totalnya FROM pomitra
                            where invoice='$invoicenya'");
                            $tampilpo=$datapo->fetch_assoc();
$total = $tampilpo['totalnya'];
$diskon = $total *35/100;
$totalbayar = $total - $diskon;
 
// echo $sisa_saldo;
//  echo   $totalbayar;
 ?> 	

<?php if ($sisa_saldo >= $totalbayar): ?>

    <div class="form-group">
          <label>Nama Bank Pengirim</label>    
          <input type="text" class="form-control" name="bank" value="Deposit" readonly> 
	</div>             
    <div class="form-group">
          <label>Nama / Nomor Rekening Pengirim</label>	
          <input type="text" class="form-control" name="norek" value="Deposit" readonly> 
	</div>          
    <div class="form-group">   
          <label>Jumlah Bayar</label>
          <input type="number" class="form-control" value="<?= $totalbayar ?>" name="jmlh" readonly>    
	</div>
    <div class="form-group">
    <label>Metode Pembayaran</label>
<input type="text" class="form-control" name="metodebayar" value="Deposit" readonly>    
    </div>  
	<?php else: ?>
		<p>Saldo Tidak Cukup</p>
<?php endif ?>

 <?php endif ?>