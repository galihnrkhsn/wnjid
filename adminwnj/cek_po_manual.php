<?php 
  include "koneksi.php";
  
	 $id=$_GET["idpoproduk"];
	 $result_explode = explode('|', $id);
    $idpoproduk=$result_explode[1];
     $namapo=$result_explode[2];
     $status=$result_explode[0];     

 echo "<h4>Surat Jalan $namapo</h4>";
// echo "$status";
 ?>




<div class="table-responsive">
    
    <table class="table table-bordered" id="tb_surat_manual">
        <thead>
        <tr>
            <th>No</th>
<?php if ($status=="IPO"): ?>
          <th>Invoice</th>  
<?php else: ?>                      
          <th>No Surat Jalan</th>
            <?php endif ?>            
          <th>Nama Mitra</th>
            <th>Waktu</th>
            <th>Opsi</th>
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT surat_jalan_manual.no_sj, 
                  surat_jalan_manual.invoice, 
                  admin_mitra.namamitra, 
                  surat_jalan_manual.waktu 
                  FROM surat_jalan_manual 
                  JOIN podetail on podetail.idpodetail = surat_jalan_manual.idproduk
                  JOIN pokategori on pokategori.idpo = podetail.idpo
                  JOIN poproduk on poproduk.idpoproduk = pokategori.idpoproduk
                  JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin
                  WHERE surat_jalan_manual.status = '$status'
                  AND poproduk.idpoproduk = '$idpoproduk'
                  GROUP BY surat_jalan_manual.no_sj
                  order by surat_jalan_manual.id_sj desc
                  ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                ?>
                <tr>
                <td>
                  <?= $no++; ?>
                </td>
                <td>
<?php if ($status=="IPO"): ?>
                  <a href="detail_surat_manual.php?no_sj=<?= $tampilkan['no_sj'] ?>"><?= $tampilkan['invoice'] ?></a> 
<?php else: ?>                      
                  <a href="detail_surat_manual.php?no_sj=<?= $tampilkan['no_sj'] ?>"><?= $tampilkan['no_sj'] ?></a>
            <?php endif ?>                    

                </td>
                <td>
                  <?= $tampilkan['namamitra']; ?>
                </td>
                <td>
                  <?= $tampilkan['waktu'] ?>
                </td>
                <td>
                  <form method="post">
                  <input type="hidden" name="no_sj" value="<?= $tampilkan['no_sj'] ?>">
                  <button type="submit" class="btn btn-danger" name="hapus" onclick="return confirm('Yakin Akan Menghapus Surat Jalan?');">Hapus</button>
                  </form>
                </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
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
    $('#tb_surat_manual').DataTable({
        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
});
} );
</script>