
<?php 

include "koneksi.php";


        if(isset($_POST['but_update_marketer'])){


            if(isset($_POST['update_status'])){
                foreach($_POST['update_status'] as $updateid){
$invoice = $_POST['invoice'.$updateid];
                   
                      // echo "<script>alert('$updateid');</script>";
                    $sqlnya = $koneksi->query("UPDATE surat_jalan_subdb set Status='Ambil Barang', no_sj= null where invoice='$invoice'");
                }
                if ($sqlnya) {
                  echo "<script>alert('status berhasil diubah');</script>";
                  echo "<script>location='suratjalan.php';</script>";  
                  }else{
                    echo "<script>alert('status gagal diubah');</script>";
                    echo "<script>location='suratjalan.php';</script>";
                }
               

               
            }
    }


        if(isset($_POST['but_update_reseller'])){


            if(isset($_POST['update_status'])){
                foreach($_POST['update_status'] as $updateid){
$invoice = $_POST['invoice'.$updateid];
                   
                      // echo "<script>alert('$updateid');</script>";
                    $sqlnya = $koneksi->query("UPDATE surat_jalan_subdb set Status='Ambil Barang', no_sj= null where invoice='$invoice'");
                }
                if ($sqlnya) {
                  echo "<script>alert('status berhasil diubah');</script>";
                  echo "<script>location='suratjalan.php';</script>";  
                  }else{
                    echo "<script>alert('status gagal diubah');</script>";
                    echo "<script>location='suratjalan.php';</script>";
                }
               

               
            }
    } 

        if(isset($_POST['but_update_agen'])){


            if(isset($_POST['update_status'])){
                foreach($_POST['update_status'] as $updateid){
$invoice = $_POST['invoice'.$updateid];
                   
                      // echo "<script>alert('$updateid');</script>";
                    $sqlnya = $koneksi->query("UPDATE surat_jalan_subdb set Status='Ambil Barang', no_sj= null where invoice='$invoice'");
                }
                if ($sqlnya) {
                  echo "<script>alert('status berhasil diubah');</script>";
                  echo "<script>location='suratjalan.php';</script>";  
                  }else{
                    echo "<script>alert('status gagal diubah');</script>";
                    echo "<script>location='suratjalan.php';</script>";
                }
               

               
            }
    }       

 ?>       

                                                          