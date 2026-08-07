<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';
include 'assets/components/Sessions/sesDistri.php';



// if(!isset($_SESSION["admin_mitra"])){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//    echo "<script>location='login2.php';</script>";
//    header('location:login2.php');
//    exit();
// }

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<title>Mitra <?php echo $_SESSION['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="elearning.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Tutorial WEB</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  margin-top: 15px;
  padding: 2px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<!--================ NAVBARU END =================-->
        <div class="container">
       
    <div class="row">
		   <div class="col-md-12 col-sm-6 mb-3">
			    
                <table class="table" border=0 id="dataTables-example">

					<tbody>
					<?php
					// Include / load file koneksi.php
				include "koneksi.php";
					
					// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
				$sql = mysqli_query($koneksi, "SELECT * from tutorial where idmitra=0 and kategori='Market Tools' order by idtutorial asc");
				$no=1;	
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
					<!--================ TAMPIL DATA =================-->
						<tr>
						    <td>
						          <div id="accordion">
      
    
    <div class="card">
        
          <div class="card-header">
            <center><a class="card-link" data-toggle="collapse" href="#<?php echo $data['idtutorial']; ?>">
              <?php echo $data['judul']; ?>
            </a></center>
          </div>
      
          <div id="<?php echo $data['idtutorial'] ?>" class="collapse" data-parent="#accordion">
            
            <div class="card-body">
                        <center>
                            <?php echo  nl2br($data['teksatas']); ?><br>
                            <?php if($data['link']<>''){ ?>
                            <iframe width="250" src="https://www.youtube.com/embed/<?php echo $data['link'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                              <?php } ?>
                              <?php if($data['file']<>''){ ?>
                              <a class="dropdown-item" href="viewpdf.php?id=<?php echo $data['idtutorial'] ?>"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i> PDF</a>
                              <?php } ?>
                             <?php echo  nl2br($data['teksbawah']); ?>
                          </div></center>
						    </td> 
						
						</tr>
					<!--================ TAMPIL DATA END =================-->
					 
					</tbody>
					<?php } ?>
				</table>
		    	</div>
		    	
			
		</div>
		</div><br><br><br><br>
<?php include "menubawah.php"; ?>
	</body>
</html>