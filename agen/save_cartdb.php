<?php
	session_start();
	if(isset($_POST['save'])){
	    include "koneksi.php";
	
		$idprodukdb = $_POST["idprodukdb"];
		$idmitra = $_POST["idmitra"];
		$jmlh=$_POST["jmlh"];
		$jmlhbaru=$_POST["jmlhbaru"];
		$harga=$_POST["harga"];
		$stock=$_POST["stock"];
		 $jumlah_dipilih=count($idprodukdb);
            
            for($x=0;$x<$jumlah_dipilih;$x++){
                $koneksi->query("UPDATE keranjangdb SET jmlh='$jmlhbaru[$x]',subtotal=$harga[$x]*$jmlhbaru[$x] where idprodukdb='$idprodukdb[$x]' and idagen='$idmitra' ");
                $jmlhlagi=$jmlhbaru[$x]-$jmlh[$x];
                $total=$stock[$x]-$jmlhlagi;
		    	$koneksi->query("UPDATE produkdb SET stock='$total' where idprodukdb='$idprodukdb[$x]' ");
            }
            
		$_SESSION['message'] = 'keranjang Berhasil di Simpan';
		header('location: view_cartdb.php');
	}
	
		else if(isset($_POST['checkout'])){
		include "koneksi.php";

        // Ambil Data yang Dikirim dari Form
        date_default_timezone_set('Asia/Jakarta');
        $today = date("dhs");
        $idmitra = $_POST["idmitra"];
        $id='AD';
        $iddb = $_POST["idadmin"];
        $idprodukdb = $_POST["idprodukdb"];
        $harga = $_POST["harga"];
        $jmlhbaru = $_POST["jmlhbaru"];
        $subtotal = $_POST["subtotal"];
        $berat = $_POST["berat"];
        //$total=$_POST[$total];    
        $jumlah_dipilih=count($idprodukdb);
        
            for($x=0;$x<$jumlah_dipilih;$x++){
                $koneksi->query("insert into orderagendb (idorderdb,idmitraagen,iddb,idprodukdb,harga,jumlah,subtotal,tgl,invoice,status,payment) values(null,'$idmitra','$iddb','$idprodukdb[$x]','$harga[$x]','$jmlhbaru[$x]','$subtotal[$x]',NOW(),'$id$idmitra$today','Pending','Belum Bayar')");
               	$koneksi->query("delete from keranjangdb where idagen='$idmitra'");

            }
            
        	header("location: formpengirimandb.php?id=$id$idmitra$today&berat=$berat");    
		    
		}   
?>
