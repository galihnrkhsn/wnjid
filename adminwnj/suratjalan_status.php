
<?php 

include "koneksi.php";


        if(isset($_POST['but_update'])){


            if(isset($_POST['update_status'])){
                foreach($_POST['update_status'] as $updateid){
$invoice = $_POST['invoice'.$updateid];
                   
                      // echo "<script>alert('$updateid');</script>";
                    $sqlnya = $koneksi->query("UPDATE surat_jalan set Status='Ambil Barang', no_sj= null where invoice='$invoice'");
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

                                                          