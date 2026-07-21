<center>
<?php include "../inkubator/ekspedisi.php"; ?>
    </center>
    
<!-- AKHIR MODE  -->    

    <!-- JS here -->

  <script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>



<!--================ END MAP MITRA =================-->
<br>
<br>
<div class="container">
<?php
$tglsekarang=date('Y');

?>
<c style="color:silver">Copyright &copy; <?php echo $tglsekarang; ?><br>
by Wanoja ITSupport</c>
</div>

  
</div>
<br><br><br><br>
<script>
// Mengatur waktu akhir perhitungan mundur
var countDownDate = new Date("Feb 01, 2021 23:59:00").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {

  // Untuk mendapatkan tanggal dan waktu hari ini
  var now = new Date().getTime();
    
  // Temukan jarak antara sekarang dan tanggal hitung mundur
  var distance = countDownDate - now;
    
  // Perhitungan waktu untuk hari, jam, menit dan detik
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Keluarkan hasil dalam elemen dengan id = "demo"
  document.getElementById("demokolibri").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // Jika hitungan mundur selesai, tulis beberapa teks 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demokolibri").innerHTML = "Link PO tidak tersedia";
      var x = document.getElementById("linkkolibri");
 
    //x.style.display = "block";
    x.style.display = "none";
    }
}, 1000);
</script>     




<script>

// JAVASCRIPT SLIDER

// Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
  interval: false
});

// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item.
$('.multi-item-carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));
  
  if (next.next().length>0) {
    next.next().children(':first-child').clone().appendTo($(this));
  } else {
    $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
  }
});  





</script> 

 <!-- FOOTER 2 -->         
<div class="container row fixed-bottom jumbotron3" >
  <div class="col-20 "><a href="index.php"><i class="fa fa-home fa-lg "></i><p class="text-center">Home</p></a></div>
  <div class="col-20"><a href="wanoja-link.php"><i class="fa fa-info-circle fa-lg "></i><p class="text-center">W-Info</p></a></div>
  <div class="col-20"><a href="elearning.php"><i class="fab fa-leanpub fa-lg"></i><p class="text-center">Tutorial</p></a></div>
  <div class="col-20"><a href="setting.php"><i class="fa fa-cog fa-lg"></i> <p class="text-center">Setting</p></a></div>
  <div class="col-20"><a href="profile.php"><i class="fa fa-user-circle fa-lg"></i><p class="text-center">Profil</p></a></div>
</div>    
 <!-- FOOTER 2 END --> 

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron3 {
    background: #f2f2f2;
   
    margin: auto;
  text-align: center;
    overflow: hidden;
    padding: 10px 0px 0px 0px;
}

.jumbotron3 p {
  text-decoration: none;
  padding: 0px 0px 0px 0px;
  
   
   text-align: center;
   
}


.col-20 {width: 20%;}


</style>

           


</body>

</html>

