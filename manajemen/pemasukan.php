<?php 
    session_start();
    include 'koneksi.php'; 
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
?>
<!-- NAVBAR -->
<?php include "assets/components/Navbar/navbar.php"; ?>
<!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <!-- TABLE -->
        <div class="card p-3">
            <div class="col mt-2">
                <h1 class="text-center text-dark"> Pemasukan Harian</h1>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal-kredit">
                    <i class="bi bi-plus"></i>
                    Pemasukan
                </button>
                <a href="index" class="btn btn-info btn-sm float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = $koneksi->query("SELECT tanggal, created_at 
                                                            FROM catatan 
                                                            GROUP BY tanggal, created_at 
                                                            ORDER BY tanggal DESC, created_at DESC");
                                while ($data = $query->fetch_assoc()) {
                                    $tanggal        = $data['tanggal'];
                                    $created_date   = $data['created_at'];
                                    if ($tanggal === $created_date) {
                                        $label = $tanggal;
                                    } else {
                                        $label = "$tanggal ($created_date)";
                                    }

                                    $url = "detail_pemasukan.php?tanggal={$tanggal}&created_date={$created_date}";
                            ?>
                            <tr>
                                <td>
                                    <p class="mb-0">
                                        <a href="<?= $url ?>"><?= $label ?></a>
                                    </p>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- TABLE END -->
        <!-- Modal Pemasukan -->
        <div class="modal fade" id="exampleModal-kredit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Pemasukan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formPemasukan">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="tanggal" class="form-label mb-0">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="tanggal" required>
                            </div>

                            <div class="form-group">
                                <label for="rekening" class="mb-0">Rekening <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="rekening" required>
                                    <option disabled selected>~ Default Selected ~</option>
                                    <?php
                                        $query = $koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A' ORDER BY namabank");
                                        while ($data = $query->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data['namabank'] ?>"><?= $data['namabank'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nominal" class="form-label mb-0">Nominal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="nominal" placeholder="Rp. " min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="form-label mb-0">Keterangan <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-sm" id="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script>
        document.getElementById("formPemasukan").addEventListener("submit", function(e) {
        e.preventDefault();

        const tanggal = document.getElementById("tanggal").value;
        const rekening = document.getElementById("rekening").value;
        const nominal = document.getElementById("nominal").value;
        const keterangan = document.getElementById("keterangan").value;

        const createdDate = new Date().toISOString().slice(0,10);

        fetch("https://wnj.id/manajemen/api/insert-pemasukan.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                tanggal: tanggal,
                rekening: rekening,
                nominal: nominal,
                keterangan: keterangan
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            window.location.href = "https://wnj.id/manajemen/detail_pemasukan.php?tanggal=" + tanggal + "&created_date=" + createdDate;
        })
        .catch(error => {
            alert("Terjadi kesalahan: " + error);
            console.error(error);
        });
    });
    </script>
    <!-- END SCRIPT -->