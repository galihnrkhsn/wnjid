<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];
    $idpoproduk     = $_GET['id'];
?>

<?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- Page Heading -->
    <div class="container-fluid mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="m-0 font-weight-bold text-secondary">
                Pre Order
                <a href="index.php" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
            </h2>
        </div>
        <!-- Content Row -->
        <div class="row">                                                                                  
            <div class="table-responsive">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Per Nama PO</a></li>
                    <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Per Bulan</a></li>
                    <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Per Hari</a></li>
                </ul>
        
                <div class="tab-content">
                    <div id="home" class="tab-pane fade show active bg-white" role="tabpanel"> 
                        <div class="form-group">
                                <label class="mt-4">Nama PO</label>
                                <select class="form-control" name="namapo" id="namapo">
                                    <option enabled selected>- Pilih Nama PO -</option>
                                    <?php
                                    $datadb=$koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC");
                                    while($tampilkan=$datadb->fetch_assoc()){
                                    ?>
                                <option value="<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></option>
                                <?php } ?>  
                                </select>      
                        </div> 
                        <div class="form-group"  id="tabel_dp" name="tabel_dp"></div>          
                    </div>
                    <div id="menu1" class="tab-pane fade bg-white">
                        <div class="form-group">
                            <label class="mt-4">Bulan</label>
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
                    </div>
                    <div id="menu2" class="tab-pane fade bg-white">
                        <div class="form-group">
                            <label class="mt-4">Harian</label>
                            <input type="date" class="form-control" name="hari" id="hari">
                                            
                        </div> 
                        <div id="tabel_hari" name="tabel_hari"></div>             
                    </div>          
                </div>
            </div>

        </div><!-- Content Row -->
    </div>
    <?php include 'assets/components/Footer/footer.php'; ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('#namapo').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var namapo = $('#namapo').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_po.php',
                    data :  'namapo=' + namapo,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tabel_dp").html(data);
                    }
                    
                });
            });
            
        });
    </script> 

    <script type="text/javascript">

        $(document).ready(function(){



            $('#tanggal').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var tanggal = $('#tanggal').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_po_bulan.php',
                    data :  'tanggal=' + tanggal,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tabel_filter").html(data);
                    }
                    
                });
            });  


            
        });
    </script>  

    <script type="text/javascript">

        $(document).ready(function(){



            $('#hari').change(function(){

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var hari = $('#hari').val();
                
                $.ajax({
                    type : 'GET',
                    url : 'cek_po_hari.php',
                    data :  'hari=' + hari,
                        success: function (data) {

                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#tabel_hari").html(data);
                    }
                    
                });
            });  


            
        });
    </script>