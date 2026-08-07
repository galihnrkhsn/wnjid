<?php 
session_start();

include 'koneksi.php';
include 'assets/components/Sessions/sesDistri.php';

// if(!isset($_SESSION["admin_mitra"])){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//    echo "<script>location='login2.php';</script>";
//    header('location:login2.php');
//    exit();
// }

$idsupport=$_GET["idsupport"];
$ambil=$koneksi->query("SELECT * FROM support_ticket where idsupport='$idsupport'"); 
       $data=$ambil->fetch_assoc();
?>
<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- END NAVBAR -->

    <!-- MAIN SECTION -->
  <div class="container">
    <h2 class="mb-4 mt-12">Support Ticket</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="select1">Masalah:</label>
            <select name="masalah" class="form-control">
              <option selected="<?php echo $data['masalah'] ?>"><?php echo $data["masalah"] ?></option>
              <option>Retur & Refund</option>
              <option>Barang Tidak Sampai</option>
              <option>Masalah Website</option>
              <option>Service CS</option>
              <option>Lain-lain</option>
            </select>
        </div>
        <div class="form-group">
          <label>Nama CS</label>
            <select name="namacs" class="form-control">
              <option selected="<?php echo $data['namacs'] ?>"><?php echo $data["namacs"] ?></option>
              <option>Yara</option>
              <option>Intan</option>
              <option>Daniar</option>
              <option>Zahra</option>
              <option>Tya</option>
              <option>IT Support</option>
              <option>Lainnya</option>
            </select>
        </div>
        <div class="form-group">
            <label for="text">Note</label>
            <textarea name="note" class="form-control"><?php echo $data["note"]; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
        <a class="btn btn-default" href='return'>Batal</a>
    </form>
</div>

<?php
include 'koneksi.php';

// Ambil data yang akan diedit berdasarkan ID yang diterima dari parameter URL
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $koneksi->query("SELECT * FROM support_ticket WHERE idsupport = $id");
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $masalah = $data['masalah'];
        $namacs = $data['namacs'];
        $note = $data['note'];
    } else {
        echo "Data tidak ditemukan.";
        exit; // Stop eksekusi script jika data tidak ditemukan
    }
} else {
    // Mode create, set nilai awal untuk variabel
    $masalah = "";
    $namacs = "";
    $note = "";
}

if (isset($_POST["tambah"])) {
    // Ambil Data yang Dikirim dari Form
    $idadmin = $_SESSION["idadmin"];
    $masalah = $_POST['masalah'];
    $namacs = $_POST['namacs'];
    $note = addslashes(htmlspecialchars($_POST['note']));

    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        // Jika sedang mode edit, lakukan update data
        $koneksi->query("UPDATE `support_ticket` SET `masalah`='$masalah', `namacs`='$namacs', `note`='$note' WHERE `idsupport`='$id'");
        echo "<script>alert('Data berhasil diupdate');</script>";
    } else {
        // Jika tidak ada parameter action=edit, lakukan insert data baru
        $status = $_POST['status']; // Apakah ini status tiket? Anda perlu menyesuaikan dengan struktur tabel Anda
        $koneksi->query("INSERT INTO `support_ticket` (`idsupport`,`idadmin`, `masalah`, `namacs`, `tgl`,`note`, `status`) VALUES 
      (null,'$idadmin','$masalah','$namacs',NOW(),'$note','Ticket Diajukan')");
        echo "<script>alert('Data berhasil ditambahkan');</script>";
    }

    echo "<script>location='return'</script>";
}
?>
</body>
</html>  