<?php 
  include "koneksi.php";
  
  $namapo = $_GET['namapo'];


$datapo=$koneksi->query("SELECT namapo FROM poproduk
                            where idpoproduk='$namapo'");
$tampilpo=$datapo->fetch_assoc(); 


 ?>

 <div class="table-responsive">

<br>
<h4>
<?php   echo $tampilpo['namapo']; ?>
</h4>
				<table class="table table-bordered" id="tb_dp" style="width: 100%">
          <thead>
					<tr>
					    <th>No</th>
					    <th><input type='checkbox' id='checkAll'></th>
              <th>Status PO / Pembayaran</th>
						<th>Nama Mitra</th>
            			<!-- <th>Nama PO</th> -->
					    <th>Bank / Rekening / Nama Pengirim</th>
						<th>Transfer DP</th>
						<th>Metode Pembayaran</th>
						<th>No Invoice</th>
            <!-- <th>Total</th> -->
					    <th>Waktu Transfer</th>
					    
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $no=1;
                            $datapo=$koneksi->query("SELECT 
                              
                              pomitra.status,
                              poproduk.namapo,
                              popembayaran.invoice,
                              popembayaran.bankpengirim,
                              popembayaran.rekeningpengirim,
                              popembayaran.jmlhtransfer,
                              popembayaran.jmlh_lunas,
                              popembayaran.metodebayar,
                              popembayaran.tgl,
                              popembayaran.waktu,
                              popembayaran.jenis,
                              popembayaran.ket,
                              popembayaran.idpembayaran 
                              FROM popembayaran 
                              LEFT JOIN pomitra on popembayaran.invoice=pomitra.invoice 
                              
                              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin 
                              
                              INNER JOIN poproduk on popembayaran.idpoproduk=poproduk.idpoproduk 
                              WHERE pomitra.idpoproduk = '$namapo'
                              AND pomitra.idmitra <>''
                              GROUP BY popembayaran.idpembayaran 
                              ORDER BY popembayaran.idpembayaran DESC");
                           
                            while($tampilkan=$datapo->fetch_assoc()){
$total_transfer +=$tampilkan['jmlhtransfer'];

                            	$id = $tampilkan['idpembayaran'];
                            	$invoice1 = $tampilkan['invoice'];

                              $metodebayar = $tampilkan['metodebayar'];
$result_explode = explode(' ', $metodebayar);
$bank = $result_explode[0];

if ($bank=="Bank") {
  $bank="BSI";
}           

$datamitra=$koneksi->query("SELECT admin_mitra.namamitra, pomitra.invoice FROM pomitra
							JOIN admin_mitra on admin_mitra.idadmin = pomitra.idmitra
                            where pomitra.invoice='$invoice1'");
                            $tampilprogres=$datamitra->fetch_assoc();                                   
                              $namamitra = $tampilprogres['namamitra'];  
                                             	
                            ?>
                        <tr>
                             <td>
                             <?php echo $no++; ?>
                        </td>     
							<td>								
             
                <input type='checkbox' name='update[]' value='<?= $id ?>' >    			
              </td>
              <!-- <td><?= $tampilkan['jenis']; ?>  </td> -->
              <td style="width: 10px"><?= $tampilkan['status']; ?> / <?= $tampilkan['jenis']; ?> <?= $tampilkan['ket']; ?></td>
                           <td>
                           <?php echo $namamitra; ?>
                          </td>
                          <!-- <td>
                           <?php echo $tampilkan['namapo']; ?>
                          </td> -->
                            <td>
                              <?php echo $tampilkan['bankpengirim']; ?> / <?php echo $tampilkan['rekeningpengirim']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['jmlhtransfer']; ?>
                          </td>
                            <td>
                           <?php echo $bank; ?>
                          </td>
                          <td>
                           <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                          </td>
                          <!-- <td> -->
<!-- <?php 
$invoice = $tampilkan['invoice'];
$total=0;
          $sql = mysqli_query($koneksi, "SELECT podetail.variant,
                              podetail.harga,
                              pomitra.jumlah
                          FROM pomitra 
                          JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
                          WHERE pomitra.invoice='$invoice' 
                          AND pomitra.jumlah>0");
          
          while($data = mysqli_fetch_array($sql)){
          $sum+= $data['jumlah'];
          $total += $data['jumlah']*$data['harga'];
 ?>                           
 <?php } ?>
<?php 
  $ongkir = 0;
  $dropship = 0;  
   $querypengiriman = "SELECT 
            podropship.ongkir,
            podropship.dropship
        FROM podropship 
        WHERE podropship.invoice='$invoice'";
  $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
  $datapengiriman = mysqli_fetch_array($sqlpengiriman);
  $ongkir = $datapengiriman['ongkir'];
  $dropship = $datapengiriman['dropship'];  
 ?>

 <?php $subtotal = $total-(35/100*$total)+$ongkir+$dropship; ?> 
                          <?= $subtotal;?> -->
                          <!-- </td> -->
                          <td>
                           <?php echo $tampilkan['tgl']; ?>/<?php echo $tampilkan['waktu']; ?>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="5">Total Jumlah Transfer</td>
                          <td><?= $total_transfer; ?></td>
                          <td colspan="3"></td>
                        </tr>
                      </tfoot>
                    </table>
                      
                  </div>




  <?php include "settingdatatables.php"; ?>   
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_dp').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
          columnDefs: [
    { orderable: false, targets: 1 }
  ]
});
} );
</script>                  

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>