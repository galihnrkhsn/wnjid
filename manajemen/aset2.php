<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    date_default_timezone_set('Asia/Jakarta');
    $namalengkap    = $user['username'];
    $iduser         = $user['id'];
    $tgl            = date('Y-m-d');
?>
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- jQuery (dipakai script di bawah, bukan bagian dari Bootstrap - Bootstrap-nya sendiri sudah dari navbar.php) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <?php
        $ambil2         = $koneksi->query("SELECT sum(nilai) as total FROM aset"); 
        $distributor2   = $ambil2->fetch_assoc();
    ?>
    <div class="jumbotron text-center">
        <h2>Aset Total</h2>
        <h2>Rp. <?php echo number_format($distributor2["total"]); ?></h2>
    </div>    
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm2">
        <i class="fa fa-plus"></i> Tambah data
    </button> 
    <a href="index.php" class="btn btn-info">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>

    <div class="table-responsive" style="border:0px;">
    <br>
        <table class="table table-bordered table-striped" id="tbrk">
            <thead>
                <tr>
                    <th style="text-align:center;">Tanggal</th>
                    <th style="text-align:center;">Item</th>
                    <th style="text-align:center;">Nilai</th>
                    <th>
                    <select name="skill_dropdown" id="skill_dropdown">
                        <option value="View">View</option>
                        <option value="Edit">Edit</option>
                        <option value="Delete">Delete</option>
                    </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php  
                    $ambil = $koneksi->query("SELECT * FROM aset ORDER BY id DESC"); 
                    while ($tampil = $ambil->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $tampil['tgl']; ?></td>
                        <td><?= $tampil['item']; ?></td>
                        <td>Rp <?= number_format($tampil['nilai']); ?></td>
                        <td>
                            <div class="Edit skill" style="display: none">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $tampil['id']; ?>"><i class="fa fa-edit"></i></button>
                            </div>
                            <div class="View skill">
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalView<?= $tampil['id']; ?>"><i class="fa fa-eye"></i></button>
                            </div>
                            <form method="POST">
                                <div class="Delete skill" style="display: none">
                                    <input type="hidden" value="<?= $tampil['id']; ?>" name="id" id="id" readonly>
                                    <button class="btn btn-danger" type="submit" name="hapus" onclick="return confirm('Yakin Akan Hapus Data?');"><i class="fa fa-trash"></i></button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php
                        if (isset($_POST["hapus"])) {
                            $id     = $_POST["id"];
                            $sql    = $koneksi->query("DELETE FROM aset WHERE id = '$id'");
                            if ($sql) {
                                echo "<script>alert('Aset ($id) telah berhasil dihapus');</script>";
                                echo "<script>location='aset.php'</script>";
                            } else {
                                echo "<script>alert('Aset ($id) gagal dihapus');</script>";
                                echo "<script>location='aset.php'</script>";
                            }
                        } 
                    ?>

                    <!-- Modal View -->
                    <div class="modal fade" id="modalView<?= $tampil['id']; ?>" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><i class="fa fa-eye"></i> View</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <p class="statusMsg"></p>
                                        <div class="form-group">
                                            <label for="Tanggal">Tanggal</label>
                                            <input type="text" class="form-control" id="Tanggal" value="<?= $tampil['tgl']; ?>" name="Tanggal" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="Item">Item</label>
                                            <input type="text" class="form-control" id="Item" value="<?= $tampil['item']; ?>" name="Item" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nilai">Nilai</label>
                                            <input type="text" class="form-control" id="Nilai" value="<?= $tampil['nilai']; ?>" name="Nilai" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $tampil['id']; ?>" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><i class="fa fa-edit"></i> Ubah</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <p class="statusMsg"></p>
                                        <input type="hidden" name="id" value="<?= $tampil['id']; ?>" />
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="date" class="form-control" id="tanggal" value="<?= $tampil['tgl']; ?>" name="tanggal" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="text" class="form-control" id="item" value="<?= $tampil['item']; ?>" name="item" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilai">Nilai</label>
                                            <input type="number" class="form-control" min="0" id="nilai" value="<?= $tampil['nilai']; ?>" name="nilai" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                        <button type="submit" class="btn btn-success" name="kirimubah">Ubah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php 
                        if (isset($_POST["kirimubah"])) {
                            date_default_timezone_set('Asia/Jakarta');
                            $tgl    = $_POST["tanggal"];
                            $item   = $_POST["item"];
                            $nilai  = $_POST["nilai"];
                            $id     = $_POST["id"];
                            $sql    = $koneksi->query("UPDATE aset SET tgl = '$tgl', item = '$item', nilai = '$nilai', waktu = NOW() WHERE id = '$id'");
                            if ($sql) {
                                echo "<script>alert('Aset berhasil diubah');</script>";
                                echo "<script>location='aset.php'</script>";
                            } else {
                                echo "<script>alert('Aset gagal diubah');</script>";
                                echo "<script>location='aset.php'</script>";
                            }
                        } 
                    ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

<!-- Modal Form2 -->
    <div class="modal fade" id="modalForm2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Aset</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="statusMsg"></p>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="item">Item</label>
                            <input type="text" class="form-control" id="item" name="item" required>
                        </div>
                        <div class="form-group">
                            <label for="nilai">Nilai</label>
                            <input type="number" class="form-control" id="nilai" name="nilai" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                        <button type="submit" class="btn btn-primary" name="kirimkredit">KIRIM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
        if (isset($_POST["kirimkredit"])) {
            date_default_timezone_set('Asia/Jakarta');
            $tanggal    = $_POST["tanggal"];
            $item       = $_POST["item"];
            $nilai      = $_POST["nilai"];
            $sql        = $koneksi->query("INSERT INTO aset (id, iduser, item, nilai, tgl, waktu) VALUES (NULL, '$iduser', '$item', '$nilai', '$tanggal', NOW())");
            if ($sql) {
                echo "<script>alert('Aset berhasil ditambahkan');</script>";
                echo "<script>location='aset.php'</script>";
            } else {
                echo "<script>alert('Aset gagal ditambahkan');</script>";
                echo "<script>location='aset.php'</script>";
            }
        } 
    ?>
<?php include 'template/footer.php'; ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbrk').DataTable();
        $("#skill_dropdown").change(function () {
            var inputVal = $(this).val();
            var eleBox = $("." + inputVal);
            $(".skill").hide();
            $(eleBox).show();
        });
    });
</script>
