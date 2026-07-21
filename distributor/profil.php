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
<link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>
<style>
body{
  background-image: url('profile/2.png');
  background-size: cover;
  background-repeat: no-repeat;
}
.btn {
  background-color: white;
opacity: 0.6;
  border: none;
  color: #8b6661;
  font-style: bold;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
  width:80%;
  height:50px;
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: white;
  opacity: 1;
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




</style>
</head>

<body>
   
    
<br>

<div class="row">
    
    
                <?php 
                    $instagram=$_GET["user"];
                    $ambil=$koneksi->query("SELECT * FROM admin_mitra where instagram='$instagram' ");
                    while($tampilkan=$ambil->fetch_assoc()){
                ?>
 </div>   
<div class="row">

    <center>
           
			
			<img src="foto/<?php echo $tampilkan['foto'] ?>" alt="Avatar" class="avatar">	
           
            </center>
        <br>
       
</div>
      
<div  align=center>
   
    <label style="font-family: comfortaa;font-size: 25px;"><?php echo $tampilkan['namamitra']; ?></label><br>
    <?php if ($tampilkan['idadmin']==10): ?>
      <label style="font-family: comfortaa;font-size: 12px;">Useful Everywhere</label><br><br>
    <?php endif ?>
<br>
<img src="profile/12.png" style="width: 200px;height: 15px;">
<br><br>
<a href="https://api.whatsapp.com/send?phone=<?php echo $tampilkan['whatsapp']; ?>"><img src="profile/3.png" style="width: 300px; height: 40px;"></a><br><br>
<a href="https://t.me/<?php echo $tampilkan['telegram']; ?>"><img src="profile/4.png" style="width: 300px; height: 40px;"></a><br><br>
<a href="https://www.facebook.com/<?php echo $tampilkan['facebook']; ?>/?hl=en"><img src="profile/5.png" style="width: 300px; height: 40px;"></a><br><br>
<a href="https://www.instagram.com/<?php echo $tampilkan['instagram']; ?>/?hl=en"><img src="profile/6.png" style="width: 300px; height: 40px;"></a><br><br>
<a href="mailto:<?php echo $tampilkan['email']; ?>"><img src="profile/7.png" style="width: 300px; height: 42px;"></a><br><br>
<!-- <button class="btn2"><?php echo $tampilkan['alamat']; ?></button> -->
<br>
<img src="profile/8.png" style="width: 200px;height: 15px;">
<?php } ?>


</div>



</body>
	
</html>

