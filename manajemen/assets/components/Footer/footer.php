<!-- Footer -->
<footer class="mt-auto">
        <div class="container text-center">
            <div class="row">
                <div class="col-12 mt-3">
                    <p>&copy; 2024 WNJ Corp.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/manajemen/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/manajemen/bootstrap/js/bootstrap.bundle.min.js"></script>


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

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_stock').DataTable();
        } );
    </script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_stock1').DataTable();
        } );
    </script>