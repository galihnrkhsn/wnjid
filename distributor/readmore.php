<?php 
error_reporting (0);
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
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

		#myJudul {
    
  text-align: center;
  border-collapse: collapse;
  width: 100%;
  font-size: 18px;
  
  
}
#myJudul th  {
  
  padding: 12px;
  background-color: #f1f1f1;
  font-size: 18px;
}
#myJudul td {
  text-align: center;
  padding: 12px;
 
  
}

p {font-size:14px; line-height: 1.5em; font-family:verdana;
    
}

.navbar-default .navbar-nav > li.clr1 a{
        color:#ffffff;
}
.navbar-default .navbar-nav > li.clr2 a{
    color: #FFEB3B;;
}
.navbar-default .navbar-nav > li.clr3 a{
    color: #5EC64D;
}
.navbar-default .navbar-nav > li.clr4 a{
    color: #29AAE2;
}
.navbar-default .navbar-nav > li.clr5 a{
    color: #CEE229;
}

.navbar-default .navbar-nav > li.clr1.active a{
    color:#fff;
    background: #F55;
}
.navbar-default .navbar-nav > li.clr1 a:hover{
    background: rgba(255, 85, 85, 0.75);
} 
.navbar-default .navbar-nav > li.clr2.active a{
    color: #fff;
    background:#973CB6;
}
.navbar-default .navbar-nav > li.clr2 a:hover{
 background: rgba(151, 60, 182, 0.66)
}


.navbar-default .navbar-nav > li.clr3.active a{
    color: #fff;
    background:#5EC64D;
}
.navbar-default .navbar-nav > li.clr3 a:hover
{
  background: rgba(94, 198, 77, 0.78);
}
.navbar-default .navbar-nav > li.clr4.active a{
    color: #fff;
    background: #29AAE2;
}
.navbar-default .navbar-nav > li.clr4 a:hover{
    background: rgba(41, 170, 226, 0.62);
}

.navbar-default .navbar-nav > li.clr5.active a{
    color: #fff;
    background: #CEE229;
}
.navbar-default .navbar-nav > li.clr5 a:hover{
    background: rgba(206, 226, 41, 0.63);
}
.navbar-default{
    background-color: #3b5998;
    font-size:18px;
    border-color:none;
}
.navbar-default .navbar-brand {
    color: #ffffff;
    font-weight:bold;
}
.navbar-default .navbar-text {
    color:#ffffff;
}

.pemisah-btnnav{
    margin-left: 20px;
    margin-right: 20px;
}

.recent{
    padding-top:20px;
    
}
.info-meta{
    padding-top: 10px;
    color:#9999;
}
a:focus, 
a:hover {
text-decoration: none;
outline: none;
color: #9c9c9c;
}
.footer-bottom {
background-color:#3b5998;
color:#fff;
padding-top:10px;
padding-bottom:10px;
}

</style>

	
	
</head>
<body>

<?php 
include '../header.php'; ?> 
<?php 
      $id=$_GET["idgood"];
      $ambil=$koneksi->query("SELECT * FROM goodnews where idgood='$id'"); 
      while($data=$ambil->fetch_assoc()){
      ?>
<div class="row">
<table id="myJudul">
      <tr class="header">
          <th style="width:1%;"><a class="glyphicon glyphicon-chevron-left" href='index.php')<</a><th>
          <th style="width:65%;">Good News</th>
  </tr>
    <td></td>
  </tr>
</table>
  
<div class="container" style="margin-top:0px">
    <div class="row">
        <div class="col-md-9">
              <div >
                  <h3> &nbsp <?php echo $data['judul']; ?></h3>
               <div class="panel-body">
           
                    <div class="info-meta">
                        <ul class="list-inline">
                            <li><i class="fa fa-clock-o"></i> <?php echo $data['tgl']; ?> </li>
                        </ul>
                    </div>
                  <hr>
                  
                    <div class = "media">
                       <a class = "pull-left" href = "#">
                         
                       </a>
                       <div class = "media-body">
                           <?php
                          echo nl2br(htmlspecialchars($data['isi']));
                          ?>
                       </div>
    
                    </div>
                    <?php } ?>
                    
               </div>
            </div>
         </div>  
        <!--<div class="col-md-3">
            <div class="panel panel-default">
               <div class="panel-heading"><h4 class="text-center">Latest News</h4></div>
               <div class="panel-body">
                    <div class="recent">
                        <a href="#"></a>                
                        <div class="info-meta">
                            <ul class="list-inline">
                                <li><i class="fa fa-clock-o"></i> Jan 5, 2016 </a></li>
                                <li><i class="fa fa-eye"></i>21k</li>
                                <li><i class="fa fa-heart-o"></i>372</li>
                            </ul>
                        </div>
                        <h4>
                            <a href="">Pertandingan Basket NBA berlangsung sangat sengit</a>
                        </h4>
                    </div>
                    
                    <div class="recent">
                        <a href="#"></a>                           
                        <div class="info-meta">
                            <ul class="list-inline">
                                <li><i class="fa fa-clock-o"></i> Jan 5, 2016</li>
                            </ul>
                        </div>
                        <h4 class="entry-title">
                            <a href="">Tip dan Trik dalam memilih laptop untuk penyuka game</a>
                        </h4>
                    </div>
                </div>
            </div>      
        </div>          
    </div>          
</div>
<!--FOOTER-->
  <!--  <div class="footer-bottom">
        <div class="container-fluid text-center">
            <p>Copyright &copy; 2020  <!--DTC. Developed by <a href="https://wanojahijab.com/">Wanoja Hijab</a></p>
        </div>
    </div>              -->
    <script src='assets/js/jquery.js'></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

