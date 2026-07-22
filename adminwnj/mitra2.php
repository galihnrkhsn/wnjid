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
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    
    <style type="text/css">
        body{
            padding-right: 0px ! important;
        }    
            .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        }

        .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
        }

        .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        }

        .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        }

        input:checked + .slider {
        background-color: #2196F3;
        }

        input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
        border-radius: 34px;
        }

        .slider.round:before {
        border-radius: 50%;
        }
    </style>

</head>

<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><strong>Data Distributor</strong></h1>
            </div>

            <?php 
                $tampiling      = $koneksi->query("SELECT COUNT(*) as jmlh FROM admin_mitra WHERE status=0 and kota<>''");
                $tampiljmlh     = $tampiling->fetch_assoc();
            ?>
            <?php 

                $tampiling2     = $koneksi->query("SELECT COUNT(*) as jmlh2 FROM admin_mitra WHERE (kota='' or kota is NULL) and (status=0 or status=1) ");
                $tampiljmlh2    = $tampiling2->fetch_assoc();
            ?>

            <p align="left">
                <div class="badge bg-info text-white rounded-pill">
                    B Status Tampil : <?php echo $tampiljmlh['jmlh']; ?> Akun
                </div>
                |
                <div class="badge bg-danger text-white rounded-pill">
                    DB Status non-Tampil : <?php echo $tampiljmlh2['jmlh2']; ?> Akun
                </div>
            </p>
           
            <!-- Content Row -->
            <div class="row">
             
            <div class="row container">
                <a class="btn btn-primary" href="input_mitra.php">
                    <span class="fas fa-plus"></span> Tambah Distributor
                </a>

                <a class="btn btn-success mx-2" href="excel_mitra.php">
                    <span class="fas fa-print"></span> Export Excel
                </a>
            </div>

            <div class="table-responsive" style="margin-top: 3%">
                <form method="post">  
                    <div class="form-group col-4">
                        <label>Nama CS</label>
                        <select class="form-control" name="namacs" id="namacs">
                            <option enabled selected>- Pilih Nama CS -</option>
                            <?php
                                $datadb = $koneksi->query("SELECT * FROM admin_mitra_cs WHERE namacs IS NOT NULL GROUP BY namacs ORDER BY namacs asc");
                                while ($tampilkan = $datadb->fetch_assoc()) {
                                    ?>
                                    <?php if ($tampilkan['namacs'] == ""): ?>
                                        <option value="Semua CS">Semua CS</option>
                                    <?php else: ?>
                                        <option value="<?= $tampilkan['namacs']; ?>"><?= $tampilkan['namacs']; ?></option>
                                    <?php endif ?>
                            <?php } ?>
                        </select>   
                        <button class="btn btn-primary btn-sm" type="submit" name="cari">Cari</button>   
                    </div>
                </form>
                <table class="table table-striped table-bordered table-hover" id="tbmitra">
                    <thead>
                        <tr>
                            <th>Nama Mitra</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Whatsapp</th>
                            <th>Kota</th>
                            <th>Nama CS</th>
                            <th>Status</th>
                            <th>Private Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
                <?php
                    if(isset($_POST["hapus"])){
                        $idadmin    = $_POST['id'];
                        $koneksi->query("delete from admin_mitra where idadmin='$idadmin'");

                        echo "<script>alert('data sudah terhapus');</script>";
                        echo "<script>location='mitra.php';</script>";
                    }   

                    if(isset($_POST["gantistatus"])){    
                        $idadmin        = $_POST['id'];
                        $nilaistatus    = $_POST['nilaistatus'];

                        if($nilaistatus == "1"){
                            $koneksi->query("UPDATE admin_mitra SET status=0 where idadmin='$idadmin';");
                            echo "<script>alert('Status telah tampil');</script>";
                            echo "<script>location='mitra.php';</script>";
                        } else {
                            $koneksi->query("UPDATE admin_mitra SET status=1 where idadmin='$idadmin';");
                            echo "<script>alert('Status tidak tampil');</script>";
                            echo "<script>location='mitra.php';</script>";
                        }
                    }

                    if(isset($_POST["gantiprivate"])){    
                        $idadmin        = $_POST['id'];
                        $nilaiprivate   = $_POST['nilaiprivate'];

                        if($nilaiprivate == "off"){
                            $koneksi->query("UPDATE admin_mitra SET privateorder='on' where idadmin='$idadmin';");
                            echo "<script>alert('Private Order telah diaktifkan');</script>";
                            echo "<script>location='mitra.php';</script>";
                        }
                        else{
                            $koneksi->query("UPDATE admin_mitra SET privateorder='off' where idadmin='$idadmin';");
                            echo "<script>alert('Private Order telah dimatikan');</script>";
                            echo "<script>location='mitra.php';</script>";
                        }
                    }

                    if (isset($_POST["resetPassword"])) {
                        $idadmin    = $_POST['id'];
                        $queryDb    = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");                        
                        if ($queryDb && $queryDb->num_rows > 0) {
                            $dataDb = $queryDb->fetch_assoc();
                            $email  = $dataDb['email'];
                            $idUser = $dataDb['iduser'];       
                            // Pastikan variabel $email tidak kosong
                            if ($email) {
                                $newPassword = $email . '_' . $idadmin;
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                $updatePassword = $koneksi->query("UPDATE users SET password = '$hashedPassword' WHERE id = '$idUser'");
                                
                                if ($updatePassword) {
                                    echo "<script>alert('Password berhasil diubah');</script>";
                                    echo "<script>location='mitra.php';</script>";
                                } else {
                                    echo "<script>alert('Gagal mengubah password');</script>";
                                }
                            } else {
                                echo "<script>alert('Email tidak ditemukan');</script>";
                            }
                        } else {
                            echo "<script>alert('Data admin tidak ditemukan');</script>";
                        }
                    }           
                ?>

                <script type="text/javascript">
                    $(document).ready(function(){

                        $("#gantiprivate").click(function(){
                            var data = $('#form-private').serialize();
                            $.ajax({
                                type  : 'POST',
                                url : "aksi.php",
                                data: data,

                                cache : false,
                                success : function(data){
                                    alert('Private Order telah di Ubah');
                                }
                            });
                        });
                    });
                </script> 

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
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tbmitra').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'https://wnj.id/api/get_mitra.php',
                    type: 'POST'
                },
                columns: [
                    { data: 'namamitra' },
                    { data: 'email' },
                    { data: 'alamat' },
                    { data: 'whatsapp' },
                    { data: 'city_name' },
                    { data: 'namacs' },
                    {
                        data: 'status',
                        render: function (data, type, row) {
                            let checked = data == "1" ? "checked" : "";
                            return `
                                <label class="switch">
                                <input type="checkbox" class="toggle-status" data-id="${row.idadmin}" ${checked}>
                                <span class="slider round"></span>
                                </label>`;
                        }
                    },
                    {
                        data: 'privateorder',
                        render: function (data, type, row) {
                            let checked = data == "on" ? "checked" : "";
                            return `
                                <label class="switch">
                                <input type="checkbox" class="toggle-private" data-id="${row.idadmin}" ${checked}>
                                <span class="slider round"></span>
                                </label>`;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary edit" data-id="${row.idadmin}"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-sm btn-warning reset-password" data-id="${row.idadmin}"><i class="fas fa-power-off"></i></button>
                                <button class="btn btn-sm btn-danger delete" data-id="${row.idadmin}"><i class="fa fa-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>

</body>

</html>

		                    