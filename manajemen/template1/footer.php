                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->           
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
<?php 
            date_default_timezone_set('Asia/Jakarta');
            $waktu=date('Y');
 ?>                        
                        <span>Copyright &copy; WNJ Corp <?= $waktu; ?></span>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Klik "Logout" dibawah ini jika ingin mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Kembali</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/manajemen/jquery/jquery.min.js"></script>
    <script src="../vendor/manajemen/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/manajemen/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>





<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_listpo_artikel').DataTable();
} );
</script>

<script type="text/javascript">
        $(document).ready( function () {
    $('#tbmaximus').DataTable();
} );
</script>

<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_dropship').DataTable();
} );
</script>

<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_stock').DataTable();
} );
</script>

<script type="text/javascript">
        $(document).ready( function () {
    $('#tb_po').DataTable({
  "dom": '',
  "pageLength": 15,
  "ordering": false
});
} );
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_vendor').DataTable({
            columnDefs: [
                { orderable: false, targets: 1 }
            ]
        });
    });
</script>

</body>

</html>