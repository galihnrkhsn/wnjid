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
        $idkeranjang = $_POST["idkeranjang"];
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
            
            }

            if($selisih[$x]>=$stock){
            $_SESSION['message'] = 'Stock Kami tidak mencukupi jumlah yang diminta, 
                                Silahkan Periksa Lagi Stock yang Tersedia';
                               // echo $selisih[$x];
                               /// echo "<script>alert('$selisih[$x]')</script>";
        
            }
        }
        echo "<script>location='view_cart.php';</script>";
    }
	
		else if(isset($_POST['checkout'])){
		include "koneksi.php";

        // Ambil Data yang Dikirim dari Form
        date_default_timezone_set('Asia/Jakarta');
        $today = date("mdHis");
        $idmitra = $_POST["idmitra"];
    $sql = "SELECT * FROM mitrareseller WHERE idmitrareseller='$idmitra' ";
    $query = $koneksi->query($sql);
    $datamitra = $query->fetch_assoc();

        $iddb = $datamitra["idadmin"];
        $idagen = $datamitra["idmitraagen"];

        $id='R';
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


            $s='RS';

            $invoice=$s.'-'.$idmitra.'-'.$today;
            for($z=0;$z<$jumlah_dipilih;$z++){
                $cekdata=$koneksi->query("SELECT keranjang.jmlh FROM keranjang
                                WHERE idkeranjang='$idkeranjang[$z]'");
                $cektampilkan=$cekdata->fetch_assoc();
                $cekjmlh +=$cektampilkan["jmlh"];

            }            
$hasil_mod = fmod($cekjmlh, 2);
if ($hasil_mod==1) {
$_SESSION['message'] = 'Jumlah Produk Harus Genap';
echo "<script>location='view_cart.php';</script>";
return false;    
}
     
$kategori_1=0;
$kategori_2=0; 
$kategori_3=0;
$kategori_4=0;
$kategori_5=0;
$kategori_6=0;
$kategori_7=0;
$kategori_8=0;

$kategori_9=0;
$kategori_10=0;
$kategori_11=0;
$kategori_12=0;

$kategori_13=0;
$kategori_14=0;
$kategori_15=0;
$kategori_16=0;

$kategori_17=0;
$kategori_18=0;
$kategori_19=0;
$kategori_20=0;

$kategori_21=0;
$kategori_22=0;
$kategori_23=0;
$kategori_24=0;

           for($y=0;$y<$jumlah_dipilih;$y++){
                $cekdata_kategori=$koneksi->query("SELECT produk.namaproduk, produk.idkategori, keranjang.jmlh FROM keranjang
                                JOIN produk ON produk.idproduk = keranjang.idproduk
                                WHERE idkeranjang='$idkeranjang[$y]'");
                $cek_kategori=$cekdata_kategori->fetch_assoc();
                $idkategori= $cek_kategori['idkategori'];
                $namaproduk= $cek_kategori['namaproduk'];
                $jmlah_ker=$cek_kategori['jmlh'];
                 
               // if ($cek_kategori['idkategori']==51) {
                //     $kategori_1 = $kategori_1+$jmlah_ker;
                // }
                // if ($cek_kategori['idkategori']==52) {
                //     $kategori_2 = $kategori_2+$jmlah_ker;
                // }
                if ($cek_kategori['idkategori']==53) {
                    $kategori_3 = $kategori_3+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==54) {
                    $kategori_4 = $kategori_4+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==55) {
                    $kategori_5 = $kategori_5+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==56) {
                    $kategori_6 = $kategori_6+$jmlah_ker;
                }   
                if ($cek_kategori['idkategori']==57) {
                    $kategori_7 = $kategori_7+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==58) {
                    $kategori_8 = $kategori_8+$jmlah_ker;
                }  

                if ($cek_kategori['idkategori']==59) {
                    $kategori_9 = $kategori_9+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==60) {
                    $kategori_10 = $kategori_10+$jmlah_ker;
                }   
                if ($cek_kategori['idkategori']==61) {
                    $kategori_11 = $kategori_11+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==62) {
                    $kategori_12 = $kategori_12+$jmlah_ker;
                }   

                if ($cek_kategori['idkategori']==63) {
                    $kategori_13 = $kategori_13+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==64) {
                    $kategori_14 = $kategori_14+$jmlah_ker;
                }       


                if ($cek_kategori['idkategori']==65) {
                    $kategori_15 = $kategori_15+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==66) {
                    $kategori_16 = $kategori_16+$jmlah_ker;
                }       


                if ($cek_kategori['idkategori']==67) {
                    $kategori_17 = $kategori_17+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==68) {
                    $kategori_18 = $kategori_18+$jmlah_ker;
                }       


                if ($cek_kategori['idkategori']==69) {
                    $kategori_19 = $kategori_19+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==70) {
                    $kategori_20 = $kategori_20+$jmlah_ker;
                }       


                if ($cek_kategori['idkategori']==71) {
                    $kategori_21 = $kategori_21+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==72) {
                    $kategori_22 = $kategori_22+$jmlah_ker;
                }  

                if ($cek_kategori['idkategori']==73) {
                    $kategori_23 = $kategori_23+$jmlah_ker;
                }
                if ($cek_kategori['idkategori']==74) {
                    $kategori_24 = $kategori_24+$jmlah_ker;
                }                                                               
            }
            
$hasil_3 = fmod($kategori_3, 2);
if ($hasil_3==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_4 = fmod($kategori_4, 2);
if ($hasil_4==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_5 = fmod($kategori_5, 2);
if ($hasil_5==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_6 = fmod($kategori_6, 2);
if ($hasil_6==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_7 = fmod($kategori_7, 2);
if ($hasil_7==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_8 = fmod($kategori_8, 2);
if ($hasil_8==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_9 = fmod($kategori_9, 2);
if ($hasil_9==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_10 = fmod($kategori_10, 2);
if ($hasil_10==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_11 = fmod($kategori_11, 2);
if ($hasil_11==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_12 = fmod($kategori_12, 2);
if ($hasil_12==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_13 = fmod($kategori_13, 2);
if ($hasil_13==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_14 = fmod($kategori_14, 2);
if ($hasil_14==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_15 = fmod($kategori_15, 2);
if ($hasil_15==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_16 = fmod($kategori_16, 2);
if ($hasil_16==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_17 = fmod($kategori_17, 2);
if ($hasil_17==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}


$hasil_18 = fmod($kategori_18, 2);
if ($hasil_18==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_19 = fmod($kategori_19, 2);
if ($hasil_19==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_20 = fmod($kategori_20, 2);
if ($hasil_20==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_21 = fmod($kategori_21, 2);
if ($hasil_21==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_22 = fmod($kategori_22, 2);
if ($hasil_22==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_23 = fmod($kategori_23, 2);
if ($hasil_23==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}

$hasil_24 = fmod($kategori_24, 2);
if ($hasil_24==1) {
$_SESSION['message'] = 'Produk tidak sesuai';
echo "<script>location='view_cart.php';</script>";
return false;    
}


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

                $koneksi->query("INSERT into orderreseller (idorder,idmitrareseller,idmitraagen,iddb,idproduk,harga,jumlah,subtotal,tgl,invoice,status,payment,berat) 
                    values(null,'$idmitra','$idagen','$iddb','$idproduk','$harga','$jmlhbaru','$subtotal',NOW(),'$invoice','Pending','Belum Bayar','$berattotal')");
                $koneksi->query("DELETE from keranjang where idkeranjang='$idkeranjang[$x]'");

               $berat=$berat+$berattotal;      
            }
            
        	header("location: formpengiriman.php?id=$invoice&berat=$berat");    
		    
		}   
?>
