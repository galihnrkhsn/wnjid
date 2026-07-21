<?php 
var_dump();
die();
    include "../../koneksi.php";
    $iddb = $_GET['iddb'];
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
            <form method="post" action="suratjalan_print.php"  target="_blank">  
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
                        $datapo = $koneksi->query("SELECT ordermitra.invoice, admin_mitra.namamitra, admin_mitra.idadmin, admin_mitra_cs.namacs,
                                                        SUM(ordermitra.jumlah) as qty, MAX(ordermitra.tgl) as tgl, MAX(ordermitra.payment) as payment,
                                                        MAX(ordermitra.status_progres) as status_progres
                                                    FROM 
                                                        `ordermitra` 
                                                    INNER JOIN 
                                                        admin_mitra ON ordermitra.idmitra = admin_mitra.idadmin 
                                                    JOIN 
                                                        admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
                                                    WHERE 
                                                        ordermitra.jumlah > 0 
                                                        AND ordermitra.payment = 'Lunas' 
                                                        AND ordermitra.tgl > '2024-01-01'
                                                    GROUP BY 
                                                        ordermitra.invoice,
                                                        admin_mitra.namamitra,
                                                        admin_mitra.idadmin,
                                                        admin_mitra_cs.namacs
                                                    ORDER BY 
                                                        MAX(ordermitra.idorder) DESC
                                                    LIMIT 2000   
                                                ");
                        $no     = 1;
                        while($tampilkan = $datapo->fetch_assoc()){
                            $invoice1 		= $tampilkan['invoice'];
                            $datamitra		= $koneksi->query("SELECT SUM(surat_jalan.progres) as progresnya, surat_jalan.invoice FROM surat_jalan WHERE surat_jalan.invoice='$invoice1'");
                            $tampilprogres	= $datamitra->fetch_assoc();                                   
                            $kurang 		= $tampilkan['qty']-$tampilprogres['progresnya'];  
                    ?>
                    <?php if ($kurang<>0): ?>
						<tr>
							<td>
								<? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
									<a href="ambilbarang2.php?invoice=<?= $tampilkan['invoice']; ?>" target="blank()"><?= $tampilkan['invoice']; ?></a>
								<? else :?>
									<a href="ambilbarang.php?invoice=<?= $tampilkan['invoice']; ?>" target="blank()"><?= $tampilkan['invoice']; ?></a>
								<? endif ?>
							</td>
							<td>
								<?= $tampilkan['namamitra']; ?> (<?= $tampilkan['idadmin']; ?>) 
							</td>
							<td style="text-align:center"><?= $kurang; ?></td>
							<td>
								<strong><?= $tampilkan['namacs']; ?></strong>
							</td> 
							<td>
								<i class="fas fa-calendar" style="color: red"></i> <?= $tampilkan['tgl']; ?>
							</td>
						</tr>
					  <?php endif ?>
					  <?php } ?>
                </tbody>
            </table>
      <input type='submit' class="btn btn-primary" value='Simpan Surat Jalan' name='but_export'>
    </form>        
  </div>
</div>

<div id="menu2" class="tab-pane fade">
 <div class="table-responsive">
<!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
<form method="post" action="suratjalan_status.php" >  
<table class="table table-striped" id="tb_multiprint" target="_blank">
                     <thead>
          <tr>       
           <th><input type='checkbox' id='checkAll_status' > Check</th>
            <th>No</th>
            <th>No Surat Jalan</th>
            <th>Invoce</th>
            <!-- <th>Nama Produk</th> -->
            
            <th>Ready</th>
            <th>Status</th>
            <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $datapo=$koneksi->query("SELECT surat_jalan.invoice,
                                            produk.namaproduk,
                                            ordermitra.jumlah,
                                            surat_jalan.no_sj,
                                            surat_jalan.progres,
                                            surat_jalan.status,
                                            surat_jalan.waktu,
                                            surat_jalan.id_sj,
                                            SUM(surat_jalan.progres) as jumlahnya,
                                            ordermitra.idmitra
                                            FROM surat_jalan
                                            JOIN produk on produk.idproduk = surat_jalan.idproduk
                                            JOIN ordermitra on ordermitra.idorder = surat_jalan.idorder
                                            WHERE ordermitra.idmitra = '$iddb' and surat_jalan.progres>0 and surat_jalan.status <> 'Ambil Barang'
                                            GROUP BY surat_jalan.no_sj ORDER BY surat_jalan.no_sj

                                    ");
                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                                $id = $tampilkan['id_sj'];
                                $no_sj=$tampilkan['no_sj'];
            ?>
             <tr>
                         <td><input type='checkbox' name='update_status[]' value='<?= $id ?>' >
                            <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                            <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                            <input type='hidden' name='idorder<?= $id ?>' value='<?= $tampilkan['idorder']; ?>' >
                            <input type='hidden' name='idadmin' value='<?= $tampilkan['idmitra']; ?>' >
                         </td>
                         <td>
                             <?= $no++; ?>
                        </td>  
                        <td>
                            <a href="suratjalan_print_checker.php?no_sj=<?= $tampilkan['no_sj'] ?>&idadmin=<?= $tampilkan['idmitra'] ?>" target="_blank"><i class="fa fa-print"></i> <?= $tampilkan['no_sj']; ?></a>
                            
                          </td>   
                          <td>
            <?php 
            $data_invoice=$koneksi->query("SELECT surat_jalan.invoice
                                            
                                            FROM surat_jalan
                                            
                                            WHERE surat_jalan.no_sj = '$no_sj'
                                            GROUP BY surat_jalan.invoice

                                    ");
                            while($tampilkan_invoice=$data_invoice->fetch_assoc()){
                              echo $tampilkan_invoice['invoice'];
                              echo "<br>";
                            }
            ?>                            
                            
                          </td>
                          <!-- <td>
                            <?= $tampilkan['namaproduk']; ?>
                          </td> -->
                         
              <td>
                            <?= $tampilkan['jumlahnya']; ?> 
                          </td>
                           <td>
                            <?= $tampilkan['status']; ?> 
                          </td>
                           <td>
                            <?= $tampilkan['waktu']; ?> 
                          </td>
                        </tr>
            <?php } ?>
          </tbody>
        </table>
        
 <input type='submit' class="btn btn-success" value='Ubah Status' name='but_update' onclick="return confirm('Yakin Akan Ubah Data?');">        
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
