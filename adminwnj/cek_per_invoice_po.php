<?php 
    include "koneksi.php";
    $iddb           = $_GET['iddb'];
    $result_explode = explode('|', $iddb);
    $idmitra        = $result_explode[0];
    $mitra          = $result_explode[1];
 // echo "$idmitra";
 // echo "<br>";
 // echo "$mitra"; 
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
                <form method="post" action="suratjalan_po_print.php" target="_blank">  
                    <table class="table table-striped" id="tb_multiprint_1">
                        <thead>
                            <tr>       
                                <th><input type='checkbox' id='checkAll' > Check</th>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Nama Produk</th>
                                <th>Ready</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if ($mitra == 'D') {
                                    // Query untuk data tanpa idpoproduk = 335
                                    $queryNo335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitra = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk NOT IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                ";
                            
                                    // Query untuk data dengan idpoproduk = 335, dengan GROUP BY
                                    $queryWith335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitra = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                    GROUP BY surat_jalan_po.custom, pomitra.invoice";
                                }            
                                if ($mitra == 'A') { 
                                    // Query untuk data tanpa idpoproduk = 335
                                    $queryNo335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitraagen AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitraagen = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk NOT IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                ";
                            
                                    // Query untuk data dengan idpoproduk = 335, dengan GROUP BY
                                    $queryWith335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitraagen AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitraagen = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                    GROUP BY pomitra.custom, pomitra.invoice";
                                }
                                if ($mitra == 'R') { 
                                    // Query untuk data tanpa idpoproduk = 335
                                    $queryNo335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitrareseller AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitrareseller = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk NOT IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                ";
                            
                                    // Query untuk data dengan idpoproduk = 335, dengan GROUP BY
                                    $queryWith335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitrareseller AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitrareseller = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                    GROUP BY pomitra.custom, pomitra.invoice";
                                }
                                if ($mitra == 'M') { 
                                    // Query untuk data tanpa idpoproduk = 335
                                    $queryNo335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitramarketer AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitramarketer = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk NOT IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                ";
                            
                                    // Query untuk data dengan idpoproduk = 335, dengan GROUP BY
                                    $queryWith335 = "SELECT surat_jalan_po.invoice,                                                                                      
                                                            surat_jalan_po.progres,
                                                            surat_jalan_po.status,
                                                            surat_jalan_po.waktu,
                                                            surat_jalan_po.id_sj,
                                                            surat_jalan_po.idpomitra,
                                                            pomitra.idmitramarketer AS idmitra,
                                                            pomitra.custom,
                                                            podetail.variant,
                                                            pomitra.idpoproduk
                                                    FROM surat_jalan_po
                                                    INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                    INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                    WHERE pomitra.idmitramarketer = '$idmitra' 
                                                    AND surat_jalan_po.progres > 0 
                                                    AND surat_jalan_po.status = 'Ambil Barang'
                                                    AND pomitra.idpoproduk IN (335, 339)
                                                    AND surat_jalan_po.waktu > '2025-12-30 23:59:59'
                                                    GROUP BY pomitra.custom, pomitra.invoice
                                                ";
                                }
                            
                                // Eksekusi query tanpa idpoproduk = 335
                                $datapo = $koneksi->query($queryNo335);
                            
                                // Eksekusi query dengan idpoproduk = 335 (GROUP BY)
                                $datapo335 = $koneksi->query($queryWith335);
                                $no = 1;
                                while($tampilkan = $datapo335->fetch_assoc()){
                                    $id         = $tampilkan['id_sj'];
                                    $invoice    = $tampilkan['invoice'];
                                    $custom     = $tampilkan['custom'];
                                    $string     = $custom;
                                    // Mengubah semua huruf menjadi huruf kecil
                                    $string     = strtolower($string);
                                    // Mengganti spasi dengan tanda hubung
                                    $string     = str_replace(' ', '-', $string);
                                    // Menghilangkan tanda - di awal string
                                    $string     = ltrim($string, '-');
                                    // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                    $string     = preg_replace('/[^a-z0-9\-]/', '', $string);

                                    $idsQuery   = $koneksi->query("SELECT GROUP_CONCAT(id_sj) AS ids FROM surat_jalan_po WHERE invoice = '$invoice' AND custom = '$custom'");
                                    $idsResult  = $idsQuery->fetch_assoc();
                                    $all_ids    = $idsResult['ids'];
                            ?>
                                    <tr>
                                <td><input type='checkbox' name='update[]' value='<?= $all_ids ?>' >
                                    <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                                    <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                                    <input type='hidden' name='idorder<?= $id ?>' value='<?= $tampilkan['idorder']; ?>' >
                                    <input type='hidden' name='idadmin' value='<?= $tampilkan['idmitra']; ?>' >
                                    <input type='hidden' name='mitra' value='<?= $mitra; ?>' >
                                </td>
                                <td><?= $no++; ?></td>     
                                <td><?= $tampilkan['invoice']; ?></td>
                                <td>
                                <?php
                                    $query = $koneksi->query("SELECT 
                                                                    podetail.*, pomitra.*
                                                                FROM
                                                                    pomitra
                                                                        INNER JOIN
                                                                    podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                WHERE
                                                                    pomitra.invoice = '$invoice'
                                                                        AND pomitra.jumlah > 0
                                                                        AND pomitra.custom = '$custom'
                                                            ");
                                    $first = true;
                                    while ($data_produk = $query->fetch_assoc()) {
                                        if (!$first) {
                                            echo " - ";
                                        }
                                        $first = false;
                                ?>
                                    <?= $data_produk['variant'] ?>
                                <?php 
                                    }
                                ?>
                                </td>
                                <td>
                                    <?= $tampilkan['progres']; ?> 
                                </td>
                                <td>
                                    <?= $tampilkan['status']; ?> 
                                </td>
                                <td>
                                    <?= $tampilkan['waktu']; ?> 
                                </td>
                            </tr>
                                <?}?>
                                <?php
                                while($tampilkan = $datapo->fetch_assoc()){
                                    $id = $tampilkan['id_sj'];
                            ?>
                            <tr>
                                <td><input type='checkbox' name='update[]' value='<?= $id ?>' >
                                    <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                                    <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                                    <input type='hidden' name='idorder<?= $id ?>' value='<?= $tampilkan['idorder']; ?>' >
                                    <input type='hidden' name='idadmin' value='<?= $tampilkan['idmitra']; ?>' >
                                    <input type='hidden' name='mitra' value='<?= $mitra; ?>' >
                                    <input type="hidden" name="idpoproduk<?= $id ?>" value="<?= $tampilkan['idpoproduk']; ?>">
                                </td>
                                <td><?= $no++; ?></td>     
                                <td><?= $tampilkan['invoice']; ?></td>
                                <td>
                                    <?= $tampilkan['variant']; ?>
                                    <?php if ($tampilkan['custom']): ?>
                                    ( <?= $tampilkan['custom']; ?> )
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?= $tampilkan['progres']; ?> 
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
                    <input type='submit' class="btn btn-primary" value='Print Data' name='but_export'>
                </form>        
            </div>

</div>

<div id="menu2" class="tab-pane fade">
 <div class="table-responsive">
<!--  <center><a class="btn btn-info" href="listpoinvoicemegameli.php">SUMMARY PO MEGA & MELI</a></center><br> -->
<form method="post" action="suratjalan_po_status.php" >  
<table class="table table-striped" id="tb_multiprint" target="_blank">
                     <thead>
          <tr>       
           <th><input type='checkbox' id='checkAll_status' > Check</th>
            <th>No</th>
            <th>Print</th>
            <th>No Surat Jalan</th>
            <th>Invoce</th>
            
            
            <th>Ready</th>
            <th>Status</th>
            <th>Waktu</th>
            </tr>
            </thead>
            <tbody>
            <?php 
if ($mitra=='D') {
            $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,                                                                                      
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            surat_jalan_po.idpomitra,

                                            SUM(surat_jalan_po.progres) as jumlahnya,

                                            pomitra.idmitra,
                                            podetail.variant

                                            FROM surat_jalan_po
                                            INNER JOIN pomitra ON pomitra.idpomitra=surat_jalan_po.idpomitra
                                            INNER JOIN podetail ON podetail.idpodetail=surat_jalan_po.idpodetail

                                            WHERE pomitra.idmitra = '$idmitra' AND surat_jalan_po.progres>0 AND surat_jalan_po.status = 'Checker'
                                            GROUP BY surat_jalan_po.no_sj ORDER BY surat_jalan_po.waktu desc
                                    ");
}
if ($mitra=='A') {
            $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,                                                                                      
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            surat_jalan_po.idpomitra,

                                            SUM(surat_jalan_po.progres) as jumlahnya,

                                            pomitra.idmitraagen as idmitra,
                                            podetail.variant

                                            FROM surat_jalan_po
                                            INNER JOIN pomitra ON pomitra.idpomitra=surat_jalan_po.idpomitra
                                            INNER JOIN podetail ON podetail.idpodetail=surat_jalan_po.idpodetail

                                            WHERE pomitra.idmitraagen = '$idmitra' AND surat_jalan_po.progres>0 AND surat_jalan_po.status = 'Checker'
                                            GROUP BY surat_jalan_po.no_sj ORDER BY surat_jalan_po.waktu desc
                                    ");  

}
if ($mitra=='R') {
            $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,                                                                                      
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            surat_jalan_po.idpomitra,

                                            SUM(surat_jalan_po.progres) as jumlahnya,

                                            pomitra.idmitrareseller as idmitra,
                                            podetail.variant

                                            FROM surat_jalan_po
                                            INNER JOIN pomitra ON pomitra.idpomitra=surat_jalan_po.idpomitra
                                            INNER JOIN podetail ON podetail.idpodetail=surat_jalan_po.idpodetail

                                            WHERE pomitra.idmitrareseller = '$idmitra' AND surat_jalan_po.progres>0 AND surat_jalan_po.status = 'Checker'
                                            GROUP BY surat_jalan_po.no_sj ORDER BY surat_jalan_po.waktu desc
                                    ");    

}
if ($mitra=='M') {
            $datapo=$koneksi->query("SELECT surat_jalan_po.invoice,                                                                                      
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            surat_jalan_po.id_sj,
                                            surat_jalan_po.no_sj,
                                            surat_jalan_po.idpomitra,

                                            SUM(surat_jalan_po.progres) as jumlahnya,

                                            pomitra.idmitramarketer as idmitra,
                                            podetail.variant

                                            FROM surat_jalan_po
                                            INNER JOIN pomitra ON pomitra.idpomitra=surat_jalan_po.idpomitra
                                            INNER JOIN podetail ON podetail.idpodetail=surat_jalan_po.idpodetail

                                            WHERE pomitra.idmitramarketer = '$idmitra' AND surat_jalan_po.progres>0 AND surat_jalan_po.status = 'Checker'
                                            GROUP BY surat_jalan_po.no_sj ORDER BY surat_jalan_po.waktu desc
                                    ");    

}

                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                                $id         = $tampilkan['id_sj'];
                                $no_sj      = $tampilkan['no_sj'];
                                $idpoproduk = $tampilkan['idpoproduk'];
            ?>
             <tr>
                         <td><input type='checkbox' name='update_status[]' value='<?= $id ?>' >
                            <input type='hidden' name='invoice<?= $id ?>' value='<?= $tampilkan['invoice']; ?>' >
                            <input type='hidden' name='no_sj<?= $id ?>' value='<?= $tampilkan['no_sj']; ?>' >
                            <input type='hidden' name='id_sj<?= $id ?>' value='<?= $tampilkan['id_sj']; ?>' >
                            <input type='hidden' name='idadmin' value='<?= $tampilkan['idmitra']; ?>' >
                         </td>
                         <td>
                             <?= $no++; ?>
                        </td>
                        <td>
                            <?php if ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374) : ?>
                                <a href="suratjalan_po_print_checker3.php?no_sj=<?= $tampilkan['no_sj'] ?>&idadmin=<?= $tampilkan['idmitra'] ?>&mitra=<?= $mitra; ?>" target="_blank"><i class="fa fa-print"></i> </a>
                            <?php else : ?>
                                <a href="suratjalan_po_print_checker.php?no_sj=<?= $tampilkan['no_sj'] ?>&idadmin=<?= $tampilkan['idmitra'] ?>&mitra=<?= $mitra; ?>" target="_blank"><i class="fa fa-print"></i> </a>
                            <?php endif; ?>
                        </td>  
                        <td>
                            <a href="detail_sj_po.php?no_sj=<?= $tampilkan['no_sj'] ?>" target="_blank">
                                <?= $tampilkan['no_sj']; ?>
                            </a>
                          </td>   
                          <td>
            <?php 
            $data_invoice_sj=$koneksi->query("SELECT surat_jalan_po.invoice
                                            
                                            FROM surat_jalan_po
                                            
                                            WHERE surat_jalan_po.no_sj = '$no_sj'
                                            GROUP BY surat_jalan_po.invoice

                                    ");
                            while($tampilkan_invoice_sj=$data_invoice_sj->fetch_assoc()){?>
                              <a href="detail_progres_po.php?invoice=<?= $tampilkan_invoice_sj['invoice'] ?>" target="_blank"> 
                                    <?= $tampilkan_invoice_sj['invoice'] ?>
                                </a>
                                <br>
                            <?php } ?> 
                            
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
