<?php 
    include "koneksi.php";
    $idmitraagen = $_GET['idmitraagen'];
?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Ambil Barang</a></li>
        <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Checker</a></li>
        <!-- <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
        <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
    </ul>
<div class="tab-content">
    <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">    
        <div class="table-responsive">
            <!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
            <form method="post" action="suratjalan_print_agen.php" target="_blank">  
            <table class="table table-striped" id="tb_multiprint_1">
                <thead>
                    <tr>       
                        <th><input type='checkbox' id='checkAll' > Check</th>
                        <th>No</th>
                        <th>Invoce</th>
                        <th>Nama Produk</th>
                        <th>Ready</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice,
                                                        products.namaproduk,
                                                        orderagen.jumlah,
                                                        surat_jalan_subdb.progres,
                                                        surat_jalan_subdb.status,
                                                        surat_jalan_subdb.waktu,
                                                        surat_jalan_subdb.id_sj,
                                                        surat_jalan_subdb.idorder,
                                                        orderagen.idmitraagen
                                                    FROM surat_jalan_subdb
                                                    JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                                    JOIN products on products.id = variants.idproducts
                                                    JOIN orderagen on (orderagen.idorder = surat_jalan_subdb.idorder and orderagen.invoice = surat_jalan_subdb.invoice)
                                                    WHERE orderagen.idmitraagen = '$idmitraagen' 
                                                    AND surat_jalan_subdb.progres > 0 
                                                    AND surat_jalan_subdb.status = 'Ambil Barang'
                                                ");
                            $no = 1;
                            while($tampilkan = $datapo->fetch_assoc()){
                                $id = $tampilkan['id_sj'];
                    ?>
                        <tr>
                            <td><input type='checkbox' name='update[]' value='<?= $id ?>' >
                                <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                                <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                                <input type='hidden' name='idorder<?= $id ?>' value='<?= $tampilkan['idorder']; ?>' >
                                <input type='hidden' name='idmitraagen' value='<?= $tampilkan['idmitraagen']; ?>' >
                            </td>
                            <td><?= $no++; ?></td>     
                            <td><?= $tampilkan['invoice']; ?></td>
                            <td><?= $tampilkan['namaproduk']; ?></td>
                            <td><?= $tampilkan['progres']; ?></td>
                            <td><?= $tampilkan['status']; ?></td>
                            <td><?= $tampilkan['waktu']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type='submit' class="btn btn-primary" value='Print Data' name='but_export'>
        </form>        
    </div>
</div>

<div id="menu2" class="tab-pane fade">
    <div class="table-responsive">
        <!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
        <form method="post" action="suratjalan_status_subdb.php" >  
            <table class="table table-striped" id="tb_multiprint" target="_blank">
                <thead>
                    <tr>       
                        <th><input type='checkbox' id='checkAll_status' > Check</th>
                        <th>No</th>
                        <th>No Surat Jalan</th>
                        <th>Invoce</th>
                        <th>Ready</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice,
                                                        products.namaproduk,
                                                        orderagen.jumlah,
                                                        surat_jalan_subdb.no_sj,
                                                        surat_jalan_subdb.progres,
                                                        SUM(surat_jalan_subdb.progres) as jumlahnya,
                                                        surat_jalan_subdb.status,
                                                        surat_jalan_subdb.waktu,
                                                        surat_jalan_subdb.id_sj,
                                                        orderagen.idmitraagen
                                                    FROM surat_jalan_subdb
                                                    JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                                    JOIN products on products.id = variants.idproducts
                                                    JOIN orderagen on (orderagen.idorder = surat_jalan_subdb.idorder and orderagen.invoice = surat_jalan_subdb.invoice)
                                                    WHERE orderagen.idmitraagen = '$idmitraagen'
                                                    AND surat_jalan_subdb.progres > 0 
                                                    AND surat_jalan_subdb.status = 'Checker'
                                                    GROUP BY surat_jalan_subdb.invoice ORDER BY surat_jalan_subdb.no_sj
                                                ");
                            $no = 1;
                        while($tampilkan = $datapo->fetch_assoc()){
                            $id = $tampilkan['id_sj'];
                    ?>
                        <tr>
                            <td>
                                <input type='checkbox' name='update_status[]' value='<?= $id ?>' >
                                <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                                <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                                <input type='hidden' name='idorder<?= $id ?>' value='<?= $tampilkan['idorder']; ?>' >
                                <input type='hidden' name='idadmin' value='<?= $tampilkan['idmitraagen']; ?>' >
                            </td>
                            <td><?= $no++; ?></td>  
                            <td>
                                <a href="suratjalan_print_subdb2.php?no_sj=<?= $tampilkan['no_sj'] ?>&idadmin=<?= $tampilkan['idmitraagen'] ?>&jenis=Agen" target="_blank"><i class="fa fa-print"></i> <?= $tampilkan['no_sj']; ?></a>
                            </td>   
                            <td><?= $tampilkan['invoice']; ?></td>
                            <td><?= $tampilkan['jumlahnya']; ?></td>
                            <td><?= $tampilkan['status']; ?></td>
                            <td><?= $tampilkan['waktu']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type='submit' class="btn btn-success" value='Ubah Status' name='but_update_agen' onclick="return confirm('Yakin Akan Ubah Data?');">        
        </form>
    </div>
</div>

</div>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_multiprint').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
  columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_multiprint_1').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
  columnDefs: [
    { orderable: false, targets: 0 }
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

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_status').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_status[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_status[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_status[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_status[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_status[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_status').prop('checked',true);
                    }else{
                        $('#checkAll_status').prop('checked',false);
                    }
                });
            });
        </script>        
