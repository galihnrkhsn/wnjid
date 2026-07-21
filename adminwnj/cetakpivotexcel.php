<?php
include "koneksi.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Laporan PO</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->

      
        
                            <?php
                        
                                                     
                            mysqli_query($koneksi, "SET SESSION group_concat_max_len = 1000000");
                            //mysqli_query($koneksi, "SET @sql = NULL");
                            mysqli_query($koneksi, "SET @sql_dinamis = ( SELECT GROUP_CONCAT( DISTINCT CONCAT('SUM( IF(pomitra.idpodetail = ' , pomitra.idpodetail , ',pomitra.jumlah,0) ) AS variant_' , podetail.idpodetail ) ) FROM pomitra inner join podetail on pomitra.idpodetail=podetail.idpodetail where idpoproduk=25 
                                                    );");
                            $query_expression = "SET @sql = CONCAT('SELECT admin_mitra.namamitra, ', 
                                                    			  @sql_dinamis, ' 
                                                    		   FROM pomitra inner join admin_mitra on pomitra.idmitra=admin_mitra.idadmin where pomitra.idpoproduk=25
                                                    		   GROUP BY pomitra.idmitra  WITH ROLLUP'
                                                    	   );
                                                    	   PREPARE stmt FROM @sql; \n
                                                    	   EXECUTE stmt;";
                                                    
                                                   // $query_expression = "DEALLOCATE PREPARE stmt";
                                                    
                            $query = mysqli_query($koneksi, $query_expression);                         
                          
                              //while($tampilkan=$query->fetch_assoc()){
                          
                                var_dump(mysqli_error($koneksi));
                                var_dump($query);
                            ?>        
                 
                   
                    <script>
                    window.print();
                    </script>