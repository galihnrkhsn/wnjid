
<?php 

include "koneksi.php";


        if(isset($_POST['but_update'])){


            if(isset($_POST['update_status'])){
                foreach($_POST['update_status'] as $updateid){
$invoice = $_POST['invoice'.$updateid];
$no_sj = $_POST['no_sj'.$updateid];
                   
                      // echo "<script>alert('$updateid');</script>";
                    $sqlnya = $koneksi->query("UPDATE surat_jalan_po set Status='Ambil Barang', no_sj= null where no_sj='$no_sj'");
                }
                if ($sqlnya) {
                  echo "<script>alert('status berhasil diubah');</script>";
                  echo "<script>location='suratjalan_po.php';</script>";  
                  }else{
                    echo "<script>alert('status gagal diubah');</script>";
                    echo "<script>location='suratjalan_po.php';</script>";
                }
               

               
            }
    }

 ?>       

                                                          