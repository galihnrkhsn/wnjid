<?php 
    session_start();
    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">
                        <a href="input_bukapo.php" class="btn btn-primary text-white" name="" style="margin-bottom: 1%;"><span class="fas fa-plus" ></span> Tambah Data Buka PO</a>
                        <h3><strong>Daftar Buka PO</strong></h3>
                        <div class="table-responsive">
                            <form method="post">
                                <table class="table table-striped" id="tb_bukapo">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>
                                                All
                                                <br>
                                                <input type="checkbox" id='checkAll'/>
                                            </th>
                                            <th>Nama PO / Status PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Jenis Mitra</th>
                                            <th>Jenis PO</th>
                                            <th>Uji</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no         = 1;  
                                            $dataproduk = $koneksi->query("SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po, 
                                                                                bukapo.idpoproduk, bukapo.tgl, bukapo.tgl_acc_db, bukapo.tgl_ubah,
                                                                                bukapo.tgl_bayar, bukapo.tgl_dropship, bukapo.status,
                                                                                poproduk.namapo 
                                                                            FROM bukapo 
                                                                            INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                                                                            ORDER BY bukapo.idbpo DESC");
                                            while($tampilkan = $dataproduk->fetch_assoc()){
                                                $id = $tampilkan['idbpo'];
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>  
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" id="idpoproduk<?= $id ?>" name="idpoproduk<?= $id ?>" required style="width: 300px;">
                                                        <option value="<?php echo $tampilkan['idpoproduk']; ?>" selected><?php echo $tampilkan['idpoproduk']; ?> | <?php echo $tampilkan['namapo']; ?></option>
                                                        <?php
                                                            $ambil = $koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk desc");
                                                            while($row = $ambil->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo $row['idpoproduk']; ?>"><?php echo $row['idpoproduk']; ?> | <?php echo $row['namapo']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <br>
                                                <?php if ($tampilkan['status']=="PUBLISH"): ?>
                                                    <div class="badge bg-success text-white rounded-pill">
                                                        <?php echo $tampilkan['status']; ?>
                                                    </div>
                                                <?php elseif ($tampilkan['status']=="UNPUBLISH"): ?>
                                                    <div class="badge bg-danger text-white rounded-pill">
                                                        <?php echo $tampilkan['status']; ?>
                                                    </div>
                                                <?php endif ?>                            
                                            </td>
                                            <td>
                                                <label>Tanggal Tutup PO</label>
                                                <input type="date" name="tgl<?= $id ?>" class="form-control" required value="<?php echo $tampilkan['tgl']; ?>">
                                                <br>
                                                <label>Tanggal Acc DB PO</label>
                                                <input type="date" name="tgl_acc_db<?= $id ?>" class="form-control" required value="<?php echo $tampilkan['tgl_acc_db']; ?>">
                                                <br>
                                                <label>Tanggal Ubah PO</label>
                                                <input type="date" name="tgl_ubah<?= $id ?>" class="form-control" required value="<?php echo $tampilkan['tgl_ubah']; ?>">
                                                <br>                            
                                                <label>Tanggal Bayar PO</label>
                                                <input type="date" name="tgl_bayar<?= $id ?>" class="form-control" required value="<?php echo $tampilkan['tgl_bayar']; ?>">
                                                <br>
                                                <label>Tanggal Dropship PO</label>
                                                <input type="date" name="tgl_dropship<?= $id ?>" class="form-control" required value="<?php echo $tampilkan['tgl_dropship']; ?>">
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" id="jenis_mitra<?= $id ?>" name="jenis_mitra<?= $id ?>" required style="width: 200px;">
                                                        <option value="<?php echo $tampilkan['jenis_mitra']; ?>" selected><?php echo $tampilkan['jenis_mitra']; ?></option>
                                                        <option value="Semua Mitra">Semua Mitra</option>
                                                        <option value="Distributor">Distributor</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" id="jenis_po<?= $id ?>" name="jenis_po<?= $id ?>" required style="width: 200px;">
                                                        <option value="<?php echo $tampilkan['jenis_po']; ?>" selected><?php echo $tampilkan['jenis_po']; ?></option>
                                                        <option value="PO dengan Stok">PO dengan Stok</option>
                                                        <option value="PO tanpa Stok">PO tanpa Stok</option>
                                                        <option value="PO Miki Custom">PO Miki Custom</option>
                                                        <option value="PO Miki Polos">PO Miki Polos</option>
                                                        <option value="PO Brooch Custom">PO Brooch Custom</option>
                                                        <option value="PO Bagi Rata">PO Bagi Rata</option>
                                                        <option value="PO Kolibri">PO Kolibri</option>
                                                        <option value="PO Hampers">PO Hampers</option>
                                                        <option value="PO Karakter Stok">PO Karakter Stok</option>
                                                        <option value="PO Mandiri">PO Mandiri</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
                                                    <a href="https://wnj.id/distributor/formpostok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>
                                                <?php elseif ($tampilkan['jenis_po']=="PO tanpa Stok" && $tampilkan['idpoproduk'] != 331): ?>
                                                    <a href="https://wnj.id/distributor/formpoku.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Bundling 2"): ?>
                                                    <a href="https://wnj.id/distributor/formpobundling2.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
                                                    <a href="https://wnj.id/distributor/formpo_tab.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['jenis_po']=="PO Custom Tab Stok"): ?>
                                                    <a href="https://wnj.id/distributor/formpo_tabstok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>          
                                                <?php elseif ($tampilkan['jenis_po']=="PO Brooch Custom"): ?>
                                                    <a href="https://wnj.id/distributor/formpobrooch_custom.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>
                                                <?php elseif ($tampilkan['jenis_po']=="PO Custom"): ?>
                                                    <a href="https://wnj.id/distributor/formpomikicustom.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Miki Custom"): ?>
                                                    <a href="https://wnj.id/distributor/formpomikicustomstock.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Miki Polos"): ?>
                                                    <a href="https://wnj.id/distributor/formpomikipolosstock.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>         
                                                <?php elseif ($tampilkan['jenis_po']=="PO Bagi Rata"): ?>
                                                    <a href="https://wnj.id/distributor/formbagirata.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['jenis_po']=="PO Kolibri"): ?>
                                                    <a href="https://wnj.id/distributor/pokolibri.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Konin"): ?>
                                                    <a href="https://wnj.id/distributor/pokonin.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Bundling Custom"): ?>
                                                    <a href="https://wnj.id/distributor/formpobundling.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Inner Custom"): ?>
                                                    <a href="https://wnj.id/distributor/formpoinner2.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Hampers"): ?>
                                                    <a  href="https://wnj.id/distributor/formpo_thr.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['jenis_po']=="PO Karakter Stok"): ?>
                                                    <a  href="https://wnj.id/distributor/formpo_karakterstok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['jenis_po']=="PO Bundling 5"): ?>
                                                    <a  href="https://wnj.id/distributor/formpobundling5.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['jenis_po']=="PO Mandiri"): ?>
                                                    <a  href="https://wnj.id/distributor/formpo_mandiri.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==234 or $tampilkan['idpoproduk']==245 or $tampilkan['idpoproduk'] == 251): ?>
                                                    <a  href="https://wnj.id/distributor/formpoinner_custom.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>
                                                <?php elseif ($tampilkan['idpoproduk']==235 or $tampilkan['idpoproduk']==243): ?>
                                                    <a  href="https://wnj.id/distributor/formporocela.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==261): ?>
                                                    <a  href="https://wnj.id/distributor/formpocustomlegging.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==260): ?>
                                                    <a  href="https://wnj.id/distributor/formpoleggingpolos.php?id=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==236 or $tampilkan['idpoproduk']==244): ?>
                                                    <a  href="https://wnj.id/distributor/formpogoura.php?=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==237 or $tampilkan['idpoproduk']==240): ?>
                                                    <a  href="https://wnj.id/distributor/formpocustomgabungan.php?=<?php echo $tampilkan['idpoproduk']; ?>&idadmin=<?= $tampilkan['idadmin'] ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['idpoproduk']==269): ?>
                                                    <a  href="https://wnj.id/distributor/formpomatari.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>  
                                                <?php elseif ($tampilkan['idpoproduk']==331): ?>
                                                    <a  href="https://wnj.id/distributor/pocustom.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Link <?php echo $tampilkan['namapo']; ?></a>
                                                <?php endif ?>
                                                <!-- <br>  
                                                <br>  
                                                <?php if ($tampilkan['jenis_po']=="PO dengan Stok"): ?>
                                                    <a  href="https://wnj.id/distributor/ubahpostok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                                <?php elseif ($tampilkan['jenis_po']=="PO tanpa Stok"): ?>
                                                    <a  href="https://wnj.id/distributor/ubahpo.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Ubah <?php echo $tampilkan['namapo']; ?></a> 
                                                <?php elseif ($tampilkan['jenis_po']=="PO Custom Tab"): ?>
                                                    <a  href="https://wnj.id/distributor/ubahpo.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Ubah <?php echo $tampilkan['namapo']; ?></a>    
                                                <?php elseif ($tampilkan['jenis_po']=="PO Custom Tab Stok"): ?>
                                                    <a  href="https://wnj.id/distributor/ubahpostok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Ubah <?php echo $tampilkan['namapo']; ?></a>      
                                                <?php elseif ($tampilkan['jenis_po']=="PO Legging Custom"): ?>
                                                    <a  href="https://wnj.id/distributor/ubahpostok.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target="_blank()">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                                <?php endif ?> -->
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success" name="ubahdata" onclick="return confirm('Yakin Akan Mengubah Data?');"><span class="fa fa-edit" ></span> Ubah Data</button>
                                &nbsp;
                                <button type="submit" class="btn btn-warning" name="publishpo" onclick="return confirm('Yakin Akan Publish PO?');"><i class="fas fa-cloud-upload-alt"></i> Publish</button>
                                &nbsp;
                                <button type="submit" class="btn btn-info" name="unpublishpo" onclick="return confirm('Yakin Akan Unpublish PO?');"><i class="fas fa-cloud-download-alt"></i> Unpublish</button>

                                &nbsp;
                                <button type="submit" class="btn btn-danger" name="hapuspo" onclick="return confirm('Yakin Akan Hapus Data Buka PO?');"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                            <br>
                            <br>                    
                        </div>
                    </div>
                </div>

                <?php 
                    if(isset($_POST['ubahdata'])){

                        if(isset($_POST['update'])){
                            foreach($_POST['update'] as $updateid){
                                $idpoproduk   = $_POST['idpoproduk'.$updateid];
                                $tgl          = $_POST['tgl'.$updateid];
                                $tgl_acc_db   = $_POST['tgl_acc_db'.$updateid];
                                $tgl_ubah     = $_POST['tgl_ubah'.$updateid];
                                $tgl_bayar    = $_POST['tgl_bayar'.$updateid];
                                $tgl_dropship = $_POST['tgl_dropship'.$updateid];
                                $jenis_mitra  = $_POST['jenis_mitra'.$updateid];
                                $jenis_po     = $_POST['jenis_po'.$updateid];

                                $sqlnya = $koneksi->query("UPDATE bukapo set idpoproduk='$idpoproduk', 
                                tgl           = '$tgl',tgl_acc_db='$tgl_acc_db',
                                tgl_ubah      = '$tgl_ubah',
                                tgl_bayar     = '$tgl_bayar',
                                tgl_dropship  = '$tgl_dropship',
                                jenis_mitra   = '$jenis_mitra', 
                                jenis_po      = '$jenis_po' 
                                where idbpo   = '$updateid'");
                                
                                
                            }
                            if ($sqlnya) {
                            echo "<script>alert('data berhasil diubah');</script>";
                            echo "<script>location='data_bukapo.php';</script>";  
                            }else{
                                echo "<script>alert('data gagal diubah');</script>";
                                echo "<script>location='data_bukapo.php';</script>";
                            }
                        
                        }
                        
                    }  

                    if(isset($_POST['publishpo'])){

                        if(isset($_POST['update'])){
                            foreach($_POST['update'] as $updateid){ 
                                $sqlnya = $koneksi->query("UPDATE bukapo set status='PUBLISH' where idbpo='$updateid'");
                            }
                            if ($sqlnya) {
                            echo "<script>alert('data berhasil diubah');</script>";
                            echo "<script>location='data_bukapo.php';</script>";  
                            }else{
                                echo "<script>alert('data gagal diubah');</script>";
                                echo "<script>location='data_bukapo.php';</script>";
                            }
                        
                        }
                        
                    } 

                    if(isset($_POST['unpublishpo'])){

                        if(isset($_POST['update'])){
                            foreach($_POST['update'] as $updateid){

                                $sqlnya = $koneksi->query("UPDATE bukapo set status='UNPUBLISH' where idbpo='$updateid'");
                                
                                
                            }
                            if ($sqlnya) {
                            echo "<script>alert('data berhasil diubah');</script>";
                            echo "<script>location='data_bukapo.php';</script>";  
                            }else{
                                echo "<script>alert('data gagal diubah');</script>";
                                echo "<script>location='data_bukapo.php';</script>";
                            }
                        
                        }
                        
                    }  

                            if(isset($_POST['hapuspo'])){

                        if(isset($_POST['update'])){
                            foreach($_POST['update'] as $updateid){
                                $sqlnya = $koneksi->query("DELETE FROM bukapo where idbpo='$updateid'");
                                
                                
                            }
                            if ($sqlnya) {
                            echo "<script>alert('data berhasil dihapus');</script>";
                            echo "<script>location='data_bukapo.php';</script>";  
                            }else{
                                echo "<script>alert('data gagal dihapus');</script>";
                                echo "<script>location='data_bukapo.php';</script>";
                            }
                        
                        }
                        
                    }                              
                ?>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2020</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
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
    <?php include "settingdatatables.php"; ?>
</body>
</html>                 