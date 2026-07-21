<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['idpo'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["idadmin"];

    $queryDB = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $dataDB = $queryDB->fetch_assoc();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wanoja | Dropship <?= $invoice ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        <!-- Navbar Start -->
        <nav class="navbar bg-body-secondary">
            <div class="container">
                <a class="navbar-brand" href="listds.php?id=335&invoice=<?= $invoice ?>"><i class="bi bi-chevron-left"></i></a>
                <span class="fw-bold text-uppercase fs-6">
                    Pre Order
                </span>
                <span></span>
            </div>
        </nav>
        <!-- Navbar End -->

        <div class="container py-4">
            <h6 class="text-center mb-3 text-uppercase">Invoice <span class="text-secondary">#<?= $invoice ?></span></h6>

            <div class="card">
                <div class="card-body">
                    <form method="post" class="row">
                        <div class="col-sm-6">
                            <label for="namaPengirim" class="form-label mb-1">Nama Pengirim <span class="text-danger">*</span></label>
                            <input type="text" name="namapengirim" class="form-control form-control-sm" id="namaPengirim" placeholder="Masukan Nama Pengirim ..." required>
                        </div>
                        <div class="col-sm-6">
                            <label for="tlpPengirim" class="form-label mb-1">Telepon Pengirim <span class="text-danger">*</span></label>
                            <input type="number" name="tlppengirim" class="form-control form-control-sm" id="tlpPengirim" value="00000000" min="0" required>
                        </div>
    
                        <div class="col-sm-12">
                            <hr />
                        </div>
    
                        <div class="col-sm-6">
                            <label for="namaPenerima" class="form-label mb-1">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="namapenerima" class="form-control form-control-sm" id="namaPenerima" placeholder="Masukan Nama Penerima ..." required>
                        </div>
                        <div class="col-sm-6">
                            <label for="tlpPenerima" class="form-label mb-1">Telepon Penerima <span class="text-danger">*</span></label>
                            <input type="number" name="tlppenerima" class="form-control form-control-sm" id="tlpPenerima" value="00000000" min="0" required>
                        </div>
    
                        <div class="col-sm-12">
                            <hr />
                        </div>
    
                        <div class="col-sm-12">
                            <p class="text-uppercase fw-medium mb-0">Alamat Tujuan</p>
                            <hr width="129" class="m-0"/>
                        </div>
    
                        <div class="col-sm-12 mt-1">
                            <label for="alamatPenerima" class="form-label mb-1">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamatpenerima" class="form-control form-control-sm" id="alamatPenerima" rows="3" required></textarea>
                        </div>
                        <div class="col-sm-4 mt-1">
                            <label for="prov" class="form-label mb-1">Provinsi <span class="text-danger">*</span></label>
                            <select name="prov" id="prov" class="form-select form-select-sm">
                                <option value="">- Default Selected -</option>
                                <?php
                                    $idprov = $_SESSION["provinsi"];
                                    $ambil = $koneksi->query("SELECT * FROM tb_ro_provinces");
                                    while($row = $ambil->fetch_assoc()) {
                                ?>
                                    <option value="<?= $row['province_id']; ?>|<?= $row['province_name']; ?>"><?= $row['province_name']; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-4 mt-1">
                            <label for="kabupaten" class="form-label mb-1">Kota / Kabupaten <span class="text-danger">*</span></label>
                            <select name="kabupaten" id="kabupaten" class="form-select form-select-sm" required>
                                <option value="">- Default Selected -</option>
                            </select>
                        </div>
                        <div class="col-sm-4 mt-1">
                            <label for="kecamatan" class="form-label mb-1">Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan" id="kecamatan" class="form-select form-select-sm" required>
                                <option value="">- Default Selected -</option>
                            </select>
                        </div>
    
                        <div class="col-sm-8 mt-1">
                            <label for="keterangan" class="form-label mb-1">Keterangan</label>
                            <textarea name="keterangan" class="form-control form-control-sm" id="keterangan" rows="1" required></textarea>
                        </div>
                        <div class="col-sm-4 mt-1">
                            <label for="ekspedisi" class="form-label mb-1">Ekspedisi</label>
                            <select name="ekspedisi" id="ekspedisi" class="form-select form-select-sm">
                                <option value="">- Default Selected -</option>
                                <option value="jne oke">JNE OKE</option>
                                <option value="jne reg">JNE REG</option>
                                <option value="jne yes">JNE YES</option>
                                <option value="jtr">JTR</option>
                                <option value="wahana">WAHANA</option>
                                <option value="sicepat">SICEPAT</option>
                                <option value="lion">LION PARCEL</option>
                                <option value="j&t">J&T</option>
                                <option value="tiki">TIKI</option>
                                <option value="ide">ID EXPRESS</option>
                                <option value="pos kilat">POS KILAT</option>
                                <option value="pos ekonomi jumbo">POS EKONOMI JUMBO</option>
                                <option value="gosend">GOSEND</option>
                            </select>
                        </div>
    
                        <div class="col-sm-12">
                            <hr />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('#prov').change(function(){
                    //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                    var provinsi = $('#prov').val();
                    $.ajax({
                        type : 'GET',
                        url : 'cek_kabupaten_dropship.php',
                        data :  'prov_id=' + provinsi,
                        success: function (data) {
                            //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                            $("#kabupaten").html(data);
                        }
                    });
                });
            $('#kabupaten').change(function(){
            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var kabupaten = $('#kabupaten').val();
                    $.ajax({
                        type : 'GET',
                        url : 'cek_kecamatan_dropship.php',
                        data :  'kabupaten_id=' + kabupaten,
                        success: function (data) {
                            //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                            $("#kecamatan").html(data);
                        }
                    });
                });
            });  
        </script>
        <script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
    </body>
</html>