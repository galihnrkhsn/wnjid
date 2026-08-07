<?php
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

<style>

body {
  background: #F1F3FA;
}

/* Profile container */
.profile {
  margin: 20px 0;
}

/* Profile sidebar */
.profile-sidebar {
  padding: 20px 0 10px 0;
  background: #fff;
}

.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 50%;
  height: 50%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
}

.profile-usertitle {
  text-align: center;
  margin-top: 20px;
}

.profile-usertitle-name {
  color: #5a7391;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 7px;
}

.profile-usertitle-job {
  text-transform: uppercase;
  color: #5b9bd1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 15px;
}

.profile-userbuttons {
  text-align: center;
  margin-top: 10px;
}

.profile-userbuttons .btn {
  text-transform: uppercase;
  font-size: 11px;
  font-weight: 600;
  padding: 6px 15px;
  margin-right: 5px;
}

.profile-userbuttons .btn:last-child {
  margin-right: 0px;
}
    
.profile-usermenu {
  margin-top: 30px;
}

.profile-usermenu ul li {
  border-bottom: 1px solid #f0f4f7;
}

.profile-usermenu ul li:last-child {
  border-bottom: none;
}

.profile-usermenu ul li a {
  color: #93a3b5;
  font-size: 14px;
  font-weight: 400;
}

.profile-usermenu ul li a i {
  margin-right: 8px;
  font-size: 14px;
}

.profile-usermenu ul li a:hover {
  background-color: #fafcfd;
  color: #5b9bd1;
}

.profile-usermenu ul li.active {
  border-bottom: none;
}

.profile-usermenu ul li.active a {
  color: #5b9bd1;
  background-color: #f6f9fb;
  border-left: 2px solid #5b9bd1;
  margin-left: -2px;
}

/* Profile Content */
.profile-content {
  padding: 20px;
  background: #fff;
  min-height: 460px;
}

* {
  box-sizing: border-box;
}

body {
  font-family: Arial, Helvetica, sans-serif;
}

/* Style the header */
.header {
  background-color: #f1f1f1;
  padding: 30px;
  text-align: center;
  font-size: 35px;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 50px;
  height: 40px; /* Should be removed. Only for demonstration */
  
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Style the footer */
.footer {
  background-color: #f1f1f1;
  padding: 10px;
  text-align: center;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media (max-width: 600px) {
  .column {
    width: 50%;
  }
}
img {
  border-radius: 50%;
}


.link:hover{
	color: blue;
}
.link:link{
	color: white;
}
.link:active{
	color: white;
}
.link:visited{
    color: white;
	background: white;
}


HTML CSSResult
EDIT ON
/* General button style */
.btn {
    border: none;
    font-family: 'Lato';
    font-size: inherit;
    color: inherit;
    background: none;
    cursor: pointer;
    padding: 25px 80px;
    display: inline-block;
    margin: 15px 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    outline: none;
    position: relative;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    transition: all 0.3s;
}

.btn:after {
    content: '';
    position: absolute;
    z-index: -1;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    transition: all 0.3s;
}

/* Pseudo elements for icons */
.btn:before {
    font-family: 'FontAwesome';
    speak: none;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    position: relative;
    -webkit-font-smoothing: antialiased;
}




</style>
</head>
<body>

 
<div class="row">
    
                <?php 
                    $instagram=$_GET["instagram"];
                    $ambil=$koneksi->query("SELECT * FROM admin_mitra where instagram='$instagram' ");
                    while($tampilkan=$ambil->fetch_assoc()){
                ?>
    
    <center>
            
            <img src="../foto/<?php echo $tampilkan['foto'] ?>" style="width:90px;height:90px;">
            <center>
        
       <?php 
       $idadmin=$_SESSION['admin_mitra']['idadmin'];
    if(isset($_POST['save'])){
	$nama= $_FILES['foto']['name'];
	$lokasi=$_FILES['foto']['tmp_name'];
	move_uploaded_file($lokasi, "../foto/".$nama);
	$koneksi->query("UPDATE admin_mitra SET foto='$nama' WHERE idadmin='$idadmin'");

	echo "<div class='alert alert-info'>upload photo berhasil</div>";
	echo "<script>location='index.php';</script>";
}
?>



<div class="container">
    <div class="row profile">
		<div class="col-md-3">
			<div class="profile-sidebar">
			 
				<!-- SIDEBAR USER TITLE -->
				<div class="profile-usertitle">
					<div class="profile-usertitle-name">
						<b><?php echo $_SESSION["admin_mitra"]["namamitra"]; ?></b>
					</div>
					
				</div>
				<!-- END SIDEBAR USER TITLE -->
				
				<!-- SIDEBAR MENU -->
				<div class="profile-usermenu">
					<ul class="nav">
						<li class="active">
							<a href="#">
							<i class="fa fa-envelope"></i>
							<?php echo $tampilkan['email']; ?> </a>
						</li>
						<li>
							<a href="https://api.whatsapp.com/send?phone=<?php echo $tampilkan['whatsapp']; ?>">
							<i class="fab fa-whatsapp"></i>
							<?php echo $tampilkan['whatsapp']; ?></a>
						</li>
						<li>
							<a href="#" >
							<i class="fab fa-telegram"></i>
							<?php echo $tampilkan['telegram']; ?></a>
						</li>
						<li>
							<a href="#">
							<i class="fab fa-facebook"></i>
							<?php echo $tampilkan['facebook']; ?> </a>
						</li>
						<li>
							<a href="https://www.instagram.com/<?php echo $tampilkan['instagram']; ?>/?hl=en">
							<i class="fab fa-instagram"></i>
							<?php echo $tampilkan['instagram']; ?> </a>
						</li>
						<li>
							<a href="#">
							<i class="fa fa-home"></i>
							<?php echo $tampilkan['alamat']; ?> </a>
						</li>
					</ul>
				</div>
				<!-- END MENU -->
				<?php } ?>
				
			</div>
			
		</div>
	<!-- SIDEBAR BUTTONS -->
				<div class="profile-userbuttons">
				
				</div>
				<!-- END SIDEBAR BUTTONS -->
	</div>
</div>

      
  
</div>



</body>
</html>

