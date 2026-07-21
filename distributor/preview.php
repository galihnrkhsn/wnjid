
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WNJ Preview</title>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/500/fabric.min.js"></script>


</head>
<body>




<style>
/* Place the navbar at the bottom of the page, and make it stick */

@font-face {
  font-family: Halaney Demo;
  src: url(font/Halaney-Demo.otf);
}

@font-face {
  font-family: BlackJack;
  src: url(font/blackjack.otf);
}

@font-face {
  font-family: Awal Ramadhan;
  src: url(font/aAwalRamadhan.otf);
}

@font-face {
  font-family: Amazing Mother;
  src: url(font/aAmazingMother.otf);
}



   .input-hidden {
  position: absolute;
  left: -9999px;
}

input[type=radio]:checked + label>img {
  border: 1px solid #fff;
  box-shadow: 0 0 3px 3px #00CED1;
}

/* Stuff after this is only to make things more pretty */
input[type=radio] + label>img {
  border: 1px solid silver;
  border-radius: 10px;
  transition: 500ms all;
}

input[type=radio]:checked + label>img {
 
}

/*
 | //lea.verou.me/css3patterns
 | Because white bgs are boring.
*/
html {
  background-color: #fff;
  background-size: 100% 1.2em;
  /*background-image: */
  /*  linear-gradient(*/
  /*    90deg, */
  /*    transparent 79px, */
  /*    #abced4 79px, */
  /*    #abced4 81px, */
  /*    transparent 81px*/
  /*  ),*/
  /*  linear-gradient(*/
  /*    #eee .1em, */
  /*    transparent .1em*/
  /*  );*/
}

.besarcard {
    width: 12rem;
    margin-left: 5%;
    padding: 10%;
        margin-top: 10%;
}

@media only screen and (max-width: 600px) {

.besarcard {
    width: 8rem;
    margin-left: 10%;
    margin-top: 10%;
}
    
}

</style>
<!--================ NAVBARU  =================-->

<!--================ NAVBARU END =================-->
  <br><br>

<div class="container">
    <div class="form-group">
            <select class="form-control" style="display: none" id="kategori" name="kategori" required>
                <option>Black</option>
            </select>
   </div>       
</div>
<div class="container">
    <div id="templateBaju" class="d-flex flex-column" style="position: absolute;top: 100px;left: 15px;z-index: 22;"></div>
        <div id="shirtDiv" class="page" style="width: 400px;margin: auto; position: relative; background-color: rgb(255, 255, 255);">
            <div id="mockupContainer">
                <img name="tshirtview" id="tshirtFacing" src="img/crew_front.png" style="width: 100%;position: relative;">
            </div>
           
            <div id="drawingArea" style="position: absolute;top: 100px;left: 125px;z-index: 10;width: 150px;height: 120px;">
                <div class="canvas-container" style="width: 150px; height: 120px; position: relative; user-select: none;">
                    <canvas id="tcanvas" width="150" height="120" class="hover lower-canvas" 
                            style="user-select: none; position: absolute; width: 200px; height: 400px; left: 0px; top: 0px; touch-action: none;">
                    </canvas>
                    <canvas class="upper-canvas hover" width="200" height="400" 
                            style="user-select: none; position: absolute; width: 150px; height: 120px; left: 0px; top: 0px; touch-action: none;">
                    </canvas>
                </div>
            </div>
            <div class="container d-flex justify-content-center" id="listCustomImage"></div>
        </div>
</div>    
<div class="container">
<label>Template</label>   
<br>
    <div id="myCarousel" class="carousel slide">

        <div class="carousel-inner">
            <div class="item active">
                <div class="row">
                    <div class="col-4">
                        <input type="radio" name="template" value="" checked id="templateBaju0" class="input-hidden" />
                        <label for="templateBaju0" >
                          <img src="img/baju-lebaran.png" class="img-responsive" style="background-color:grey;  margin-top: 10px;" />
                        </label>  
                        <br>
                        <label>Tanpa Template</label>                    
                    </div>                
                    
                    <div class="col-4">
                        <input type="radio" name="template" value="img/baju-lebaran-1.png" id="templateBaju1" class="input-hidden" />
                        <label for="templateBaju1" >
                          <img src="img/KaosLebaran/KL_001.png" class="img-responsive" style="  margin-top: 10px;margin-left: 5px;background-color:grey; "/>
                        </label>
                        <br>
                        <label>Kaos Lebaran</label>
                    </div>
                     <div class="col-4">
                        <input type="radio" name="template" value="img/KaosLebaran/KL_004.png" id="templateBaju3" class="input-hidden" />
                        <label for="templateBaju3" >
                          <img src="img/KaosLebaran/KL_003.png" class="img-responsive" style="  margin-top: 10px;background-color:grey; " />
                        </label>  
                        <br>
                        <label>Eid Mubarak</label>        
                    </div>  
                </div>

            </div>

        </div>

        <a class="left carousel-control" href="#myCarousel" data-slide="prev" style="margin-top: 10%;margin-left: -10%;background-image:none !important">
            <i class="fa fa-chevron-left fa-4" style="color: black;"></i></a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next" style="margin-top: 10%;margin-right: -10%;background-image:none !important">
            <i class="fa fa-chevron-right fa-4" style="color: black;"></i></a>
    </div>   
</div>
<div class="container">
    <div class='form-group row'>
        <div class='col-sm-6'>
            <label>Nama Custom</label>   
            <br>
            <input type='text' class='form-control' name='nama' id="customText" required maxlength='15' placeholder='Tulis Nama Custom'>
        </div>
        <div class='col-sm-6'>
            <label>Font</label>   
            <br>                        
            <select  id="customFont" class='form-control' name='font' required>  
                <option value='Arial'>Arial</option>
                <option value='Times New Roman'>Times New Roman</option>          
                <option>Amazing Mother</option>
                <option>Awal Ramadhan</option>
                <option>BlackJack</option>
                <option>Halaney Demo</option>
            </select>
        </div>
    </div>    
</div>      

    <script src=main_kaos.js></script>