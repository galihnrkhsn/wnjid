<?php 
  include "koneksi.php";
  
  $idpoproduk = $_GET['idpoproduk'];

 // echo "$idpoproduk";

 ?>

  <div class="table-responsive" >
    
    <table class="table table-bordered" id="tb_surat_manual_po">
        <thead>
        <tr>
          <th><input type='checkbox' id='checkAll' ></th>
            <th>No</th>
          <th>Nama Produk</th>
          <th>Jumlah</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT podetail.idpodetail, podetail.variant FROM poproduk 
                	inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk 
                	inner join podetail  on pokategori.idpo=podetail.idpo 
                	WHERE poproduk.idpoproduk='$idpoproduk' 
                	order by podetail.variant asc
                  ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                  $id = $tampilkan['idpodetail'];
                ?>
                <tr>
        <td><input type='checkbox' name='update[]' value='<?= $id ?>'>
                            <input type='hidden' name='idpodetail<?= $id ?>' value='<?php echo $tampilkan['idpodetail']; ?>' >
 
                            
        </td>                 
                <td>
                  <?= $no++; ?>
                </td>
                <td>
                  <?= $tampilkan['variant'] ?>
                </td>
                <td>
                  <input type="number" name='progres<?= $id ?>' class="form-control" min="0" value="0">
                </td>

                        </tr>
                    
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    </div>

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