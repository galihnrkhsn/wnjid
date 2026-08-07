
<!DOCTYPE html>
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
  margin-right: 14px;
  margin-left: 14px;
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
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
  margin-right: 14px;
  margin-left: 14px;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
  margin: 10px;
 
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

#panel,#flip,#panel2,#flip2,#panel3,#flip3,#panel4,#flip4,#panel5,#flip5,#panel6,#flip6,#panel7,#flip7,#panel8,#flip8,#panel9,#flip9,#panel10,#flip10,#panel11,#flip11,#panel12,#flip12
,#panel13,#flip13{
    padding:5px;
    text-align:center;
    background-color:#D3D3D3;
    border:solid 1px #c3c3c3;
}
#panel,#panel2,#panel3,#panel4,#panel5,#panel6,#panel7,#panel8,#panel9,#panel10,#panel11,#panel12,#panel13{
    padding:50px;
    display:none;
    background-color: #F5FFFA
}

</style>
</head>
<body>

<?php 
include '../header.php';  ?>

<div class="row">
<table id="myJudul">
      <table id="myJudul">
      <tr class="header">
          <th style="width:1%;"><a class="glyphicon glyphicon-chevron-left" href='index.php')<</a><th>
          <th style="width:70%;">Tutorial Web Mitra</th>
    </tr>      
</table>
</div>

<div class="container">
 <center>
        <h6 style="font-family:verdana;" class="outset">Silahkan pilih tutorial berdasarkan menu yang anda perlukan, klik tombol nya 
        video tutorial akan segera di putar!</h6>
        </center><br>    
    
<div class="table-responsive"> 
<table class="table table-striped table-bordered" id="dataTables-example">

<tr>  
<td>
<div id="flip">LOGIN</div>
<div id="panel">video</div></td><br> 
<td>
<div id="flip2">PROFIL</div>
<div id="panel2">video</div></td><br>
</tr>

<tr>
<td>
<div id="flip3">SALDO</div>
<div id="panel3">video</div></td><br>
<td>
<div id="flip4">PREORDER</div>
<div id="panel4">video</div></td><br>
</tr>

<tr>
<td>    
<div id="flip5">TRANSAKSI</div>
<div id="panel5">video</div></td><br>
<td>
<div id="flip6">STOK PUSAT</div>
<div id="panel6">video</div></td><br>
</tr>

<tr>
<td>
<div id="flip7">MY STOCK</div>
<div id="panel7">video</div></td><br>
<td>
<div id="flip8">NYABAR</div>
<div id="panel8">video</div></td><br>
</tr>

<tr>
<td>
<div id="flip9">KEEP</div>
<div id="panel9">video</div></td><br>
<td>
<div id="flip10">KATALOG</div>
<div id="panel10">video</div></td><br>
</tr>

<tr>
<td><div id="flip11">PRICELIST</div>
<div id="panel11">video</div></td><br>
<td>
<div id="flip12">RESI</div>
<div id="panel12">video</div></td><br>
</tr>

</table>
</div>
<div id="flip13">W-LINK</div>
<div id="panel13">video</div><br>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script>
$(document).ready(function(){
      $("#flip").click(function(){
        $("#panel").slideToggle("slow");
      });
        $("#flip2").click(function(){
        $("#panel2").slideToggle("slow");
      });
      $("#flip3").click(function(){
        $("#panel3").slideToggle("slow");
      });
      $("#flip4").click(function(){
        $("#panel4").slideToggle("slow");
      });
      $("#flip5").click(function(){
        $("#panel5").slideToggle("slow");
      });
      $("#flip6").click(function(){
        $("#panel6").slideToggle("slow");
      });
      $("#flip7").click(function(){
        $("#panel7").slideToggle("slow");
      });
      $("#flip8").click(function(){
        $("#panel8").slideToggle("slow");
      });
      $("#flip9").click(function(){
        $("#panel9").slideToggle("slow");
      });
      $("#flip10").click(function(){
        $("#panel10").slideToggle("slow");
      });
      $("#flip11").click(function(){
        $("#panel11").slideToggle("slow");
      });
      $("#flip12").click(function(){
        $("#panel12").slideToggle("slow");
      });
      $("#flip13").click(function(){
        $("#panel13").slideToggle("slow");
      });
    });
</script>
</body>
</html>

