<?php 
  include "koneksi.php";
  
  $jenis_mitra = $_GET['jenis_mitra'];
echo "<br>";
echo "Pilih $jenis_mitra";

 ?>


  	<?php if ($jenis_mitra=='Distributor'): ?>
        <div class="form-group">
              <select class="form-control" name="db" id="db">
                  <option enabled selected>- Pilih Distibutor -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT admin_mitra.idadmin, admin_mitra.namamitra 
                                                  FROM admin_mitra 
                                                  
                                                  GROUP BY admin_mitra.idadmin
                                                  ORDER BY admin_mitra.namamitra");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idadmin']; ?>|D"><?php echo $tampilkan['namamitra']; ?> (<?php echo $tampilkan['idadmin']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div  id="tabel" name="tabel"></div> 

<script type="text/javascript">
	        $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_invoice_po.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel").html(data);
                }
                
            });
        });

</script>        
  	<?php endif ?>
	  	  	

 <?php if ($jenis_mitra=='Agen'): ?>
       <div class="form-group">
              <select class="form-control" name="db" id="db">
                  <option enabled selected>- Pilih Agen -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitraagen.idmitraagen, mitraagen.namaagen 
                                            FROM mitraagen 
                                            
                                            GROUP BY mitraagen.idmitraagen
                                            ORDER BY mitraagen.namaagen ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitraagen']; ?>|A"><?php echo $tampilkan['namaagen']; ?> (<?php echo $tampilkan['idmitraagen']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div  id="tabel" name="tabel"></div> 

<script type="text/javascript">
          $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_invoice_po.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel").html(data);
                }
                
            });
        });

</script>       
    <?php endif ?>   


 <?php if ($jenis_mitra=='Reseller'): ?>
       <div class="form-group">
              <select class="form-control" name="db" id="db">
                  <option enabled selected>- Pilih Reseller -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitrareseller.idmitrareseller, mitrareseller.namaagen 
                                            FROM mitrareseller 
                                            GROUP BY mitrareseller.idmitrareseller
                                            ORDER BY mitrareseller.namaagen ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitrareseller']; ?>|R"><?php echo $tampilkan['namaagen']; ?></option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div  id="tabel" name="tabel"></div> 

<script type="text/javascript">
          $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_invoice_po.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel").html(data);
                }
                
            });
        });

</script>       
    <?php endif ?>        

    <?php if ($jenis_mitra=='Marketer'): ?>
       <div class="form-group">
              <select class="form-control" name="db" id="db">
                  <option enabled selected>- Pilih Marketer -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitramarketer.idmitramarketer, mitramarketer.namaagen 
                                            FROM mitramarketer 
                                            GROUP BY mitramarketer.idmitramarketer
                                            ORDER BY mitramarketer.namaagen ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitramarketer']; ?>|M"><?php echo $tampilkan['namaagen']; ?></option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div  id="tabel" name="tabel"></div> 

<script type="text/javascript">
          $('#db').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var iddb = $('#db').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_invoice_po.php',
                data :  'iddb=' + iddb,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel").html(data);
                }
                
            });
        });

</script>       
    <?php endif ?>                   