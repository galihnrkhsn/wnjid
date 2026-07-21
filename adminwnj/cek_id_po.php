<?php 
include "koneksi.php";

  $idpoproduk = $_GET['idpoproduk_custom'];

 // echo "$idpoproduk";

 ?>

<div class="form-group">
	<label>Jumlah Tab</label>
		<select class="form-control" id="jmlh_tab" name="jmlh_tab" required>
			<option disabled='disabled' value="" selected>~Pilih Jumlah Tab~</option>
			<option value="<?= $idpoproduk; ?>|1">1</option>
			<option value="<?= $idpoproduk; ?>|2">2</option>
			<option value="<?= $idpoproduk; ?>|3">3</option>
      <option value="<?= $idpoproduk; ?>|4">4</option>
      <option value="<?= $idpoproduk; ?>|5">5</option>
		</select>
</div>      
        <div class="form-group"  id="tabel_jmlh_tab" name="tabel_jmlh_tab"></div>        
           <script type="text/javascript">

  $(document).ready(function(){
    $('#jmlh_tab').change(function(){
      //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
      var jmlh_tab = $('#jmlh_tab').val();
          $.ajax({
              type : 'GET',
              url : 'cek_jmlh_tab.php',
              data :  'jmlh_tab=' + jmlh_tab,
          success: function (data) {
          //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
          $("#tabel_jmlh_tab").html(data);
        }
            });
    });

  });
  </script>      