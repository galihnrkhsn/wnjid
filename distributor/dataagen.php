<?php 
session_start();

include 'koneksi.php'; 
include 'assets/components/Sessions/sesDistri.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mitra <?php echo $_SESSION['namamitra']; ?>| WNJ </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
      <!-- Include Navbar -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- Navbar End -->

    <!-- Main Content -->
    <div class="container mt-3">
        <h2 class="text-center mb-4">SUB MITRA</h2>
        <a class="btn btn-success mb-2" href="inputagen">Tambah Mitra</a> 
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Agen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Reseller</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#marketer">Marketer</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- Tab Content for Agen -->
            <div id="agen" class="container tab-pane active">
                <h3 class="mb-3">Data Agen</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tb_dataagen">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">Opsi</th>
                                <th>Status</th>
                                <th>Nama Agen</th>
                                <th>Email</th>
                                <th>Whatsapp</th>
                                <th>Telegram</th>
                                <th>Facebook</th>
                                <th>Instagram</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                                $limit = 20;
                                $limit_start = ($page - 1) * $limit;
                                $idadmin = $_SESSION["idadmin"];
                                $no = 1;
                                $query = "SELECT * FROM mitraagen WHERE idadmin='$idadmin' ORDER BY namaagen LIMIT $limit_start, $limit";
                                $result = $koneksi->query($query);
                                
                                while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td class="text-center">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['idmitraagen'];?>">
                                        <input type="hidden" name="menu" value="agen">
                                        <input type="hidden" name="iduser" value="<?php echo $row['iduser'];?>">
                                        <button class="btn btn-danger" name="hapus"><span class="fa fa-trash"></span></button>
                                    </form>
                                </td>
                                <td><?php echo $row['status'];?></td>
                                <td><?php echo $row['namaagen'];?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo $row['whatsapp'];?></td>
                                <td><?php echo $row['telegram'];?></td>
                                <td><?php echo $row['facebook'];?></td>
                                <td><?php echo $row['instagram'];?></td>
                                <td><?php echo $row['alamat'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Content for Agen END -->
            <!-- Tab Content for Reseller -->
            <div id="reseller" class="container tab-pane fade">
                <h3 class="mb-3">Data Reseller</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tb_datareseller">
                        <thead>
                            <tr>
                            <tr>
                            <th class="text-center" style="width: 60px;">Opsi</th>
                              <th style="text-align: center">Nama SubDB</th>
                              <th style="text-align: center">Nama DB</th>
                              <th style="text-align: center">Email</th>
                              <th style="text-align: center">Password</th>
                              <th style="text-align: center">Whatsapp</th>
                              <th style="text-align: center">Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                          $no=1;
                          $tampil =$koneksi->query("SELECT * FROM mitrareseller INNER JOIN admin_mitra ON mitrareseller.idadmin=admin_mitra.idadmin
                            WHERE admin_mitra.idadmin='$idadmin'
                            ORDER BY mitrareseller.idmitrareseller DESC");
                          while($tampilMas=$tampil->fetch_assoc()){  
                        ?>
                            <tr>
                                <td class="text-center">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['idmitraagen'];?>">
                                        <input type="hidden" name="menu" value="agen">
                                        <input type="hidden" name="iduser" value="<?php echo $row['iduser'];?>">
                                        <button class="btn btn-danger" name="hapus"><span class="fa fa-trash"></span></button>
                                    </form>
                                </td>                            
                                <td><?php echo $tampilMas['namaagen']; ?> (<?php echo $tampilMas['idmitrareseller']; ?>)</td>
                                <td><?php echo $tampilMas['namamitra']; ?></td>
                                <td><?php echo $tampilMas['email']; ?></td>
                                <td><?php echo $tampilMas['password']; ?></td>
                                <td><?php echo $tampilMas['whatsapp']; ?></td>
                                <td><?php echo $tampilMas['alamat']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Content for Reseller END -->
            <!-- Tab Content for Marketer -->
            <div id="marketer" class="container tab-pane fade">
                <h3 class="mb-3">Data Marketer</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tb_datamarketer">
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Status</th>
                                <th>Nama Agen</th>
                                <th>Email</th>
                                <th>Whatsapp</th>
                                <th>Telegram</th>
                                <th>Facebook</th>
                                <th>Instagram</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                                $limit = 20; // Jumlah data per halamannya
                                // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
                                $limit_start = ($page - 1) * $limit;
                                $idadmin = $_SESSION["idadmin"];				
                                $no = 1;
                                
                                // Lakukan query untuk mengambil data dari tabel mitraagen berdasarkan idadmin dari session
                                $query = "SELECT * FROM mitramarketer WHERE idadmin='$idadmin' ORDER BY namaagen LIMIT $limit_start, $limit";
                                $result = $koneksi->query($query);
                                
                                while ($row = $result->fetch_assoc()) {
                                ?>
                            <tr>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['idmitramarketer'];?>">
                                        <input type="hidden" name="menu" value="marketer">
                                        <input type="hidden" name="iduser" value="<? echo $row['iduser'] ?>">
                                        <button class="btn btn-danger" name="hapus">
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    </form>
                                </td>
                                <td><?php echo $row['status'];?></td>
                                <td><?php echo $row['namaagen'];?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo $row['whatsapp'];?></td>
                                <td><?php echo $row['telegram'];?></td>
                                <td><?php echo $row['facebook'];?></td>
                                <td><?php echo $row['instagram'];?></td>
                                <td><?php echo $row['alamat'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tab Content for Marketer END -->
        </div>
    </div>
    <br><br><br><br>
    <!-- Main Content End -->

    <!-- FOOTER -->
    <?
        include 'menubawah.php';
    ?>
    <!-- FOOTER END -->
  
    <!-- PHP SYNTAK -->
    <?
    include 'koneksi.php';
        if (isset($_POST["hapus"])) {
            $id = $_POST['id'];
            $menu = $_POST['menu'];
            $idUser = $_POST['iduser'];
    
            // Tentukan tabel berdasarkan pilihan pengguna
            switch ($menu) {
                case 'agen':
                    $table = "mitraagen";
                    $idField = "idmitraagen";
                    break;
                case 'reseller':
                    $table = "mitrareseller";
                    $idField = "idmitrareseller";
                    break;
                case 'marketer':
                    $table = "mitramarketer";
                    $idField = "idmitramarketer";
                    break;
                default:
                    echo "Menu tidak valid";
                    exit();
            }
            $query1 = "DELETE FROM $table WHERE $idField='$id'";
            $query2 = "DELETE FROM users WHERE id='$idUser'";
            $sql = mysqli_query($koneksi, $query1);
            $sql = mysqli_query($koneksi, $query2);
            if ($sql) { 
                echo "<script>alert('Data berhasil dihapus.'); window.location.href = 'dataagen.php';</script>";
                exit();
            } else {
                echo "<script>alert('Gagal menyimpan data.'); window.location.href = 'dataagen.php';</script>";
            }
        } 
    ?>
    <!-- PHP SYNTAK END -->

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="../adminwnj/assets/dist/js/jquery.min.js"></script>
    <script src="../adminwnj/assets/dist/js/bootstrap.min.js"></script>
    <script src="../adminwnj/assets/dist/DataTables/datatables.min.js"></script>


    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_dataagen').DataTable({
                "pageLength": 25,
                "language": {
                "decimal":        "",
                "emptyTable":     "Tidak ada data rekening",
                "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
                "infoFiltered":   "(Disaring dari _MAX_ total data)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Tampilkan _MENU_ Data",
                "loadingRecords": "Memuat...",
                "processing":     "Pemrosesan...",
                "search":         "Cari Data:",
                "zeroRecords":    "Data yang dicari tidak ditemukan",
                "paginate": {
                    "first":      "Awal",
                    "last":       "Akhir",
                    "next":       "&#10095;",
                    "previous":   "&#10094;"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_datareseller').DataTable({
                "pageLength": 25,
                "language": {
                "decimal":        "",
                "emptyTable":     "Tidak ada data rekening",
                "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
                "infoFiltered":   "(Disaring dari _MAX_ total data)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Tampilkan _MENU_ Data",
                "loadingRecords": "Memuat...",
                "processing":     "Pemrosesan...",
                "search":         "Cari Data:",
                "zeroRecords":    "Data yang dicari tidak ditemukan",
                "paginate": {
                    "first":      "Awal",
                    "last":       "Akhir",
                    "next":       "&#10095;",
                    "previous":   "&#10094;"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_datamarketer').DataTable({
                "pageLength": 25,
                "language": {
                "decimal":        "",
                "emptyTable":     "Tidak ada data rekening",
                "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
                "infoFiltered":   "(Disaring dari _MAX_ total data)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Tampilkan _MENU_ Data",
                "loadingRecords": "Memuat...",
                "processing":     "Pemrosesan...",
                "search":         "Cari Data:",
                "zeroRecords":    "Data yang dicari tidak ditemukan",
                "paginate": {
                    "first":      "Awal",
                    "last":       "Akhir",
                    "next":       "&#10095;",
                    "previous":   "&#10094;"
                    }
                }
            });
        });
    </script>
    <!-- SCRIPT -->

</div>
  
</body>
</html>

