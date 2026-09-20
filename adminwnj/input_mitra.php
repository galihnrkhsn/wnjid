<?php
session_start();
include 'koneksi.php';

// --- AUTH GUARD (harus paling atas, sebelum output apapun) ---
if (!isset($_SESSION["administrator"])) {
    header('Location: login.php');
    exit();
}

// Role yang valid -> menentukan tabel tujuan. WHITELIST ketat, jangan pernah
// membangun nama tabel langsung dari input user.
const ROLE_MAP = [
    'distributor' => ['table' => 'admin_mitra',    'id_col' => 'idadmin',          'has_parent' => false],
    'agen'        => ['table' => 'mitraagen',       'id_col' => 'idmitraagen',      'has_parent' => true],
    'reseller'    => ['table' => 'mitrareseller',   'id_col' => 'idmitrareseller',  'has_parent' => true],
    'marketer'    => ['table' => 'mitramarketer',   'id_col' => 'idmitramarketer',  'has_parent' => true],
];

$errors = [];

// --- HANDLE SUBMIT (sebelum HTML, redirect via header setelah selesai) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {

    $role = $_POST['role'] ?? '';

    if (!array_key_exists($role, ROLE_MAP)) {
        $errors[] = 'Jenis mitra tidak valid';
    } else {
        $email     = trim($_POST['email'] ?? '');
        $rawPass   = $_POST['password'] ?? '';
        $namamitra = trim($_POST['namamitra'] ?? '');
        $whatsapp  = trim($_POST['whatsapp'] ?? '');
        $alamat    = trim($_POST['alamat'] ?? '');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
        if ($rawPass === '') $errors[] = 'Password wajib diifsi';
        if ($namamitra === '') $errors[] = 'Nama wajib diisi';
        if ($whatsapp === '') $errors[] = 'Whatsapp wajib diisi';
        if ($alamat === '') $errors[] = 'Alamat wajib diisi';

        $idadmin = null;
        // if (ROLE_MAP[$role]['has_parent']) {
        //     $idadmin = $_POST['idadmin'] ?? '';
        //     if ($idadmin === '' || !ctype_digit((string)$idadmin)) {
        //         $errors[] = 'Distributor (DB) induk wajib dipilih';
        //     }
        // }

        // Field khusus Distributor
        $telegram = trim($_POST['telegram'] ?? '');
        $facebook = trim($_POST['facebook'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $provinsi = $kabupaten = $kecamatan = null;

        if ($role === 'distributor') {
            $provRaw = $_POST['prov'] ?? '';
            $kabRaw  = $_POST['kabupaten'] ?? '';
            $kecRaw  = $_POST['kecamatan'] ?? '';
            if ($provRaw === '' || $kabRaw === '' || $kecRaw === '') {
                $errors[] = 'Provinsi/Kota/Kecamatan wajib dipilih';
            } else {
                $provinsi  = explode('|', $provRaw)[0];
                $kabupaten = explode('|', $kabRaw)[0];
                $kecamatan = explode('|', $kecRaw)[0];
            }
        }

        // Cek email sudah dipakai atau belum (prepared statement)
        if (empty($errors)) {
            $check = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain.';
            }
            $check->close();
        }

        
        if (empty($errors)) {
          $hashedPassword = password_hash($rawPass, PASSWORD_DEFAULT);

            $koneksi->begin_transaction();
            try {
                // 1) Selalu buat akun login di tabel users
                $insUser = $koneksi->prepare(
                    "INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())"
                );
                $insUser->bind_param("ssss", $namamitra, $email, $hashedPassword, $role);
                $insUser->execute();
                $iduser = $koneksi->insert_id;
                $insUser->close();

                if ($role === 'distributor') {
                    $kodeakses = 'db';
                    $status = 1;
                    $stmt = $koneksi->prepare(
                        "INSERT INTO admin_mitra
                            (iduser, kodeakses, email, namamitra, whatsapp, telegram, facebook, instagram, alamat, provinsi, kota, kecamatan, status, tgl_daftar)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
                    );
                    $stmt->bind_param(
                        "issssssssssi",
                        $iduser, $kodeakses, $email, $namamitra, $whatsapp,
                        $telegram, $facebook, $instagram, $alamat,
                        $provinsi, $kabupaten, $kecamatan, $status
                    );
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // agen / reseller / marketer -> tabel berbeda tapi struktur field sama
                    $table = ROLE_MAP[$role]['table'];
                    // nama tabel berasal dari whitelist ROLE_MAP di atas, bukan dari input user,
                    // jadi aman untuk disisipkan langsung ke SQL di sini.
                    $idadminInt = (int)$idadmin;
                    $stmt = $koneksi->prepare(
                        "INSERT INTO {$table} (idadmin, iduser, namaagen, email, whatsapp, alamat)
                         VALUES (?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->bind_param(
                        "iissss",
                        $idadminInt, $iduser, $namamitra, $email, $whatsapp, $alamat
                    );
                    $stmt->execute();
                    $stmt->close();
                }

                $koneksi->commit();
                header('Location: input_mitra.php?status=success');
                exit();
            } catch (Throwable $e) {
                $koneksi->rollback();
                $errors[] = 'Gagal menyimpan data: ' . $e->getMessage();
            }
        }
    }
}

// --- Ambil daftar Distributor (untuk dropdown induk Agen/Reseller/Marketer) ---
$distributors = [];
$distRes = $koneksi->query("SELECT idadmin, namamitra FROM admin_mitra ORDER BY namamitra ASC");
if ($distRes) {
    while ($r = $distRes->fetch_assoc()) {
        $distributors[] = $r;
    }
}

// --- Ambil daftar Provinsi (untuk form Distributor) ---
$provinces = [];
$provRes = $koneksi->query("SELECT province_id, province_name FROM tb_ro_provinces ORDER BY province_name ASC");
if ($provRes) {
    while ($r = $provRes->fetch_assoc()) {
        $provinces[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>WNJ.ID</title>

  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .role-card {
      max-width: 760px;
    }
    .role-selector .btn-check + label {
      cursor: pointer;
    }
    .field-section {
      display: none;
    }
    .field-section.active {
      display: block;
    }
    .spinner-overlay {
      position: fixed;
      inset: 0;
      background: rgba(255,255,255,0.7);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
    }
    .spinner-overlay.show { display: flex; }
  </style>
</head>

<body id="page-top">

  <div id="wrapper">

    <?php include "sidebar.php"; ?>

    <div class="container-fluid">

      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
      </div>

      <h3 class="mb-4"><strong>Input Data Mitra</strong></h3>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger role-card">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
              <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="card role-card shadow-sm">
        <div class="card-body">

          <div class="form-group role-selector mb-4">
            <label class="d-block mb-2"><strong>Jenis Mitra</strong></label>
            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
              <label class="btn btn-outline-primary role-btn active">
                <input type="radio" name="role_display" value="distributor" checked> Distributor
              </label>
              <label class="btn btn-outline-primary role-btn">
                <input type="radio" name="role_display" value="agen"> Agen
              </label>
              <label class="btn btn-outline-primary role-btn">
                <input type="radio" name="role_display" value="reseller"> Reseller
              </label>
              <label class="btn btn-outline-primary role-btn">
                <input type="radio" name="role_display" value="marketer"> Marketer
              </label>
            </div>
          </div>

          <form method="post" enctype="multipart/form-data" id="mitraForm">
            <input type="hidden" name="role" id="roleInput" value="distributor">

            <!-- Dropdown Distributor induk: hanya untuk Agen/Reseller/Marketer -->
            <div class="field-section" data-group="parent">
              <div class="form-group">
                <label for="idadmin">Pilih Distributor (DB) Induk</label>
                <select class="form-control" id="idadmin" name="idadmin">
                  <option value="" disabled selected>~Pilih Distributor~</option>
                  <?php foreach ($distributors as $d): ?>
                    <option value="<?php echo (int)$d['idadmin']; ?>"><?php echo htmlspecialchars($d['namamitra']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required minlength="6">
            </div>

            <div class="form-group">
              <label id="namaLabel">Nama Mitra</label>
              <input type="text" name="namamitra" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Whatsapp</label>
              <input type="number" name="whatsapp" class="form-control" required>
            </div>

            <!-- Field tambahan: hanya untuk Distributor -->
            <div class="field-section active" data-group="distributor-extra">
              <div class="form-group">
                <label>Telegram</label>
                <input type="text" name="telegram" class="form-control">
              </div>
              <div class="form-group">
                <label>Facebook</label>
                <input type="text" name="facebook" class="form-control">
              </div>
              <div class="form-group">
                <label>Instagram</label>
                <input type="text" name="instagram" class="form-control">
              </div>
              <div class="form-group">
                <label for="prov">Provinsi</label>
                <select class="form-control" id="prov" name="prov">
                  <option disabled selected value="">~Pilih Provinsi Tujuan~</option>
                  <?php foreach ($provinces as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['province_id'] . '|' . $p['province_name']); ?>">
                      <?php echo htmlspecialchars($p['province_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="kabupaten">Kota/Kabupaten</label>
                <select class="form-control" id="kabupaten" name="kabupaten"></select>
              </div>
              <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <select class="form-control" id="kecamatan" name="kecamatan"></select>
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Lengkap</label>
              <textarea name="alamat" class="form-control" required></textarea>
            </div>

            <button class="btn btn-primary" name="save" type="submit">Tambah</button>
          </form>

        </div>
      </div>

    </div>

    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright &copy; Your Website 2020</span>
        </div>
      </div>
    </footer>

  </div>

  <div class="spinner-overlay" id="spinnerOverlay">
    <div class="spinner-border text-primary" style="width:3rem;height:3rem" role="status"></div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

  <script type="text/javascript">
  $(document).ready(function () {

    // --- Toggle field sesuai role yang dipilih ---
    function applyRole(role) {
      $('#roleInput').val(role);

      var isDistributor = (role === 'distributor');

      // Section khusus distributor (telegram/fb/ig/provinsi cascade)
      $('[data-group="distributor-extra"]').toggleClass('active', isDistributor);
      $('#prov, #kabupaten, #kecamatan').prop('required', isDistributor);

      // Section dropdown distributor induk, untuk agen/reseller/marketer
      $('[data-group="parent"]').toggleClass('active', !isDistributor);
      // $('#idadmin').prop('required', !isDistributor);

      $('#namaLabel').text(isDistributor ? 'Nama Mitra (Distributor)' : 'Nama ' + role.charAt(0).toUpperCase() + role.slice(1));
    }

    $('.role-btn input[name="role_display"]').on('change', function () {
      applyRole($(this).val());
    });
    applyRole('distributor'); // state awal

    // --- Cascading Provinsi -> Kabupaten -> Kecamatan (khusus Distributor) ---
    $('#prov').change(function () {
      var provinsi = $('#prov').val();
      $.ajax({
        type: 'GET',
        url: 'cek_kabupaten2.php',
        data: 'prov_id=' + provinsi,
        success: function (data) { $('#kabupaten').html(data); }
      });
    });

    $('#kabupaten').change(function () {
      var kabupaten = $('#kabupaten').val();
      $.ajax({
        type: 'GET',
        url: 'cek_kecamatan2.php',
        data: 'kabupaten_id=' + kabupaten,
        success: function (data) { $('#kecamatan').html(data); }
      });
    });

    // --- Spinner saat submit ---
    $('#mitraForm').on('submit', function () {
      $('#spinnerOverlay').addClass('show');
    });

    // --- Alert sukses dari redirect (?status=success), lalu bersihkan URL ---
    var params = new URLSearchParams(window.location.search);
    if (params.get('status') === 'success') {
      alert('Data berhasil disimpan.');
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.pathname);
      }
    }
  });
  </script>

</body>

</html>