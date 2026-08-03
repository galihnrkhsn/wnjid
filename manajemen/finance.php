<?php 
    session_start();
    include 'koneksi.php';
    include '../includes/image_upload_helper.php';
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $tipe           = $_GET['tipe'];
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];

    $queryManage    = $koneksi->query("SELECT *, role.id as id_role FROM user_manajemen INNER JOIN role ON user_manajemen.id_role = role.id WHERE user_manajemen.id = '$id'");
    $data           = $queryManage->fetch_assoc();
    $iduser         = $data['id'];
?>
<!-- NAVBAR -->
<?php include "assets/components/Navbar/navbar.php"; ?>
<!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <h2 class="m-0 font-weight-bold text-secondary">
            <?php if ($tipe !== 'MF') :?>
                Kas
            <?php endif; ?>
            <?php if ($tipe == "P"): ?>
                Produksi
            <?php elseif ($tipe == "AF"): ?>
                Admin
            <?php elseif ($tipe == "Pemotretan"): ?>
                Pemotretan
            <?php elseif ($tipe == "M"): ?>
                Manajemen
            <?php elseif ($tipe == "O"): ?>
                Owner
            <?php elseif ($tipe == "MF"): ?>
                Mutasi Finance
            <?php endif ?>
        </h2>

        <!-- SALDO -->
        <div class="row">
            <?php
                $ambil2         = $koneksi->query("SELECT sisasaldo as sisa FROM saldo_per_tipe WHERE tipe = '$tipe'"); 
                $distributor2   = $ambil2->fetch_assoc();
            ?>
            <div class="text-center text-dark bg-warning mt-3 rounded border border-dark">
                <p class="pt-5">Saldo Akhir</p>
                <h2 class="pb-5">Rp. <?= number_format($distributor2["sisa"] ?? 0); ?></h2>
            </div>
        </div>
        <!-- SALDO END -->

        <!-- TABLE -->
        <div class="col mt-2">
            <div class="mb-3">
                <?php if ($role == 'Owner' || $role == 'Admin Finance' && $tipe == 'AF') : ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm2"><i class="fa fa-plus"></i> Kredit</button>
                <?php endif ?>
                <?php if ($tipe == 'P') : ?>
                    <a href="produksi/input_kredit.php?tipe=P" class="btn btn-success"><i class="fa fa-plus"></i> Kredit</a>
                <?php endif; ?>
                <?php if ($tipe == 'P') : ?>
                    <a href="produksi/input_debit.php?tipe=P" class="btn btn-danger"><i class="fa fa-minus"></i> Debit</a>
                <?php elseif ($tipe == 'AF') : ?>
                    <a href="produksi/input_debit.php?tipe=AF" class="btn btn-danger"><i class="fa fa-minus"></i> Debit</a>
                <?php else :?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalForm" ><i class="fa fa-minus"></i> Debit</button>
                <?php endif; ?>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-file-export"></i> Export</button>
                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <br>
                            </div>
                            <div class="modal-body">
                                <form action="excel.php" method="post" target="_blank">
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" name="tanggal1" id="tanggal1" required>
                                        <span class="input-group-text" style="border-radius:0;">s/d</span>
                                        <input type="date" class="form-control" name="tanggal2" id="tanggal2" required>
                                        <input type="hidden" name="tipe" value="<?= $tipe; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-info" name="export"><i class="fa fa-file-export"></i> Export</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="index" class="btn btn-info float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="table-responsive" style="border:0px;">
                <table class="table table-bordered table-striped" id="tb_finance">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">
                                <select name="skill_dropdown" id="skill_dropdown">
                                    <option value="Edit">Edit</option>
                                    <?php if ($tipe !== 'MF') :?>
                                        <option value="View">View</option>
                                    <?php endif; ?>
                                    <option value="Delete">Delete</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no = 1;
                            if ($tipe == 'O') {
                                $ambil = $koneksi->query("SELECT * FROM rekeningkoran 
                                                            INNER JOIN kategori_manajemen ON kategori_manajemen.idkategori = rekeningkoran.kategori_id 
                                                            WHERE rekeningkoran.tipe = '$tipe' 
                                                            AND deleted_at IS NULL 
                                                            ORDER BY idrk DESC LIMIT 200");
                            } else {
                                $ambil = $koneksi->query("SELECT * FROM rekeningkoran 
                                                            INNER JOIN kategori_manajemen ON kategori_manajemen.idkategori = rekeningkoran.kategori_id 
                                                            WHERE rekeningkoran.tipe = '$tipe' 
                                                            AND tanggal > '2024-12-30'
                                                            AND deleted_at IS NULL
                                                            ORDER BY idrk DESC LIMIT 200");
                            }

                            while ($tampil = $ambil->fetch_assoc()) {
                                $tanggalnya = $tampil['tanggal'];
                        ?>
                            <tr>
                                <!-- <td><?= $no++; ?></td> -->
                                <td>
                                    <?= date("y-m-d", strtotime($tanggalnya)); ?>
                                    <br>
                                    <?= $tampil['waktu']; ?>
                                </td>
                                <td>
                                    <?= $tampil['keterangan']; ?>
                                    <br>
                                    <?php if ($tampil['debit'] > 0) { ?>
                                        <b><font color="red">(D) -<?= number_format($tampil['debit']); ?></font></b>
                                        <br>
                                        <p class="badge badge-danger">
                                            <font color="black">
                                                <?= $tampil['nama_kategori']; ?>
                                            </font>
                                        </p>
                                    <?php } else { ?>
                                        <b><font color="green">(K) +<?= number_format($tampil['kredit']); ?></font></b>
                                        <br>
                                        <p class="badge badge-success">
                                            <font color="black">
                                                <?= $tampil['nama_kategori']; ?>
                                            </font>
                                        </p>
                                    <?php } ?>
                                    <?php if (isset($tampil['bank'])) :?>
                                        <p class="badge badge-success">
                                            <?= $tampil['bank']; ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="Edit skill">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $tampil['idrk']; ?>"><i class="fa fa-edit"></i></button>
                                    </div>
                                    <?php if ($tipe !== 'MF') :?>
                                    <div class="View skill" style="display: none">
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalView<?php echo $tampil['idrk'];?>"><i class="fa fa-eye"></i></button>
                                    </div>
                                    <?php endif; ?>
                                    <form method="POST">
                                        <div class="Delete skill" style="display: none">
                                            <input type="hidden" value="<?= $tampil['idrk']; ?>" name="idrk" id="idrk" readonly>
                                            <button class="btn btn-danger" type="submit" name="hapus" onclick="return confirm('Yakin Akan Hapus Data?');"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <!-- Modal VIEW -->
                            <div class="modal fade" id="modalView<?php echo $tampil['idrk'];?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="labelModalKu"><i class="fa fa-eye"></i> View</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Body -->
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <p class="statusMsg"></p>
                                                <center>
                                                    <div class="card" style="width: 18rem;">
                                                        <?php if($tampil['buktitf']==""){ ?>
                                                            <img src="..." class="card-img-top" alt="Foto Tidak Ada">
                                                        <?php } else { ?>
                                                            <img src="../image/bukti_manajemen/<?php echo $tampil['buktitf'];?>" class="card-img-top" alt="Bukti Transfer">
                                                        <?php } ?>
                                                    </div>       
                                                </center>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal EDIT -->
                            <div class="modal fade" id="modalEdit<?= $tampil['idrk']; ?>" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="labelModalKu"><i class="fa fa-edit"></i> Ubah</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Body -->
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <p class="statusMsg"></p>
                                                <input type="hidden" name="idrk" value="<?php echo $tampil['idrk'];?>"/>    
                                                <div class="form-group">
                                                    <label for="tanggal">Tanggal</label>
                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $tampil['tanggal'];?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" value="<?php echo $tampil['keterangan'];?>" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kredit">Kredit</label>
                                                    <input type="number" min="0" class="form-control" id="kredit" name="kredit" placeholder="Masukkan Total Kredit" value="<?php echo $tampil['kredit'];?>" <?php echo $tampil['kredit'] > 0 ? 'required' : 'readonly required'; ?>/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="debit">Debit</label>
                                                    <input type="number" min="0" class="form-control" id="debit" name="debit" placeholder="Masukkan Total Debit" value="<?php echo $tampil['debit'];?>" <?php echo $tampil['debit'] > 0 ? 'required' : 'readonly required'; ?>/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kategori">Kategori</label>
                                                    <select name="kategori" id="kategori" class="form-control">
                                                        <option value="<?= $tampil['idkategori']; ?>" selected><?= $tampil['nama_kategori']; ?></option>
                                                        <?php 
                                                            if ($tipe == 'MF') {
                                                                $getKate    = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'MF'");
                                                            } elseif ($tampil['kredit'] > 0) {
                                                                $getKate    = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'kredit'");
                                                            } elseif ($tampil['debit'] > 0) {
                                                                $getKate    = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'debit'");
                                                            }
                                                            while($data = $getKate->fetch_assoc()) {
                                                        ?>
                                                        <option value="<?= $data['idkategori']?>"><?= $data['nama_kategori'] ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php if ($tipe !== 'MF') :?>
                                                <div class="form-group">
                                                    <label for="foto">Nota</label>
                                                    <br>
                                                    <input type="file" name="foto" id="foto">
                                                </div>
                                                <?php endif;?>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                                <button type="submit" class="btn btn-primary" name="kirimubah">KIRIM</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            }
                            if (isset($_POST["hapus"])) {
                                $idrk   = $_POST["idrk"];
                                $koneksi->query("UPDATE rekeningkoran SET deleted_at = NOW() WHERE idrk = '$idrk'");
                                    echo "<script>alert('Transaksi ($idrk) telah berhasil dihapus');</script>";
                                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                            }
                        ?>
                    </tbody>
                </table>
                <!-- Modal Kredit -->
                <div class="modal fade" id="modalForm2" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu"><i class="fa fa-plus"></i> Kredit</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- Modal Body -->
                            <form method="POST" id="formFinanceKredit" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p class="statusMsg"></p>

                                    <!-- Hidden field untuk tipe -->
                                    <input type="hidden" name="tipe" value="<?= $tipe ?>">
                                    <input type="hidden" name="iduser" value="<?= $iduser ?>">
                                    <input type="hidden" name="jenis_transaksi" value="kredit"> 

                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" min="0" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan Total jumlah" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control" required>
                                            <option value="" disabled selected>~~ Pilih Kategori ~~</option>
                                            <?php
                                                if ($tipe == 'MF') {
                                                    $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'MF'");
                                                } else {
                                                    $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'kredit'");
                                                }
                                                while($data = $getKate->fetch_assoc()) {
                                            ?>
                                            <option value="<?= $data['idkategori']?>"><?= $data['nama_kategori'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <?php if ($tipe != 'MF') :?>
                                    <div class="form-group">
                                        <label for="foto">Nota</label>
                                        <input type="file" name="foto" id="foto" accept="image/*" required />
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                    <button type="submit" class="btn btn-primary">KIRIM</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Debit -->
                <div class="modal fade" id="modalForm" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu"><i class="fa fa-minus"></i> Debit</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- Modal Body -->
                            <form method="POST" id="formFinanceDebit" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p class="statusMsg"></p>

                                    <!-- Hidden field untuk tipe -->
                                    <input type="hidden" name="tipe" value="<?= $tipe ?>">
                                    <input type="hidden" name="iduser" value="<?= $iduser ?>">
                                    <input type="hidden" name="jenis_transaksi" value="debit"> 

                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" min="0" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan Total jumlah" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control" required>
                                            <option value="" disabled selected>~~ Pilih Kategori ~~</option>
                                            <?php
                                                if ($tipe == 'MF') {
                                                    $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'MF'");
                                                } else {
                                                    $getKate = $koneksi->query("SELECT * FROM kategori_manajemen WHERE tipe = 'debit'");
                                                }
                                                while($data = $getKate->fetch_assoc()) {
                                            ?>
                                            <option value="<?= $data['idkategori']?>"><?= $data['nama_kategori'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php if ($tipe == 'MF') :?>
                                    <div class="form-group">
                                        <label for="bank">Bank</label>
                                        <select name="bank" id="bank" class="form-control" required>
                                            <option value="" disabled selected>~~ Pilih Bank ~~</option>
                                            <option value="bca">BCA</option>
                                            <option value="bsi">BSI</option>
                                            <option value="mandiri">Mandiri</option>
                                            <option value="bri">BRI</option>
                                            <option value="muamalat">Muamalat</option>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($tipe != 'MF') :?>
                                    <div class="form-group">
                                        <label for="foto">Nota</label>
                                        <input type="file" name="foto" id="foto" accept="image/*" required />
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">&times; Close</button>
                                    <button type="submit" class="btn btn-primary">KIRIM</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TABLE END -->
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
        <!-- UPDATE -->
        <?php 
            if (isset($_POST["kirimubah"])) {
                date_default_timezone_set('Asia/Jakarta');
                $waktu      = date('H:i:s');
                $idrk       = $_POST["idrk"];
                $tanggal    = $_POST["tanggal"];
                $keterangan = addslashes(htmlspecialchars($_POST["keterangan"]));
                $kategori   = $_POST['kategori'];
                $kredit     = $_POST["kredit"];
                $debit      = $_POST["debit"];
                $sisa       = $distributor2['sisa'] + $kredit - $debit;
                
                $foto       = $_FILES['foto']['name'];
                $tmp        = $_FILES['foto']['tmp_name'];
                $ukuranFile = $_FILES['foto']['size'];
                
                if ($foto == "") {
                    $sql = $koneksi->query("UPDATE rekeningkoran SET tanggal = '$tanggal', waktu = '$waktu', keterangan = '$keterangan', kredit = '$kredit', debit = '$debit', kategori_id = '$kategori' WHERE idrk = '$idrk'");
                    if ($sql) {
                        echo "<script>alert('Transaksi ($keterangan) berhasil diubah');</script>";
                        echo "<script>location='finance.php?tipe=$tipe'</script>";
                    } else {
                        echo "<script>alert('Transaksi ($keterangan) gagal diubah');</script>";
                        echo "<script>location='finance.php?tipe=$tipe'</script>";
                    }
                    return false;
                }
                
                $ekstensiGambarValid    = ['jpg', 'jpeg', 'png'];
                $ekstensiGambar         = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>alert('Yang anda upload bukan gambar (jpg/jpeg/png)');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                    return false;
                }

                $namadepan      = $kredit == 0 ? 'D' . $tipe : 'K' . $tipe;
                $namaFileBaru   = convertUploadedImageToWebp($tmp, $ekstensiGambar, '../image/bukti_manajemen/', $namadepan . uniqid());

                if ($namaFileBaru) {
                    $sql = $koneksi->query("UPDATE rekeningkoran SET tanggal='$tanggal', waktu='$waktu', keterangan='$keterangan', kredit='$kredit', debit='$debit', buktitf='$namaFileBaru' WHERE idrk='$idrk'");
                }
                if ($sql) {
                    echo "<script>alert('Transaksi ($keterangan) berhasil diubah');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                } else {
                    echo "<script>alert('Transaksi ($keterangan) gagal diubah');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
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
            $('#tb_finance').DataTable({
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                order: false,
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
    <script>
        function handleFormSubmit(formId) {
            const form = document.getElementById(formId);

            if (!form) {
                console.warn(`Form dengan ID '${formId}' tidak ditemukan.`);
                return;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);

                const submitButton = form.querySelector("button[type='submit']");
                // if (submitButton) submitButton.disabled = true;
    
                fetch("https://wnj.id/manajemen/api/insert-finance.php", {
                    method: 'POST',
                    body: formData
                })
                .then(async res => {
                    const text = await res.text();
                    console.log('Response mentah:', text);
    
                    try {
                        const json = JSON.parse(text);
                        if (json.success) {
                            alert(json.message);
                            location.reload();
                        } else {
                            alert('Gagal: ' + json.message);
                        }
                    } catch (e) {
                        console.error('Respon bukan JSON valid:', text);
                    }
                })
                .catch(err => {
                    console.error('Network error:', err);
                });
            });
        }

        handleFormSubmit('formFinanceKredit');
        handleFormSubmit('formFinanceDebit');
    </script>

    <!-- END SCRIPT -->
</body>
</html>
