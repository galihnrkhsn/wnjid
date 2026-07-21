<?php 
    include "koneksi.php";
    $jenis_mitra = $_GET['jenis_mitra'];
    echo "$jenis_mitra";
?>
  	<?php if ($jenis_mitra=='Distributor'): ?>
        <div class="form-group">
              <select class="form-control" name="db" id="db">
                  <option enabled selected>- Pilih Distibutor -</option>
                  <?php
                $datadb=$koneksi->query("   SELECT 
                                                admin_mitra.idadmin,
                                                admin_mitra.namamitra 
                                            FROM 
                                                admin_mitra 
                                            GROUP BY 
                                                admin_mitra.idadmin
                                            ORDER BY 
                                                admin_mitra.namamitra ASC;
                                        ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idadmin']; ?>"><?php echo $tampilkan['namamitra']; ?> (<?php echo $tampilkan['idadmin']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div class="form-group"  id="tabel" name="tabel"></div> 
        <script type="text/javascript">
                    $('#db').change(function(){
                    //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                    var iddb = $('#db').val();
                    $.ajax({
                        type : 'GET',
                        url : 'cek_per_invoice2.php',
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
              <select class="form-control" name="agen" id="agen">
                  <option enabled selected>- Pilih Agen -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitraagen.namaagen, mitraagen.idmitraagen
                                          FROM mitraagen 
                                          GROUP BY mitraagen.idmitraagen
                                          ORDER BY mitraagen.namaagen");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitraagen']; ?>"><?php echo $tampilkan['namaagen']; ?>(<?php echo $tampilkan['idmitraagen']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div class="form-group"  id="tabel_agen" name="tabel_agen"></div> 

        <script type="text/javascript">
                $('#agen').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var idmitraagen = $('#agen').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_per_agen2.php',
                    data :  'idmitraagen=' + idmitraagen,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tabel_agen").html(data);
                    }
                    
                });
            });
        </script>     			
  	<?php endif ?>


  	<?php if ($jenis_mitra=='Reseller'): ?>
       <div class="form-group">
              <select class="form-control" name="reseller" id="reseller">
                  <option enabled selected>- Pilih Reseller -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitrareseller.idmitrareseller, mitrareseller.namaagen 
                                            FROM mitrareseller 
                                            GROUP BY mitrareseller.idmitrareseller
                                            ORDER BY mitrareseller.namaagen ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitrareseller']; ?>"><?php echo $tampilkan['namaagen']; ?>(<?php echo $tampilkan['idmitrareseller']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div class="form-group"  id="tabel_reseller" name="tabel_reseller"></div> 

<script type="text/javascript">
          $('#reseller').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var idmitrareseller = $('#reseller').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_reseller2.php',
                data :  'idmitrareseller=' + idmitrareseller,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_reseller").html(data);
                }
                
            });
        });

</script>     		
  	<?php endif ?>

  	<?php if ($jenis_mitra=='Marketer'): ?>
       <div class="form-group">
              <select class="form-control" name="marketer" id="marketer">
                  <option enabled selected>- Pilih Marketer -</option>
                  <?php
                  $datadb=$koneksi->query("SELECT mitramarketer.idmitramarketer, mitramarketer.namaagen 
                                            FROM mitramarketer 
                                            GROUP BY mitramarketer.idmitramarketer
                                            ORDER BY mitramarketer.namaagen ");
                  while($tampilkan=$datadb->fetch_assoc()){
                  ?>
              <option value="<?php echo $tampilkan['idmitramarketer']; ?>"><?php echo $tampilkan['namaagen']; ?>(<?php echo $tampilkan['idmitramarketer']; ?>)</option>
              <?php } ?>                       
              </select>      
        </div> 
        <br>       
        <div class="form-group"  id="tabel_marketer" name="tabel_marketer"></div> 

<script type="text/javascript">
          $('#marketer').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var idmitramarketer = $('#marketer').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_marketer2.php',
                data :  'idmitramarketer=' + idmitramarketer,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_marketer").html(data);
                }
                
            });
        });

</script>    		
  	<?php endif ?>  	  	  	