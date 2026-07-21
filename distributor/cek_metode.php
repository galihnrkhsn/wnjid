<?php 
session_start();
include "koneksi.php";

$metodenya = $_GET['metode'];
 $result_explode = explode('|', $metodenya);
  $metode=$result_explode[0];
  $totalbayar=$result_explode[1];

echo $metode;

 ?>

 <?php if ($metode=="Bank"): ?>
 <?php  $idadmin=$_SESSION["admin_mitra"]["idadmin"];       
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idadmin='$idadmin'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){?>
                <label>Nama Bank Pengirim</label>
                <div class='form-group'>
                  <input type='text' class='form-control' name='bankpengirim' required>
                </div>
               <?php }else{ ?>
                  <div class='form-group'>
                    <label>Nama Bank Pengirim</label>
                      <select name='bankpengirim' class='form-control'>";
                  <?php $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                  while($distributor=$ambil->fetch_assoc()){
                      ?>
                  <option value="<?php echo $distributor['bank']?>"><?php echo $distributor['bank'] ?></option>
                     <?php }?>
           </select><br></div>
             <?php   }
                ?>
    
    
        <?php  $idadmin=$_SESSION["admin_mitra"]["idadmin"];        
                  $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idadmin='$idadmin'"); 
                  $bank=$ambil->fetch_assoc();
                if ($bank['bank']==0){ ?>
                <div class='form-group'>
                  <label>Nama / Nomor Rekening Pengirim</label>
                  <input type='text' class='form-control' name='rekeningpengirim' required>
                </div>
              <?php  }else{ ?>
                  <div class='form-group'>
          <label>Nama / Nomor Rekening Pengirim</label>
          <select name='rekeningpengirim' class='form-control' required>
                 <?php $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                  while($distributor=$ambil->fetch_assoc()){
                      ?>
                  <option value="<?php echo $distributor['namapemilik']." ".$distributor['rekening']?>"><?php echo $distributor['namapemilik']." ".$distributor['rekening']?></option>
                     <?php }?>
           </select><br>
         </div>
             <?php   }
                ?>
    
   
    
    <hr>
    
      <div class="form-group">
          <label>Jumlah Transfer</label>
    <input type="number" min="0" class="form-control" name="jmlhtransfer" required>
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
 <?php endif ?>

 <?php if ($metode=="Saldo"): ?>
<?php 
$idmitra=$_SESSION["admin_mitra"]["idadmin"];

$datamitra=$koneksi->query("SELECT (SUM(debit)-SUM(credit)) as sisa_saldo FROM saldo
                            where idadmin='$idmitra'");
                            $tampilsaldo=$datamitra->fetch_assoc();
$sisa_saldo = $tampilsaldo['sisa_saldo'];  

?>
<?php if ($sisa_saldo >= $totalbayar): ?>
	
<!-- <?= $sisa_saldo; ?> -->

    <div class="form-group">
          <label>Nama Bank Pengirim</label>    
          <input type="text" class="form-control" name="bankpengirim" value="Deposit" readonly> 
	</div>             
    <div class="form-group">
          <label>Nama / Nomor Rekening Pengirim</label>	
          <input type="text" class="form-control" name="rekeningpengirim" value="Deposit" readonly> 
	</div>          
    <div class="form-group">   
          <label>Jumlah DP</label>
          <input type="number" class="form-control" value="<?= $totalbayar ?>" name="jmlhtransfer" readonly>    
	</div>
    <div class="form-group">
    <label>Metode Pembayaran</label>
<input type="text" class="form-control" name="metodebayar" value="Deposit" readonly>    
    </div>   	
<?php else: ?>
			<p>Saldo Tidak Cukup</p>
<?php endif ?>    
 <?php endif ?>