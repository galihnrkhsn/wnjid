<?php
	session_start();
	if(isset($_POST['save'])){
	    include "koneksi.php";
	
		$idproduk = $_POST["idproduk"];
		$idmitra = $_POST["idmitra"];
        $idkeranjang = $_POST["idkeranjang"];
        $idkeranjangubah = $_POST["idkeranjangubah"];
        $idprodukubah = $_POST["idprodukubah"];
		$jmlh=$_POST["jmlh"];
		$jmlhbaru=$_POST["jmlhbaru"];
		$harga=$_POST["harga"];
		//$stock=$_POST["stock"];
		 $jumlah_dipilih=count($idprodukubah);
            
         for($x=0;$x<$jumlah_dipilih;$x++){
            $datastock=$koneksi->query("SELECT stock FROM produk WHERE idproduk='$idprodukubah[$x]' ");
            $tampil=$datastock->fetch_assoc();
            $stock=$tampil["stock"];

            $selisih[$x]=$jmlhbaru[$x]-$jmlh[$x];

            if($selisih[$x]<=$stock){
            $koneksi->query("UPDATE keranjang SET jmlh='$jmlhbaru[$x]',subtotal=$harga[$x]*$jmlhbaru[$x] 
                             where idkeranjang='$idkeranjangubah[$x]' ");
            $total[$x]=$stock-$selisih[$x];
            $koneksi->query("UPDATE produk SET stock='$total[$x]' where idproduk='$idprodukubah[$x]' ");
            $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
            //echo "<script>alert('$selisih[$x]')</script>";
            echo "<script>location='view_cart.php';</script>";
            }

            if($selisih[$x]>=$stock){
            $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, 
                                Silahkan Periksa Lagi Stock yang Tersedia';
                               // echo $selisih[$x];
                               /// echo "<script>alert('$selisih[$x]')</script>";
            echo "<script>location='view_cart.php';</script>";
            
        
            }
        }
    }
	
		else if(isset($_POST['checkout'])){
		include "koneksi.php";

        // Ambil Data yang Dikirim dari Form
        date_default_timezone_set('Asia/Jakarta');
        $today = date("mdHis");
        $idmitra = $_POST["idmitra"];
        $id='A';
        $iddb = $_POST["idadmin"];
        $idkeranjang = $_POST["idkeranjang"];
        //$total=$_POST[$total];    
        $jumlah_dipilih=count($idkeranjang);
        $berat=0;
        if ($idkeranjang=='') {
                $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
                                               // echo $selisih[$x];
                                               /// echo "<script>alert('$selisih[$x]')</script>'";
                echo "<script>location='view_cart.php';</script>";
                return false;
            } 


            $s='AV';


            for($z=0;$z<$jumlah_dipilih;$z++){
                $cekdata=$koneksi->query("SELECT keranjang.jmlh FROM keranjang
                                WHERE idkeranjang='$idkeranjang[$z]'");
                $cektampilkan=$cekdata->fetch_assoc();
                $cekjmlh +=$cektampilkan["jmlh"];

            }            
$hasil_mod = fmod($cekjmlh, 3);

if ($cekjmlh % 3 <> 0) {
$_SESSION['message'] = 'Jumlah Produk Harus Kelipatan 3';
echo "<script>location='view_cart.php';</script>";
return false;    
}
     
// $kategori_1=0;
// $kategori_2=0; 
// $kategori_3=0;
// $kategori_4=0;
// $kategori_5=0;
// $kategori_6=0;
// $kategori_7=0;
// $kategori_8=0;

// $kategori_9=0;
// $kategori_10=0;
// $kategori_11=0;
// $kategori_12=0;

// $kategori_13=0;
// $kategori_14=0;

//            for($y=0;$y<$jumlah_dipilih;$y++){
//                 $cekdata_kategori=$koneksi->query("SELECT produk.namaproduk, produk.idkategori, keranjang.jmlh FROM keranjang
//                                 JOIN produk ON produk.idproduk = keranjang.idproduk
//                                 WHERE idkeranjang='$idkeranjang[$y]'");
//                 $cek_kategori=$cekdata_kategori->fetch_assoc();
//                 $idkategori= $cek_kategori['idkategori'];
//                 $namaproduk= $cek_kategori['namaproduk'];
//                 $jmlah_ker=$cek_kategori['jmlh'];
                 
//                 if ($cek_kategori['idkategori']==51) {
//                     $kategori_1 = $kategori_1+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==52) {
//                     $kategori_2 = $kategori_2+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==53) {
//                     $kategori_3 = $kategori_3+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==54) {
//                     $kategori_4 = $kategori_4+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==55) {
//                     $kategori_5 = $kategori_5+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==56) {
//                     $kategori_6 = $kategori_6+$jmlah_ker;
//                 }   
//                 if ($cek_kategori['idkategori']==57) {
//                     $kategori_7 = $kategori_7+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==58) {
//                     $kategori_8 = $kategori_8+$jmlah_ker;
//                 }  

//                 if ($cek_kategori['idkategori']==59) {
//                     $kategori_9 = $kategori_9+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==60) {
//                     $kategori_10 = $kategori_10+$jmlah_ker;
//                 }   
//                 if ($cek_kategori['idkategori']==61) {
//                     $kategori_11 = $kategori_11+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==62) {
//                     $kategori_12 = $kategori_12+$jmlah_ker;
//                 }   

//                 if ($cek_kategori['idkategori']==63) {
//                     $kategori_13 = $kategori_13+$jmlah_ker;
//                 }
//                 if ($cek_kategori['idkategori']==64) {
//                     $kategori_14 = $kategori_14+$jmlah_ker;
//                 }                                                             
//             }

// if ($kategori_1!=$kategori_2)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }
// if ($kategori_3!=$kategori_4)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }

// if ($kategori_5!=$kategori_6)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }
// if ($kategori_7!=$kategori_8)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }
// if ($kategori_9!=$kategori_10)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }
// if ($kategori_11!=$kategori_12)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }

// if ($kategori_13!=$kategori_14)  {
//     echo "<script>alert('Produk tidak sesuai')</script>'";  
//     echo "<script>location='view_cart.php';</script>";
//     return false;
// }
//  echo "<script>alert('Oke')</script>'";  
//  echo "<script>location='view_cart.php';</script>";            


            for($x=0;$x<$jumlah_dipilih;$x++){
                $beratsatu=0;
                $data=$koneksi->query("SELECT keranjang.idproduk,keranjang.jmlh,keranjang.harga,
                                keranjang.subtotal,produk.berat FROM keranjang
                                INNER JOIN produk ON keranjang.idproduk=produk.idproduk
                                WHERE idkeranjang='$idkeranjang[$x]'");
                $tampilkan=$data->fetch_assoc();
                $idproduk=$tampilkan["idproduk"];
                $harga = $tampilkan["harga"];
                $jmlhbaru = $tampilkan["jmlh"];
                $subtotal = $tampilkan["subtotal"];
                $beratsatu = $tampilkan["berat"];
                $berattotal = $beratsatu*$jmlhbaru;

$koneksi->query("INSERT into orderagen (idorder,idmitraagen,iddb,idproduk,harga,jumlah,subtotal,tgl,invoice,status,payment,berat) 
                    values
                    (null,'$idmitra','$iddb','$idproduk','$harga','$jmlhbaru','$subtotal',NOW(),'$s$idmitra$today','Pending','Belum Bayar','$berattotal')");
               	$koneksi->query("DELETE from keranjang where idkeranjang='$idkeranjang[$x]'");

               $berat=$berat+$berattotal;     
            }
            
        	header("location: formpengiriman.php?id=$s$idmitra$today&berat=$berat");    
		    
		}   
?>
