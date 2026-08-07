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
    <title>Ready Stok | WNJ.ID</title>
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <div class="container mt-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h2 class="m-0 font-weight-bold text-secondary">Ready Stok
                    <a href="index.php" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
                </h2>
                    
        </div>
        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <div class="form-group">
                    <label>Filter</label>
                    <select  class="form-control" name="filter" id="filter">
                        <option value="Harian">Harian</option>
                        <option value="Bulanan">Bulanan</option>
                    </select>  
                </div>
                <div id="tfilter" name="tfilter"></div> 
            </div>  
        </div><!-- Content Row -->
    </div>
    <?php include 'assets/components/Footer/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#filter').change(function(){
            var filter = $('#filter').val();
            $.ajax({
                type : 'GET',
                url : 'cek_filter.php',
                data :  'filter=' + filter,
                    success: function (data) {
                    $("#tfilter").html(data);
                }
            });
        });  

        $('#filter').ready(function(){
            var filter = $('#filter').val();
            $.ajax({
                type : 'GET',
                url : 'cek_filter.php',
                data :  'filter=' + filter,
                    success: function (data) {
                    $("#tfilter").html(data);
                }
                
            });
        });
    });
</script> 