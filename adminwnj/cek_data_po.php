<?php 
  include "koneksi.php";
  $namapo = $_GET['namapo'];

// echo "$namapo";

 ?>
<a href="excel_ambilpo.php?id=<?=$namapo; ?>" class="btn btn-success btn-sm mb-4">Excel</a>
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
          <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Sub-DB</a></li>
          <!-- <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
          <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
        </ul>

 <div class="tab-content">
    <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">    
    <div class="table-responsive">
    <br>
    <table class="table table-bordered" id="tb_ambil_barang">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama PO</th>
                <th>Invoice</th>
                <th>Nama Mitra</th>
                <th>Krg</th>
                <th>Nama CS</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
              <?php 
                $datapo = $koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  poproduk.namapo,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  JOIN poproduk on poproduk.idpoproduk = pomitra.idpoproduk
                  inner join admin_mitra on pomitra.idmitra=admin_mitra.idadmin
                  LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                  WHERE pomitra.idpoproduk = '$namapo' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                // $datapo=$koneksi->query("SELECT 
                //   admin_mitra.namamitra,
                //   admin_mitra.idadmin,
                //   mitraagen.namaagen,
                //   mitraagen.idmitraagen,
                //   pomitra.tgl,
                //   pomitra.invoice,
                //   admin_mitra_cs.namacs,
                //   SUM(pomitra.jumlah) as qty
                //   FROM `pomitra` 
                //   inner join mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen
                //   JOIN admin_mitra on mitraagen.idadmin = admin_mitra.idadmin
                //   JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
                //   WHERE pomitra.idpoproduk = '$namapo' and pomitra.jumlah>0
                //   GROUP BY pomitra.invoice");
                $no = 1;
              
                while($tampilkan = $datapo->fetch_assoc()){
                    $invoice1       = $tampilkan['invoice'];
                    $datamitra      = $koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                                                        where surat_jalan_po.invoice='$invoice1'");
                    $tampilprogres  = $datamitra->fetch_assoc();                                   
                    $kurang         = $tampilkan['qty']-$tampilprogres['progresnya'];                  
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <strong><?= $tampilkan['namapo']; ?></strong>
                    </td>  
                    <td>
                        <a href="ambilbarang_po.php?invoice=<?= $tampilkan['invoice']; ?>&mitra=D" target="blank()"><?= $tampilkan['invoice']; ?></a>
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
<?php 
$total_kurang += $kurang;
// $totalTesting += $tampilkan['qty'];
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <!--<tr>-->
                        <!--  <td colspan="3">Total Testing</td>-->
                        <!--  <td class="text-center"><?= $totalTesting ?></td>-->
                        <!--  <td colspan="2"></td>-->
                        <!--</tr>-->
                        <tr>
                          <td colspan="4">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td colspan="2"></td>
                        </tr>
                      </tfoot>
                    </table>
                    
                    </div>
                    </div>
                    
<div id="menu1" class="tab-pane"  role="tabpanel"> 
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#agen" class="nav-item nav-link active">Agen</a></li>
    <li><a data-toggle="tab" href="#reseller" class="nav-item nav-link">Reseller</a></li>
    <li><a data-toggle="tab" href="#marketer" class="nav-item nav-link">Marketer</a></li>
  </ul>
<div class="tab-content">
  <div id="agen" class="tab-pane fade show active" role="tabpanel"> 
    <p>Agen</p>
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_ambil_barang_agen">
        <thead>
        <tr>
            <!-- <th>No</th> -->
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Nama DB</th>
                <th>Krg</th>
                <th>Nama CS</th>
                    <th>Tanggal</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;              
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitraagen.namaagen,
                  mitraagen.idmitraagen,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen
                  JOIN admin_mitra on mitraagen.idadmin = admin_mitra.idadmin
                  JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
                  WHERE pomitra.idpoproduk = '$namapo' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanagen=$datapo->fetch_assoc()){
                  $invoice1 = $tampilkanagen['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice1'");
                            $tampilprogres=$datamitra->fetch_assoc();       

                                $kurang = $tampilkanagen['qty']-$tampilprogres['progresnya'];                      
                ?>
                <tr>
                 <!-- <td>
                     <strong><?= $no++; ?></strong>
                </td>  -->  
                  <td>
                       <a href="ambilbarang_po.php?invoice=<?= $tampilkanagen['invoice']; ?>&mitra=A" target="blank()"><?= $tampilkanagen['invoice']; ?></a>
                       
                      </td>
                   <td>
                   <?= $tampilkanagen['namaagen']; ?> (<?= $tampilkanagen['idmitraagen']; ?>) 

                  </td>
                  <td>
                   <?= $tampilkanagen['namamitra']; ?> (<?= $tampilkanagen['idadmin']; ?>) 

                  </td>
                  <td style="text-align:center"><?= $kurang; ?></td>
                  <td>
                     <strong><?= $tampilkanagen['namacs']; ?></strong>
                </td> 
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanagen['tgl']; ?>
                      </td>
              
                        </tr>
<?php 
$total_kurang += $kurang;
// $totalTesting += $tampilkanagen['qty'];
// $total_keseluruhan = $totalTesting - $total_kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <!--<tr>-->
                        <!--  <td colspan="3">Total Testing</td>-->
                        <!--  <td class="text-center"><?= $total_keseluruhan ?></td>-->
                        <!--  <td class="text-center"><?= $totalTesting; ?></td>-->
                        <!--  <td></td>-->
                        <!--</tr>-->
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td colspan="2"></td>
                        </tr>
                      </tfoot>
                    </table>
                    
                    </div>    
  </div>

  <div id="reseller" class="tab-pane" role="tabpanel">
  <p>Reseller</p>
  <div class="table-responsive">
    
    <table class="table table-bordered" id="tb_ambil_barang_reseller">
        <thead>
        <tr>
            <!-- <th>No</th> -->
          <th>Invoice</th>
          <th>Nama Mitra</th>

          <th>Nama DB</th>
                <th>Krg</th>
                <th>Nama CS</th>
                    <th>Tanggal</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;              
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitrareseller.namaagen,
                  mitrareseller.idmitrareseller,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller
                  JOIN admin_mitra on mitrareseller.idadmin = admin_mitra.idadmin
                  JOIN admin_mitra_cs on mitrareseller.idadmin = admin_mitra_cs.idadmin  
                  WHERE pomitra.idpoproduk = '$namapo' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanreseller=$datapo->fetch_assoc()){
                  $invoice1 = $tampilkanreseller['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice1'");
                            $tampilprogres=$datamitra->fetch_assoc();     

                                $kurang = $tampilkanreseller['qty']-$tampilprogres['progresnya'];                   
                ?>
                <tr>
                 <!-- <td>
                     <strong><?= $no++; ?></strong>
                </td>  -->  
                  <td>
                       <a href="ambilbarang_po.php?invoice=<?= $tampilkanreseller['invoice']; ?>&mitra=R" target="blank()"><?= $tampilkanreseller['invoice']; ?></a>
                       
                      </td>
                   <td>
                   <?= $tampilkanreseller['namaagen']; ?> (<?= $tampilkanreseller['idmitrareseller']; ?>) 
                  </td>
                  
                  <td>
                   <?= $tampilkanreseller['namamitra']; ?> (<?= $tampilkanreseller['idadmin']; ?>) 

                  </td>
                  <td style="text-align:center"><?= $kurang; ?></td>
                  <td>
                     <strong><?= $tampilkanreseller['namacs']; ?></strong>
                </td> 
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanreseller['tgl']; ?>
                      </td>
              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td colspan="2"></td>
                        </tr>
                      </tfoot>
                    </table>
                    
                    </div>
  </div>


  <div id="marketer" class="tab-pane" role="tabpanel">
  <p>Marketer</p>
<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_ambil_barang_marketer">
        <thead>
        <tr>
           <!--  <th>No</th> -->
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Nama DB</th>
                <th>Krg</th>
                <th>Nama CS</th>
                    <th>Tanggal</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;               
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitramarketer.namaagen,
                  mitramarketer.idmitramarketer,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer
                  JOIN admin_mitra on mitramarketer.idadmin = admin_mitra.idadmin 
                  JOIN admin_mitra_cs on mitramarketer.idadmin = admin_mitra_cs.idadmin 
                  WHERE pomitra.idpoproduk = '$namapo' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanmarketer=$datapo->fetch_assoc()){
                  $invoice1 = $tampilkanmarketer['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice1'");
                            $tampilprogres=$datamitra->fetch_assoc();    

                                $kurang = $tampilkanmarketer['qty']-$tampilprogres['progresnya'];                    
                ?>
                <tr>
                <!--  <td>
                     <strong><?= $no++; ?></strong>
                </td>   --> 
                  <td>
                       <a href="ambilbarang_po.php?invoice=<?= $tampilkanmarketer['invoice']; ?>&mitra=M" target="blank()"><?= $tampilkanmarketer['invoice']; ?></a>
                       
                      </td>
                   <td>
                   <?= $tampilkanmarketer['namaagen']; ?> (<?= $tampilkanmarketer['idmitramarketer']; ?>) 
                  </td>
                  <td>
                   <?= $tampilkanmarketer['namamitra']; ?> (<?= $tampilkanmarketer['idadmin']; ?>) 

                  </td>
                  
                  <td style="text-align:center"><?= $kurang; ?></td>
                  <td>
                     <strong><?= $tampilkanmarketer['namacs']; ?></strong>
                </td>
                  <td>
                        <i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanmarketer['tgl']; ?>
                      </td>
              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td colspan="2"></td>
                        </tr>
                      </tfoot>
                    </table>
                    
                    </div>

  </div>

</div>      
                        
                    </div>   
  <?php include "settingdatatables.php"; ?>                         
<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
         "pageLength": 25,
         order: [[3, 'desc']]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_agen').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
         "pageLength": 25,
         order: [[3, 'desc']]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_reseller').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
         "pageLength": 25,
         order: [[3, 'desc']]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_ambil_barang_marketer').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
         "pageLength": 25,
         order: [[3, 'desc']]
});
} );
</script>                    