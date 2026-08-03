    <!-- Footer -->
    <footer class="mt-auto py-3">
        <div class="container text-center">
            <div class="row">
                <div class="col-12">
                    <p class="mb-0 small">&copy; 2024 WNJ Corp.</p>
                </div>
            </div>
        </div>
    </footer>
    </main>
    </body>
    </html>

    <!-- jQuery + DataTables (Bootstrap sendiri sudah datang dari navbar.php, tidak diulang di sini) -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- Init DataTable per halaman - aman dijalankan di halaman manapun, no-op kalau tabel-nya tidak ada -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#tb_listpo_artikel').DataTable();
            $('#tbmaximus').DataTable();
            $('#tb_dropship').DataTable();
            $('#tb_stock').DataTable();
            $('#tb_stock1').DataTable();
            $('#tb_po').DataTable({
                "dom": '',
                "pageLength": 15,
                "ordering": false
            });
            $('#tb_vendor').DataTable({
                columnDefs: [
                    { orderable: false, targets: 1 }
                ]
            });
        });
    </script>
