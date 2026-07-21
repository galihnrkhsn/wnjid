<?php 

include 'koneksi.php'; 

  $tanggalnya = $_GET['tanggal'];

$result_explode = explode('|', $tanggalnya);
$tanggal=$result_explode[0];
$filter = $result_explode[1];

 ?>
                    <div class="form-group">
                          <label>Filter</label>
                          <select  class="form-control" name="filter_cs" id="filter_cs">
                              <option value="<?php echo $tanggalnya; ?>|Semua CS">Semua CS</option>
                              <?php
                              $data_db=$koneksi->query("SELECT namacs FROM admin_mitra_cs WHERE namacs <> 'null' GROUP BY namacs ORDER BY namacs asc");
                              while($tampilkan_db=$data_db->fetch_assoc()){
                              ?>
                              <option value="<?php echo $tanggalnya; ?>|<?php echo $tampilkan_db['namacs']; ?>"><?php echo $tampilkan_db['namacs']; ?></option>
                              <?php } ?>                             
                          </select>  
                    </div>
                    <div id="tfilter_cs" name="tfilter_cs"></div> 
<script type="text/javascript">

    $(document).ready(function(){
        $('#filter_cs').change(function(){

            var filter_cs = $('#filter_cs').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_filter_cs.php',
                data :  'filter_cs=' + filter_cs,
                    success: function (data) {

                    $("#tfilter_cs").html(data);
                }
                
            });
        });  

        $('#filter_cs').ready(function(){

            var filter_cs = $('#filter_cs').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_filter_cs.php',
                data :  'filter_cs=' + filter_cs,
                    success: function (data) {
                    $("#tfilter_cs").html(data);
                }
                
            });
        });                
    });
</script>                      