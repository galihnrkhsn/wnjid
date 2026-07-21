<!-- PEMBAYARAN BANK -->
  <?php
include "koneksi.php";
if(isset($_POST['simpanBank'])){

  date_defaultimezone_set('Asia/Jakarta');
  $tgl=date('H:i:s');
  $invoiceNilai=$_POST["invoice2"];
  $grandtotalNilai=$_POST["grandtotal2"];
  $bankpengirim=$_POST["bankpengirim"];
  $rekeningpengirim=$_POST["rekeningpengirim"];
  $jmlhtransfer=$_POST["jmlhtransfer"];
  $metodebayar=$_POST["metodebayar"];

  $hasil = $jmlhtransfer - $grandtotalNilai;
  if ($hasil<0) {
    echo "<script>alert('Jumlah Transfer Kurang!');</script>";
    return false;
  }

    $sqlpembayaran = $koneksi->query("INSERT INTO orderpembayaran (idpembayaran,invoice,bankpengirim,rekeningpengirim,jmlhtransfer,metodebayar,tgl,waktu) values
    (null,'$invoiceNilai','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar',NOW(),'$tgl')");
     $koneksi->query("UPDATE orderpengiriman SET total='$grandtotalNilai' where invoice='$invoiceNilai'");   
      $koneksi->query("UPDATE ordermitra SET status='Tunggu Confrim Admin', payment='Sudah Konfirmasi' where invoice='$invoiceNilai' ");  

      
      if ($sqlpembayaran) {
          echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
          echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
          }else{
            echo "<script>alert('Konfirmasi Pembayaran gagal');</script>";
            echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
          } 
        
  } 


// PEMBAYARAN SALDO
if(isset($_POST['simpanSaldo'])){
  $invoiceNilai=$_POST["invoice2"];
  $grandtotalNilai=$_POST["grandtotal2"];
  $saldo=$_POST["saldo"];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  date_defaultimezone_set('Asia/Jakarta');
  $tgl=date('H:i:s');
  

  $sqlpembayaran = $koneksi->query("INSERT INTO orderpembayaran (idpembayaran,invoice,bankpengirim,rekeningpengirim,jmlhtransfer,metodebayar,tgl,waktu) values
    (null,'$invoiceNilai',null,null,null,'Ambil Dari Saldo',NOW(),'$tgl')");
  $sqlpengiriman = $koneksi->query("UPDATE orderpengiriman SET total='$grandtotalNilai' where invoice='$invoiceNilai'");   
  $sqlstatus = $koneksi->query("UPDATE ordermitra SET status='Tunggu Confrim Admin', payment='Sudah Konfirmasi' where invoice='$invoiceNilai' ");  
  
  $sqlsaldo=$koneksi->query("INSERT INTO saldo (idsaldo,idadmin,tgl,transaksi,debit,credit)
      VALUES (null,'$idadmin',NOW(),'Pembayaran Order Invoice $invoiceNilai','0','$saldo')");

      
      if ($sqlsaldo) {
          echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
          echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
          }else{
            echo "<script>alert('Konfirmasi Pembayaran gagal');</script>";
            echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
          }          
        
  } 


// PEMBAYARAN SALDO BANK
if(isset($_POST['simpanSaldoBank'])){
  $invoiceNilai=$_POST["invoice2"];
  $grandtotalNilai=$_POST["grandtotal2"];
  $saldo=$_POST["saldo"];
  $sisa=$_POST["sisa"];
  $idadmin=$_SESSION["admin_mitra"]["idadmin"];
  date_defaultimezone_set('Asia/Jakarta');
  $tgl=date('H:i:s');
  $bankpengirim=$_POST["bankpengirim"];
  $rekeningpengirim=$_POST["rekeningpengirim"];
  $jmlhtransfer=$_POST["jmlhtransfer"];
  $metodebayar=$_POST["metodebayar"];

  $hasiljumlah = $saldo + $jmlhtransfer;
  
      if ($saldo>$sisa) {
          echo "<script>alert('Saldo melebihi Sisa Saldo');</script>";
          return false;
          }
      if ($hasiljumlah<$grandtotalNilai) {
          echo "<script>alert('Jumlah Transfer dan Saldo tidak cukup');</script>";
          return false;
          }else{
                $sqlpembayaran = $koneksi->query("INSERT INTO orderpembayaran (idpembayaran,invoice,bankpengirim,rekeningpengirim,jmlhtransfer,metodebayar,tgl,waktu) values
                  (null,'$invoiceNilai','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar',NOW(),'$tgl')");
                $koneksi->query("UPDATE orderpengiriman SET total='$grandtotalNilai' where invoice='$invoiceNilai'");   
                $koneksi->query("UPDATE ordermitra SET status='Tunggu Confrim Admin', payment='Sudah Konfirmasi' where invoice='$invoiceNilai' ");

                $sqlsaldo=$koneksi->query("INSERT INTO saldo (idsaldo,idadmin,tgl,transaksi,debit,credit)
                VALUES (null,'$idadmin',NOW(),'Pembayaran Order Invoice $invoiceNilai','0','$saldo')");

                    
                    if ($sqlpembayaran and $sqlsaldo) {
                        echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
                        echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
                        }else{
                          echo "<script>alert('Konfirmasi Pembayaran gagal');</script>";
                          echo "<script>location='detailorder.php?id=$invoiceNilai'</script>";
                        } 
                }               
        
  }



?>