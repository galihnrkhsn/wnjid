
<center>
<?php include "../inkubator/ekspedisi.php"; ?>
    </center>
    
<!-- AKHIR MODE  -->    

    <!-- JS here -->
<!-- Jquery, Popper, Bootstrap -->
<script src="./assets2/js/vendor/modernizr-3.5.0.min.js"></script>
<script src="./assets2/js/vendor/jquery-1.12.4.min.js"></script>
<script src="./assets2/js/popper.min.js"></script>
<script src="./assets2/js/bootstrap.min.js"></script>

<!-- Slick-slider , Owl-Carousel ,slick-nav -->
<script src="./assets2/js/owl.carousel.min.js"></script>
<script src="./assets2/js/slick.min.js"></script>
<script src="./assets2/js/jquery.slicknav.min.js"></script>

<!-- One Page, Animated-HeadLin, Date Picker -->
<script src="./assets2/js/wow.min.js"></script>
<script src="./assets2/js/animated.headline.js"></script>
<script src="./assets2/js/jquery.magnific-popup.js"></script>
<script src="./assets2/js/gijgo.min.js"></script>

<!-- Nice-select, sticky,Progress -->
<script src="./assets2/js/jquery.nice-select.min.js"></script>
<script src="./assets2/js/jquery.sticky.js"></script>
<script src="./assets2/js/jquery.barfiller.js"></script>

<!-- counter , waypoint,Hover Direction -->
<script src="./assets2/js/jquery.counterup.min.js"></script>
<script src="./assets2/js/waypoints.min.js"></script>
<script src="./assets2/js/jquery.countdown.min.js"></script>
<script src="./assets2/js/hover-direction-snake.min.js"></script>

<!-- contact js -->
<script src="./assets2/js/contact.js"></script>
<script src="./assets2/js/jquery.form.js"></script>
<script src="./assets2/js/jquery.validate.min.js"></script>
<script src="./assets2/js/mail-script.js"></script>
<script src="./assets2/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->  
<script src="./assets2/js/plugins.js"></script>
<script src="./assets2/js/main.js"></script>
  </body>



<!--================ END MAP MITRA =================-->
<br>
<br>
<div class="container">
<?php
$tglsekarang=date('Y');

?>
<c>Copyright &copy; <?php echo $tglsekarang; ?><br>
by Wanoja ITSupport</c>
</div>

  
</div>
<br><br><br><br>





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
<?php
      $idmitraagen=$_SESSION["mitraagen"]["namaagen"];
       $ambil=$koneksi->query("SELECT mode FROM mitraagen where idmitraagen='$idmitraagen' "); 
      $mode=$ambil->fetch_assoc();
          ?>
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

