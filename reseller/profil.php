<?php
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
 	    <!--================ backround =================-->
	<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Wanoja Hijab</title>
		<meta name="description" content="Link Share Mitra Wanoja" />
		<meta name="keywords" content="navigation, menu, responsive, border, overlay, css transition" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/icons.css" />
		<link rel="stylesheet" type="text/css" href="css/style1.css" />
		<script src="js/modernizr.custom.js"></script>
		<script src="https://cdn.rawgit.com/bungfrangki/efeksalju/2a7805c7/efek-salju.js" type="text/javascript"></script>
		    <!--================ backround =================-->	
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Add icon library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
  width:80%;
  height:50px;
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: RoyalBlue;
}
.btn2 {
  border: 2px solid black;
  background-color: white;
  color: black;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
  border-color: #2196F3;
  color: dodgerblue;
  width:80%;
  
}
.avatar {
  vertical-align: middle;
  width: 90px;
  height: 90px;
  border-radius: 50%;
}
body {
  
  background-color: silver; /* For browsers that do not support gradients */
  
}



</style>
</head>

<body>
   
    
<br>

<div class="row">
    
    
                <?php 
                    $email=$_GET["user"];
                    $ambil=$koneksi->query("SELECT * FROM mitrareseller where email='$email' ");
                    while($tampilkan=$ambil->fetch_assoc()){
                ?>
 </div>   
<div class="row">

    <center>
			
	<!--		<img src="../foto/<?php echo $tampilkan['foto'] ?>" alt="Avatar" class="avatar">	-->
           
            <center>
        <br>
       
</div>
      
<div  align=center>
   
    <b><?php echo $tampilkan['namaagen']; ?></b><br><br>

<a href="https://api.whatsapp.com/send?phone=<?php echo $tampilkan['whatsapp']; ?>"><button class="btn"><i class="fa fa-whatsapp"></i>   Whatsapp</button></a><br><br>
<a href="https://t.me/<?php echo $tampilkan['telegram']; ?>"><button class="btn"><i class="fa fa-telegram"></i>  Telegram</button></a><br><br>
<a href="https://www.facebook.com/<?php echo $tampilkan['facebook']; ?>/?hl=en"><button class="btn"> <i class="fa fa-facebook-official"></i>  Facebook </button></a><br><br>
<a href="https://www.instagram.com/<?php echo $tampilkan['instagram']; ?>/?hl=en"><button class="btn"><i class="fa fa-instagram"></i>  Instagram</button></a><br><br>
<a href="mailto:<?php echo $tampilkan['email']; ?>"><button class="btn"><i class="fa fa-envelope-o"></i> Email</button></a><br><br>
<button class="btn2"><?php echo $tampilkan['alamat']; ?></button>

<?php } ?>


</div>



</body>
	
</html>

