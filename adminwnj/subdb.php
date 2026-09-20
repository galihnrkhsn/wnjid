<?php
session_start();
include 'koneksi.php';

// --- AUTH GUARD (dijalankan sebelum output apapun) ---
if (!isset($_SESSION["administrator"])) {
    header('Location: login.php');
    exit();
}

// --- HANDLE RESET PASSWORD (dipindah ke atas, sebelum HTML, pakai prepared statement) ---
$resetMessage = null;
$resetIsError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
    $idmitraagen = $_POST['id'] ?? null;

    if ($idmitraagen === null || !ctype_digit((string)$idmitraagen)) {
        $resetMessage = 'ID tidak valid';
        $resetIsError = true;
    } else {
        $stmt = $koneksi->prepare("SELECT email, iduser FROM mitraagen WHERE idmitraagen = ?");
        $stmt->bind_param("i", $idmitraagen);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $dataDb = $result->fetch_assoc();
            $email  = $dataDb['email'];
            $idUser = $dataDb['iduser'];

            if ($email) {
                $newPassword    = $email . '_' . $idmitraagen;
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $update = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $hashedPassword, $idUser);

                if ($update->execute()) {
                    $resetMessage = 'Password berhasil diubah';
                } else {
                    $resetMessage = 'Gagal mengubah password';
                    $resetIsError = true;
                }
                $update->close();
            } else {
                $resetMessage = 'Email tidak ditemukan';
                $resetIsError = true;
            }
        } else {
            $resetMessage = 'Data admin tidak ditemukan';
            $resetIsError = true;
        }
        $stmt->close();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    /* --- Compact table --- */
    #agen table.table, #reseller table.table, #marketer table.table {
      font-size: 0.85rem;
    }
    #agen table.table th, #agen table.table td,
    #reseller table.table th, #reseller table.table td,
    #marketer table.table th, #marketer table.table td {
      padding: 0.4rem 0.5rem;
      vertical-align: middle;
      white-space: nowrap;
    }
    .table-responsive .btn-sm { padding: 0.15rem 0.4rem; font-size: 0.75rem; }

    /* --- Loading overlay for DataTables processing indicator --- */
    .table-responsive { position: relative; min-height: 80px; }
    div.dataTables_wrapper div.dataTables_processing {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      width: 100%; height: 100%;
      background: rgba(255, 255, 255, 0.75);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      margin: 0;
      padding: 0;
    }
    div.dataTables_wrapper div.dataTables_processing .spinner-border {
      width: 2.5rem;
      height: 2.5rem;
    }

    /* --- Spinner shown while a tab's table is being initialized for the first time --- */
    .tab-loading-overlay {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 0;
    }
  </style>
</head>

