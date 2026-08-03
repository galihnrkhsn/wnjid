<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];

    $queryManage    = $koneksi->query("SELECT *, role.id AS role_id 
                                        FROM user_manajemen 
                                        INNER JOIN role ON role.id = user_manajemen.id_role 
                                        WHERE user_manajemen.id = '$id'");
    $data           = $queryManage->fetch_assoc();

    $tipe           = $data['name']; 
?>
<!-- NAVBAR -->
<?php include "assets/components/Navbar/navbar.php"; ?>
<!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="row">
            <?php
                $ambil2 = $koneksi->query("SELECT (bca+bni+bri+bsi+mandiri+muamalat) as total FROM rekeningbank ORDER BY id DESC LIMIT 1"); 
                $distributor2 = $ambil2->fetch_assoc();
            ?>  
            <div class="text-center text-dark bg-warning mt-3 rounded border border-dark">
                <p class="pt-5">Cash Bank</p>
                <h2 class="pb-5">Rp. <?php echo number_format($distributor2["total"]); ?></h2>
            </div>
            <?php if ($tipe == "Admin Finance"): ?>   
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm2"><i class="fa fa-plus"></i> Tambah data</button>  
            <?php endif ?>
        </div>
        <div class="mt-2">
            <a href="index.php" class="btn btn-info" style="float: right;">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <div class="table-responsive" style="border:0px;">
                <br>
                <table class="table table-bordered table-striped" id="tb_cashbank">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="align:center;">Waktu</th>
                            <th style="align:center;">Total</th>
                            <th>
                                <select name="skill_dropdown" id="skill_dropdown">
                                    <option value="View">View</option>
                                    <?php if ($tipe == "Admin Finance"): ?>                 
                                    <option value="Edit">Edit</option>
                                    <option value="Delete">Delete</option>
                                    <?php endif ?>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no = 1;
                            $ambil = $koneksi->query("SELECT * FROM rekeningbank ORDER BY id DESC"); 
                            while($tampil = $ambil->fetch_assoc()){
                        ?>      
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tampil['waktu']; ?></td>
                                <td>Rp. <?= number_format($total = $tampil['bca'] + $tampil['bni'] + $tampil['bri'] + $tampil['bsi'] + $tampil['mandiri'] + $tampil['muamalat']); ?></td>
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
                            <!-- MODAL VIEW -->
                            <div class="modal fade" id="modalView<?= $tampil['id']; ?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- MODAL HEADER -->
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="labelModalKu"><i class="fa fa-eye"></i> View</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- MODAL BODY -->
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <p class="statusMsg"></p>
                                                <div class="form-group">
                                                    <label for="bca">BCA</label>
                                                    <input type="text" class="form-control" id="bca" value="Rp. <?= number_format($tampil['bca']); ?>" name="bca" placeholder="BCA" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bni">BNI</label>
                                                    <input type="text" class="form-control" id="bni" value="Rp. <?= number_format($tampil['bni']); ?>" name="bni" placeholder="BNI" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bri">BRI</label>
                                                    <input type="text" class="form-control" id="bri" value="Rp. <?= number_format($tampil['bri']); ?>" name="bri" placeholder="BRI" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bsi">BSI</label>
                                                    <input type="text" class="form-control" id="bsi" value="Rp. <?= number_format($tampil['bsi']); ?>" name="bsi" placeholder="BSI" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mandiri">Mandiri</label>
                                                    <input type="text" class="form-control" id="mandiri" value="Rp. <?= number_format($tampil['mandiri']); ?>" name="mandiri" placeholder="Mandiri" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="muamalat">Muamalat</label>
                                                    <input type="text" class="form-control" id="muamalat" value="Rp. <?= number_format($tampil['muamalat']); ?>" name="muamalat" placeholder="Muamalat" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">Note.</label>
                                                    <textarea class="form-control" id="note" name="note" readonly><?= $tampil['note']; ?></textarea>
                                                </div>
                                            </div>
                                            <!-- MODAL FOOTER -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- MODAL EDIT -->
                            <div class="modal fade" id="modalEdit<?= $tampil['id']; ?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- MODAL HEADER -->
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="labelModalKu"><i class="fa fa-edit"></i> Ubah</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- MODAL BODY -->
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <p class="statusMsg"></p>
                                                <input type="hidden" name="id" value="<?php echo $tampil['id']; ?>" />
                                                <div class="form-group">
                                                    <label for="bca">BCA</label>
                                                    <input type="number" min="0" class="form-control" id="bca" value="<?= $tampil['bca']; ?>" name="bca" placeholder="BCA" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bni">BNI</label>
                                                    <input type="number" min="0" class="form-control" id="bni" value="<?= $tampil['bni']; ?>" name="bni" placeholder="BNI" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bri">BRI</label>
                                                    <input type="number" min="0" class="form-control" id="bri" value="<?= $tampil['bri']; ?>" name="bri" placeholder="BRI" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bsi">BSI</label>
                                                    <input type="number" min="0" class="form-control" id="bsi" value="<?= $tampil['bsi']; ?>" name="bsi" placeholder="BSI" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mandiri">Mandiri</label>
                                                    <input type="number" min="0" class="form-control" id="mandiri" value="<?= $tampil['mandiri']; ?>" name="mandiri" placeholder="Mandiri" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="muamalat">Muamalat</label>
                                                    <input type="number" min="0" class="form-control" id="muamalat" value="<?= $tampil['muamalat']; ?>" name="muamalat" placeholder="Muamalat" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">Note.</label>
                                                    <textarea class="form-control" id="note" name="note"></textarea>
                                                </div>
                                            </div>
                                            <!-- MODAL FOOTER -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                                <button type="submit" class="btn btn-success" name="kirimubah">Ubah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- MODAL KREDIT -->
            <div class="modal fade" id="modalForm2" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="labelModalKu"><i class="fa fa-plus"></i> Kredit</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <p class="statusMsg"></p>
                                <div class="form-group">
                                    <label for="bca">BCA</label>
                                    <input type="number" min="0" class="form-control" id="bca" value="0" name="bca" placeholder="BCA" required>
                                </div>
                                <div class="form-group">
                                    <label for="bni">BNI</label>
                                    <input type="number" min="0" class="form-control" id="bni" value="0" name="bni" placeholder="BNI" required>
                                </div>
                                <div class="form-group">
                                    <label for="bri">BRI</label>
                                    <input type="number" min="0" class="form-control" id="bri" value="0" name="bri" placeholder="BRI" required>
                                </div>
                                <div class="form-group">
                                    <label for="bsi">BSI</label>
                                    <input type="number" min="0" class="form-control" id="bsi" value="0" name="bsi" placeholder="BSI" required>
                                </div>
                                <div class="form-group">
                                    <label for="mandiri">Mandiri</label>
                                    <input type="number" min="0" class="form-control" id="mandiri" value="0" name="mandiri" placeholder="Mandiri" required>
                                </div>
                                <div class="form-group">
                                    <label for="muamalat">Muamalat</label>
                                    <input type="number" min="0" class="form-control" id="muamalat" value="0" name="muamalat" placeholder="Muamalat" required>
                                </div>
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea class="form-control" id="note" name="note"></textarea>
                                </div>
                            </div>
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                <button type="submit" class="btn btn-primary" name="kirimkredit">KIRIM</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
        <!-- UPDATE -->
        <?php 
            if(isset($_POST["kirimubah"])){
                date_default_timezone_set('Asia/Jakarta');
                $note = addslashes(htmlspecialchars($_POST["note"]));
                $bca = $_POST["bca"];
                $bni = $_POST["bni"];
                $bri = $_POST["bri"];
                $bsi = $_POST["bsi"];
                $mandiri = $_POST["mandiri"];
                $muamalat = $_POST["muamalat"];
                $id = $_POST["id"];

                $sql = $koneksi->query("UPDATE rekeningbank 
                                        SET bca='$bca',
                                            bni='$bni',
                                            bri='$bri',
                                            bsi='$bsi',
                                            mandiri='$mandiri',
                                            muamalat='$muamalat',
                                            note='$note'
                                        WHERE id='$id'");
                if ($sql) {
                    echo "<script>alert('Cash Bank berhasil diubah');</script>";
                    echo "<script>location='cashbank.php'</script>";
                } else {
                    echo "<script>alert('Cash Bank gagal diubah');</script>";
                    echo "<script>location='cashbank.php'</script>";
                }
            }
        ?>

        <!-- STORE KREDIT -->
        <?php 
            if(isset($_POST["kirimkredit"])){
                date_default_timezone_set('Asia/Jakarta');
                $waktu = date('Y-m-d H:i:s');
                $note = addslashes(htmlspecialchars($_POST["note"]));
                $bca = $_POST["bca"];
                $bni = $_POST["bni"];
                $bri = $_POST["bri"];
                $bsi = $_POST["bsi"];
                $mandiri = $_POST["mandiri"];
                $muamalat = $_POST["muamalat"];

                $sql = $koneksi->query("INSERT INTO rekeningbank (id, iduser, waktu, bca, bni, bri, bsi, mandiri, muamalat, note) VALUES
                                        (null, '$id', '$waktu', '$bca', '$bni', '$bri', '$bsi', '$mandiri', '$muamalat', '$note')"); 
                if ($sql) {
                    echo "<script>alert('Cash Bank berhasil ditambahkan');</script>";
                    echo "<script>location='cashbank.php'</script>";
                } else {
                    echo "<script>alert('Cash Bank gagal ditambahkan');</script>";
                    echo "<script>location='cashbank.php'</script>";
                }
            }
        ?>
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#tb_cashbank').DataTable({
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                order: [[0, 'desc']],
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#skill_dropdown").change(function () {
                var inputVal = $(this).val();
                var eleBox = $("." + inputVal);
                $(".skill").hide();
                $(eleBox).show();
            });
        });
    </script>
    <!-- END SCRIPT -->
</body>
</html>
