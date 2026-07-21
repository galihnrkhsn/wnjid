<?php 
  include "koneksi.php";
  
  $idadmin = $_GET['idadmin'];

 $result_explode = explode('|', $idadmin);
  $idadminnya=$result_explode[0];
  $invoicenya=$result_explode[1];

 // echo "$idadminnya";

         

 ?>

      <div class="form-group">
          <label>Pilih Metode Bayar</label>
          <select class="form-control" name="metode" id="metode" required>
              <option value="" selected>- Pilih Metode Bayar -</option>

          <option value="Transfer Bank">Transfer Bank</option>
          <option value="Deposit|<?= $idadminnya; ?>|<?= $invoicenya; ?>">Deposit</option>
                      
          </select>      
    </div> 


<div  id="tabel_metode" name="tabel_metode"></div> 

<script type="text/javascript">

    $(document).ready(function(){
        $('#metode').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var metode = $('#metode').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_metode_dp.php',
                data :  'metode=' + metode,
                    success: function (data) {

                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#tabel_metode").html(data);
                }
                
            });
        });


        
    });
</script>     