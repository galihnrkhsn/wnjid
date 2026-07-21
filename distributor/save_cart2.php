<?php
	session_start();
	if(isset($_POST['save'])){
	    include "koneksi.php";
	    
        $idproduk = $_POST["idproduk"];
        $idkeranjangubah = $_POST["idkeranjangubah"];
		$idprodukubah = $_POST["idprodukubah"];
		$idmitra = $_POST["idmitra"];
		$jmlh=$_POST["jmlh"];
		$jmlhbaru=$_POST["jmlhbaru"];
		$harga=$_POST["harga"];
		$stock=$_POST["stock"];
        $jumlah_dipilih=count($idprodukubah);
    
         for($x=0;$x<$jumlah_dipilih;$x++){
            $selisih[$x]=$jmlhbaru[$x]-$jmlh[$x];

            if($selisih[$x]<=$stock[$x]){
            $koneksi->query("UPDATE keranjang SET jmlh='$jmlhbaru[$x]',subtotal=$harga[$x]*$jmlhbaru[$x] 
                             where idkeranjang='$idkeranjangubah[$x]' ");
            $total[$x]=$stock[$x]-$selisih[$x];
            $koneksi->query("UPDATE produk SET stock='$total[$x]' where idproduk='$idprodukubah[$x]' ");
            $_SESSION['message'] = 'Keranjang Berhasil di Simpan ';
            //echo "<script>alert('$selisih[$x]')</script>";
            echo "<script>location='newcart.php';</script>";
            }

            if($selisih[$x]>=$stock[$x]){
            $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, 
                                Silahkan Periksa Lagi Stock yang Tersedia';
                               // echo $selisih[$x];
                               /// echo "<script>alert('$selisih[$x]')</script>";
            echo "<script>location='newcart.php';</script>";
            
        
            }
        }
    }
	
		else if(isset($_POST['checkout'])){
		include "koneksi.php";

        // Ambil Data yang Dikirim dari Form
        date_default_timezone_set('Asia/Jakarta');
        $today = date("mdHis");
        $idmitra = $_POST["idmitra"];
        $idkeranjang = $_POST["idkeranjang"];

            if ($idkeranjang=='') {
                $_SESSION['message'] = 'Check salah satu barang yang ingin di checkout';
                                               // echo $selisih[$x];
                                               /// echo "<script>alert('$selisih[$x]')</script>'";
                echo "<script>location='newcart.php';</script>";
                return false;
            } 

        //$total=$_POST[$total];    
        $jumlah_dipilih=count($idkeranjang);
        $berat=0;
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

            $koneksi->query("insert into ordermitra (idorder,idmitra,idproduk,harga,jumlah,subtotal,tgl,invoice,status,payment,berat) values(null,'$idmitra','$idproduk','$harga','$jmlhbaru','$subtotal',NOW(),'$idmitra$today','Pending','Belum Bayar','$berattotal')");
           	$koneksi->query("DELETE from keranjang where idkeranjang='$idkeranjang[$x]'");

                $berat=$berat+$berattotal;

            }
            
        	header("location: formpengiriman.php?id=$idmitra$today&berat=$berat");    
		    
		}   


?>
