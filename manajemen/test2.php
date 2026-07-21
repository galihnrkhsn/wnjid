<?
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';
    $namapo   = $_GET("namapo");
    $idmanage = $_SESSION["idmanage"];
    $tipe     = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();

    $datapo = $koneksi->query("SELECT namapo FROM poproduk WHERE idpoproduk='$namapo'");
    $tampilpo = $datapo->fetch_assoc(); 
      echo $tampilpo['namapo'];
?>