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

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

 <?php include "sidebar.php"; ?>
 
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Surat Jalan</h1>
            <h1 class="h3 mb-0 text-gray-800"><a href="suratjalan.php"><i class="fa fa-arrow-left"></i> Kembali</a></h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#menu1" class="nav-item nav-link active">Surat Jalan Checker</a></li>
          <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Surat Jalan Proses</a></li>
        </ul>

    <div class="tab-content">
                    
<div id="menu1" class="tab-pane fade show active">    
<?php include "sj_checker_rs.php"; ?>
</div>
<div id="menu2" class="tab-pane fade">    
<?php include "sj_proses_rs.php"; ?>
</div>
   </div>



          </div>


        <!-- </div> -->
                                
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

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>



<script type="text/javascript">

    $(document).ready(function(){

        $('#jenis_mitra').change(function(){

            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var jenis_mitra = $('#jenis_mitra').val();
            
            $.ajax({
                type : 'GET',
                url : 'cek_per_jenismitra.php',
                data :  'jenis_mitra=' + jenis_mitra,
                    success: function (data) {

                    //jika data berhasil didapatkan, agen_tampilkan ke dalam option select kabupaten
                    $("#tabel_suratjalan").html(data);
                }
                
            });
        });




        
    });
</script> 
<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_db').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_db[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_db[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_db[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_db[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_db[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_db').prop('checked',true);
                    }else{
                        $('#checkAll_db').prop('checked',false);
                    }
                });
            });
        </script> 
<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll_agen').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update_agen[]"]').prop('checked',true);
                    }else{
                        $('input[name="update_agen[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update_agen[]"]').click(function(){
                    var total_checkboxes = $('input[name="update_agen[]"]').length;
                    var total_checkboxes_checked = $('input[name="update_agen[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll_agen').prop('checked',true);
                    }else{
                        $('#checkAll_agen').prop('checked',false);
                    }
                });
            });
        </script>                  

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_sj').DataTable({
  columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>


<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_sj_agen').DataTable({
  columnDefs: [
    { orderable: false, targets: 0 }
  ]
});
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_sj_agen_pr').DataTable();
} );
</script>

<script type="text/javascript">
    $(document).ready( function () {
    $('#tb_sj_pr').DataTable();
} );
</script>



</body>

</html>

                                                    