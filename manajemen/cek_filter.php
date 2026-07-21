<?php 
    $filter = $_GET['filter'];
?>

<?php if ($filter=="Harian"): ?>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" class="form-control" name="tanggal" id="tanggal">    
    </div>
    <div id="tabel_filter" name="tabel_filter"></div>    

    <script type="text/javascript">
        $(document).ready(function(){
            $('#tanggal').change(function(){
                var tanggal = $('#tanggal').val();
                $.ajax({
                    type : 'GET',
                    url : 'cek_filter_jenis.php',
                    data :  'tanggal=' + tanggal+'|Harian',
                        success: function (data) {
                        $("#tabel_filter").html(data);
                    }
                });
            });
        });
    </script>                         
<?php endif ?>
 
<?php if ($filter=="Bulanan"): ?>
    <div class="form-group">
            <label>Bulan</label>
            <select  class="form-control" name="tanggal" id="tanggal">
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>               
    </div> 
    <div id="tabel_filter" name="tabel_filter"></div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tanggal').change(function(){
                var tanggal = $('#tanggal').val();
                $.ajax({
                    type : 'GET',
                    url : 'cek_filter_jenis.php',
                    data :  'tanggal=' + tanggal+'|Bulanan',
                        success: function (data) {
                        $("#tabel_filter").html(data);
                    }   
                });
            });  
        });
    </script>     
<?php endif ?>