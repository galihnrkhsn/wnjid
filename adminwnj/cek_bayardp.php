<?php 
  include "koneksi.php";
  
  $id = $_GET['idpoproduk'];

$result_explode = explode('|', $id);
$idpoproduk=$result_explode[0];
$jenis = $result_explode[1];

 // echo "$idpoproduk";

$datapo=$koneksi->query("SELECT namapo FROM poproduk
                            where idpoproduk='$idpoproduk'");
$tampilpo=$datapo->fetch_assoc(); 
  echo $tampilpo['namapo'];    


 ?>

     <div class="form-group">
          <label>Pilih Distributor</label>
          <select class="form-control" name="idadmin" id="idadmin" required>
              <option value="" selected>- Pilih Distibutor -</option>
              <?php
              $datadb=$koneksi->query("SELECT pomitra.idmitra, admin_mitra.namamitra, pomitra.invoice, pomitra.status FROM `pomitra` JOIN admin_mitra ON admin_mitra.idadmin = pomitra.idmitra WHERE pomitra.idpoproduk ='$idpoproduk'  GROup BY pomitra.idmitra ORDER BY admin_mitra.namamitra");
              while($tampilkan=$datadb->fetch_assoc()){
                $status = $tampilkan['status'];
              ?>
<?php if ($jenis=="DP"): ?>                                         
              <?php if ($status=="Sudah DP" or $status=="Lunas"): ?>
                <?php else: ?>
                <option value="<?php echo $tampilkan['idmitra']; ?>|<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['namamitra']; ?> (<?php echo $tampilkan['idmitra']; ?>)</option>
              <?php endif ?>
<?php endif ?> 

<?php if ($jenis=="Lunas"): ?>
              <?php if ($status=="Sudah DP"): ?>
                <option value="<?php echo $tampilkan['idmitra']; ?>|<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['namamitra']; ?> (<?php echo $tampilkan['idmitra']; ?>)</option>
              <?php endif ?>                
<?php endif ?>              
          <?php } ?>                       
          </select>      
    </div> 


<div  id="tabel_saldo" name="tabel_saldo"></div> 

<script type="text/javascript">

    $(document).ready(function(){
        $('#idadmin').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var idadmin = $('#idadmin').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_saldo_db.php',
                data :  'idadmin=' + idadmin,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_saldo").html(data);
                }
                
            });
        });


        
    });
</script> 