<?php 
    include "koneksi.php";

    date_default_timezone_set('Asia/Jakarta');
    $jenis_mitra    = $_GET['iddb'];
    $jumlahhari     ='-30 days'; 
    $tgl1           = date('Y-m-d');
    $tgl2           = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); 
?>
<p>Pembayaran <?= $jenis_mitra; ?></p>  
<?php if ($jenis_mitra=='Distributor'): ?>
    <div class="table-responsive">
        <table class="table table-bordered" id="tb_pembayaran_db1">
            <thead>
                <tr>
                    <th>No</th>
                    <th><input type='checkbox' id='checkAll' > Check</th>
                    <th>Payment</th>
                    <th>Nama Mitra</th>
                    <th>Bank Pengirim</th>
                    <th>Rekening / Nama Pengirim</th>
                    <th>Jumlah Transfer</th>
                    <th>Metode Pembayaran</th>
                    <th>No Order</th>
                    <th>Tanggal / Waktu TF</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $datapo     = $koneksi->query("SELECT admin_mitra.namamitra,
                                                        ordermitra.tgl as tglorder,
                                                        ordermitra.invoice,
                                                        ordermitra.payment,
                                                        orderpembayaran.bankpengirim,
                                                        orderpembayaran.rekeningpengirim,
                                                        orderpembayaran.jmlhtransfer,
                                                        orderpembayaran.metodebayar,
                                                        orderpembayaran.tgl as tgltf,
                                                        orderpembayaran.waktu 
                                                    FROM orderpembayaran 
                                                    RIGHT join ordermitra on ordermitra.invoice=orderpembayaran.invoice
                                                    LEFT join admin_mitra on ordermitra.idmitra=admin_mitra.idadmin
                                                    WHERE orderpembayaran.tgl > '$tgl2'
                                                    Group by ordermitra.invoice 
                                                    ORDER BY orderpembayaran.idpembayaran DESC LIMIT 1000
                                                ");
                    $no         = 1;
                    while($tampilkan = $datapo->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <?php if ($tampilkan['payment']=="Lunas" or $tampilkan['payment']=="LUNAS") : ?>
                            <p class="bg-success text-center text-light rounded">OK</p>
                            <?php else : ?>
                                <input type="checkbox" id="checkAll" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>">
                            <?php endif ?>
                        </td>     
                        <td><?php echo $tampilkan['payment']; ?></td>
                        <td><?php echo $tampilkan['namamitra']; ?></td>
                        <td><?php echo $tampilkan['bankpengirim']; ?></td>
                        <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
                        <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
                        <td><?php echo $tampilkan['metodebayar']; ?></td>
                        <?php if(strtotime($tampilkan['tglorder']) > strtotime('2024-11-02')): ?>
                            <td><a href="detail_pembayaran2.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=D"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php else :?>
                            <td><a href="detail_pembayaran.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=D"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php endif; ?>
                        <td><?php echo $tampilkan['tgltf']; ?> / <?php echo $tampilkan['waktu']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" name="done">Selesai</button>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="invoice[]"]').prop('checked',true);
                }else{
                    $('input[name="invoice[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="invoice[]"]').click(function(){
                var total_checkboxes = $('input[name="invoice[]"]').length;
                var total_checkboxes_checked = $('input[name="invoice[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
<?php endif ?>
<?php if ($jenis_mitra=='Agen'): ?>
    <div class="table-responsive">        
        <table class="table table-bordered" id="tb_pembayaran_agen">
            <thead>
                <tr>
                    <th>No</th>
                    <th><input type='checkbox' id='checkAll' > Check</th>
                    <th>No Order</th>
                    <th>Payment</th>
                    <th>Nama Agen</th>
                    <th>Nama DB</th>
                    <th>Bank Pengirim</th>
                    <th>Rekening / Nama Pengirim</th>
                    <th>Jumlah Transfer</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal TF</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $datapo     = $koneksi->query("SELECT admin_mitra.namamitra,
                                                        mitraagen.namaagen,
                                                        orderagen.tgl as tglorder,
                                                        orderagen.invoice,
                                                        orderagen.payment,
                                                        orderpembayaran.bankpengirim,
                                                        orderpembayaran.rekeningpengirim,
                                                        orderpembayaran.jmlhtransfer,
                                                        orderpembayaran.metodebayar,
                                                        orderpembayaran.tgl as tgltf,
                                                        orderpembayaran.waktu 
                                                    FROM `orderagen` 
                                                    inner join mitraagen 
                                                    inner join orderpembayaran 
                                                    inner join admin_mitra on admin_mitra.idadmin=mitraagen.idadmin 
                                                    and orderagen.idmitraagen=mitraagen.idmitraagen 
                                                    and orderagen.invoice=orderpembayaran.invoice 
                                                    WHERE orderpembayaran.tgl > '$tgl2'
                                                    Group by orderagen.invoice ORDER BY orderpembayaran.idpembayaran DESC LIMIT 1000");
                    $no         = 1;      
                    while($tampilkan = $datapo->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>     
                        <td>
                            <?php if ($tampilkan['payment']=="Lunas" or $tampilkan['payment']=="LUNAS") : ?>
                                <p class="bg-success text-center text-light rounded">OK</p>
                            <?php else : ?>
                                <input type="checkbox" id="checkAll" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                            <?php endif; ?>
                        </td>
                        <?php if(strtotime($tampilkan['tglorder']) > strtotime('2024-11-02')): ?>
                            <td><a href="detail_pembayaran2.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=A"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php else :?>
                            <td><a href="detail_pembayaran.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=A"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php endif; ?>
                        <td><?php echo $tampilkan['payment']; ?></td>
                        <td><?php echo $tampilkan['namaagen']; ?></td>
                        <td><?php echo $tampilkan['namamitra']; ?></td>
                        <td><?php echo $tampilkan['bankpengirim']; ?></td>
                        <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
                        <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
                        <td><?php echo $tampilkan['metodebayar']; ?></td>
                        <td><?php echo $tampilkan['tgltf']; ?></td>
                        <td><?php echo $tampilkan['waktu']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>  
        <button type="submit" class="btn btn-success" name="done2">Selesai</button>               
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="invoice[]"]').prop('checked',true);
                }else{
                    $('input[name="invoice[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="invoice[]"]').click(function(){
                var total_checkboxes = $('input[name="invoice[]"]').length;
                var total_checkboxes_checked = $('input[name="invoice[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>                    
<?php endif ?>
<?php if ($jenis_mitra=='Reseller'): ?>
    <div class="table-responsive">       
        <table class="table table-bordered" id="tb_pembayaran_reseller">
            <thead>
                <tr>
                    <th>No</th>
                    <th><input type='checkbox' id='checkAll' > Check</th>
                    <th>No Order</th>
                    <th>Payment</th>
                    <th>Nama Reseller</th>
                    <th>Nama Agen</th>
                    <th>Nama DB</th>
                    <th>Bank Pengirim</th>
                    <th>Rekening / Nama Pengirim</th>
                    <th>Jumlah Transfer</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal TF</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $datapo         = $koneksi->query("SELECT mitrareseller.namaagen as namareseller,
                                                            admin_mitra.namamitra,
                                                            mitraagen.namaagen,
                                                            orderreseller.tgl as tglorder,
                                                            orderreseller.invoice,
                                                            orderreseller.payment,
                                                            orderpembayaran.bankpengirim,
                                                            orderpembayaran.rekeningpengirim,
                                                            orderpembayaran.jmlhtransfer,
                                                            orderpembayaran.metodebayar,
                                                            orderpembayaran.tgl as tgltf,
                                                            orderpembayaran.waktu 
                                                        FROM orderreseller 
                                                        INNER JOIN orderpembayaran on orderreseller.invoice=orderpembayaran.invoice 
                                                        INNER JOIN mitrareseller on mitrareseller.idmitrareseller=orderreseller.idmitrareseller 
                                                        LEFT JOIN admin_mitra on mitrareseller.idadmin=admin_mitra.idadmin 
                                                        LEFT JOIN mitraagen ON mitrareseller.idmitraagen=mitraagen.idmitraagen 
                                                        WHERE orderpembayaran.tgl > '$tgl2'
                                                        Group by orderreseller.invoice ORDER BY orderpembayaran.idpembayaran DESC LIMIT 1000
                                                    ");
                    $no             = 1;
                    while($tampilkan = $datapo->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>     
                        <td>
                           <?php if ($tampilkan['payment']=="Lunas" or $tampilkan['payment']=="LUNAS") : ?>
                                <p class="bg-success text-center text-light rounded">OK</p>
                            <?php else : ?>
                                <input type="checkbox" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                            <?php endif; ?>
                        </td> 
                        <?php if(strtotime($tampilkan['tglorder']) > strtotime('2024-11-02')): ?>
                            <td><a href="detail_pembayaran2.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=R"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php else :?>
                            <td><a href="detail_pembayaran.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=R"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php endif; ?>
                        <td><?php echo $tampilkan['payment']; ?></td>
                        <td><?php echo $tampilkan['namareseller']; ?></td>                          
                        <td><?php echo $tampilkan['namaagen']; ?></td>
                        <td><?php echo $tampilkan['namamitra']; ?></td>
                        <td><?php echo $tampilkan['bankpengirim']; ?></td>
                        <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
                        <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
                        <td><?php echo $tampilkan['metodebayar']; ?></td>
                        <td><?php echo $tampilkan['tgltf']; ?></td>
                        <td><?php echo $tampilkan['waktu']; ?></td>
                    </tr>
                <?php  } ?>
            </tbody>      
        </table>
        <button type="submit" class="btn btn-success" name="done3">Selesai</button>          
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="invoice[]"]').prop('checked',true);
                }else{
                    $('input[name="invoice[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="invoice[]"]').click(function(){
                var total_checkboxes = $('input[name="invoice[]"]').length;
                var total_checkboxes_checked = $('input[name="invoice[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
<?php endif ?>
<?php if ($jenis_mitra=='Marketer'): ?>
    <div class="table-responsive">           
        <table class="table table-bordered" id="tb_pembayaran_marketer">
            <thead>
                <tr>
                    <th>No</th>
                    <th><input type='checkbox' id='checkAll' > Check</th>
                    <th>Payment</th>
                    <th>Nama Marketer</th>
                    <th>Nama Agen</th>
                    <th>Nama DB</th>
                    <th>Bank Pengirim</th>
                    <th>Rekening / Nama Pengirim</th>
                    <th>Jumlah Transfer</th>
                    <th>Metode Pembayaran</th>
                    <th>No Order</th>
                    <th>Tanggal TF</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $datapo     = $koneksi->query("SELECT mitramarketer.namaagen as namamarketer,
                                                        admin_mitra.namamitra,
                                                        mitraagen.namaagen,
                                                        ordermarketer.tgl as tglorder,
                                                        ordermarketer.invoice,
                                                        ordermarketer.payment,
                                                        orderpembayaran.bankpengirim,
                                                        orderpembayaran.rekeningpengirim,
                                                        orderpembayaran.jmlhtransfer,
                                                        orderpembayaran.metodebayar,
                                                        orderpembayaran.tgl as tgltf,
                                                        orderpembayaran.waktu 
                                                    FROM ordermarketer 
                                                    INNER JOIN orderpembayaran on ordermarketer.invoice=orderpembayaran.invoice 
                                                    INNER JOIN mitramarketer on mitramarketer.idmitramarketer=ordermarketer.idmitramarketer 
                                                    LEFT JOIN admin_mitra on mitramarketer.idadmin=admin_mitra.idadmin 
                                                    LEFT JOIN mitraagen ON mitramarketer.idmitraagen=mitraagen.idmitraagen 
                                                    WHERE orderpembayaran.tgl > '$tgl2'
                                                    Group by ordermarketer.invoice ORDER BY orderpembayaran.idpembayaran DESC 
                                                ");
                    $no         = 1;
                    while($tampilkan = $datapo->fetch_assoc()){
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>     
                        <td>
                        <?php if ($tampilkan['payment']=="Lunas" or $tampilkan['payment']=="LUNAS") : ?>
                                <p class="bg-success text-center text-light rounded">OK</p>
                            <?php else : ?>
                                <input type="checkbox" class="check-item" name="invoice[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $tampilkan['payment']; ?></td>
                        <td><?php echo $tampilkan['namamarketer']; ?></td>                          
                        <td><?php echo $tampilkan['namaagen']; ?></td>
                        <td><?php echo $tampilkan['namamitra']; ?></td>
                        <td><?php echo $tampilkan['bankpengirim']; ?></td>
                        <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
                        <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
                        <td><?php echo $tampilkan['metodebayar']; ?></td>
                        <?php if(strtotime($tampilkan['tglorder']) > strtotime('2024-11-02')): ?>
                            <td><a href="detail_pembayaran2.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=M"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php else :?>
                            <td><a href="detail_pembayaran.php?invoice=<?php echo $tampilkan['invoice']; ?>&jenis=M"><?php echo $tampilkan['invoice']; ?></a></td>
                        <?php endif; ?>
                        <td><?php echo $tampilkan['tgltf']; ?></td>
                        <td><?php echo $tampilkan['waktu']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>            
        </table> 
        <button type="submit" class="btn btn-success" name="done4">Selesai</button>                     
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="invoice[]"]').prop('checked',true);
                }else{
                    $('input[name="invoice[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="invoice[]"]').click(function(){
                var total_checkboxes = $('input[name="invoice[]"]').length;
                var total_checkboxes_checked = $('input[name="invoice[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>                    
<?php endif ?>
<?php include 'settingdatatables.php'; ?>
<script type="text/javascript">
    $(document).ready( function () {
        $('#tb_pembayaran_db1').DataTable({
            columnDefs: [
                { orderable: false, targets: 1 }
            ]
        });
    });
</script>