<body id="page-top" class="sidebar-toggled">

  <div id="wrapper">

    <?php include "sidebar.php"; ?>

    <div class="container-fluid">

      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
      </div>

      <div>
        <div class="d-flex justify-content-between align-items-center mb-3"> 
          <h3><strong>Mitra</strong></h3><br>
          <a href="input_mitra.php" type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Mitra</a>
        </div>
      </div>

      <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#agen">Agen</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reseller">Reseller</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#marketer">Marketer</a></li>
      </ul>

      <div class="tab-content mt-3">

        <!-- AGEN -->
        <div id="agen" class="tab-pane fade show active" role="tabpanel">
          <h3 class="mb-0">Data Agen</h3>
          <button class="btn btn-outline-success btn-icon-split mb-3" onclick="window.location.href='subdb/excel_agen.php';">
            <span class="icon text-white-50 bg-success"><i class="fas fa-download" style="margin-top:20%;"></i></span>
            <span class="text">Download Excel Agen</span>
          </button>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" id="tbagen" style="width:100%">
              <thead>
                <tr>
                  <th style="text-align:center">No</th>
                  <th style="text-align:center">Nama SubDB</th>
                  <th style="text-align:center">Nama DB</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center">Whatsapp</th>
                  <th style="text-align:center">Alamat</th>
                  <th style="text-align:right"><i class="fas fa-cog"></i></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- RESELLER -->
        <div id="reseller" class="tab-pane fade">
          <h3 class="mb-3">Data Reseller</h3>
          <button class="btn btn-outline-success btn-icon-split mb-3" onclick="window.location.href='subdb/excel_reseller.php';">
            <span class="icon text-white-50 bg-success"><i class="fas fa-download" style="margin-top:20%;"></i></span>
            <span class="text">Download Excel Reseller</span>
          </button>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" id="tbreseller" style="width:100%">
              <thead>
                <tr>
                  <th style="text-align:center">No</th>
                  <th style="text-align:center">Nama SubDB</th>
                  <th style="text-align:center">Nama DB</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center">Whatsapp</th>
                  <th style="text-align:center">Alamat</th>
                  <th style="text-align:right"><i class="fas fa-cog"></i></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

        <!-- MARKETER -->
        <div id="marketer" class="tab-pane fade">
          <h3 class="mb-3">Data Marketer</h3>
          <button class="btn btn-outline-success btn-icon-split mb-3" onclick="window.location.href='subdb/excel_marketer.php';">
            <span class="icon text-white-50 bg-success"><i class="fas fa-download" style="margin-top:20%;"></i></span>
            <span class="text">Download Excel Marketer</span>
          </button>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm" id="tbmarketer" style="width:100%">
              <thead>
                <tr>
                  <th style="text-align:center">No</th>
                  <th style="text-align:center">Nama SubDB</th>
                  <th style="text-align:center">Nama DB</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center">Whatsapp</th>
                  <th style="text-align:center">Alamat</th>
                  <th style="text-align:right"><i class="fas fa-cog"></i></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
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

  <!-- Hidden form template used by JS to submit reset-password requests -->
  <form id="resetPasswordForm" method="post" style="display:none">
    <input type="hidden" name="id" id="resetPasswordId" value="">
    <input type="hidden" name="resetPassword" value="1">
  </form>

  <!-- Logout Modal-->
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

  <?php include "settingdatatables.php"; ?>

  <script>
  $(function () {
    <?php if ($resetMessage): ?>
    alert(<?php echo json_encode($resetMessage); ?>);
    <?php if (!$resetIsError): ?>
    // reload tanpa membawa POST data (hindari resubmit saat refresh)
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.pathname);
    }
    <?php endif; ?>
    <?php endif; ?>

    function actionColumn(idField) {
      return function (data, type, row) {
        return '<form method="post" class="reset-form" style="display:inline">' +
                 '<input type="hidden" name="id" value="' + row[idField] + '">' +
                 '<input type="hidden" name="resetPassword" value="1">' +
                 '<button type="submit" class="btn btn-info btn-sm reset-btn"><span class="fa fa-power-off"></span></button>' +
               '</form> ' +
               '<a href="ubah_password.php?id=' + row[idField] + '" class="btn btn-warning btn-sm"><span class="fa fa-pencil"></span></a>';
      };
    }

    // Spinner HTML shown by DataTables while it's fetching a page/searching/sorting
    var spinnerHtml = '<div class="spinner-border text-primary" role="status">' +
                         '<span class="sr-only">Loading...</span>' +
                       '</div>';

    // Server-side DataTables config generator
    function makeTable(selector, ajaxUrl, idField) {
      return $(selector).DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        language: { processing: spinnerHtml },
        ajax: { url: ajaxUrl, type: 'POST' },
        columns: [
          { data: null, orderable: false, searchable: false, render: function (d, t, r, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
          { data: 'nama_subdb' },
          { data: 'nama_db' },
          { data: 'email' },
          { data: 'whatsapp' },
          { data: 'alamat' },
          { data: null, orderable: false, searchable: false, render: actionColumn(idField) }
        ]
      });
    }

    // Load Agen immediately (visible tab), lazy-load others when their tab is shown
    var tables = { agen: null, reseller: null, marketer: null };
    tables.agen = makeTable('#tbagen', 'subdb/ajax_agen.php', 'id');

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
      // show a spinner in the tab pane the moment the user clicks, before the table exists yet
      var target = $(e.target).attr('href');
      var alreadyLoaded = (target === '#reseller' && tables.reseller) ||
                          (target === '#marketer' && tables.marketer) ||
                          (target === '#agen');
      if (!alreadyLoaded) {
        $(target).find('.table-responsive').prepend(
          '<div class="tab-loading-overlay">' + spinnerHtml + '</div>'
        );
      }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr('href');
      if (target === '#reseller' && !tables.reseller) {
        tables.reseller = makeTable('#tbreseller', 'subdb/ajax_reseller.php', 'id');
        tables.reseller.on('draw.dt', function () { $(target).find('.tab-loading-overlay').remove(); });
      } else if (target === '#marketer' && !tables.marketer) {
        tables.marketer = makeTable('#tbmarketer', 'subdb/ajax_marketer.php', 'id');
        tables.marketer.on('draw.dt', function () { $(target).find('.tab-loading-overlay').remove(); });
      }
    });

    // Confirm before submitting a reset-password form (event delegation, works for AJAX-rendered rows)
    $(document).on('submit', '.reset-form', function () {
      return confirm('Yakin ingin reset password?');
    });
  });
  </script>

</body>
</html>